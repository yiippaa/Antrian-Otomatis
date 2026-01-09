<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class TvDisplayController extends Controller
{
    public function index()
    {
        return view('tv-display');
    }

    private function toWib($dt): ?string
    {
        if (!$dt) return null;

        // Aman untuk Carbon dari Eloquent
        return $dt->copy()
            ->timezone('Asia/Jakarta')
            ->format('Y-m-d H:i:s');
    }

    public function data(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $items = Queue::query()
            ->with(['polyclinic:id,name', 'counter:id,name'])
            ->whereDate('queue_date', $date)
            ->whereIn('status', ['called', 'serving', 'done'])
            ->orderByRaw("FIELD(status, 'serving', 'called', 'done')")
            ->orderByDesc('called_at')
            ->limit(20)
            ->get()
            ->map(function ($q) {
                return [
                    'display_code' => $q->display_code,
                    'status'       => $q->status,
                    'polyclinic'   => $q->polyclinic?->name,
                    'counter'      => $q->counter?->name,
                    // ✅ FIX WIB di sini
                    'called_at'    => $this->toWib($q->called_at),
                ];
            });

        $lastCalled = Queue::query()
            ->with(['polyclinic:id,name', 'counter:id,name'])
            ->whereDate('queue_date', $date)
            ->whereNotNull('called_at')
            ->orderByDesc('called_at')
            ->first();

        return response()->json([
            'date' => $date,
            'items' => $items,
            'last_called' => $lastCalled ? [
                'display_code' => $lastCalled->display_code,
                'polyclinic'   => $lastCalled->polyclinic?->name,
                'counter'      => $lastCalled->counter?->name,
                // ✅ FIX WIB di sini juga
                'called_at'    => $this->toWib($lastCalled->called_at),
            ] : null,
        ]);
    }

    public function repeat(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $lastCalled = Queue::query()
            ->with(['polyclinic:id,name', 'counter:id,name'])
            ->whereDate('queue_date', $date)
            ->whereNotNull('called_at')
            ->orderByDesc('called_at')
            ->first();

        if (! $lastCalled) {
            return response()->json([
                'ok' => false,
                'message' => 'Belum ada pemanggilan.',
            ], 404);
        }

        $text = "Nomor antrian {$lastCalled->display_code}. "
            . "Silakan menuju {$lastCalled->polyclinic?->name}. "
            . ($lastCalled->counter?->name ? "Loket {$lastCalled->counter->name}." : "");

        return response()->json([
            'ok' => true,
            'text' => $text,
            'display_code' => $lastCalled->display_code,
            'polyclinic' => $lastCalled->polyclinic?->name,
            'counter' => $lastCalled->counter?->name,
            // ✅ WIB juga biar konsisten kalau kamu tampilin
            'called_at' => $this->toWib($lastCalled->called_at),
        ]);
    }
}
