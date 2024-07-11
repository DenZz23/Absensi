@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Pegawai
                </h2>
            </div>      
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                </div>                                    
                                @endif

                                @if (Session::get('warning'))
                                <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                </div>                                    
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btntambahpegawai">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 5l0 14"></path>
                                        <path d="M5 12l14 0"></path>
                                     </svg>
                                    Tambah Data Pegawai
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/pegawai" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" placeholder="Nama Pegawai" value="{{ Request('nama_pegawai') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="kode_div" id="kode_div" class="form-select">
                                                    <option value="">Divisi</option>
                                                    @foreach ($divisi as $d)
                                                        <option {{ Request('kode_div') == $d->kode_div ? 'selected' : '' }}
                                                            value="{{ $d->kode_div }}">{{ $d->nama_div }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                        <path d="M21 21l-6 -6"></path>
                                                     </svg>
                                                     Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nik</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>No Hp</th>
                                            <th>Foto</th>
                                            <th>Divisi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pegawai as $d)
                                        @php
                                            $path = Storage::url('uploads/pegawai/'.$d->foto);
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration + $pegawai->firstItem()-1 }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->nama_lengkap }}</td>
                                                <td>{{ $d->jabatan }}</td>
                                                <td>{{ $d->no_hp }}</td>
                                                <td>
                                                    @if (empty($d->foto))
                                                    <img src="{{ asset('assets/img/nopoto.png') }}" class="avatar" alt="">
                                                    @else
                                                    <img src="{{ url($path) }}" class="avatar" alt="">
                                                    @endif                                           
                                                </td>
                                                <td>{{ $d->nama_div }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="edit btn btn-info btn-sm" nik="{{ $d->nik }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                                <path d="M16 5l3 3"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="/konfigurasi/{{ $d->nik }}/setjamkerja" class="btn btn-success btn-sm" style="margin-left: 5px">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                                <path d="M12 10l0 3l2 0"></path>
                                                                <path d="M7 4l-2.75 2"></path>
                                                                <path d="M17 4l2.75 2"></path>
                                                             </svg>
                                                        </a>
                                                        <form action="/pegawai/{{ $d->nik }}/delete" method="POST" style="margin-left: 5px">
                                                            @csrf
                                                            <a class="btn btn-danger btn-sm delete-confirm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" stroke-width="0" fill="currentColor"></path>
                                                                    <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" stroke-width="0" fill="currentColor"></path>
                                                                </svg>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $pegawai->links() }}
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>   
</div>


<div class="modal modal-blur fade" id="modal-inputpegawai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Pegawai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/pegawai/store" method="POST" id="frmpegawai" enctype="multipart/form-data">
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
                        <input type="text" maxlength="19" value="" class="form-control" id="nik" name="nik" placeholder="NIK">
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
                        <input type="text" value="" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap">
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
                        <input type="text" value="" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan">
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
                        <input type="text" value="" class="form-control" id="no_hp" name="no_hp" placeholder="No Hp">
                      </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="form-label">Foto Pegawai</div>
                        <input type="file" name="foto" class="form-control">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <select name="kode_div" id="kode_div" class="form-select">
                        <option value="">Divisi</option>
                        @foreach ($divisi as $d)
                            <option {{ Request('kode_div') == $d->kode_div ? 'selected' : '' }}
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
        </div>
        
      </div>
    </div>
  </div>

  {{-- Modal Edit Pegawai --}}
  <div class="modal modal-blur fade" id="modal-editpegawai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Pegawai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">
          
        </div>
        
      </div>
    </div>
  </div>
@endsection

@push('myscript')
    <script>
        $(function(){

            $("#nik").mask("0000000000000000000");
            $("#no_hp").mask("0000000000000");
            $("#btntambahpegawai").click(function(){
                $("#modal-inputpegawai").modal("show");
            });

            $(".edit").click(function(){
                var nik = $(this).attr('nik');
                $.ajax({
                    type: 'POST',
                    url: '/pegawai/edit',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token(); }}",
                        nik: nik
                    },
                    success: function(respond){
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editpegawai").modal("show");
            });

            $(".delete-confirm").click(function(e){
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                title: 'Apakah Anda Yakin Data Ini Akan Di Hapus?',
                text: "Periksa Terlebih Dahulu Data Yang Akan Di hapus | Jika Ya Maka Data Akan Terhapus Pemanent!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire(
                    'Terhapus!',
                    'Data Berhasil Di Hapus.',
                    'success'
                    )
                }
                })
            });

            $("#frmpegawai").submit(function(){
                var nik = $("#nik").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var no_hp = $("#no_hp").val();
                var kode_div = $("#frmpegawai").find("#kode_div").val();
                if (nik=="") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                    title: 'Opps!',
                    text: 'NIK Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#nik").focus();
                    });
                    return false;
                } else if (nama_lengkap=="") {
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Nama Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#nama_lengkap").focus();
                    });
                    return false;
                }
                else if (jabatan=="") {
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Jabatan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#jabatan").focus();
                    });
                    return false;
                }
                else if (no_hp=="") {
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Nomor Hp Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#no_hp").focus();
                    });
                    return false;
                }
                else if (kode_div=="") {
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Kode Divisi Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#kode_div").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush