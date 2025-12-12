<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->payment_code ?? 'N/A' }} - Pondok Pesantren Al-Quran Bani Syahid</title>
    <style>
        /* Import Font */
        @font-face {
    font-family: 'DejaVu Sans';
    src: url('https://fonts.googleapis.com/css2?family=DejaVu+Sans&display=swap');
    }

        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 0;
            margin: 0;
            font-size: 12px;
        }

        /* Invoice Container */
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }

        /* Header */
        .invoice-header {
            background: #057572;
            color: white;
            padding: 25px 30px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .institution-info h1 {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .institution-info .tagline {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 400;
        }

        .institution-info .address {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 6px;
            max-width: 280px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-title {
           font-family: 'DejaVu Sans', sans-serif;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 13px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 8px;
        }

        /* Content Area */
        .invoice-content {
            padding: 30px;
        }

        /* Info Sections */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            border-left: 4px solid #057572;
        }

        .info-card h3 {
            color: #057572;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e0e0e0;
            font-size: 11px;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: 600;
            text-align: right;
        }

        /* Items Table */
        .items-section {
            margin-bottom: 25px;
        }

        .section-title {
            color: #057572;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            font-size: 11px;
        }

        .items-table thead {
            background: #057572;
            color: white;
        }

        .items-table th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .items-table tbody tr:last-child {
            border-bottom: none;
        }

        .items-table td {
            padding: 10px 12px;
            vertical-align: top;
        }

        .item-name {
            font-weight: 600;
            color: #333;
        }

        .item-description {
            color: #666;
            font-size: 10px;
            margin-top: 2px;
            line-height: 1.3;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Total Section */
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }

        .total-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            width: 300px;
            border: 1px solid #eaeaea;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .total-label {
            color: #666;
        }

        .total-amount {
            color: #333;
            font-weight: 600;
        }

        .grand-total {
            font-size: 14px;
            font-weight: 700;
            color: #057572;
            border-top: 2px solid #eaeaea;
            padding-top: 12px;
            margin-top: 12px;
        }

        /* Payment Status */
        .payment-status {
            background: #e8f5e8;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #28a745;
        }

        .payment-status h3 {
            color: #155724;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .payment-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            font-size: 11px;
        }

        .payment-detail strong {
            color: #155724;
        }

        /* Notes */
        .notes-card {
            background: #fff9e6;
            border-radius: 6px;
            padding: 15px;
            border-left: 5px solid #ffc107;
        }

        .notes-card h3 {
            color: #856404;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notes-list {
            list-style: none;
            padding-left: 0;
            font-size: 11px;
        }

        .notes-list li {
            margin-bottom: 6px;
            color: #856404;
            padding-left: 18px;
            position: relative;
        }

        .notes-list li:before {
            content: "•";
            color: #ffc107;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 14px;
        }

        /* Footer */
        .invoice-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eaeaea;
        }

        .footer-text {
            color: #666;
            font-size: 10px;
            margin-bottom: 4px;
        }

        .footer-text strong {
            color: #057572;
        }

        /* Utility Classes */
        .mb-15 {
            margin-bottom: 15px;
        }

        .mt-25 {
            margin-top: 25px;
        }

        .color-primary {
            color: #057572;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .invoice-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .invoice-header {
                background: #057572 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .items-table thead {
                background: #057572 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .payment-status {
                background: #e8f5e8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .notes-card {
                background: #fff9e6 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }
        }
    </style>
