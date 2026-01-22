@extends('layouts.user')

@section('title', 'Spin Wheel')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="navy-card mb-4">
                <div class="navy-card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white"><i class="fas fa-gift me-2"></i> Daily Spin Wheel</h4>
                    <span class="badge badge-accent">{{ $maxSpinsPerDay - $todaySpins }}/{{ $maxSpinsPerDay }} Spin Tersisa</span>
                </div>
                <div class="navy-card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <div class="stat-card mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-sync-alt fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Kesempatan Spin</h6>
                                        <p class="mb-0 fw-bold">{{ $maxSpinsPerDay - $todaySpins }} dari {{ $maxSpinsPerDay }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-calendar-day fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Reset Harian</h6>
                                        <p class="mb-0 fw-bold">Setiap pukul 00:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$canSpin)
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-1">Kesempatan Spin Habis</h6>
                            <p class="mb-0">Anda sudah menggunakan kesempatan spin hari ini. Silakan kembali besok!</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="navy-card">
                <div class="navy-card-body text-center p-4">
                    <div class="wheel-container mx-auto mb-5 position-relative">
                        <canvas id="wheelCanvas" width="400" height="400"></canvas>
                        <div class="wheel-pointer"></div>
                        <div class="wheel-center">
                            <div class="wheel-center-inner">
                                <i class="fas fa-gift"></i>
                            </div>
                        </div>
                        <div class="wheel-outer-ring"></div>
                    </div>

                    <div class="mb-4">
                        <button id="spinBtn" class="btn btn-accent btn-lg px-5 py-3 shadow-lg" 
                                {{ !$canSpin ? 'disabled' : '' }}>
                            <i class="fas fa-redo-alt me-2"></i> 
                            <span class="fw-bold">PUTAR SEKARANG</span>
                        </button>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted mb-2">
                            <small><i class="fas fa-info-circle me-1"></i> Dapatkan hadiah menarik setiap hari dengan spin gratis</small>
                        </p>
                    </div>

                    <div id="resultContainer" class="mt-4" style="display: none;">
                        <div class="alert shadow" id="resultAlert">
                            <div class="d-flex align-items-center mb-3">
                                <div id="resultIcon" class="me-3 fs-2"></div>
                                <div>
                                    <h4 id="resultTitle" class="alert-heading mb-1"></h4>
                                    <p id="resultMessage" class="mb-0"></p>
                                </div>
                            </div>
                            <div id="resultDetails" class="mt-3"></div>
                            <div class="mt-4">
                                <button id="closeResultBtn" class="btn btn-navy btn-sm">Tutup</button>
                                <button id="viewVoucherBtn" class="btn btn-accent btn-sm ms-2" style="display: none;">Lihat Voucher</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="navy-card mt-4">
                <div class="navy-card-header">
                    <h5 class="mb-0 text-white"><i class="fas fa-award me-2"></i> Daftar Hadiah</h5>
                </div>
                <div class="navy-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">Warna</th>
                                    <th>Hadiah</th>
                                    <th width="120" class="text-center">Nilai</th>
                                    <th width="100" class="text-center">Peluang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewards as $reward)
                                <tr class="align-middle">
                                    <td>
                                        <div class="reward-color-indicator" style="background-color: {{ $reward->color }}; width: 24px; height: 24px; border-radius: 4px; margin: 0 auto;"></div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $reward->name }}</h6>
                                            <small class="text-muted">
                                                @if($reward->type == 'voucher')
                                                Voucher diskon
                                                @elseif($reward->type == 'zonk')
                                                Tidak mendapatkan hadiah
                                                @else
                                                {{ ucfirst(str_replace('_', ' ', $reward->type)) }}
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($reward->type == 'voucher' && $reward->voucher_amount > 0)
                                        <span class="fw-bold text-success">Rp {{ number_format($reward->voucher_amount, 0, ',', '.') }}</span>
                                        @elseif($reward->type == 'zonk')
                                        <span class="text-muted">-</span>
                                        @else
                                        <span class="fw-bold text-primary">GRATIS</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-navy">{{ $reward->probability }}%</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb me-1"></i>
                                Total peluang: {{ $rewards->sum('probability') }}%
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-history me-1"></i>
                                Update terakhir: {{ now()->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="navy-card mt-4">
                <div class="navy-card-header">
                    <h5 class="mb-0 text-white"><i class="fas fa-history me-2"></i> Riwayat Spin</h5>
                </div>
                <div class="navy-card-body">
                    <div id="spinHistory">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="mt-2 text-muted">Memuat riwayat spin...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wheel-container {
    position: relative;
    width: 400px;
    height: 400px;
    margin: 0 auto;
    filter: drop-shadow(0 10px 20px rgba(10, 25, 47, 0.15));
}

#wheelCanvas {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
}

.wheel-pointer {
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 18px solid transparent;
    border-right: 18px solid transparent;
    border-top: 30px solid #0a192f;
    z-index: 20;
    filter: drop-shadow(0 3px 5px rgba(0,0,0,0.2));
}

.wheel-pointer::after {
    content: '';
    position: absolute;
    top: -30px;
    left: -8px;
    width: 16px;
    height: 16px;
    background: #0a192f;
    border-radius: 50%;
}

.wheel-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 30px rgba(0,0,0,0.2);
    z-index: 15;
    border: 5px solid #0a192f;
}

