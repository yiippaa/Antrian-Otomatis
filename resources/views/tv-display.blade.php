<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TV Antrian - RS Mayapada</title>
    <link rel="stylesheet" href="/antrian.css">
</head>
<body>
<header>
    <h1>TV Antrian — RS Damanik</h1>
    <div class="clock" id="clock">-</div>
</header>

<div class="wrap">
    <div class="grid">
        <div class="card">
            <div class="muted">Nomor dipanggil terakhir</div>
            <div class="big" id="lastNumber">-</div>
            <div class="muted" id="lastMeta">Belum ada pemanggilan</div>
        </div>

        <div class="card">
            <div class="head-row">
                <div class="muted">Status realtime (Called / Serving / Done)</div>
                <button class="btn-call" id="btnRepeat" disabled>PANGGIL</button>
            </div>

            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Poli</th>
                    <th>Loket</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody id="rows">
                <tr><td colspan="4" class="muted">Belum ada data…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<button class="enable-audio" id="btnEnableAudio">🔊 Aktifkan Suara</button>

<script>
    // ======================
    // CLOCK
    // ======================
    setInterval(() => {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleString('id-ID');
    }, 1000);

    // ======================
    // STATE
    // ======================
    let audioEnabled = false;
    let lastSpokenCalledAt = null;
    let hasLastCalled = false;

    const btnEnableAudio = document.getElementById('btnEnableAudio');
    const btnRepeat = document.getElementById('btnRepeat');

    // ======================
    // AUDIO UNLOCK
    // ======================
    function pickIndonesianVoice() {
        const voices = window.speechSynthesis?.getVoices?.() ?? [];
        return voices.find(v => (v.lang || '').toLowerCase().includes('id'))
            || voices.find(v => (v.name || '').toLowerCase().includes('indonesia'))
            || null;
    }

    function speakText(text) {
        if (!audioEnabled) return;
        if (!('speechSynthesis' in window)) return;

        window.speechSynthesis.cancel();

        const u = new SpeechSynthesisUtterance(text);
        const voice = pickIndonesianVoice();
        if (voice) u.voice = voice;

        u.lang = voice?.lang ?? 'id-ID';
        u.rate = 1;
        u.pitch = 1;

        window.speechSynthesis.speak(u);
    }

    function speakQueue(displayCode, polyclinic, counter) {
        const text = `Nomor antrian ${displayCode}. Silakan menuju ${polyclinic ?? 'poli'}. ${counter ? 'Loket ' + counter : ''}.`;
        speakText(text);
    }

    // Klik manual "Aktifkan Suara"
    function unlockAudioManual() {
        audioEnabled = true;

        // speak pendek untuk unlock (user gesture)
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance('Suara aktif.');
            u.lang = 'id-ID';
            window.speechSynthesis.speak(u);
        }

        btnEnableAudio.style.display = 'none';
    }

    btnEnableAudio.addEventListener('click', unlockAudioManual);

    // Auto-unlock (dipakai saat klik tombol PANGGIL)
    async function ensureAudioEnabled() {
        if (audioEnabled) return true;
        if (!('speechSynthesis' in window)) return false;

        try {
            // Trik: speak kosong, untuk "unlock"
            window.speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance(' ');
            u.lang = 'id-ID';
            window.speechSynthesis.speak(u);

            audioEnabled = true;
            btnEnableAudio.style.display = 'none';
            return true;
        } catch (e) {
            return false;
        }
    }

    // ======================
    // REPEAT BUTTON (PANGGIL)
    // Butuh route POST /tv/repeat (server balikin text / data)
    // ======================
    async function repeatCall() {
        if (!hasLastCalled) {
            alert('Belum ada nomor yang dipanggil.');
            return;
        }

        // ✅ Auto unlock dulu (tanpa pop-up)
        const ok = await ensureAudioEnabled();
        if (!ok) {
            // fallback: kalau browser super ketat, suruh klik tombol bawah
            btnEnableAudio.style.display = 'block';
            btnEnableAudio.focus();
            return;
        }

        btnRepeat.disabled = true;
        btnRepeat.innerText = 'MEMANGGIL...';

        try {
            const res = await fetch('/tv/repeat', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });

            const json = await res.json();

            if (!res.ok || !json.ok) {
                alert(json.message || 'Gagal memanggil ulang.');
                return;
            }

            // Server disarankan mengirim "text"
            if (json.text) {
                speakText(json.text);
            } else {
                speakQueue(json.display_code, json.polyclinic, json.counter);
            }
        } catch (e) {
            alert('Error repeat call: ' + e.message);
        } finally {
            btnRepeat.innerText = 'PANGGIL';
            btnRepeat.disabled = !hasLastCalled;
        }
    }

    btnRepeat.addEventListener('click', repeatCall);

    // Enter = panggil ulang
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') repeatCall();
    });

    // ======================
    // FETCH DATA REALTIME
    // ======================
    async function fetchData() {
        const res = await fetch('/tv/data');
        const json = await res.json();

        const last = json.last_called;

        if (last) {
            hasLastCalled = true;
            btnRepeat.disabled = false;

            document.getElementById('lastNumber').innerText = last.display_code ?? '-';
            document.getElementById('lastMeta').innerText =
                `${last.polyclinic ?? '-'} • ${last.counter ?? '-'} • ${last.called_at ?? '-'}`;

            // Trigger otomatis jika called_at berubah
            if (last.called_at && last.called_at !== lastSpokenCalledAt) {
                lastSpokenCalledAt = last.called_at;

                // kalau audio belum enable, jangan otomatis ngomong
                // (biar gak ditolak browser). Setelah enable, baru bisa manual via PANGGIL.
                if (audioEnabled) {
                    speakQueue(last.display_code, last.polyclinic, last.counter);
                }
            }
        } else {
            hasLastCalled = false;
            btnRepeat.disabled = true;

            document.getElementById('lastNumber').innerText = '-';
            document.getElementById('lastMeta').innerText = 'Belum ada pemanggilan';
        }

        // table
        const rowsEl = document.getElementById('rows');
        rowsEl.innerHTML = '';

        const items = json.items || [];
        if (items.length === 0) {
            rowsEl.innerHTML = `<tr><td colspan="4" class="muted">Belum ada data…</td></tr>`;
            return;
        }

        items.forEach(item => {
            const badgeClass =
                item.status === 'serving' ? 'serving' :
                item.status === 'called' ? 'called' :
                'done';

            const statusText = (item.status || '').toUpperCase();

            rowsEl.innerHTML += `
                <tr>
                    <td style="font-weight:700">${item.display_code ?? '-'}</td>
                    <td>${item.polyclinic ?? '-'}</td>
                    <td>${item.counter ?? '-'}</td>
                    <td><span class="badge ${badgeClass}">${statusText}</span></td>
                </tr>
            `;
        });
    }

    // Load voices
    if ('speechSynthesis' in window) {
        window.speechSynthesis.getVoices();
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    }

    // Polling realtime
    fetchData();
    setInterval(fetchData, 2000);
</script>
</body>
</html>
