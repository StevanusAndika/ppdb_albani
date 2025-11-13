<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->payment_code }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #057572;
        }

        .pesantren-info h1 {
            color: #057572;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .pesantren-info .tagline {
            color: #666;
            font-size: 14px;
            font-weight: 400;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            color: #057572;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .invoice-title .number {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #057572;
        }

        .info-box h3 {
            color: #057572;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        .info-value {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #057572;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .total-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .total-row.grand-total {
            font-size: 18px;
            font-weight: 700;
            color: #057572;
            border-top: 2px solid #e9ecef;
            padding-top: 10px;
            margin-top: 10px;
        }

        .payment-info {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin-bottom: 30px;
        }

        .payment-info h3 {
            color: #28a745;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .notes {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 30px;
        }

        .notes h3 {
            color: #856404;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .notes ul {
            list-style: none;
            padding-left: 0;
        }

        .notes li {
            margin-bottom: 5px;
            font-size: 14px;
            color: #856404;
        }

        .notes li:before {
            content: "•";
            color: #856404;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            max-width: 150px;
            height: auto;
        }

        @media print {
            body {
                background: white !important;
            }

            .invoice-container {
                padding: 0;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .info-section {
                page-break-inside: avoid;
            }

            .items-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="pesantren-info">
                <h1>Pondok Pesantren Al-Qur'an Bani Syahid</h1>
                <p class="tagline">Lembaga Pendidikan Islam Berbasis Al-Qur'an dan Sunnah</p>
                <p style="margin-top: 10px; font-size: 14px; color: #666;">
                   Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452<br>
                    Telp: (021) 1234-5678 | Email: admin@banisyahid.sch.id
                </p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p class="number">#{{ $payment->payment_code }}</p>
                <p style="margin-top: 10px; font-size: 14px; color: #666;">
                    Tanggal: {{ $payment->created_at->translatedFormat('d F Y') }}<br>
                    Status: <span class="status-badge status-success">LUNAS</span>
                </p>
            </div>
        </div>

        <!-- Informasi Santri dan Pembayaran -->
        <div class="info-section">
            <div class="info-box">
                <h3>Informasi Santri</h3>
                <div class="info-row">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value">{{ $payment->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ID Pendaftaran:</span>
                    <span class="info-value">{{ $payment->registration->id_pendaftaran }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $payment->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telepon:</span>
                    <span class="info-value">{{ $payment->user->phone_number ?? '-' }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>Detail Pembayaran</h3>
                <div class="info-row">
                    <span class="info-label">Metode Pembayaran:</span>
                    <span class="info-value">
                        @if($payment->payment_method === 'cash')
                        <strong>Cash</strong>
                        @else
                        <strong>Online (Xendit)</strong>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Bayar:</span>
                    <span class="info-value">
                        @if($payment->paid_at)
                            {{ $payment->paid_at->translatedFormat('d F Y H:i') }}
                        @else
                            {{ $payment->created_at->translatedFormat('d F Y H:i') }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    {{-- <span class="info-label">Program Unggulan:</span>
                    <span class="info-value">
                        @if($payment->registration->programUnggulan && $payment->registration->programUnggulan->judul)
                            {{ $payment->registration->programUnggulan->judul }}
                        @else
                            Tidak ada program unggulan
                        @endif --}}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Paket:</span>
                    <span class="info-value">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                </div>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="width: 100px; text-align: center;">Qty</th>
                    <th style="width: 150px; text-align: right;">Harga Satuan</th>
                    <th style="width: 150px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Biaya Pendaftaran Santri</strong><br>
                        <small>Paket: {{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</small><br>
                        <small>Program:
                            @if($payment->registration->programUnggulan && $payment->registration->programUnggulan->judul)
                                {{ $payment->registration->programUnggulan->judul }}
                            @else
                                Tidak ada program unggulan
                            @endif
                        </small>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">{{ $payment->formatted_amount }}</td>
                    <td style="text-align: right;">{{ $payment->formatted_amount }}</td>
                </tr>
                <!-- Jika ada rincian harga dari package -->
                @if(isset($packagePrices) && $packagePrices->count() > 0)
                    @foreach($packagePrices as $price)
                    <tr>
                        <td>
                            <strong>{{ $price->item_name }}</strong>
                            @if($price->description)
                            <br><small>{{ $price->description }}</small>
                            @endif
                        </td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: right;">Rp {{ number_format($price->amount, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($price->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Total -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>{{ $payment->formatted_amount }}</span>
                </div>
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>{{ $payment->formatted_amount }}</span>
                </div>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="payment-info">
            <h3>Informasi Pembayaran</h3>
            <div class="payment-details">
                <div>
                    <strong>Status:</strong>
                    <span class="status-badge status-success">LUNAS</span>
                </div>
                <div>
                    <strong>Metode:</strong>
                    {{ $payment->payment_method === 'cash' ? 'Cash' : 'Transfer Online' }}
                </div>
                <div>
                    <strong>Tanggal Bayar:</strong>
                    @if($payment->paid_at)
                        {{ $payment->paid_at->translatedFormat('d F Y H:i') }}
                    @else
                        {{ $payment->created_at->translatedFormat('d F Y H:i') }}
                    @endif
                </div>
                <div>
                    <strong>Kode Referensi:</strong>
                    {{ $payment->payment_code }}
                </div>
            </div>
        </div>

        <!-- QR Code -->
        @if($payment->registration->qr_code_url)
        <div class="qr-code">
            <img src="{{ $payment->registration->qr_code_url }}" alt="QR Code">
            <p style="margin-top: 10px; font-size: 12px; color: #666;">
                Scan QR Code untuk verifikasi
            </p>
        </div>
        @endif

        <!-- Catatan -->
        <div class="notes">
            <h3>Catatan Penting</h3>
            <ul>
                <li>Invoice ini merupakan bukti pembayaran yang sah</li>
                <li>Simpan invoice ini untuk keperluan administrasi</li>
                <li>Untuk pertanyaan terkait pembayaran, hubungi admin pesantren</li>
                <li>Pembayaran sudah termasuk biaya administrasi pendaftaran</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Pondok Pesantren Al-Qur'an Bani Syahid</strong><br>
                 Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452 | Telp: (021) 1234-5678<br>
                Email: admin@banisyahid.sch.id | Website: www.banisyahid.sch.id
            </p>
            <p style="margin-top: 10px;">
                Invoice ini dibuat otomatis pada {{ now()->translatedFormat('d F Y H:i:s') }}
            </p>
        </div>

        <!-- Print Button (Hanya tampil di browser) -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="
                background: #057572;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                margin-right: 10px;
            ">
                <i class="fas fa-print"></i> Print Invoice
            </button>


        </div>
    </div>

    <script>
        function downloadPDF() {
            // Redirect ke route download PDF
            window.location.href = "{{ route('santri.payments.download-invoice-pdf', $payment->payment_code) }}";
        }

        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
