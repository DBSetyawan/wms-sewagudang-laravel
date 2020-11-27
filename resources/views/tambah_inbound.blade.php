@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Tambah Inbound
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
        href="{{route('inbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inbound </a>
</li>
<li class="breadcrumb-item active">Tambah Inbound
</li>
@endsection

@section('content')
<section id="number-tabs simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Buat Inbound</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="steps-wizard wizard-circle">

                            <!-- Step 1 -->
                            <h6>Inbound Header</h6>

                            <fieldset>
                                <div class="inbound_header row">
                                    <div class="col-sm-12 noinbound_container" style="display:none">
                                        <div class="form-group">
                                            <label for="tanggal_inbound">Nomor Inbound</label>
                                            <input type="text" class="form-control noinbound" id="firstName1" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="tanggal_inbound">Tanggal Inbound</label>
                                            <input type='text' class="form-control required tanggal_inbound"
                                                placeholder="Tanggal Inbound" id="tanggal_inbound"
                                                name="tanggalInbound" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="referensi">Referensi</label>
                                            <input type="text" class="form-control required referensi" id="referensi"
                                                name="referensi" placeholder="Referensi">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="asal">Asal Inbound</label>
                                            <input type="text" class="form-control required asal_inbound" id="asal"
                                                name="asal" placeholder="Asal Inbound">
                                        </div>
                                    </div>
                                    {!! $template->form_inbound_header !!}
                                </div>
                            </fieldset>

                            <!-- Step 2 -->
                            <h6>Detail Inbound <b class="noinbound_label"></b></h6>
                            <hr>
                            <fieldset>
                                <div class="detail_inbound row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="kode_item">Kode Item</label>
                                            <div class="form-group controlss">
                                                <select class="form-control input_field pilih_kode_item" id="kode_item"
                                                    name="kode_item" onchange="prefillDataItem()" required>
                                                    <option value selected disabled>Pilih item</option>
                                                    @foreach ($list_item as $item)
                                                    <option
                                                        value="{{$item->id_item . " - " . $item->kode_item . " - " . $item->nama_item}}">
                                                        {{$item->label_barcode . ' - ' . $item->nama_item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="aktual_weight">Actual Weight </label>
                                            <input type="number" class="form-control input_field actual_weight"
                                                id="aktual_weight" name="aktual_weight" placeholder="Actual Weight"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="aktual_cbm">Actual CBM</label>
                                            <input type="number" class="form-control input_field actual_cbm"
                                                id="aktual_cbm" id="aktual_cbm" placeholder="Actual CBM" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" class="form-control input_field quantity required"
                                                    id="quantity" name="quantity" placeholder="Quantity"
                                                    data-validation-required-message="Nama gudang harus diisi!"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="label_item">Label</label>
                                            <input type="text" class="form-control label" id="label_item"
                                                name="label_item" placeholder="Label">
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="batch_item">Batch</label>
                                            <input type="text" class="form-control input_field batch" id="batch_item"
                                                name="batch_item" placeholder="Batch" required>
                                        </div>
                                    </div> --}}
                                    {!! $template->form_detail_inbound !!}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="vs-checkbox-con vs-checkbox-primary form-group">
                                                <input type="checkbox" class="create_packages" value="false"
                                                    onchange="create_packages()">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Create packages</span>
                                            </div>
                                            <input type="number" class="form-control packages" style="display:none">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-between">
                                    <div class="col-md-8 col-12">
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="btn-group">
                                            <button class="btn btn-outline-primary btn_tambah_item_ke_detail_inbound"
                                                type='submit'>Tambah
                                                item ke
                                                detail inbound</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row"></div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="row 1" style="display:none">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Inbound</h4>
                </div>
                <div class="card-content tbl_format">
                    <div class="card-body">
                        <div class="1">
                            <div class="table-responsive ">
                                <table class="table nowrap tbl_detail_inbound" width="100%">
                                    <thead>
                                        <tr class="th_detail_inbound">
                                            <th style="display:none">Id Item</th>
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Actual Weight</th>
                                            <th>Actual CBM</th>
                                            <th>Quantity</th>
                                            <th>Label</th>
                                            {{-- <th>Batch</th> --}}
                                            {!! $template->th_detail_inbound !!}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="content_detail_inbound">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="simple-validation">
    <div class="modal-size-lg mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="add_new_item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Form buat item baru</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
    $(".pilih_kode_item").change(function () { 
        $(".quantity").focus();

     });

    $(".testing").submit(false);
    $(".wizard-circle").submit(function(e){
        e.preventDefault();
    });
    $(".inbound_detail").submit(false);
    let daftar_item = [];
    let action = "";
    let dt = "";
    $("#add_new_item").on('show.bs.modal', function () {
        $(".pilih_kode_item").select2('close');
    });

    var form = $(".steps-wizard").show();
    $(".steps-wizard").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Save inbound detail',
            next : 'Save inbound header'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }

            let currentClassName = document.getElementsByClassName(newIndex)[0].className.split(' ');

            if ($("." + currentClassName[1]).css('display') == 'none') {
                $("." + currentClassName[1]).attr('style', 'display:block');
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();

        },
        onStepChanged: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {

                let noinbound = "";
                if (sessionStorage.getItem('noinbound') == null) {
                    action = "save_inbound_header";
                } else {
                    action = "update_inbound_header";
                    noinbound = sessionStorage.getItem('noinbound');
                }

                let tanggal_inbound = $(".tanggal_inbound").val();
                let asal = $(".asal_inbound").val();
                let referensi = $('.referensi').val();
                let nodelist_custom_label_inbound_header = document.querySelectorAll(
                    '.custom_label_inbound_header');
                let array_custom_label_value = [];

                nodelist_custom_label_inbound_header.forEach(element => {
                    let split = element.className.split(" ");
                    array_custom_label_value.push({
                        'custom_label': split[3],
                        'value': $("." + split[3] + "_input").val()
                    });
                });

                $.ajax({
                    type: 'post',
                    url: "{!! route('inbound.store', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject]) !!}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    data: {
                        noinbound: noinbound,
                        tanggal_inbound: tanggal_inbound,
                        asal_inbound: asal,
                        referensi : referensi,
                        inbound_header_value: JSON.stringify(array_custom_label_value),
                        action: action
                    },
                    success: function (data) {
                        console.log(data);
                        if (sessionStorage.getItem('noinbound') == null) {
                            sessionStorage.setItem('noinbound', `${data}`);
                        }

                        $(".noinbound").val(data);
                        $(".noinbound_container").attr('style', 'display:block');

                        $(".noinbound_label").html(data);
                        $(".pilih_kode_item").select2('open');
                        triggeredToast("Inbound header berhasil disimpan!");
                        // getInboundHeader(data);
                    }
                })
            }

            let previousClassName = document.getElementsByClassName(newIndex)[0].className.split(' ');

            if ($("." + previousClassName[1]).css('display') == 'block') {
                $("." + previousClassName[1]).attr('style', 'display:none');
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            event.preventDefault();
            simpanInbound();

        }
    })

    $(".steps-wizard").validate({
        ignore: 'input[type=hidden]', // ignore hidden fields
        errorClass: 'danger',
        successClass: 'success',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler : function(form){
            // form.submit();
            refreshItem();
            $(".quantity").val("");
            $(".actual_weight").val("");
            $(".actual_cbm").val("");
            $(".label").val("");
            // $(".batch").val("");
            $(".create_packages").prop('checked',false);
            create_packages();
            let custom_label_inbound_detail = document.querySelectorAll(".custom_label_detail_inbound");
            custom_label_inbound_detail.forEach(element => {
                $(`#${element.id}`).val("");
            });
        },
        rules: {
            email: {
                email: true
            }
        }
    });

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

    function generateNoInbound() {
        $.ajax({
            type: 'get',
            url: `{!! route('inbound.generatenomorinbound',['kodegudang' => $kodegudang, 'kodeproject'=> $kodeproject]) !!}`,
            headers: {
                "X-CSRF-TOKEN": '{{ csrf_token() }}'
            },
            success: function (data) {
                $(".no_inbound").val(data);
            }
        })
    }

    function prefillDataItem() {
        let id_item = $(".pilih_kode_item").val().split(' - ');
        let urlFormat =
            "{!! route('item.info',['kodegudang'=>$kodegudang,'kodeproject'=>$kodeproject ,'kodeitem'=>'ID_ITEM']) !!}";
        urlFormat = urlFormat.replace('ID_ITEM', id_item[0]);
        $.ajax({
            type: 'get',
            url: urlFormat,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (data) {
                let result = JSON.parse(data);
                $(".actual_weight").val(result[0]['berat_bersih']);
                $(".actual_cbm").val(result[0]['cbm']);
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
                    let result = data;
                    console.log(result);
                    let info, type_swal = "";
                    if (result == "success") {
                        info = "Item berhasil disimpan!";
                        type_swal = "success";
                        title = "Berhasil!";

                        $(".kode_item").val("");
                        $(".kode_item").val("");
                        $(".kode_item").prop('disabled', false);
                        $(".label_barcode").val("");
                        $(".label_barcode").prop('disabled', false);
                        $(".generate_kode_item").html("Buat Kode");
                        $(".nama_item").val("");
                        $(".uom").val("");
                        $(".hitung_cbm").val("");
                        $(".panjang_value").val("");
                        $(".lebar_value").val("");
                        $('.tinggi_value').val("");
                        $(".cbm").val("");
                        $(".tonase").val("");
                        $(".berat_kotor").val("");
                        $(".berat_bersih").val("");

                        refreshItem();
                    }
                    else
                    {
                        type_swal = "error";
                        title="Gagal!";
                        info = result;
                    }

                    Swal.fire({
                        title: type_swal,
                        text: info,
                        type: type_swal,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            })
        }
    }

    function refreshItem() {
        $.ajax({
            type: 'get',
            url: `{!! route('item.getallitem',["kodegudang" => $kodegudang, "kodeproject"=>$kodeproject]) !!}`,
            headers: {
                "X-CSRF-TOEKN": "{{ csrf_token() }}"
            },
            success: function (data) {
                let result = data;
                $(".pilih_kode_item").html("");
                $(".pilih_kode_item").append('<option value="" disabled selected>Pilih Item</option>')
                result.forEach(item => {
                    $(".pilih_kode_item").append(`<option value='` + item['id_item'] + " - " + item[
                            'kode_item'] + " - " + item['nama_item'] + `'>` + item[
                        'kode_item'] + ` - ` + item['nama_item'] + `</option>`);
                });
            }
        })
    }

    function create_packages()
    {
        if($(".create_packages").prop("checked") == true)
        {
            $(".packages").attr('style', 'display:block');
            $(".packages").val(1);
        }
        else
        {
            $(".packages").attr('style', 'display:none');
            $(".packages").val(0);
        }
    }

    $(".generate_kode_item").click(function () {
        let status = $(".generate_kode_item").text();

        if (status != "Input manual") {
            $.ajax({
                type: 'get',
                url: `{!! route('item.generatekodeitem',['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}`,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function (data) {
                    $(".kode_item").val(data);
                    $(".label_barcode").val(data);
                    $(".label_barcode").prop('disabled', true);
                    $(".kode_item").prop('disabled', true);
                    $(".generate_kode_item").html("Input manual");

                }
            })
        } else {
            $(".kode_item").val("");
            $(".kode_item").prop('disabled', false);
            $(".label_barcode").val("");
            $(".label_barcode").prop('disabled', false);
            $(".generate_kode_item").html("Buat Kode");
        }
    });

    

    $(".btn_tambah_item_ke_detail_inbound").click(async function () {
        let empty = "";
        let string = "";
        let new_label = "";
        let index = 0;
        $(".input_field").each(function(){
            if(this.value.trim() == "")
            {
                empty = true;
                return empty
            }
        });
        if(empty != true)
        {
            console.log($(".label").val());
            let info_item = $(".pilih_kode_item").val();
            info_item = info_item.split(' - ');
            let id_item = info_item[0];
            let kode_item = info_item[1];
            let nama_item = info_item[2];
            let actual_weight = $(".actual_weight").val();
            let actual_cbm = $(".actual_cbm").val();
            let quantity = $(".quantity").val();
            let label = $(".label").val();
            // let batch = $(".batch").val();

            let list_custom_label = document.querySelectorAll('.custom_label_detail_inbound');
            let value_custom_label = "";

            list_custom_label.forEach(custom_label => {
                value_custom_label = value_custom_label + `<td class="custom_label ${custom_label.id}">` + custom_label.value +
                    `</td>`
            });

           
            if($('.create_packages').prop('checked') == true)
            {
                let iteration_count = $(".quantity").val() / $(".packages").val();
                let i =0;
                
                for(i; i< iteration_count; i++)
                {
                    let daftar_item_length = daftar_item.length;
                    if(label.trim() == "")
                    {
                        if( daftar_item.length > 0)
                        {
                            let angkaBelakang = daftar_item[daftar_item_length-1]['index'] + 1
                            index  = angkaBelakang;
                            new_label = sessionStorage.getItem('noinbound') + angkaBelakang.toString().padStart(4, 0);
                        }
                        else
                        {
                            index= 1;
                            new_label = sessionStorage.getItem('noinbound') + '0001';
                        }
                    }
                    else
                    {
                        new_label = label;
                    }
                    string = `<tr class="item_inbound_${index}">
                            <td style='display:none'>` + id_item + `</td>
                            <td>` + kode_item + `</td>
                            <td>` + nama_item + `</td>
                            <td>` + actual_weight + `</td>
                            <td>` + actual_cbm + `</td>
                            <div contenteditable><td>` + $(".packages").val() + `</td></div>
                            <td contenteditable="true" class="editable">` + new_label + `</td>` +
                            value_custom_label +
                            `<td><button class="btn btn-icon btn-danger btn-sm" onclick="removeItemFromInboundDetail(${index}, $(this).closest('tr'))"><i class="fa fa-trash"></i></button></td>` +
                        `</tr>`;
                    dt.rows.add($(string)).draw();
                    daftar_item.push({
                        'id_item': id_item,
                        // 'batch': batch,
                        'label': new_label,
                        'qty': $(".packages").val(),
                        'custom_td': value_custom_label,
                        'index' :  index
                    });
                }
            }
            else
            {
                let daftar_item_length = daftar_item.length;
                
                if(label.trim() == "")
                {
                    if(daftar_item_length != 0)
                    {
                        
                        // console.log(daftar_item[daftar_item_length-1]);
                        let angkaBelakang = daftar_item[daftar_item_length-1]['index'] + 1
                        index = angkaBelakang;
                        new_label = sessionStorage.getItem('noinbound') + angkaBelakang.toString().padStart(4, 0);
                    }
                    else
                    {
                        index = 1;
                        new_label = sessionStorage.getItem('noinbound') + '0001';
                    }
                }
                else
                {
                    
                    if(checkerSameLabel(label, daftar_item).length > 0)
                    {
                        Swal.fire({
                            title : 'Gagal!',
                            text : 'Label tidak boleh sama!',
                            type :"error"
                        })

                        return;
                    }
                    else
                    {
                        new_label = label;
                    }
                }

                string = `
                    <tr class="item_inbound_${index}">
                        <td style='display:none'>` + id_item + `</td>
                        <td>` + kode_item + `</td>
                        <td>` + nama_item + `</td>
                        <td>` + actual_weight + `</td>
                        <td>` + actual_cbm + `</td>
                        <td>` + quantity + `</td>
                        <td contenteditable="true" class="editable">` + new_label + `</td>` +
                        value_custom_label +
                        `<td><button class="btn btn-icon btn-outline-primary btn-sm" onclick="removeItemFromInboundDetail(${index}, $(this).closest('tr'))"><i class="fa fa-minus"></i></button></td>` +
                    `</tr>
                `;
                dt.rows.add($(string)).draw();
                daftar_item.push({
                    'id_item': id_item,
                    // 'batch': batch,
                    'label': new_label,
                    'qty': quantity,
                    'custom_td': value_custom_label,
                    'index' : index
                });
            }
            triggeredToast("Item berhasil ditambahkan!");
        }
        hideHiddenLabel();

    });

    $('.tbl_detail_inbound tbody').on( 'blur', 'td.editable', function (e) {
        let idx = $(this).closest('tr').index();
        let previousLabelName = daftar_item[idx]['label'];
        
        let temp_arry = daftar_item;
        temp_arry[idx]['label'] = $(this).html();

        if(checkerSameLabel($(this).html(), temp_arry).length > 1 )
        {
            $(this).html(previousLabelName);
            daftar_item[idx]['label'] = previousLabelName;
            Swal.fire({
                title : "Gagal!",
                type : "error",
                text : "Label sudah terdaftar!"
            })
        }
        else
        {
            daftar_item[idx]['label'] = $(this).html();
            triggeredToast("Label berhasil disimpan!");
        }
        
    } );

    function checkerSameLabel(argLabel, argTempArray) {
        return argTempArray.filter(item => item['label'] == argLabel);
    }

    function removeItemFromInboundDetail(argIndex, argObjectCell) { 
        console.log(argObjectCell);
        daftar_item.forEach(item => {
            if(item['index'] == argIndex)
            {
                let indexof = daftar_item.indexOf(item);
                daftar_item.splice(indexof,1);
            }
        });
        dt.row(argObjectCell).remove().draw();
        hideHiddenLabel();
        triggeredToast("Item berhasil dihapus dari list!");
    }

    function checkerMultipleLabel() {
        let tortoise = daftar_item[0];
        let hare = daftar_item[0];
        while(true){
            tortoise = daftar_item[tortoise];
            hare = daftar_item[daftar_item[hare]];
            if(tortoise['label'] == hare['label'])
            {
                break;
            }
        }

        let pattern1 = daftar_item[0];
        let pattern2 = tortoise;
        while (pattern1['label'] != pattern2['label']){
            pattern1 = daftar_item[pattern1];
            pattern2 = daftar_item[pattern2];
        }

        return pattern1;
    }

    function simpanInbound() {
        if(daftar_item.length == 0)
        {
            Swal.fire({
                title: "Gagal!",
                text: "Inbound detail tidak boleh kosong!",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
        else
        {
            let no_inbound = $(".noinbound").val();
            let tanggal_inbound = $(".tanggal_inbound").val();
            let asal = $(".asal_inbound").val();
            let nodelist_custom_label_inbound_header = document.querySelectorAll('.custom_label_inbound_header');
            let array_custom_label_value = [];

            nodelist_custom_label_inbound_header.forEach(element => {
                let split = element.className.split(" ");
                array_custom_label_value.push({
                    'custom_label': split[3],
                    'value': $("." + split[3] + "_input").val()
                });
            });

            //  console.log(no_inbound);

            $.ajax({
                type: 'post',
                url: "{!! route('inbound.store', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject]) !!}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: "json",
                data: {
                    no_inbound: no_inbound,
                    tanggal_inbound: tanggal_inbound,
                    asal_inbound: asal,
                    list_item: JSON.stringify(daftar_item),
                    inbound_header_value: JSON.stringify(array_custom_label_value),
                    action: "insert_list_item"
                },
                success: function (data) {
                    let text = "";
                    let type_swal = "";
                    let title = "";
                    if (data == "success") {
                        window.location =
                            "{!! route('inbound.index', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}"
                    } else {
                        text = data;
                        type_swal = "error";
                        title = "Gagal"

                        Swal.fire({
                            title: title,
                            text: text,
                            type: type_swal,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }


                }
            })
        }
    }

    function createDataListView(argNamaClass)
    {
        let object = $("." + argNamaClass).DataTable({
            responsive: false,
            columnDefs: [{
                orderable: true,
                targets: 0,
                checkboxes: {
                    selectRow: false
                }
            }],
            dom: '<"top"<"actions action-btns"B><"action-filters"f>><"clear">rt<"bottom"<"actions">p>',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sSearch: ""
            },
            aLengthMenu: [
                [4, 10, 15, 20],
                [4, 10, 15, 20]
            ],
            order: [
                [1, "asc"]
            ],
            ordering : false,
            sorting : false,
            bInfo: false,
            paging: false,
            pageLength: 4,
            buttons: [],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    // function getInboundHeader(argNoInbound) {
    //     let url =
    //         "{!! route('inbound.show', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'noinbound'=> 'NO_INBOUND']) !!}";
    //     url = url.replace('NO_INBOUND', argNoInbound);
    //     $.ajax({
    //         type: "get",
    //         url: url,
    //         headers: {
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         },
    //         success: function (response) {
    //             let result = JSON.parse(response);
    //             $(".tanggal_inbound").val(result[0]['tanggal_inbound']);
    //             $(".asal_inbound").val(result[0]['origin']);
    //             let custom_label_json = JSON.parse(result[0]['custom_field']);

    //             custom_label_json.forEach(element => {
    //                 $("." + element['custom_label'] + "_input").val(element['value']);
    //             });
    //         }
    //     });
    // }

    window.addEventListener('unload', function (event) {
        sessionStorage.removeItem('noinbound');
    })

    $(document).ready(function () {
        dt = createDataListView('tbl_detail_inbound');
        $(".hidden_label").attr('style','display:none');

        $(".tanggal_inbound").val(moment().format('DD-MM-YYYY'));
        $('.tanggal_inbound').pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });

        $(".pilih_kode_item").select2({
            dropdownAutoWidth: true,
            width: '100%',
            language: {
                noResults: function () {
                    return `<button id="no-results-btn" data-toggle="modal" data-target="#add_new_item">Hasil tidak ditemukan. Apakah ingin menambahkan item baru?`;
                }
            },
            placeholder: "Pilih Item",
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        

        if (sessionStorage.getItem('noinbound') != null) {
            $(".noinbound").val(sessionStorage.getItem('noinbound'));
            $(".noinbound_container").attr('style', 'display:block');
            // getInboundHeader(sessionStorage.getItem('noinbound'));
        }
    });

</script>
@endsection