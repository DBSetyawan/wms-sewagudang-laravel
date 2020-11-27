@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Tambah Item
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
<li class="breadcrumb-item active">Tambah Item
</li>
@endsection

@section('content')
<section id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Item</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form action="#" class="testing" novalidate>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <div class="controls">
                                        <fieldset>
                                            <label for="">Kode Item</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control kode_item"
                                                    placeholder="Kode Item" aria-describedby="button-addon2"
                                                    data-validation-required-message="Kode item harus diisi!" required>
                                                <div class="input-group-append" id="button-addon2">
                                                    <button class="btn btn-primary generate_kode_item"
                                                        type="button">Buat
                                                        Kode</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Label Barcode</label>
                                            <input type="text" class="form-control label_barcode">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Nama Item</label>
                                            <input type="text" class="form-control nama_item"
                                                data-validation-required-message="Nama item harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">UOM</label>
                                            <select name="" id="" class="select2 form-control uom">
                                                <option value="kg">kg</option>
                                                <option value="m">m</option>
                                                <option value="cm">cm</option>
                                                <option value="mm">mm</option>
                                                <option value="liter">liter</option>
                                                <option value="dus">dus</option>
                                                <option value="box">box</option>
                                                <option value="bal">bal</option>
                                                <option value="galon">galon</option>
                                                <option value="botol">botol</option>
                                                <option value="pcs">pcs</option>
                                                <option value="karton">Karton</option>
                                                <option value="Cartons">Cartons</option>
                                                <option value="Drum">Drum</option>
                                                <option value="Bags">Bags</option>
                                                <option value="Sak">Sak</option>
                                                <option value="Karung">Karung</option>
                                                <option value="Palet">Palet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Hitugn CBM</label>
                                            <select name="" id="" class="form-control hitung_cbm">
                                                <option value="manual" selected>Manual</option>
                                                <option value="langsung">Langsung</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 panjang">
                                    <div class="form-group manual_cbm">
                                        <label for="">P (cm)</label>
                                        <input type="number" step="0.0001" min="0" class="form-control panjang_value"
                                            onchange="hitungManualCBM()" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 lebar">
                                    <div class="form-group manual_cbm">
                                        <label for="">L (cm)</label>
                                        <input type="number" step="0.0001" min="0" class="form-control lebar_value"
                                            onchange="hitungManualCBM()" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6 tinggi">
                                    <div class="form-group manual_cbm">
                                        <label for="">T (cm)</label>
                                        <input type="number" step="0.0001" min="0" class="form-control tinggi_value"
                                            onchange="hitungManualCBM()" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">CBM (m3)</label>
                                        <input type="number" step="0.000000001" min="0" class="form-control cbm"
                                            placeholder="0" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tonase</label>
                                        <input type="number" min="0" class="form-control tonase">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Berat Bersih (kg)</label>
                                        <input type="number" step="0.0001" min="0" class="form-control berat_bersih"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Berat Kotor (kg)</label>
                                        <input type="number" step="0.0001" min="0" class="form-control berat_kotor"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" onclick="tambah_item()">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_document_ready')
<script>
    $('.testing').submit(false);
    $(".hitung_cbm").on('change',function(){
        if($('.hitung_cbm').val() =="langsung")
        {
            $(".panjang").prop('style','display:none');
            $(".lebar").prop('style','display:none');
            $(".tinggi").prop('style','display:none');
            $(".cbm").prop('disabled', false);
            $(".panjang_value").val("0");
            $(".lebar_value").val("0");
            $(".tinggi_value").val("0");
        }
        else
        {
            $(".panjang").prop('style','display:visible');
            $(".lebar").prop('style','display:visible');
            $(".tinggi").prop('style','display:visible');
            $(".cbm").prop('disabled', true);
        }
    })
    function generateNoInbound()
    {
        $.ajax({
            type:'get',
            url : `{!! route('inbound.generatenomorinbound',['kodegudang' => $kodegudang, 'kodeproject'=> $kodeproject]) !!}`,
            headers: {
                "X-CSRF-TOKEN": '{{ csrf_token() }}'
            },
            success : function(data)
            {
                $(".no_inbound").val(data);
            }
        })
    }

    function hitungManualCBM() {
        let panjang = $(".panjang_value").val();
        let lebar = $(".lebar_value").val();
        let tinggi = $(".tinggi_value").val();


        $(".cbm").val((panjang * lebar * tinggi)/1000000);
    }



    function tambah_item() {
        let kode_item = $(".kode_item").val();
        let label_barcode = $(".label_barcode").val();
        let nama_item = $(".nama_item").val();
        let uom = $(".uom").val();
        let hitung_cbm = $(".hitung_cbm").val();
        let panjang = $(".panjang_value").val();
        let lebar = $(".lebar_value").val();
        let tinggi = $('.tinggi_value').val();
        let cbm = $(".cbm").val();
        let berat_kotor = $(".berat_kotor").val();
        let berat_bersih = $(".berat_bersih").val();
        let tonase = $(".tonase").val();

        if(kode_item.trim() != "")
        {
            $.ajax({
                type: "get",
                url: "{!! route('item.store',['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                dataType: "json",
                data: {
                    kode_item: kode_item,
                    label_barcode: (label_barcode.trim() == "") ? kode_item: label_barcode ,
                    nama_item: nama_item,
                    uom: uom,
                    hitung_cbm: hitung_cbm,
                    panjang: (panjang.trim() == "") ? 0 : panjang,
                    lebar: (lebar.trim() == "" ) ? 0 : lebar,
                    tinggi: (tinggi.trim() == "") ? 0 : tinggi,
                    cbm: (cbm.trim() == "") ? 0 : cbm,
                    berat_bersih : (berat_bersih.trim() == "") ? 0 : berat_bersih,
                    berat_kotor : (berat_kotor.trim()  == "" ) ? 0 : berat_kotor,
                    tonase : (tonase.trim()  == "" ) ? 0 : tonase
                    
                },
                success: function (data) {
                    console.log(data);
                    let info, type_swal, title = "";
                    if (data == "success") {
                        window.location = "{!! route('item.index', ['kodegudang' => $kodegudang, 'kodeproject' =>$kodeproject]) !!}";
                    }
                    else
                    {
                        info = data;
                        type_swal = "error";
                        title = "Gagal!";
                        Swal.fire({
                            title: title,
                            text: info,
                            type: type_swal,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }

                    
                }
            })
        }
    }

    $(".generate_kode_item").click(function(){
        let status = $(".generate_kode_item").text();

        if(status != "Input manual")
        {
            $.ajax({
            type:'get',
            url: `{!! route('item.generatekodeitem',['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}`,
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function(data){
                    $(".kode_item").val(data);
                    $(".label_barcode").val(data);
                    $(".label_barcode").prop('disabled',true);
                    $(".kode_item").prop('disabled',true);
                    $(".generate_kode_item").html("Input manual");

                }
            })
        }
        else
        {
            $(".kode_item").val("");
            $(".kode_item").prop('disabled',false);
            $(".label_barcode").val("");
            $(".label_barcode").prop('disabled',false);
            $(".generate_kode_item").html("Buat Kode");
        }
    });

    $(document).ready(function () {
     })
</script>
@endsection