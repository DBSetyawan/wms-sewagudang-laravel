@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Tambah Stock Count
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject' => $kodeproject])}}">Gudang
        ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('inventory.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inventory</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('stock.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Stock Count</a>
</li>
<li class="breadcrumb-item active">Tambah Stock Count
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Data Stock Count</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Kode Stock Count</label>
                                        <input type="text" id="nama_perusahaan" class="form-control kode_stock_count"
                                            name="fname" placeholder="" value="{{$new_nostockcount}}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tanggal_kontrak">Tanggal</label>
                                        <input type='text' class="form-control tanggal" placeholder="Tanggal Kontrak" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="tambahStockCount()">Tambah Stock Count</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function tambahStockCount() {
        let kode_stock = $(".kode_stock_count").val();
        let tanggal = $(".tanggal").val();
        console.log(tanggal);
        $.ajax({
            type: "get",
            url: "{!! route('stock.store', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            data: {
                'kode_stock': kode_stock,
                'tanggal': tanggal
            },
            dataType: "json",
            success: function (response) {
                let title = "";
                let text = "";
                let type_swal = "";

                if (response == "success") {
                    window.location =
                        "{!! route('stock.index', ['kodeproject'=>$kodeproject,'kodegudang'=> $kodegudang]) !!}";
                } else {
                    title = "Gagal!";
                    text = response;
                    type_swal = "error";
                    Swal.fire({
                        title: title,
                        text: text,
                        type: type_swal,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }


            }
        });
    }

    $(document).ready(function () {
        $(".tanggal").val(moment().format("DD-MM-YYYY"));
        $('.tanggal').pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });
    })

</script>
@endsection