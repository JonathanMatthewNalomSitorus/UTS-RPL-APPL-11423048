<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MediTrack</title>
    <style>
        /* Gaya dasar kamu tetap dipertahankan */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            margin: 0;
            display: flex;
            background: #f4f7f6;
        }

        .sidebar {
            width: 260px;
            background: #2c3e50;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            color: #2ecc71;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #bdc3c7;
            padding: 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #34495e;
            color: white;
        }

        .sidebar a.active {
            background: #2ecc71;
            color: white;
        }

        .content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-top: 4px solid #2ecc71;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        .stat-card p {
            margin: 10px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Table Card (Fitur f) */
        .card-table {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-table h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #f4f7f6;
            padding-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f8f9fa;
            color: #7f8c8d;
            font-size: 13px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }

        .btn-pay {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-pay:hover {
            background: #27ae60;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>MediTrack</h2>
        <a href="#" class="active">Dashboard Utama</a>
        <hr style="border: 0.5px solid #455a64; margin: 20px 0;">
    </div>

    <div class="content">
        <div class="header">
            <div>
                <h2 style="margin:0;">Halo, {{ Auth::user()->name }}</h2>
                <small>Role: <span style="color: #2ecc71;">{{ strtoupper(Auth::user()->role) }}</span></small>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pasien</h3>
                <p>{{ $total_patients }}</p>
            </div>
            <div class="stat-card" style="border-top-color: #3498db;">
                <h3>Antrean Booking</h3>
                <p>{{ $pending_appointments }}</p>
            </div>
            <div class="stat-card" style="border-top-color: #e67e22;">
                <h3>Stok Kritis</h3>
                <p>{{ $low_stock_medicines }}</p>
            </div>
            <div class="stat-card" style="border-top-color: #9b59b6;">
                <h3>Pendapatan Hari Ini</h3>
                <p>Rp {{ number_format($today_revenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="card-table">
            <h3>
                Kasir: Penagihan Pasien Baru Selesai Periksa
                <small style="font-size: 12px; color: #95a5a6; font-weight: normal;">Menunggu Pembayaran</small>
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>Waktu Selesai</th>
                        <th>Nama Pasien</th>
                        <th>Dokter Pemeriksa</th>
                        <th>Total Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending_payments as $pay)
                    <tr>
                        <td>{{ $pay->updated_at->format('H:i') }}</td>
                        <td><strong>{{ $pay->patient->name }}</strong></td>
                        <td>Dr. {{ $pay->doctor->name }}</td>
                        <td style="color: #e67e22; font-weight: bold;">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('admin.pay', $pay->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-pay" onclick="return confirm('Konfirmasi pembayaran tunai?')">Terima Pembayaran</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #bdc3c7; padding: 30px;">
                            Tidak ada pasien yang menunggu pembayaran saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>