@extends('layouts.public')

@section('title', 'Kiosk Presensi Siswa')

@push('styles')
<style>
    /* Custom styling for scanner */
    #reader {
        width: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    #reader video {
        object-fit: cover !important;
        border-radius: 0.5rem;
    }

    #reader__dashboard_section_csr,
    #reader__dashboard_section_swaplink,
    #reader__header_message {
        display: none !important;
    }

    /* Android Toast */
    .toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #1f2937;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 100;
        pointer-events: none;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .toast-success { border-left: 4px solid #10b981; }
    .toast-error { border-left: 4px solid #ef4444; }
    .toast-warning { border-left: 4px solid #f59e0b; }
</style>
@endpush

@section('content')
<div class="max-w-md mx-auto my-8 px-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col relative min-h-[600px]">
        
        {{-- Header Kiosk --}}
        <div class="bg-blue-900 text-white p-5 text-center relative">
            <h2 class="text-xl font-bold tracking-wide">Scanner Presensi</h2>
            <p class="text-blue-200 text-xs mt-1">Arahkan Kartu Pelajar ke Kamera</p>
        </div>

        {{-- Configuration Panel (Shows on load) --}}
        <div id="config-panel" class="p-6 flex-1 flex flex-col justify-center bg-gray-50 absolute inset-0 top-[80px] z-20">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="fas fa-qrcode text-2xl"></i>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                    <select id="select-kelas-overlay" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach ($classrooms as $c)
                            <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Sesi</label>
                    <select id="select-sesi-overlay" class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
                        <option value="" disabled selected>-- Pilih Sesi --</option>
                        <option value="apel">🌅 Apel Pagi</option>
                        <option value="kelas">🏫 Di Kelas</option>
                        <option value="pulang">🏠 Pulang</option>
                    </select>
                </div>
                
                <button type="button" onclick="startScanningFromOverlay()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition active:scale-95 mt-2">
                    Mulai Scanner <i class="fas fa-camera ml-2"></i>
                </button>
            </div>
        </div>

        {{-- Active Scanner UI --}}
        <div id="active-scanner" class="hidden flex-1 flex-col">
            
            {{-- Info Bar --}}
            <div class="bg-blue-50 px-4 py-3 flex justify-between items-center border-b border-blue-100">
                <div class="flex flex-col text-sm">
                    <span class="text-gray-600 font-medium">Kelas: <strong id="lbl-kelas" class="text-blue-900">...</strong></span>
                    <span class="text-gray-600 font-medium">Sesi: <strong id="lbl-sesi" class="text-blue-900">...</strong></span>
                </div>
                <div class="text-center bg-white px-3 py-1.5 rounded-lg shadow-sm border border-gray-200">
                    <span class="block text-xs text-gray-500 font-bold uppercase">Hadir</span>
                    <span id="lbl-count" class="block text-lg font-bold text-green-600 leading-none">0</span>
                </div>
            </div>

            {{-- Scanner Video Area --}}
            <div class="flex-1 bg-black p-4 flex flex-col justify-center relative">
                <div id="reader" class="shadow-inner relative z-10 w-full h-[300px]"></div>
                
                <div class="text-center mt-4 text-gray-400 text-xs">
                    <p><i class="fas fa-info-circle mr-1"></i> Posisikan barcode tepat di depan kamera</p>
                </div>
            </div>

            {{-- Bottom Actions --}}
            <div class="p-4 bg-white border-t border-gray-100 flex gap-3">
                <button type="button" onclick="showConfigPanel()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition">
                    <i class="fas fa-cog mr-1"></i> Pengaturan
                </button>
                <button type="button" onclick="confirmCloseSession()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl shadow-md transition">
                    Tutup Sesi
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Toast Notification --}}
<div id="toast" class="toast">
    <i id="toast-icon" class="fas"></i>
    <span id="toast-msg"></span>
</div>

<!-- Hidden form for close session -->
<form id="form-close" method="POST" action="{{ route('presensi.close') }}" class="hidden">
    @csrf
    <input type="hidden" name="class" id="inp-close-class">
    <input type="hidden" name="session_type" id="inp-close-sesi">
    <input type="hidden" name="date" id="inp-close-date">
</form>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let currentClassId = null;
    let currentClassName = null;
    let currentSession = null;
    let isProcessing = false;
    let html5QrCode = null;

    // Audio Feedback (Synthesized beep)
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playBeep() {
        if(audioCtx.state === 'suspended') audioCtx.resume();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(800, audioCtx.currentTime);
        
        gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
        gainNode.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.05);
        gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.15);
        
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        
        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.15);
        
        if (navigator.vibrate) navigator.vibrate(50);
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const icon = document.getElementById('toast-icon');
        const msg = document.getElementById('toast-msg');
        
        toast.className = `toast toast-${type} show`;
        msg.innerText = message;
        
        if(type === 'success') icon.className = 'fas fa-check-circle text-green-400';
        else if(type === 'error') icon.className = 'fas fa-times-circle text-red-400';
        else if(type === 'warning') icon.className = 'fas fa-exclamation-circle text-yellow-400';
        
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    function updateLabels() {
        document.getElementById('lbl-kelas').innerText = currentClassName;
        const sesiLabels = {'apel': 'Apel Pagi', 'kelas': 'Di Kelas', 'pulang': 'Pulang'};
        document.getElementById('lbl-sesi').innerText = sesiLabels[currentSession] || currentSession;
    }

    function showConfigPanel() {
        if(html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode = null;
                document.getElementById('config-panel').classList.remove('hidden');
                document.getElementById('active-scanner').classList.add('hidden');
                document.getElementById('active-scanner').classList.remove('flex');
            }).catch(err => console.error("Failed to stop scanner", err));
        } else {
            document.getElementById('config-panel').classList.remove('hidden');
            document.getElementById('active-scanner').classList.add('hidden');
            document.getElementById('active-scanner').classList.remove('flex');
        }
    }

    function startScanningFromOverlay() {
        const selKelas = document.getElementById('select-kelas-overlay');
        const selSesi = document.getElementById('select-sesi-overlay');
        
        if(!selKelas.value) return alert("Pilih kelas terlebih dahulu!");
        if(!selSesi.value) return alert("Pilih jenis sesi terlebih dahulu!");
        
        currentClassId = selKelas.value;
        currentClassName = selKelas.options[selKelas.selectedIndex].text;
        currentSession = selSesi.value;
        
        updateLabels();
        
        document.getElementById('config-panel').classList.add('hidden');
        document.getElementById('active-scanner').classList.remove('hidden');
        document.getElementById('active-scanner').classList.add('flex');
        
        initScanner();
        fetchScanList();
    }

    function initScanner() {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0 // Square aspect ratio for the video container
        };
        
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).catch((err) => {
            alert("Gagal mengakses kamera. Pastikan memberikan izin kamera atau coba gunakan perangkat HTTPS.");
            console.error(err);
            showConfigPanel();
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        if (!currentClassId || !currentSession) return;
        
        isProcessing = true;
        playBeep();
        
        fetch("{{ route('presensi.scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nis: decodedText,
                barcode: decodedText,
                class: currentClassId,
                session_type: currentSession,
                date: new Date().toISOString().slice(0, 10)
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                document.getElementById('lbl-count').innerText = data.total_present;
            } else {
                if(data.message.includes('sudah')) {
                    showToast(data.message, 'warning');
                } else {
                    showToast(data.message, 'error');
                }
            }
        })
        .catch(err => {
            showToast("Terjadi kesalahan jaringan", 'error');
        })
        .finally(() => {
            setTimeout(() => { isProcessing = false; }, 2000);
        });
    }

    function onScanFailure(error) {
        // Ignored
    }

    function fetchScanList() {
        if (!currentClassId || !currentSession) return;
        
        const today = new Date().toISOString().slice(0, 10);
        fetch(`{{ route('presensi.scan-list') }}?class=${currentClassId}&session_type=${currentSession}&date=${today}`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('lbl-count').innerText = data.total_present || data.data.length;
                }
            });
    }

    setInterval(() => {
        if(document.getElementById('config-panel').classList.contains('hidden')) {
            fetchScanList();
        }
    }, 10000);

    function confirmCloseSession() {
        if(confirm("Tutup Sesi?\nSemua siswa yang belum diabsen (belum discan) akan otomatis ditandai sebagai ALPHA (Tidak Hadir). Lanjutkan?")) {
            document.getElementById('inp-close-class').value = currentClassId;
            document.getElementById('inp-close-sesi').value = currentSession;
            document.getElementById('inp-close-date').value = new Date().toISOString().split('T')[0];
            
            if(html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('form-close').submit();
                });
            } else {
                document.getElementById('form-close').submit();
            }
        }
    }
</script>
@endsection
