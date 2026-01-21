@extends('layouts.user')

@section('content')
<style>
    .wheel-container {
        position: relative;
        width: 300px;
        height: 300px;
        margin: 0 auto;
    }
    #wheel {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
        transition: transform 4s cubic-bezier(0.17, 0.67, 0.83, 0.67);
        border: 5px solid #333;
    }
    .wheel-segment {
        position: absolute;
        width: 50%;
        height: 50%;
        background-color: var(--seg-color);
        transform-origin: bottom right;
        top: 0;
        left: 0;
        clip-path: polygon(0 0, 100% 0, 100% 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .marker {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 0; 
        height: 0; 
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-top: 25px solid red;
        z-index: 10;
    }
    .spin-btn {
        margin-top: 20px;
    }
</style>

<div class="container text-center mt-5">
    <h3>Daily Spin Wheel</h3>
    <p>Kesempatan hari ini: <span id="status-text">{{ $canSpin ? 'Masih Ada' : 'Habis' }}</span></p>

    <div class="wheel-container">
        <div class="marker"></div>
        <canvas id="wheelCanvas" width="300" height="300"></canvas>
    </div>

    <button id="btn-spin" class="btn btn-primary spin-btn" {{ !$canSpin ? 'disabled' : '' }}>
        PUTAR SEKARANG
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const btnSpin = document.getElementById('btn-spin');
    
    // Data dari controller
    const rewards = @json($rewards);
    const numSegments = rewards.length;
    const arcSize = (2 * Math.PI) / numSegments;
    let currentRotation = 0;

    // Gambar Roda
    function drawWheel() {
        rewards.forEach((reward, i) => {
            const angle = i * arcSize;
            ctx.beginPath();
            ctx.fillStyle = reward.color; // Pastikan di DB ada kolom warna (hex code)
            ctx.moveTo(150, 150);
            ctx.arc(150, 150, 150, angle, angle + arcSize);
            ctx.lineTo(150, 150);
            ctx.fill();
            
            // Text
            ctx.save();
            ctx.translate(150, 150);
            ctx.rotate(angle + arcSize / 2);
            ctx.textAlign = "right";
            ctx.fillStyle = "#fff";
            ctx.font = "bold 14px Arial";
            ctx.fillText(reward.name, 130, 5);
            ctx.restore();
        });
    }

    drawWheel();

    btnSpin.addEventListener('click', function() {
        if(this.hasAttribute('disabled')) return;

        // Kunci tombol agar tidak diklik 2x
        btnSpin.disabled = true;

        // Request ke Backend
        fetch("{{ route('user.spin.process') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                Swal.fire('Gagal', data.message, 'error');
                btnSpin.disabled = true; // Matikan permanen karena limit habis
                return;
            }

            // Animasi Putar
            const winningId = data.reward_id;
            const winningIndex = rewards.findIndex(r => r.id === winningId);
            
            // Hitung derajat putaran agar jarum berhenti di item yang menang
            // Logic: Total putaran (5x360) + posisi item
            // Perlu penyesuaian matematika sedikit karena canvas 0 derajat ada di kanan (jam 3)
            const segmentAngle = 360 / numSegments;
            // Kita ingin jarum (di atas/jam 12) menunjuk ke segmen.
            // Putaran acak + putaran pasti agar berhenti di index yang benar
            
            const stopAngle = 270 - (winningIndex * segmentAngle) - (segmentAngle / 2); 
            // Tambahkan putaran ekstra (misal 5 kali putaran penuh = 1800 derajat)
            const totalRotation = 1800 + stopAngle;

            // Gunakan CSS transform untuk memutar canvas
            canvas.style.transition = "transform 4s cubic-bezier(0.17, 0.67, 0.83, 0.67)";
            canvas.style.transform = `rotate(${totalRotation}deg)`;

            // Setelah animasi selesai (4 detik)
            setTimeout(() => {
                Swal.fire({
                    title: 'Hasil Spin!',
                    text: data.message,
                    icon: data.message.includes('Zonk') ? 'warning' : 'success'
                }).then(() => {
                    location.reload(); // Reload untuk update status limit
                });
            }, 4000);

        })
        .catch(err => {
            console.error(err);
            btnSpin.disabled = false;
            Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
        });
    });
</script>
@endsection