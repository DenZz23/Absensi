<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinsakitController extends Controller
{
    public function create() {
        return view('sakit.create');
    }


    public function store(Request $request) {
        
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "s";
        $keterangan = $request->keterangan;

        $bulan = date("m", strtotime($tgl_izin_dari));
        $tahun = date("Y", strtotime($tgl_izin_dari));
        $thn = substr($tahun,2,2);
        $lastizin = DB::table('pengajuan_izin')
        ->whereRaw('MONTH(tgl_izin_dari)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_izin_dari)="'.$tahun.'"')
        ->orderBy('kode_izin', 'desc')
        ->first();
        $lastkodeizin = $lastizin != null ? $lastizin->kode_izin : "";
        $format = "IZ".$bulan.$thn;
        $kode_izin = buatkode($lastkodeizin,$format,3);
        // dd($kode_izin);
        //Simpan File SID
        
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        }else {
            $sid = null;
        } 
        
        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            //Simpan File SID
            if ($request->hasFile('sid')) {
                $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                $folderPath = "public/uploads/sid/";
                $request->file('sid')->storeAs($folderPath, $sid);
            }
           return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Di Simpan']);
        } else {
            return redirect('/presensi/izin')->with(['error'=>'Data Gagal Di Simpan']);
        }
    }

    public function edit($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('sakit.edit', compact('dataizin'));
    }

    public function update($kode_izin, Request $request) {
        
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;
        
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        }else {
            $sid = null;
        }
        
        $data = [
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];

        try {
            DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->update($data);
            if ($request->hasFile('sid')) {
                $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                $folderPath = "public/uploads/sid/";
                $request->file('sid')->storeAs($folderPath, $sid);
            }
            return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error'=>'Data Gagal Di Update']);
        }
    }
}