</head>
<body>
    <!-- Error Handler -->
    @if(!isset($payment) || !$payment)
        <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
            <h2 style="color: #dc3545;">Error: Data invoice tidak ditemukan</h2>
            <p style="color: #666;">Silakan hubungi administrator.</p>
            <p style="color: #999; font-size: 11px; margin-top: 20px;">
                Invoice Code: {{ request()->route('paymentCode') ?? 'N/A' }}
            </p>
        </div>
    @else
        <!-- Invoice Content -->
        <div class="invoice-wrapper">
            <!-- Header -->
            <div class="invoice-header">
                <div class="header-content">
                    <div class="institution-info">
                        <h1>Pondok Pesantren Al-Quran Bani Syahid</h1>
                        <p class="tagline">Lembaga Pendidikan Islam Berbasis Al-Quran dan Sunnah</p>
                        <p class="address">
                            Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis<br>
                            Kota Depok, Jawa Barat 16452<br>
                            Telp: (021) 1234-5678 | Email: admin@banisyahid.sch.id
                        </p>
                    </div>

                    <div class="invoice-meta">
                        <div class="invoice-title">INVOICE</div>
                        <div class="invoice-number">#{{ $payment->payment_code }}</div>
                        <div style="margin-top: 10px; font-size: 10px; opacity: 0.9;">
                            Tanggal: {{ $payment->created_at ? $payment->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="invoice-content">
                <!-- Informasi Santri dan Pembayaran -->
                <div class="info-grid mb-15">
                    <div class="info-card">
                        <h3>Informasi Santri</h3>
                        <div class="info-row">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $payment->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ID Pendaftaran</span>
                            <span class="info-value">{{ $payment->registration->id_pendaftaran ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $payment->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nomor Telepon</span>
                            <span class="info-value">{{ $payment->user->phone_number ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3>Detail Pembayaran</h3>
                        <div class="info-row">
                            <span class="info-label">Metode Pembayaran</span>
                            <span class="info-value">
                                @if($payment->payment_method === 'cash')
                                <strong>CASH</strong>
                                @else
                                <strong>ONLINE (XENDIT)</strong>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal Pembayaran</span>
                            <span class="info-value">
                                @if($payment->paid_at)
                                    {{ $payment->paid_at->translatedFormat('d F Y, H:i') }}
                                @else
                                    {{ $payment->created_at->translatedFormat('d F Y, H:i') }}
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Paket Program</span>
                            <span class="info-value">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                        </div>
                        {{-- <div class="info-row">
                            <span class="info-label">Program Unggulan</span>
                            <span class="info-value">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                        </div> --}}
                    </div>
                </div>

                <!-- Rincian Biaya -->
                <div class="items-section">
                    <h3 class="section-title">Rincian Biaya</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Deskripsi Item</th>
                                <th style="width: 10%;" class="text-center">Qty</th>
                                <th style="width: 20%;" class="text-right">Harga Satuan</th>
                                <th style="width: 20%;" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Item Utama -->
                            <tr>
                                <td>
                                    <div class="item-name">Biaya Pendaftaran Santri</div>
                                    <div class="item-description">
                                        Paket: {{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}
                                        | Paket: {{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}
                                    </div>
                                </td>
                                <td class="text-center">1</td>
                                <td class="text-right">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                            </tr>

                            <!-- Detail Paket Harga -->
                            @if(isset($packagePrices) && $packagePrices->count() > 0)
                                @foreach($packagePrices as $price)
                                <tr>
                                    <td>
                                        <div class="item-name">{{ $price->item_name }}</div>
                                        @if($price->description)
                                        <div class="item-description">{{ $price->description }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">1</td>
                                    <td class="text-right">Rp {{ number_format($price->amount, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($price->amount, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @endif

                            <!-- Summary Row -->
                            <tr style="background: #f8f9fa; font-weight: 600;">
                                <td colspan="3" class="text-right">Total Biaya</td>
                                <td class="text-right">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total Section -->
                <div class="total-section">
                    <div class="total-card">
                        <div class="total-row">
                            <span class="total-label">Subtotal</span>
                            <span class="total-amount">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Diskon</span>
                            <span class="total-amount">Rp 0</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Pajak</span>
                            <span class="total-amount">Rp 0</span>
                        </div>
                        <div class="total-row grand-total">
                            <span class="total-label">TOTAL PEMBAYARAN</span>
                            <span class="total-amount color-primary">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="payment-status">
                    <h3>
                        <span class="status-badge">
                            @if($payment->status == 'success')
                                LUNAS
                            @elseif($payment->status == 'pending')
                                PENDING
                            @elseif($payment->status == 'failed')
                                GAGAL
                            @else
                                {{ strtoupper($payment->status) }}
                            @endif
                        </span>
                        Status Pembayaran
                    </h3>
                    <div class="payment-details-grid">
                        <div class="payment-detail">
                            <strong>Kode Referensi:</strong> {{ $payment->payment_code }}
                        </div>
                        <div class="payment-detail">
                            <strong>Metode:</strong> {{ $payment->payment_method === 'cash' ? 'Cash' : 'Online Payment' }}
                        </div>
                        <div class="payment-detail">
                            <strong>Status:</strong> {{ $payment->status_label ?? ucfirst($payment->status) }}
                        </div>
                        <div class="payment-detail">
                            <strong>Waktu Transaksi:</strong>
                            @if($payment->paid_at)
                                {{ $payment->paid_at->translatedFormat('d F Y, H:i:s') }}
                            @else
                                {{ $payment->created_at->translatedFormat('d F Y, H:i:s') }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="notes-card">
                    <h3>Informasi & Catatan Penting</h3>
                    <ul class="notes-list">
                        <li>Invoice ini merupakan bukti pembayaran yang sah dan dapat digunakan untuk keperluan administrasi</li>
                        <li>Simpan invoice ini dengan baik sebagai bukti transaksi</li>
                        <li>Untuk pertanyaan atau bantuan terkait pembayaran, silakan hubungi admin pesantren</li>
                        <li>Pembayaran sudah termasuk semua biaya administrasi pendaftaran</li>
                        <li>Status pendaftaran akan aktif setelah pembayaran diverifikasi oleh sistem</li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                <p class="footer-text">
                    Invoice ini dibuat secara otomatis oleh <strong>Sistem Informasi Pondok Pesantren Al-Quran Bani Syahid</strong>
                </p>
                <p class="footer-text">
                    Dicetak pada: {{ now()->translatedFormat('d F Y, H:i:s') }}
                </p>
                @if(isset($isAdmin) && $isAdmin)
                <p class="footer-text" style="color: #dc3545; font-weight: bold;">
                    [Dicetak oleh Administrator]
                </p>
                @endif
                <p class="footer-text mt-25" style="font-style: italic;">
                    "Menuntut ilmu adalah kewajiban bagi setiap muslim" - HR. Ibnu Majah
                </p>
            </div>
        </div>
    @endif
</body>
</html>
