<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page { 
    size: A4 
    }

    #title {
        font-family: Tahoma, Geneva, Verdana, sans-serif;
        font-size: 16px;
        font-weight: bold;
    }

    .kop {
         border-bottom: 7px solid #000;
         padding: 3px;
         width: 100%;
        }

    .tabeldatapegawai {
        margin-top: 40px;
    }

    .tabeldatapegawai tr td{
        padding: 5px;
    }

    .tabelpresensi {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .tabelpresensi tr th {
        border: 1px solid #070707;
        padding: 8px;
        background: #cc6f05;
    }

    .tabelpresensi tr td {
        border: 1px solid #070707;
        padding: 5px;
    }

    .foto {
        width: 40px;
        height: 30px;
    }

    .nama {
         text-align: center;
         height: 100px;
         vertical-align: bottom;
        }
        .tgl {
         text-align: center;
        }

  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
    @endphp

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table class="kop" style="width: 100%" >
        <tr>
            <td style="width: 40px">
                <img src="{{ asset('assets/img/presensi.png') }}" width="120" height="120" alt="">
            </td>
            <td>
                <span id="title">LAPORAN PRESENSI PEGAWAI <br>
                    PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                    <b>SMK AN NUR SLAWI</b><br>
                </span>
                <span class=""><i>Jl. R.A Kartini No 17, Kalisapu, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52461</i></span>
            </td>
        </tr>
    </table>

    <table class="tabeldatapegawai">
        <tr>
            <td rowspan="6">
                @php
                    $path = Storage::url('uploads/pegawai/'. $pegawai->foto);
                @endphp
                <img src="{{ url($path) }}" alt="" width="110px" height="140">
            </td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $pegawai->nik }}</td>
        </tr>
        <tr>
            <td>Nama Pegawai</td>
            <td>:</td>
            <td>{{ $pegawai->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $pegawai->jabatan }}</td>
        </tr>
        <tr>
            <td>Divisi</td>
            <td>:</td>
            <td>{{ $pegawai->nama_div }}</td>
        </tr>
        <tr>
            <td>No HP</td>
            <td>:</td>
            <td>{{ $pegawai->no_hp }}</td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto</th>
            <th>Jam Pulang</th>
            <th>Foto</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
        </tr>
        @foreach ($presensi as $d)
        @if ($d->status == "h")
            @php
                    $path_in = Storage::url('uploads/absensi/'. $d->foto_in);
                    $path_out = Storage::url('uploads/absensi/'. $d->foto_out);   
                    $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
                <td>{{ $d->jam_in }}</td>
                <td><img src="{{ url($path_in) }}" alt="" class="foto"></td>
                <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                <td>
                    @if ($d->jam_out != null)
                    <img src="{{ url($path_out) }}" alt="" class="foto">
                    @else
                    <img src="{{ asset('assets/img/kamera.jpg') }}" alt="" class="foto">
                    @endif
                </td>
                <td style="text-align: center">{{ $d->status }}</td>
                <td>
                    @if ($d->jam_in > $d->jam_masuk)
                        Terlambat {{ $jamterlambat }}
                        @else
                        Tepat Waktu
                    @endif
                </td>
                <td>
                    @if ($d->jam_out != null)
                        @php
                            $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                        @endphp
                        @else
                        @php
                            $jmljamkerja = 0;
                        @endphp
                    @endif
                    {{ $jmljamkerja }}
                </td>
            </tr>
            @else
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center">{{ $d->status }}</td>
                <td>{{ $d->keterangan }}</td>
                <td></td>
            </tr>
        @endif
                
        @endforeach
    </table>
<br><br>
    <table>
        <tr>
           <td width="500"></td>
           <td class="tgl">Slawi, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
           <td width="500"></td>
           <td class="tgl">
            SMK AN NUR SLAWI<br>
            Kepala Sekolah <br>
           </td>
        </tr>
        <tr>
           <td width="500"></td>
           <td class="nama"><u><b>Moh. Nasrullah, S.Pd</b></u><br>
        </td>
        </tr>
     </table>

  </section>
  <script>
    window.print();
  </script>

</body>

</html>