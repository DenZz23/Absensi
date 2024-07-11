@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Set Jam Kerja
                </h2>
            </div>      
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <tr>
                        <th>NIK :</th>
                        <td>{{ $pegawai->nik }}</td>
                    </tr>
                    <tr>
                        <th>NAMA PEGAWAI :</th>
                        <td>{{ $pegawai->nama_lengkap }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <form action="/konfigurasi/storesetjamkerja" method="POST">
                    @csrf
                    <input type="hidden" name="nik" value="{{ $pegawai->nik }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Senin
                                    <input type="hidden" name="hari[]" value="Senin">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Selasa
                                    <input type="hidden" name="hari[]" value="Selasa">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Rabu
                                    <input type="hidden" name="hari[]" value="Rabu">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Kamis
                                    <input type="hidden" name="hari[]" value="Kamis">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Jum'at
                                    <input type="hidden" name="hari[]" value="Jum'at">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Sabtu
                                    <input type="hidden" name="hari[]" value="Sabtu">
                                </td>
                                <td>
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary w-100" type="submit">Simpan</button>
                </form>
            </div>
            <div class="col-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="6">Master Jam Kerja</th>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Awal Masuk</th>
                            <th>Jam Masuk</th>
                            <th>Akhir Masuk</th>
                            <th>Jam Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jamkerja as $d)
                            <tr>
                                <td>{{ $d->kode_jam_kerja }}</td>
                                <td>{{ $d->nama_jam_kerja }}</td>
                                <td>{{ $d->awal_jam_masuk }}</td>
                                <td>{{ $d->jam_masuk }}</td>
                                <td>{{ $d->akhir_jam_masuk }}</td>
                                <td>{{ $d->jam_pulang }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection