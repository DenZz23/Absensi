<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        // $divisi = DB::table('divisi')->orderBy('kode_div')->get();
        $nama_div = $request->nama_div;
        $query = Divisi::query();
        $query ->select('*');
        if (!empty($nama_div)) {
            $query->where('nama_div', 'Like', '%' . $nama_div . '%');
        }
        $divisi = $query->get();
        return view('divisi.index', compact('divisi'));
    }

    public function store(Request $request)
    {
        $kode_div = $request->kode_div;
        $nama_div = $request->nama_div;
        $data = [
            'kode_div' => $kode_div,
            'nama_div' => $nama_div
        ];

        $cek = DB::table('divisi')->where('kode_div', $kode_div)->count();
        if ($cek>0) {
            return Redirect::back()->with(['warning' => 'Data Dengan Kode Divisi.' . $kode_div . 'Sudah Digunakan']);
        }
        $simpan = DB::table('divisi')->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {
        $kode_div = $request->kode_div;
        $divisi = DB::table('divisi')->where('kode_div', $kode_div)->first();
        return view('divisi.edit', compact('divisi'));
    }

    public function update($kode_div, Request $request)
    {
        $nama_div = $request->nama_div;
        $data = [
            'nama_div' => $nama_div
        ];

        $update = DB::table('divisi')->where('kode_div', $kode_div)->update($data);
        if ($update) {
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update!']);
        } else {
            return Redirect::back()->with(['Warning'=>'Data Gagal Di Update!']);
        }
    }
    
    public function delete($kode_div)
    {
        $hapus = DB::table('divisi')->where('kode_div', $kode_div)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success'=>'Data Berhasil Di Hapus!']);
        } else {
            return Redirect::back()->with(['Warning'=>'Data Gagal Di Hapus!']);
        }
    } 
}
