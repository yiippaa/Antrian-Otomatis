<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TtsService
{
    public function generate(string $text): string
    {
        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => 'gpt-4o-mini-tts',
                'voice' => 'alloy',
                'input' => $text,
            ]);

        $filename = 'tts_' . time() . '.mp3';
        Storage::disk('public')->put($filename, $response->body());

        return asset('storage/' . $filename);
    }
}
