<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Download Laporan Keluhan</title>
    <style>
        body { 
            font-family: sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #F875AA;
            padding-bottom: 10px;
        }
        .header h2 { 
            margin: 0; 
            padding: 0; 
            color: #177FB9; 
            font-size: 20px;
        }
        .header p { 
            margin: 5px 0 0 0; 
            color: #555; 
            font-weight: bold;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #F875AA; 
            color: white; 
            text-align: center;
        }
        /* Pewarnaan Status */
        .status-pending { color: #FF0000; font-weight: bold; }
        .status-process { color: #D28C00; font-weight: bold; }
        .status-complete { color: #008000; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Data Pengelolaan Laporan Keluhan Warga</h2>
        <h2>MAKESENS-KALI</h2>
        <p>
            Tanggal Data: 
            {{ empty($date) ? 'Keseluruhan Waktu' : \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama Pelapor</th>
                <th width="15%">Waktu Kejadian</th>
                <th width="18%">Lokasi</th>
                <th width="22%">Deskripsi Keluhan</th>
                <th width="15%">Estimasi Biaya</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($reports) > 0)
                @php $no = 1; @endphp
                @foreach($reports as $item)
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $item['nama_pelapor'] ?? '-' }}</td>
                        <td>{{ $item['tanggal_kejadian'] ?? '-' }}</td>
                        <td>{{ $item['lokasi_kejadian'] ?? '-' }}</td>
                        <td>{{ $item['deskripsi_keluhan'] ?? '-' }}</td>
                        <td>
                            {{ isset($item['estimasi_biaya']) && is_numeric($item['estimasi_biaya']) ? 'Rp ' . number_format($item['estimasi_biaya'], 0, ',', '.') : ($item['estimasi_biaya'] ?? '-') }}
                        </td>
                        <td style="text-align: center;">
                            @php $status = $item['status'] ?? 'pending'; @endphp
                            @if($status == 'complete')
                                <span class="status-complete">Selesai</span>
                            @elseif($status == 'process')
                                <span class="status-process">Proses</span>
                            @else
                                <span class="status-pending">Tertunda</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic; padding: 20px;">
                        Tidak ada data laporan keluhan masyarakat pada tanggal ini.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>