.wheel-center-inner {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.wheel-center i {
    font-size: 28px;
    color: #64ffda;
}

.wheel-outer-ring {
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    border: 3px solid rgba(100, 255, 218, 0.3);
    border-radius: 50%;
    z-index: 5;
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.05); opacity: 0.8; }
    100% { transform: scale(1); opacity: 0.5; }
}

#spinBtn {
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

#spinBtn:not(:disabled):hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(100, 255, 218, 0.3);
}

#spinBtn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    background: #6c757d;
    border-color: #6c757d;
}

.spinning {
    animation: button-pulse 1.5s infinite;
}

@keyframes button-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.spin-loader {
    display: inline-block;
    width: 22px;
    height: 22px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-right: 12px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.reward-color-indicator {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.reward-color-indicator:hover {
    transform: scale(1.1);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(10, 25, 47, 0.05);
    transform: translateX(5px);
}

#resultAlert {
    border-left: 5px solid;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.spin-history-item {
    padding: 15px;
    border-left: 4px solid;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.spin-history-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .wheel-container {
        width: 300px;
        height: 300px;
    }
    
    #wheelCanvas {
        width: 300px;
        height: 300px;
    }
    
    .wheel-center {
        width: 60px;
        height: 60px;
    }
    
    .wheel-center-inner {
        width: 45px;
        height: 45px;
    }
    
    .wheel-center i {
        font-size: 20px;
    }
    
    #spinBtn {
        width: 100%;
        padding: 15px;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const spinBtn = document.getElementById('spinBtn');
const wheelCanvas = document.getElementById('wheelCanvas');
const ctx = wheelCanvas.getContext('2d');
const resultContainer = document.getElementById('resultContainer');
const resultAlert = document.getElementById('resultAlert');
const resultIcon = document.getElementById('resultIcon');
const resultTitle = document.getElementById('resultTitle');
const resultMessage = document.getElementById('resultMessage');
const resultDetails = document.getElementById('resultDetails');
const closeResultBtn = document.getElementById('closeResultBtn');
const viewVoucherBtn = document.getElementById('viewVoucherBtn');
const spinHistory = document.getElementById('spinHistory');

const rewards = @json($rewards);
const numSegments = rewards.length;
const centerX = wheelCanvas.width / 2;
const centerY = wheelCanvas.height / 2;
const radius = Math.min(centerX, centerY) - 15;
let currentRotation = 0;
let isSpinning = false;

function drawWheel() {
    ctx.clearRect(0, 0, wheelCanvas.width, wheelCanvas.height);
    
    const anglePerSegment = (2 * Math.PI) / numSegments;
    
    rewards.forEach((reward, index) => {
        const startAngle = index * anglePerSegment;
        const endAngle = startAngle + anglePerSegment;
        
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.closePath();
        
        ctx.fillStyle = reward.color;
        ctx.fill();
        
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 3;
        ctx.stroke();
        
        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(startAngle + anglePerSegment / 2);
        
        ctx.textAlign = 'right';
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 16px "Poppins", Arial';
        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.shadowBlur = 3;
        ctx.shadowOffsetX = 1;
        ctx.shadowOffsetY = 1;
        ctx.fillText(reward.name, radius - 25, 5);
        
        ctx.restore();
    });
    
    drawCenterCircle();
    drawOuterRing();
}

function drawCenterCircle() {
    ctx.beginPath();
    ctx.arc(centerX, centerY, 35, 0, 2 * Math.PI);
    
    const gradient = ctx.createRadialGradient(
        centerX, centerY, 15,
        centerX, centerY, 35
    );
    gradient.addColorStop(0, '#ffffff');
    gradient.addColorStop(1, '#f0f0f0');
    
    ctx.fillStyle = gradient;
    ctx.fill();
    
    ctx.strokeStyle = '#0a192f';
    ctx.lineWidth = 5;
    ctx.stroke();
    
    ctx.fillStyle = '#0a192f';
    ctx.font = 'bold 24px "Poppins", Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.shadowColor = 'rgba(0,0,0,0.2)';
    ctx.shadowBlur = 2;
    ctx.fillText('SPIN', centerX, centerY);
}

function drawOuterRing() {
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius + 5, 0, 2 * Math.PI);
    ctx.strokeStyle = 'rgba(100, 255, 218, 0.2)';
    ctx.lineWidth = 10;
    ctx.stroke();
}

function showResult(data) {
    let alertClass = '';
    let icon = '';
    let borderColor = '';
    
    switch(data.type) {
        case 'voucher':
            alertClass = 'alert-success';
            icon = '<i class="fas fa-ticket-alt"></i>';
            borderColor = '#28a745';
            break;
        case 'free_course':
            alertClass = 'alert-primary';
            icon = '<i class="fas fa-graduation-cap"></i>';
            borderColor = '#007bff';
            break;
        case 'zonk':
            alertClass = 'alert-warning';
            icon = '<i class="fas fa-times-circle"></i>';
            borderColor = '#ffc107';
            break;
        default:
            alertClass = 'alert-info';
            icon = '<i class="fas fa-gift"></i>';
            borderColor = '#17a2b8';
    }
    
    resultAlert.className = `alert ${alertClass} border-start-0`;
    resultAlert.style.borderLeftColor = borderColor;
    resultIcon.innerHTML = icon;
    resultTitle.innerHTML = data.reward_name || 'Hasil Spin';
    resultMessage.textContent = data.message;
    
    if (data.type === 'voucher') {
        resultDetails.innerHTML = `
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2"><i class="fas fa-tag me-2"></i>Detail Voucher</h6>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-3">Kode:</span>
                                <code class="bg-white px-2 py-1 rounded border">SPIN-******</code>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="me-3">Nilai:</span>
                                <span class="fs-5 fw-bold text-success">Rp ${data.amount.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white p-3 rounded border d-inline-block">
                                <small class="d-block text-muted">Berlaku hingga</small>
                                <strong class="d-block">30 Hari</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 small text-muted"><i class="fas fa-info-circle me-1"></i> Voucher dapat digunakan untuk semua pembelian course</p>
                        <p class="mb-0 small text-muted"><i class="fas fa-clock me-1"></i> Berlaku 30 hari dari sekarang</p>
                    </div>
                </div>
            </div>
        `;
        viewVoucherBtn.style.display = 'inline-block';
    } else if (data.type === 'free_course') {
        resultDetails.innerHTML = `
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-book me-2"></i>Course Gratis</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                        <div>
                            <p class="mb-1">Course gratis telah ditambahkan ke akun Anda</p>
                            <p class="mb-0 small text-muted">Akses segera melalui menu "Kursus Saya"</p>
                        </div>
                    </div>
                    <a href="{{ route('user.courses.index') }}" class="btn btn-navy w-100">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Course Saya
                    </a>
                </div>
            </div>
        `;
        viewVoucherBtn.style.display = 'none';
    } else {
        resultDetails.innerHTML = `
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-4">
                    <i class="fas fa-redo fa-3x text-muted mb-3"></i>
                    <h6 class="mb-2">Tetap Semangat!</h6>
                    <p class="mb-3 text-muted">Coba lagi besok untuk kesempatan mendapatkan hadiah menarik</p>
                    <div class="d-flex justify-content-center">
                        <div class="text-center mx-3">
                            <div class="bg-white p-2 rounded-circle mb-2" style="width: 60px; height: 60px; line-height: 56px; border: 2px solid #0a192f;">
                                <i class="fas fa-calendar text-primary"></i>
                            </div>
                            <small>Spin Harian</small>
                        </div>
                        <div class="text-center mx-3">
                            <div class="bg-white p-2 rounded-circle mb-2" style="width: 60px; height: 60px; line-height: 56px; border: 2px solid #0a192f;">
                                <i class="fas fa-gift text-success"></i>
                            </div>
                            <small>Hadiah Menarik</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        viewVoucherBtn.style.display = 'none';
    }
    
    resultContainer.style.display = 'block';
    
    setTimeout(() => {
        resultContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

function loadSpinHistory() {
    fetch('/user/spin/history')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let historyHtml = '';
                data.forEach(item => {
                    let icon = '';
                    let borderColor = '';
                    
                    if (item.result_type === 'voucher') {
                        icon = '<i class="fas fa-ticket-alt text-success"></i>';
                        borderColor = '#28a745';
                    } else if (item.result_type === 'free_course') {
                        icon = '<i class="fas fa-graduation-cap text-primary"></i>';
                        borderColor = '#007bff';
                    } else {
                        icon = '<i class="fas fa-times-circle text-warning"></i>';
                        borderColor = '#ffc107';
                    }
                    
                    historyHtml += `
                        <div class="spin-history-item" style="border-left-color: ${borderColor}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 fs-4">${icon}</div>
                                    <div>
                                        <h6 class="mb-1">${item.reward_detail}</h6>
                                        <small class="text-muted">${new Date(item.created_at).toLocaleDateString('id-ID', { 
                                            weekday: 'long', 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}</small>
                                    </div>
                                </div>
                                <span class="badge ${item.result_type === 'zonk' ? 'bg-warning' : 'bg-success'}">
                                    ${item.result_type === 'zonk' ? 'Zonk' : 'Menang'}
                                </span>
                            </div>
                        </div>
                    `;
                });
                spinHistory.innerHTML = historyHtml;
            } else {
                spinHistory.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada riwayat spin</h6>
                        <p class="text-muted small">Mulai spin pertama Anda sekarang!</p>
                    </div>
                `;
            }
        })
        .catch(() => {
            spinHistory.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h6 class="text-danger">Gagal memuat riwayat</h6>
                    <p class="text-muted small">Silakan refresh halaman</p>
                </div>
            `;
        });
}

drawWheel();

spinBtn.addEventListener('click', function() {
    if (isSpinning || spinBtn.disabled) return;
    
    isSpinning = true;
    spinBtn.disabled = true;
    spinBtn.innerHTML = '<span class="spin-loader"></span> SEDANG MEMUTAR...';
    spinBtn.classList.add('spinning');
    resultContainer.style.display = 'none';
    
    fetch('{{ route("user.spin.process") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            throw new Error(data.message);
        }
        
        const extraSpins = 360 * 5;
        const targetRotation = extraSpins + (360 - data.degree);
        
        wheelCanvas.style.transform = `rotate(${targetRotation}deg)`;
        
        setTimeout(() => {
            showResult(data);
            
            spinBtn.disabled = true;
            spinBtn.classList.remove('spinning');
            spinBtn.innerHTML = '<i class="fas fa-check me-2"></i> SUDAH DIPUTAR';
            
            isSpinning = false;
            
            setTimeout(() => {
                loadSpinHistory();
                
                Swal.fire({
                    title: 'Spin Berhasil!',
                    text: 'Status spin telah diperbarui',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    willClose: () => {
                        location.reload();
                    }
                });
            }, 1000);
        }, 4000);
    })
    .catch(error => {
        console.error('Spin Error:', error);
        
        Swal.fire({
            title: 'Gagal!',
            text: error.message || 'Terjadi kesalahan saat memproses spin',
            icon: 'error',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#0a192f'
        });
        
        spinBtn.disabled = false;
        spinBtn.classList.remove('spinning');
        spinBtn.innerHTML = '<i class="fas fa-redo-alt me-2"></i> PUTAR SEKARANG';
        isSpinning = false;
    });
});

closeResultBtn.addEventListener('click', function() {
    resultContainer.style.display = 'none';
});

// viewVoucherBtn.addEventListener('click', function() {
//     window.location.href = '{{ route("user.profiles.show", auth()->id()) }}';
// });

window.addEventListener('load', function() {
    if (!@json($canSpin)) {
        spinBtn.disabled = true;
        spinBtn.innerHTML = '<i class="fas fa-clock me-2"></i> SUDAH DIPAKAI';
    }
    
    loadSpinHistory();
});
</script>
@endsection