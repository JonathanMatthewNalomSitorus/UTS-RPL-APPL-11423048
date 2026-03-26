<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - MediTrack</title>
    <style>
        /* Gaya dasar tetap saya pertahankan agar tidak merusak UI kamu */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #1e293b;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            color: #38bdf8;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #334155;
            padding-bottom: 10px;
        }

        .sidebar a {
            display: block;
            color: #94a3b8;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #334155;
            color: white;
        }

        .sidebar a.active {
            background: #38bdf8;
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .card-table {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-size: 14px;
        }

        /* Badge Status */
        .badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-booking {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-rescheduled {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-diagnosed {
            background: #dcfce7;
            color: #166534;
        }

        /* Buttons */
        .action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-examine {
            background: #38bdf8;
            color: white;
        }

        .btn-reschedule {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-cancel {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
        }

        /* Modal / Form Inline Style */
        .reschedule-form {
            display: flex;
            gap: 4px;
        }

        .reschedule-form input {
            font-size: 12px;
            padding: 4px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>MediTrack Dr.</h2>
        <a href="{{ route('appointments.index') }}" class="active">Antrean Pasien</a>
        <a href="{{ route('doctor.history') }}">Riwayat Medis (EHR)</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout">Logout</button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1 style="margin:0; font-size: 24px; color: #1e293b;">Daftar Pemeriksaan Hari Ini</h1>
                <p style="color: #64748b; margin: 5px 0 0;">Dokter: <strong>{{ Auth::user()->name }}</strong> | {{ date('d M Y') }}</p>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            {{ session('success') }}
        </div>
        @endif

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pasien</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi Pengelolaan Jadwal (Fitur b) & EHR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $app)
                    <tr>
                        <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($app->appointment_date)->format('H:i') }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $app->patient->name }}</div>
                            <div style="font-size: 11px; color: #64748b;">ID: #PT-{{ $app->patient->id }}</div>
                        </td>
                        <td style="color: #64748b;">{{ Str::limit($app->notes ?? 'Pemeriksaan umum', 25) }}</td>
                        <td>
                            <span class="badge badge-{{ $app->status }}">
                                {{ strtoupper($app->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('appointments.edit', $app->id) }}" class="btn btn-examine">
                                    {{ $app->status == 'diagnosed' ? 'Lihat/Edit EHR' : 'Periksa' }}
                                </a>

                                <form action="{{ route('appointments.updateStatus', $app->id) }}" method="POST" class="reschedule-form">
                                    @csrf
                                    <input type="datetime-local" name="new_date" required>
                                    <button type="submit" name="action" value="reschedule" class="btn btn-reschedule" title="Atur Ulang Jadwal">Atur Jadwal</button>
                                    <button type="submit" name="action" value="cancel" class="btn btn-cancel" onclick="return confirm('Batalkan janji temu ini?')" title="Batalkan">Batal</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 60px; color: #94a3b8;">
                            <div style="font-size: 40px; margin-bottom: 10px;">☕</div>
                            Tidak ada antrean pasien untuk Anda saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>