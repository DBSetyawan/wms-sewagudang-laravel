@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Edit item
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
        href="{{route('item.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Item
        ({{$kodeitem}})</a>
</li>
<li class="breadcrumb-item active">Edit Item
</li>
@endsection

@section('content')
<section id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Item</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form action="#" class="form_edit_item" novalidate>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <div class="controls">
                                        <fieldset>
                                            <label for="">Kode Item</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control kode_item"
                                                    placeholder="Kode Item" aria-describedby="button-addon2"
                                                    value="{{$item[0]->kode_item}}" disabled>
                                                {{-- <div class="input-group-append" id="button-addon2">
                                                    <button class="btn btn-primary generate_kode_item" type="button">Buat
                                                        Kode</button>
                                                </div> --}}
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Label Barcode</label>
                                            <input type="text" class="form-control label_barcode"
                                                value="{{$item[0]->label_barcode}}"
                                                data-validation-required-message="Label barcode harus diisi!" disabled
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Nama Item</label>
                                            <input type="text" class="form-control nama_item"
                                                value="{{$item[0]->nama_item}}"
                                                data-validation-required-message="Nama item harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">UOM</label>
                                            <select name="" id="" class="select2 form-control uom">
                                                <option value="kg" {{($item[0]->nama_uom == "kg") ? "selected" : ""}}>kg
                                                </option>
                                                <option value="m" {{($item[0]->nama_uom == "m") ? "selected" : ""}}>m
                                                </option>
                                                <option value="cm" {{($item[0]->nama_uom == "cm") ? "selected" : ""}}>cm
                                                </option>
                                                <option value="mm" {{($item[0]->nama_uom == "mm") ? "selected" : ""}}>mm
                                                </option>
                                                <option value="liter"
                                                    {{($item[0]->nama_uom == "liter") ? "selected" : ""}}>liter</option>
                                                <option value="dus" {{($item[0]->nama_uom == "dus") ? "selected" : ""}}>
                                                    dus</option>
                                                <option value="box" {{($item[0]->nama_uom == "box") ? "selected" : ""}}>
                                                    box</option>
                                                <option value="bal" {{($item[0]->nama_uom == "bal") ? "selected" : ""}}>
                                                    bal</option>
                                                <option value="galon"
                                                    {{($item[0]->nama_uom == "galon") ? "selected" : ""}}>galon</option>
                                                <option value="botol"
                                                    {{($item[0]->nama_uom == "botol") ? "selected" : ""}}>botol</option>
                                                <option value="pcs" {{($item[0]->nama_uom == "pcs") ? "selected" : ""}}>
                                                    pcs</option>
                                                <option value="karton"
                                                    {{($item[0]->nama_uom == "karton") ? "selected" : ""}}>
                                                    Karton</option>
                                                <option value="Cartons"
                                                    {{($item[0]->nama_uom == "Cartons") ? "selected" : ""}}>
                                                    Cartons</option>
                                                <option value="Drum"
                                                    {{($item[0]->nama_uom == "Drum") ? "selected" : ""}}>Drum
                                                </option>
                                                <option value="Bags"
                                                    {{($item[0]->nama_uom == "Bags") ? "selected" : ""}}>Bags
                                                </option>
                                                <option value="Karung" {{($item[0]->nama_uom == "") ? "selected" : ""}}>
                                                    Karung
                                                </option>
                                                <option value="Sak" {{($item[0]->nama_uom == "Sak") ? "selected" : ""}}>
                                                    Sak
                                                </option>
                                                <option value="Karung"
                                                    {{($item[0]->nama_uom == "Karung") ? "selected" : ""}}>Karung
                                                </option>
                                                <option value="Palet"
                                                    {{($item[0]->nama_uom == "Palet") ? "selected" : ""}}>Palet
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Hitugn CBM</label>
                                        <select name="" id="" class="form-control hitung_cbm" value="">
                                            @if ($item[0]->cara_hitung_cbm == "manual")
                                            <option value="manual" selected>Manual</option>
                                            <option value="langsung">Langsung</option>
                                            @else
                                            <option value="manual">Manual</option>
                                            <option value="langsung" selected>Langsung</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 panjang">
                                    <div class="form-group manual_cbm">
                                        <div class="controls">
                                            <label for="">P (cm)</label>
                                            <input type="number" step="0.0001" min="0"
                                                class="form-control panjang_value" onchange="hitungManualCBM()"
                                                value="{{$item[0]->panjang}}"
                                                data-validation-required-message="Panjang item harus diisi"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 lebar">
                                    <div class="form-group manual_cbm">
                                        <div class="controls">
                                            <label for="">L (cm)</label>
                                            <input type="number" step="0.0001" min="0" class="form-control lebar_value"
                                                onchange="hitungManualCBM()" value="{{$item[0]->lebar}}"
                                                data-validation-required-message="Lebar item harus diisi"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 tinggi">
                                    <div class="form-group manual_cbm">
                                        <div class="controls">
                                            <label for="">T (cm)</label>
                                            <input type="number" step="0.0001" min="0" class="form-control tinggi_value"
                                                onchange="hitungManualCBM()" value="{{$item[0]->tinggi}}"
                                                data-validation-required-message="Tinggi item harus diisi"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">CBM (m3)</label>
                                            <input type="number" step="0.000000001" min="0" class="form-control cbm"
                                                placeholder="0" value="{{$item[0]->cbm}}"
                                                data-validation-required-message="CBM harus diisi!" required>
                                        </div>
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
                                        <div class="controls">
                                            <label for="">Berat Bersih (kg)</label>
                                            <input type="number" step="0.0001" min="0" class="form-control berat_bersih"
                                                value="{{$item[0]->berat_bersih}}"
                                                data-validation-required-message="Berat bersih harus diisi!"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="">Berat Kotor (kg)</label>
                                            <input type="number" step="0.0001" min="0" class="form-control berat_kotor"
                                                value="{{$item[0]->berat_kotor}}"
                                                data-validation-required-message="Berat kotor harus diisi!"
                                                placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="updateItem()">Submit</button>
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
    function hitungManualCBM() {
        let panjang = $(".panjang_value").val();
        let lebar = $(".lebar_value").val();
        let tinggi = $(".tinggi_value").val();


        $(".cbm").val((panjang * lebar * tinggi)/1000000);
    }

    $(".form_edit_item").submit(false);
    function updateItem() {
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


        $.ajax({
            type: "get",
            url: "{!! route('item.update',['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'kodeitem' => $kodeitem]) !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {
                kode_item: kode_item,
                label_barcode: label_barcode,
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
                let title, text, type_swal = "";
                if (data == "success") {
                    window.location =
                        "{!! route('item.index', ['kodegudang' => $kodegudang, 'kodeproject' =>$kodeproject]) !!}";

                } else {
                    title = "Gagal";
                    text = data;
                    type_swal = "error";
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Item berhasil disimpan",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }


            }
        })
    }

    $(".hitung_cbm").on('change', function () {
        if ($('.hitung_cbm').val() == "langsung") {
            $(".panjang").prop('style', 'display:none');
            $(".lebar").prop('style', 'display:none');
            $(".tinggi").prop('style', 'display:none');
            $(".cbm").prop('disabled', false);
        } else {
            $(".panjang").prop('style', 'display:visible');
            $(".lebar").prop('style', 'display:visible');
            $(".tinggi").prop('style', 'display:visible');
            $(".cbm").prop('disabled', true);
        }
    })

    $(document).ready(function () {

        $(".hitung_cbm").trigger('change');
    })

</script>
@endsection