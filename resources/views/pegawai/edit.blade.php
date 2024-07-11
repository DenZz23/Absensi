<form action="/pegawai/{{ $pegawai->nik }}/update" method="POST" id="frmpegawai" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-barcode" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M4 7v-1a2 2 0 0 1 2 -2h2"></path>
                    <path d="M4 17v1a2 2 0 0 0 2 2h2"></path>
                    <path d="M16 4h2a2 2 0 0 1 2 2v1"></path>
                    <path d="M16 20h2a2 2 0 0 0 2 -2v-1"></path>
                    <path d="M5 11h1v2h-1z"></path>
                    <path d="M10 11l0 2"></path>
                    <path d="M14 11h1v2h-1z"></path>
                    <path d="M19 11l0 2"></path>
                 </svg>
                </span>
                <input type="text" readonly value="{{ $pegawai->nik }}" class="form-control" id="nik" name="nik" placeholder="NIK">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                 </svg>
                </span>
                <input type="text" value="{{ $pegawai->nama_lengkap }}" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-analytics" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z"></path>
                    <path d="M7 20l10 0"></path>
                    <path d="M9 16l0 4"></path>
                    <path d="M15 16l0 4"></path>
                    <path d="M8 12l3 -3l2 2l3 -3"></path>
                 </svg>
                </span>
                <input type="text" value="{{ $pegawai->jabatan }}" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan">
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                 </svg>
                </span>
                <input type="text" value="{{ $pegawai->no_hp }}" class="form-control" id="no_hp" name="no_hp" placeholder="No Hp">
              </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-label">Foto Pegawai</div>
                <input type="file" name="foto" class="form-control">
                <input type="hidden" name="old_foto" value="{{ $pegawai->foto }}">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <select name="kode_div" id="kode_div" class="form-select">
                <option value="">Divisi</option>
                @foreach ($divisi as $d)
                    <option {{ $pegawai->kode_div == $d->kode_div ? 'selected' : '' }}
                        value="{{ $d->kode_div }}">{{ $d->nama_div }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-transfer-in" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 18v3h16v-14l-8 -4l-8 4v3"></path>
                        <path d="M4 14h9"></path>
                        <path d="M10 11l3 3l-3 3"></path>
                     </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
  </form>