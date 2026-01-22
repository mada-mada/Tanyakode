@extends('layouts.user')

@section('title', 'Daily Spin Wheel')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="navy-card mb-4 shadow-sm">
                <div class="navy-card-header bg-navy d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-accent fw-bold"><i class="fas fa-gift me-2"></i> Daily Spin Wheel</h5>
                    <span class="badge badge-accent">{{ $maxSpinsPerDay - $todaySpins }} / {{ $maxSpinsPerDay }} Spin Tersisa</span>
                </div>
                <div class="navy-card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card border text-center p-3">
                                <small class="text-muted d-block">Kesempatan Hari Ini</small>
                                <span class="fw-bold text-navy fs-5">{{ $maxSpinsPerDay }}x</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card border text-center p-3">
                                <small class="text-muted d-block">Sudah Digunakan</small>
                                <span class="fw-bold text-navy fs-5">{{ $todaySpins }}x</span>
                            </div>
                        </div>
                    </div>
                    @if(!$canSpin)
                        <div class="alert alert-warning mt-3 mb-0 small border-0 shadow-sm">
                            <i class="fas fa-info-circle me-1"></i> Kesempatan Anda sudah habis. Silakan kembali besok pukul 00:00!
                        </div>
                    @endif
                </div>
            </div>

            <div class="navy-card shadow-lg">
                <div class="navy-card-body text-center py-5">
                    <div class="wheel-container mx-auto mb-5">
                        <canvas id="wheelCanvas" width="400" height="400"></canvas>
                        <div class="wheel-pointer"></div>
                        <div class="wheel-center shadow">
                            <div class="wheel-center-inner">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>

                    <button id="spinBtn" class="btn btn-navy btn-lg px-5 py-3 rounded-pill fw-bold" 
                            {{ !$canSpin ? 'disabled' : '' }}>
                        <i class="fas fa-sync-alt me-2"></i> PUTAR SEKARANG
                    </button>
                    
                    <p class="text-muted small mt-4 mb-0">Klik tombol di atas untuk mendapatkan hadiah kursus atau voucher!</p>
                </div>
            </div>

            <div class="navy-card mt-4 shadow-sm">
                <div class="navy-card-header bg-navy">
                    <h6 class="mb-0 text-accent fw-bold"><i class="fas fa-history me-2"></i> Riwayat Spin Terakhir</h6>
                </div>
                <div class="navy-card-body p-0" id="historyList">
                    <div class="text-center py-4 text-muted">Memuat riwayat...</div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Wheel Styling */
    .wheel-container { position: relative; width: 400px; height: 400px; }
    #wheelCanvas { 
        width: 100%; height: 100%; border-radius: 50%; 
        transition: transform 4s cubic-bezier(0.15, 0, 0.15, 1); 
        border: 12px solid #0a192f;
        box-shadow: 0 0 20px rgba(10, 25, 47, 0.2);
    }
    .wheel-pointer { 
        position: absolute; top: -20px; left: 50%; transform: translateX(-50%); 
        width: 0; height: 0; border-left: 20px solid transparent; 
        border-right: 20px solid transparent; border-top: 40px solid #0a192f; 
        z-index: 20; filter: drop-shadow(0 4px 4px rgba(0,0,0,0.2));
    }
    .wheel-center { 
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
        width: 60px; height: 60px; background: white; border-radius: 50%; 
        border: 4px solid #0a192f; z-index: 15; 
    }
    .wheel-center-inner { 
        width: 100%; height: 100%; display: flex; align-items: center; 
        justify-content: center; color: #0a192f; font-size: 20px; 
    }

    /* Theme Utility */
    .bg-navy { background-color: #0a192f !important; }
    .text-navy { color: #0a192f !important; }
    .text-accent { color: #64ffda !important; }
    .badge-accent { background-color: #64ffda; color: #0a192f; font-weight: 700; }
    .btn-navy { background-color: #0a192f; color: #64ffda; border: none; transition: 0.3s; }
    .btn-navy:hover:not(:disabled) { background-color: #112240; color: #64ffda; transform: translateY(-3px); }
    .btn-navy:disabled { opacity: 0.6; cursor: not-allowed; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const spinBtn = document.getElementById('spinBtn');
const wheelCanvas = document.getElementById('wheelCanvas');
const ctx = wheelCanvas.getContext('2d');
const rewards = @json($rewards);

let currentRotation = 0;
let isSpinning = false;

// Fungsi menggambar roda
function drawWheel() {
    const numSegments = rewards.length;
    const anglePerSegment = (2 * Math.PI) / numSegments;
    const centerX = 200, centerY = 200, radius = 185;

    rewards.forEach((reward, i) => {
        const startAngle = i * anglePerSegment;
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, startAngle, startAngle + anglePerSegment);
        ctx.fillStyle = reward.color;
        ctx.fill();
        ctx.strokeStyle = "#ffffff";
        ctx.lineWidth = 2;
        ctx.stroke();

        // Gambar Teks Hadiah
        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(startAngle + anglePerSegment / 2);
        ctx.fillStyle = "white";
        ctx.font = "bold 13px Poppins";
        ctx.shadowColor = "rgba(0,0,0,0.5)";
        ctx.shadowBlur = 4;
        ctx.fillText(reward.name.substring(0, 16), 75, 5);
        ctx.restore();
    });
}

drawWheel();

spinBtn.addEventListener('click', function() {
    if (isSpinning) return;
    
    isSpinning = true;
    spinBtn.disabled = true;
    spinBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> MEMUTAR...';

    fetch('{{ route("user.spin.process") }}', {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) throw new Error(data.message);

        // Putar minimal 5 kali (1800 deg) + posisi hadiah
        const extraSpins = 360 * 5;
        // Pointer ada di atas (270 derajat), canvas mulai dari kanan (0 derajat)
        const stopDegree = extraSpins + (360 - data.degree) + 270;
        
        currentRotation += stopDegree;
        wheelCanvas.style.transform = `rotate(${currentRotation}deg)`;

        setTimeout(() => {
            Swal.fire({
                title: data.type === 'zonk' ? 'Yahhh...' : 'SELAMAT!',
                text: data.message,
                icon: data.type === 'zonk' ? 'info' : 'success',
                confirmButtonColor: '#0a192f'
            }).then(() => location.reload());
        }, 4500);
    })
    .catch(e => {
        Swal.fire('Error', e.message, 'error');
        isSpinning = false;
        spinBtn.disabled = false;
        spinBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i> PUTAR SEKARANG';
    });
});

// Load Riwayat
fetch('{{ route("user.spin.history") }}')
    .then(r => r.json())
    .then(data => {
        const historyList = document.getElementById('historyList');
        if (data.length === 0) {
            historyList.innerHTML = '<div class="p-4 text-center text-muted">Belum ada riwayat spin.</div>';
            return;
        }
        
        const html = data.map(h => `
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <div>
                    <span class="fw-bold text-navy d-block">${h.reward_detail}</span>
                    <small class="text-muted">${new Date(h.created_at).toLocaleString('id-ID')}</small>
                </div>
                <span class="badge ${h.result_type === 'zonk' ? 'bg-light text-muted' : 'bg-success'}">
                    ${h.result_type === 'zonk' ? 'Zonk' : 'Win'}
                </span>
            </div>
        `).join('');
        historyList.innerHTML = html;
    });
</script>
@endsection