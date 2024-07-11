@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }
    .datepicker-date-display{
        background-color: #0f3a7e !important;
    }

    #keterangan {
        height: 5rem !important;
    }
</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Izin Sakit</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form action="/izinsakit/{{ $dataizin->kode_izin }}/update" method="POST" id="frmizin" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" id="tgl_izin_dari" value="{{ $dataizin->tgl_izin_dari }}" name="tgl_izin_dari" placeholder="Dari Tanggal" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" id="tgl_izin_sampai" value="{{ $dataizin->tgl_izin_sampai }}" name="tgl_izin_sampai" placeholder="Sampai Tanggal" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="jml_hari" name="jml_hari" placeholder="Jumlah Hari" autocomplete="off" readonly>
                    </div>
                    @if ($dataizin->doc_sid != null)
                        <div class="row">
                            <div class="col-12">
                                @php
                                    $docsid = Storage::url('/uploads/sid/'.$dataizin->doc_sid);
                                @endphp
                                <img src="{{ url($docsid) }}" alt="" width="100px">
                            </div>
                        </div>
                    @endif
                    <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                        <input type="file" name="sid" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                        <label for="fileuploadInput">
                            <span>
                                <strong>
                                    <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" 
                                    aria-label="cloud upload outline"></ion-icon>
                                    <i>Tap to Upload SID</i>
                                </strong>
                            </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="keterangan" id="keterangan" value="{{ $dataizin->keterangan }}" class="form-control" placeholder="Keterangan" autocomplete="off"></input>
                    </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

$(document).ready(function() {
  $(".datepicker").datepicker({
    format: "yyyy-mm-dd"    
  });

  function loadjumlahhari() {
    var dari = $("#tgl_izin_dari").val();
    var sampai = $("#tgl_izin_sampai").val();
    var date1 = new Date(dari);
    var date2 = new Date(sampai);

    var Difference_In_Time = date2.getTime() - date1.getTime();
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

    if (dari == "" || sampai == "") {
        var jmlhari = 0;
    } else {
        var jmlhari = Difference_In_Days + 1;
    }
    
    $("#jml_hari").val(jmlhari + " Hari");
  }
  loadjumlahhari();
  $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
    loadjumlahhari();
  });

//   $("#tgl_izin").change(function(e){
//     var tgl_izin = $(this).val();
//     $.ajax({
//         type: 'POST',
//         url: '/presensi/cekpengajuanizin',
//         data: {
//             _token: "{{ csrf_token() }}",
//             tgl_izin: tgl_izin
//         },
//         cache:false,
//         success: function(respond){
//            if (respond == 1) {
//             Swal.fire({
//             title: 'Oops !',
//             text: 'Anda Sudah Melakukan Pengajuan Izin Hari Ini!',
//             icon: 'warning',
//             }).then((result) => {
//                 $("#tgl_izin").val("");
//             });
//            }
//         }
//     });
//   });

  $("#frmizin").submit(function(){
    var tgl_izin_dari = $("#tgl_izin_dari").val();
    var tgl_izin_sampai = $("#tgl_izin_sampai").val();
    var keterangan = $("#keterangan").val();
    if (tgl_izin_dari == "" || tgl_izin_sampai == "") {
        Swal.fire({
        title: 'Oops !',
        text: 'Tanggal Harus Di Isi',
        icon: 'warning',
        });
        return false;
    } else if (keterangan == "") {
        Swal.fire({
        title: 'Oops !',
        text: 'Keterangan Harus Di Isi',
        icon: 'warning',
        });
        return false;
    }
  });
});
    </script>
@endpush