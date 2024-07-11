<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Rekap Presensi Pegawai</title>

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
        font-size: 10px;
    }

    .tabelpresensi tr td {
        border: 1px solid #070707;
        padding: 5px;
        font-size: 12px;
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
<body class="A4 landscape">

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
                <span id="title">REKAP PRESENSI PEGAWAI <br>
                    PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                    <b>SMK AN NUR SLAWI</b><br>
                </span>
                <span class=""><i>Jl. R.A Kartini No 17, Kalisapu, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52461</i></span>
            </td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">Nik</th>
            <th rowspan="2">Nama Pegawai</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">TT</th>
        </tr>
        <tr>
            <?php
                for ($i=1; $i <= 31; $i++) { 
            ?>
            <th>{{ $i }}</th>
            <?php
                }
            ?>
        </tr>
        @foreach ($rekap as $d)
            <tr>
                <td>{{ $d->nik }}</td>
                <td>{{ $d->nama_lengkap }}</td>
                
                <?php
                $totalhadir = 0;
                $totalterlambat = 0;
                for ($i=1; $i <= 31; $i++) {
                    $tgl = "tgl_".$i;
                    if (empty($d->$tgl)) {
                        $hadir = ['',''];
                        $totalhadir += 0;
                    } else {
                        $hadir = explode("-",$d->$tgl);
                        $totalhadir += 1;
                        if ($hadir[0] > $d->jam_masuk) {
                            $totalterlambat += 1;
                        }
                    }
                ?>
                <td>
                    <span style="color:{{ $hadir[0] > $d->jam_masuk ? "red" : "" }}">{{ !empty($hadir[0]) ? $hadir[0] : '-'}}</span><br>
                    <span style="color:{{ $hadir[1] < $d->jam_pulang ? "red" : "" }}">{{ !empty($hadir[1]) ? $hadir[1] : '-'}}</span>
                </td>
                <?php
                    }
                ?>
                <td>{{ $totalhadir }}</td>
                <td>{{ $totalterlambat }}</td>
            </tr>
        @endforeach
    </table>
    <br><br><br>
    <table>
        <tr>
           <td width="800"></td>
           <td class="tgl">Slawi, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
           <td width="800"></td>
           <td class="tgl">
            SMK AN NUR SLAWI <br>
            Kepala Sekolah <br>
           </td>
        </tr>
        <tr>
           <td width="800"></td>
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