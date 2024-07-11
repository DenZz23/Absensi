<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class PresensiController extends Controller
{
    public function gethari()
    {
        $hari = date("D");
        
        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;
                    
            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
            $hari_ini = "Rabu";
            break;

            case 'Thu':
            $hari_ini = "Kamis";
            break;

            case 'Fri':
            $hari_ini = "Jum'at";
            break;

            case 'Sat':
            $hari_ini = "Sabtu";
            break;
        }

        return $hari_ini;
    }



    public function create()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nik = Auth::guard('pegawai')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $jamkerja = DB::table('konfigurasi_jamkerja')
        ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->where('nik', $nik)->where('hari', $namahari)->first();

        if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        } else {
            return view('presensi.create', compact('cek', 'lok_kantor', 'jamkerja'));
        }
        
    }
    
    public function store(Request $request)
    {

        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
        $namahari = $this->gethari();
        $jamkerja = DB::table('konfigurasi_jamkerja')
        ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->where('nik', $nik)->where('hari', $namahari)->first();


        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik."-".$tgl_presensi."-".$ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName.".png";
        $file = $folderPath . $fileName;
        
        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf Anda Berada Di Luar Radius, Jarak Anda" . $radius . "Meter Dari Kantor|radius";
        } else {
             if ($cek > 0) {
                if ($jam < $jamkerja->jam_pulang) {
                    echo "error|Maaf Belum Waktunya Pulang|in";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        echo "success|Terimakasih, Hati Hati Di Jalan|out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen, Silahkan Hubungi Admin|out";
                    }
                }
                
            } else {
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error|Maaf Belum Waktunya Melakukan Presensi|in";
                } else if($jam > $jamkerja->akhir_jam_masuk) {
                    echo "error|Maaf Waktu Untuk Presensi Sudah Habis|in";
                }
                else {
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h'
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimakasih, Selamat Bekerja|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen, Silahkan Hubungi Admin|in";
                    }
                }
                
            }
        }
        
        
    }

     //Menghitung Jarak
     function distance($lat1, $lon1, $lat2, $lon2)
     {
         $theta = $lon1 - $lon2;
         $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
         $miles = acos($miles);
         $miles = rad2deg($miles);
         $miles = $miles * 60 * 1.1515;
         $feet = $miles * 5280;
         $yards = $feet / 3;
         $kilometers = $miles * 1.609344;
         $meters = $kilometers * 1000;
         return compact('meters');
     }

    public function editprofile() {
        
        $nik = Auth::guard('pegawai')->user()->nik;
        $pegawai = DB::table('pegawai')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('pegawai'));
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $pegawai = DB::table('pegawai')->where('nik', $nik)->first();
        $request->validate([
            'foto' => 'required|image|mimes:png,jpg|max:500'
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $pegawai->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('pegawai')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/pegawai/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
           return Redirect::back()->with(['success' => 'Data Berhasil di Update!']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal di Update!']);
        }
        
     }

     public function histori() {

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
     }

     public function gethistori(Request $request) {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('pegawai')->user()->nik;

        $histori = DB::table('presensi')
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->where('nik', $nik)
        ->orderBy('tgl_presensi')
        ->get();
        
        return view('presensi.gethistori', compact('histori'));
     }

     Public function izin(Request $request) {

        $nik = Auth::guard('pegawai')->user()->nik;

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari', 'desc')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin_dari)="'. $request->bulan . '"')
            ->whereRaw('YEAR(tgl_izin_dari)="'. $request->tahun . '"')
            ->get();
        } else {
            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari', 'desc')
            ->where('nik', $nik)->limit(5)->orderBy('tgl_izin_dari', 'desc')
            ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.izin', compact('dataizin', 'namabulan'));
     }

     public function buatizin() {
        return view('presensi.buatizin');
     }

     public function storeizin(Request $request) {
        
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
           return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Di Simpan']);
        } else {
            return redirect('/presensi/izin')->with(['error'=>'Data Gagal Di Simpan']);
        }
     }

     public function monitoring()
     {
        return view('presensi.monitoring');
     }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
        ->select('presensi.*', 'nama_lengkap', 'nama_div', 'jam_masuk', 'nama_jam_kerja')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->join('pegawai', 'presensi.nik', '=' , 'pegawai.nik')
        ->join('divisi', 'pegawai.kode_div', '=' , 'divisi.kode_div')
        ->where('tgl_presensi', $tanggal)
        ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi =  DB::table('presensi')->where('id', $id)
        ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
        ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawai')->orderBy('nama_lengkap')->get();

        return view('presensi.laporan', compact('namabulan', 'pegawai'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawai')->where('nik', $nik)
        ->join('divisi', 'pegawai.kode_div', '=', 'divisi.kode_div')
        ->first();

        $presensi = DB::table('presensi')
        ->select('presensi.*', 'keterangan', 'jam_kerja.*')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
        ->where('presensi.nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->orderBy('tgl_presensi')
        ->get();
        if (isset($_POST['exportexel'])) {
            $time = date("d-M-Y H:i:s");
            header("content-type: application/vnd-ms-exel");
            header("content-Disposition: attachment; filename=Laporan Presensi Pegawai $time.xls");
            return view('presensi.cetaklaporanexel', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
        ->selectRaw('presensi.nik,nama_lengkap,jam_masuk,jam_pulang,
        MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
        MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
        MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
        MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
        MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
        MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
        MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
        MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
        MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
        MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
        MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
        MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
        MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
        MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
        MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
        MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
        MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
        MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
        MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
        MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
        MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
        MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
        MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
        MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
        MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
        MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
        MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
        MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
        MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
        MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
        MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
        ->join('pegawai','presensi.nik','=','pegawai.nik')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->groupByRaw('presensi.nik,nama_lengkap,jam_masuk,jam_pulang')
        ->get();

        if (isset($_POST['exportexel'])) {
            $time = date("d-M-Y H:i:s");
            header("content-type: application/vnd-ms-exel");
            header("content-Disposition: attachment; filename=Rekap Presensi Pegawai $time.xls");
        }

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }

    public function izinsakit(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->select('kode_izin', 'tgl_izin_dari', 'tgl_izin_sampai', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('pegawai', 'pengajuan_izin.nik', '=', 'pegawai.nik');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if ($request->status_approved == '0' || $request->status_approved == '1' || $request->status_approved == '2' ) {
            $query->where('status_approved', $request->status_approved);
        }

        $query->orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
                while(strtotime($tgl_dari) <= strtotime($tgl_sampai)) {

                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            }
            
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                   'status_approved' => $status_approved ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses']);
        }
        // $update = DB::table('pengajuan_izin')->where('id', $kode_izin)->update([
        //     'status_approved' => $status_approved
        // ]);
        // if ($update) {
        //     return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        // } else {
        //     return Redirect::back()->with(['warning' => 'Data Berhasil Di Update']);
        // }
    }

    public function batalkanizinsakit($kode_izin)
    {

        DB::beginTransaction();
        try {
            $update = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => 0
            ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Dibatalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Dibatalkan']);
        }

        // $update = DB::table('pengajuan_izin')->where('id', $id)->update([
        //     'status_approved' => 0
        // ]);
        // if ($update) {
        //     return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        // } else {
        //     return Redirect::back()->with(['warning' => 'Data Berhasil Di Update']);
        // }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('pegawai')->user()->nik;
        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

    public function showact($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin) {

        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($doc_sid != null) {
                Storage::delete('public/uploads/sid/'. $doc_sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Dihapus']);
        }
    }
}
