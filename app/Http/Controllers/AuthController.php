<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function proseslogin(Request $request)
   {
    if (Auth::guard('pegawai')->attempt(['nik' => $request->nik, 'password' => $request->password])) {
        return redirect('/dashboard');
    }else {
        return redirect('/')->with(['warning' => 'NIK / Password Salah!']);
    }

   }

   public function proseslogout()
   {
    if (Auth::guard('pegawai')->check()) {
        Auth::guard('pegawai')->logout();
        return redirect('/');
    }
   }

   public function proseslogoutadmin()
   {
    if (Auth::guard('user')->check()) {
        Auth::guard('user')->logout();
        return redirect('/panel');
    }
   }

   public function prosesloginadmin(Request $request)
   {
    if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
        return redirect('/panel/dashboardadmin');
    }else {
        return redirect('/panel')->with(['warning' => 'Email / Password Salah!']);
    }

   }
}

