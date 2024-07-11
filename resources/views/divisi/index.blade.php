@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Data Divisi
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
                                <a href="#" class="btn btn-primary" id="btntambahdivisi">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 5l0 14"></path>
                                        <path d="M5 12l14 0"></path>
                                     </svg>
                                    Tambah Data Divisi
                                </a>
                            </div>
                        </div>
                        {{-- <div class="row mt-2">
                            <div class="col-12">
                                <form action="/divisi" method="GET">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <input type="text" name="nama_div" id="nama_div" class="form-control" placeholder="Nama Divisi" value="{{ Request('nama_div') }}">
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
                        </div> --}}
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Divisi</th>
                                            <th>Nama Divisi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($divisi as $d)
                                           <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_div }}</td>
                                            <td>{{ $d->nama_div }}</td>
                                            <td>
                                                <div class="btn-group">
                                                <a href="#" class="edit btn btn-info btn-sm" kode_div="{{ $d->kode_div }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                     </svg>
                                                </a>
                                                <form action="/divisi/{{ $d->kode_div }}/delete" method="POST" style="margin-left: 5px">
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
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>   
</div>


<div class="modal modal-blur fade" id="modal-inputdivisi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Divisi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/divisi/store" method="POST" id="frmdivisi">
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
                        <input type="text" value="" class="form-control" id="kode_div" name="kode_div" placeholder="Kode Divisi">
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
                        <input type="text" value="" class="form-control" id="nama_div" name="nama_div" placeholder="Nama Divisi">
                    </div>
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
  <div class="modal modal-blur fade" id="modal-editdivisi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Divisi</h5>
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
            $("#btntambahdivisi").click(function(){
                $("#modal-inputdivisi").modal("show");
            });

            $(".edit").click(function(){
                var kode_div = $(this).attr('kode_div');
                $.ajax({
                    type: 'POST',
                    url: '/divisi/edit',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token(); }}",
                        kode_div: kode_div
                    },
                    success: function(respond){
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editdivisi").modal("show");
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

            $("#frmdivisi").submit(function(){
                var kode_div = $("#kode_div").val();
                var nama_div = $("#nama_div").val();
                if (kode_div=="") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Kode Divisi Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#kode_div").focus();
                    });
                    return false;
                } else if (nama_div=="") {
                    Swal.fire({
                    title: 'Opps!',
                    text: 'Nama Divisi Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                    }).then(()=>{
                        $("#nama_div").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush