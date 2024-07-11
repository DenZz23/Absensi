<?php

namespace App\Http\Controllers;

use App\Models\Setjamkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function jamkerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }

    public function storejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang
        ];

        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }

    public function editjamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jamkerja'));
    }

    public function updatejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $data = [
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang
        ];

        try {
            DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function deletejamkerja($kode_jam_kerja)
    {
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function setjamkerja($nik)
    {
        $pegawai = DB::table('pegawai')->where('nik', $nik)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cekjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->count();
        // dd($cekjamkerja);
        if ($cekjamkerja > 0) {
            $setjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->get();
            return view('konfigurasi.editsetjamkerja', compact('pegawai', 'jamkerja', 'setjamkerja'));
        } else {
            return view('konfigurasi.setjamkerja', compact('pegawai', 'jamkerja'));
        } 
    }

    public function storesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        
        for ($i=0; $i < count($hari); $i++) { 
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }

        try {
            Setjamkerja::insert($data);
            return redirect('/pegawai')->with(['success' => 'Jam Kerja Berhasil Di Seting']);
        } catch (\Exception $e) {
            return redirect('/pegawai')->with(['warning' => 'Jam Kerja Gagal Di Seting']);
        }
    }

    public function updatesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        
        for ($i=0; $i < count($hari); $i++) { 
            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }

        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jamkerja')->where('nik', $nik)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return redirect('/pegawai')->with(['success' => 'Jam Kerja Berhasil Di Seting']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/pegawai')->with(['warning' => 'Jam Kerja Gagal Di Seting']);
        }
    }
}
