@extends('layouts.app')
@section('title', 'Camera QR Scan')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div style="width: 500px; max-width: 100%; text-align: center;">

        {{-- Kotak kamera / QR view --}}
        <div id="camera-wrapper"
             style="background:#9e9e9e; border-radius:8px; padding:8px; margin-bottom:20px;">
            <div id="qr-reader"
                 style="width:100%; height:260px; border-radius:6px; overflow:hidden;"></div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('camera-test.store') }}" id="camera-form">
            @csrf

            <div style="margin-bottom:16px;">
                <input type="text"
                       name="field_input"
                       id="field_input"
                       placeholder="Field Input"
                       class="form-control"
                       style="height:44px; text-align:center;">
            </div>

            <button type="submit"
                    style="width:100%; height:48px; border:none; border-radius:8px;
                           background:#007b3a; color:white; font-weight:600;">
                Submit
            </button>
        </form>
    </div>
</div>

{{-- LIBRARY QR CODE VIA CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputField = document.getElementById('field_input');
        const qrRegionId = "qr-reader";
        const html5QrCode = new Html5Qrcode(qrRegionId);

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        };

        function onScanSuccess(decodedText, decodedResult) {

            // Tampilkan hasil QR ke input
            inputField.value = decodedText;

            // Jika QR berisi link → auto buka tab baru
            if (decodedText.startsWith("http://") || decodedText.startsWith("https://")) {
                window.open(decodedText, "_blank");
            }

            document.getElementById('camera-form').submit();

            // Stop scanner agar tidak scan terus
            html5QrCode.stop();
        }

        function onScanFailure(error) {
            // error ringan abaikan
        }


        // start kamera menggunakan facingMode
        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Tidak dapat memulai scanner:", err);
            alert("Tidak dapat memulai scanner: " + err);
        });
    });
    
</script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: {!! json_encode(session('success')) !!},
            confirmButtonText: 'OK'
        }).then((result) => {
            @if(session('scanned_url'))
                const url = "{{ session('scanned_url') }}";

                // Cek apakah datanya benar-benar URL
                if (url.startsWith('http://') || url.startsWith('https://')) {
                    // Buka di tab baru setelah user klik OK
                    window.open(url, '_blank');
                }
            @endif
        });
    });
</script>
@endif
@endsection

