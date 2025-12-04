<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->payment_code }} - ADMIN COPY</title>
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
            font-size: 12px;
            position: relative;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Watermark untuk admin */
        .admin-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(5, 117, 114, 0.1);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #057572;
        }

        .pesantren-info h1 {
            color: #057572;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .pesantren-info .tagline {
            color: #666;
            font-size: 10px;
            font-weight: 400;
        }

        .pesantren-info p {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .invoice-title {
            text-align: right;
            position: relative;
        }

        .invoice-title h2 {
            color: #057572;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .invoice-title .number {
            color: #666;
            font-size: 10px;
            font-weight: 500;
        }

        /* Admin Stamp */
        .admin-stamp {
            position: absolute;
            top: -20px;
            right: 0;
            padding: 8px 12px;
            background: #057572;
            color: white;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            transform: rotate(15deg);
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #057572;
        }

        .info-box h3 {
            color: #057572;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .items-table th {
            background: #057572;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .total-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: 700;
            color: #057572;
            border-top: 2px solid #e9ecef;
            padding-top: 8px;
            margin-top: 8px;
        }

        .payment-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #28a745;
            margin-bottom: 20px;
        }

        .payment-info h3 {
            color: #28a745;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 10px;
        }

        .notes {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #ffc107;
            margin-bottom: 20px;
        }

        .notes h3 {
            color: #856404;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .notes ul {
            list-style: none;
            padding-left: 0;
            font-size: 10px;
        }

        .notes li {
            margin-bottom: 4px;
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

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        /* Footer khusus admin */
        .admin-footer {
            background: #f0f8ff;
            padding: 15px;
            border-top: 2px dashed #057572;
            text-align: center;
            margin-top: 30px;
        }

        .admin-footer p {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }

        .admin-footer .print-info {
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-container {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="admin-watermark">SALINAN ADMIN</div>

    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="pesantren-info">
                <h1>Pondok Pesantren Al-Quran Bani Syahid</h1>
                <p class="tagline">Lembaga Pendidikan Islam Berbasis Al-Quran dan Sunnah</p>
                <p>
                    Jl. Kp. Tipar Tengah, RT.5/RW.10, Mekarsari, Kec. Cimanggis, Kota Depok, Jawa Barat 16452<br>
                    Telp: (021) 1234-5678 | Email: admin@banisyahid.sch.id
                </p>
            </div>
            <div class="invoice-title">
                <div class="admin-stamp">SALINAN ADMIN</div>
                <h2>INVOICE</h2>
                <p class="number">#{{ $payment->payment_code }}</p>
                <p style="margin-top: 5px; font-size: 10px; color: #666;">
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
                    <span class="info-value">{{ $payment->registration->id_pendaftaran ?? '-' }}</span>
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
                        <strong>Online</strong>
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
                    <span class="info-label">Paket:</span>
                    <span class="info-value">{{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Pendaftaran:</span>
                    <span class="info-value">
                        @if($payment->registration->status_pendaftaran === 'diterima')
                        <strong style="color: #28a745;">Diterima</strong>
                        @else
                        {{ $payment->registration->status_pendaftaran ?? '-' }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="width: 60px; text-align: center;">Qty</th>
                    <th style="width: 100px; text-align: right;">Harga Satuan</th>
                    <th style="width: 100px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Biaya Pendaftaran Santri</strong><br>
                        <small>Paket: {{ $payment->registration->package->name ?? 'Paket Pendaftaran' }}</small>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
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

                <!-- Row untuk total -->
                <tr style="background: #f8f9fa;">
                    <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
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

        <!-- Catatan -->
        <div class="notes">
            <h3>Catatan Penting</h3>
            <ul>
                <li>Invoice ini merupakan salinan untuk administrasi pesantren</li>
                <li>Simpan invoice ini untuk keperluan audit dan laporan</li>
                <li>Data santri telah diverifikasi dan diterima</li>
                <li>Pembayaran sudah termasuk biaya administrasi pendaftaran</li>
            </ul>
        </div>

        <!-- Footer Admin -->
        <div class="admin-footer">
            <p>INVOICE ADMIN - Pondok Pesantren Al-Quran Bani Syahid</p>
            <p>Dicetak oleh: {{ auth()->user()->name }} | ID Admin: {{ auth()->id() }}</p>
            <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
            <p class="print-info">Dokumen ini dicetak secara otomatis oleh sistem pesantren</p>
        </div>
    </div>
</body>
</html>
