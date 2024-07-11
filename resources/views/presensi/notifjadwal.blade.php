@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Absensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video{
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        .jam-digital {
        background-color: #27272783;
        position: absolute;
        top: 65px;
        right: 10px;
        z-index: 9999;
        width: 150px;
        border-radius: 10px;
        padding: 5px;
        }

        .jam-digital p {
        color: #fff;
        font-size: 16px;
        text-align: left;
        margin-top: 0;
        margin-bottom: 0;
        }

        #map { height: 200px; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
@section('content')
<div class="row" style="margin-top: 60px">
    <div class="col">
        <div class="alert alert-warning">
            <p>Maaf, Anda Tidak Memiliki Jadwal Jam Kerja Pada Hari Ini !, Silahkan Hubungi Admin</p>
        </div>
    </div>
</div>
@endsection

