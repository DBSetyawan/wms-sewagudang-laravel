@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Buat template inbound
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item active">Template Inbound
</li>
@endsection

@section('content')
<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Buat Template Inbound dan Incoming Inbound</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="steps-wizard wizard-circle">

                            <!-- Step 1 -->
                            <h6>Inbound Header</h6>
                            <hr>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="firstName1">Label </label>
                                                <input type="text" class="form-control inbound-header-label"
                                                    id="firstName1"
                                                    data-validation-required-message="Nama label harus diisi!"
                                                    placeholder="Label inbound header">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lastName1">Type</label>
                                            <fieldset class="form-group">
                                                <select class="form-control inbound-header-type" id="basicSelect">
                                                    <option value="text">Text</option>
                                                    <option value="date">Date</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="inbound-header-required" value="required">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">Required</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <label></label>
                                        <button type="button"
                                            class="btn btn-outline-primary form-control btn_tambah_row_inbound_header">
                                            Tambah Label
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Step 2 -->
                            <h6>Detail Inbound</h6>
                            <fieldset>
                                <div class=" row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="firstName1">Label </label>
                                                <input type="text" class="form-control detail-inbound-label"
                                                    id="firstName1"
                                                    data-validation-required-message="Nama label harus diisi!"
                                                    placeholder="Label detail inbound">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lastName1">Type</label>
                                            <fieldset class="form-group">
                                                <select class="form-control detail-inbound-type" id="basicSelect">
                                                    <option value="text">Text</option>
                                                    <option value="date">Date</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="detail-inbound-required" value="required">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">Required</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <label></label>
                                        <button type="button"
                                            class="btn btn-outline-primary form-control btn_tambah_row_detail_inbound">
                                            Tambah Label
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Step 3 -->
                            <h6>Incoming Inbound</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="firstName1">Label </label>
                                                <input type="text" class="form-control incoming-inbound-label"
                                                    id="firstName1"
                                                    data-validation-required-message="Nama label harus diisi!"
                                                    placeholder="Label incoming inbound">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lastName1">Type</label>
                                            <fieldset class="form-group">
                                                <select class="form-control detail-inbound-type incoming-inbound-type"
                                                    id="basicSelect">
                                                    <option value="text">Text</option>
                                                    <option value="date">Dater</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="incoming-inbound-required" value="required">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">Required</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <label></label>
                                        <button type="button"
                                            class="btn btn-outline-primary form-control btn_tambah_row_incoming_inbound">
                                            Tambah Label
                                        </button>
                                    </div>

                                </div>
                            </fieldset>
                            <hr>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Template Inbound - <b class="jenis_inbound">Inbound Header</b></h4>
                </div>
                <hr>
                <div class="card-content tbl_format">
                    <div class="card-body">
                        <div class="0" style="">
                            <div class="table-responsive ">
                                <table class="table tbl_inbound_header">
                                    @if ($template == "")
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Label</th>
                                            <th>Type Data</th>
                                            <th>Required</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Date Inbound</td>
                                            <td>Date</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>No Inbound</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Referensi</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                    @else
                                    {!! $template->template_inbound_header !!}
                                    @endif

                                </table>
                            </div>
                        </div>
                        <div class="1" style="display:none">
                            <div class="table-responsive">
                                <table class="table tbl_detail_inbound">
                                    @if ($template == "")
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Label</th>
                                            <th>Type Data</th>
                                            <th>Required</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Kode Item</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Deskripsi Item</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Quantity</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>Uom</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>Label</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>

                                    </tbody>
                                    @else
                                    {!! $template->template_detail_inbound !!}
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="2" style="display: none">
                            <table class="table tbl_incoming_inbound">
                                @if ($template == "")
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Label</th>
                                        <th>Type Data</th>
                                        <th>Required</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Date</td>
                                        <td>Date</td>
                                        <td></td>
                                        <td>-</td>
                                    </tr>

                                </tbody>
                                @else
                                {!! $template->template_incoming_inbound !!}
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="form_inbound_header_custom_label" id="number-tabs" style="display:none">
    @if ($template != "")
    {!! $template->form_inbound_header !!}
    @endif
</section>
<section class="form_detail_inbound_custom_label" id="number-tabs" style="display:none">
    @if ($template != "")
    {!! $template->form_detail_inbound !!}
    @endif
</section>
<section class="form_incoming_inbound_custom_label" id="number-tabs" style="display:none">
    @if ($template != "")
    {!! $template->form_incoming_inbound !!}
    @endif
</section>

<section class="th_detail_inbound" style="display: none">
</section>
@endsection

@section('script_document_ready')
<script>
    var form = $(".steps-wizard").show();
    let daftar_custom_label_inbound_header = [];
    let daftar_custom_label_detail_inbound = [];
    let daftar_custom_label_incoming_inbound = [];
    $(".steps-wizard").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        enableAllSteps: true,
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Submit'
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }

            let currentClassName = document.getElementsByClassName(newIndex)[0].className;
            let previousClassName = document.getElementsByClassName(currentIndex)[0].className;

            if ($("." + currentClassName).css('display') == 'none') {
                $("." + currentClassName).attr('style', 'display:block');
                $("." + previousClassName).attr('style', 'display:none');
                if(currentClassName == 0)
                {
                    $(".jenis_inbound").html("Inbound Header");
                }
                else if(currentClassName == 1)
                {
                    $(".jenis_inbound").html("Detail Inbound")
                }
                else
                {
                    $(".jenis_inbound").html("Incoming Inbound Header")
                }
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();

        },
        onStepChanged: function (event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            let currentClassName = document.getElementsByClassName(currentIndex)[0].className;
            let previousClassName = document.getElementsByClassName(newIndex)[0].className;

            if ($("." + currentClassName).css('display') == 'none') {
                $("." + currentClassName).attr('style', 'display:block');
                $("." + previousClassName).attr('style', 'display:none');

                if(currentClassName == 0)
                {
                    $(".jenis_inbound").html("Inbound Header");
                }
                else if(currentClassName == 1)
                {
                    $(".jenis_inbound").html("Detail Inbound")
                }
                else
                {
                    $(".jenis_inbound").html("Incoming Inbound Headers")
                }
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            let queryselector_custom_label_inbound_header = document.querySelectorAll(
                ".custom_label_inbound_header");
            let custom_label_inbound_header = [];
            queryselector_custom_label_inbound_header.forEach(element => {
                custom_label_inbound_header.push(element);
            });

            let queryselector_custom_label_detail_inbound = document.querySelectorAll(
                ".custom_label_detail_inbound");
            let custom_label_detail_inbound = [];
            queryselector_custom_label_detail_inbound.forEach(element => {
                custom_label_detail_inbound.push(element);
            });

            let queryselector_custom_label_incoming_inbound = document.querySelectorAll(
                ".custom_label_incoming_inbound");
            let custom_label_incoming_inbound = [];
            queryselector_custom_label_incoming_inbound.forEach(element => {
                custom_label_incoming_inbound.push(element);
            });

            let inbound_header_custom_label = $(".form_inbound_header_custom_label").html();
            let detail_inbound_custom_label = $(".form_detail_inbound_custom_label").html();
            let incoming_inbound_custom_label = $(".form_incoming_inbound_custom_label").html();
            let th_detail_inbound = $(".th_detail_inbound").html();
            $.ajax({
                type: 'post',
                url: "{!! route('inbound.simpantemplateinbound', ['kodeproject'=>$kodeproject]) !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                dataType: "json",
                data: {
                    'template': JSON.stringify({
                        'template_inbound_header': $(".tbl_inbound_header").html(),
                        'template_detail_inbound': $(".tbl_detail_inbound").html(),
                        'template_incoming_inbound': $(".tbl_incoming_inbound").html(),
                        'th_detail_inbound': th_detail_inbound,
                        'form_inbound_header': inbound_header_custom_label,
                        'form_detail_inbound': detail_inbound_custom_label,
                        'form_incoming_inbound': incoming_inbound_custom_label,
                        'daftar_label_custom_inbound_header' : daftar_custom_label_inbound_header,
                        'daftar_label_custom_detail_inbound' : daftar_custom_label_detail_inbound,
                        'daftar_label_custom_incoming_inbound' : daftar_custom_label_incoming_inbound
                    }),

                },
                success: function (data) {
                    console.log(data);
                    if (data == "success") {
                        window.location =
                            "{!! route('project.index') !!}"
                    } else {
                        Swal.fire({
                            title: "Gagal!",
                            text: data,
                            type: "error",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                    }
                }
            })
        }
    });

    function changeVisibleOfElementForm(argNamaForm,argNamaLabel) { 
        // console.log($(`.th_detail_inbound .${argNamaLabel}`).html());
        if($(`.${argNamaForm} .${argNamaLabel}`).css('display') =="block")
        {
            $(`.${argNamaForm} .${argNamaLabel}`).attr('style', 'display:none');
            $(`.th_detail_inbound .${argNamaLabel}`).addClass('hidden_label');
            $(`.btn_visible_${argNamaLabel}`).html("Visible");
            triggeredToast("Custom label berhasil disembunyikan!");
        }
        else
        {
            $(`.${argNamaForm} .${argNamaLabel}`).attr('style', 'display:block');
            $(`.th_detail_inbound .${argNamaLabel}`).removeClass('hidden_label');
            $(`.btn_visible_${argNamaLabel}`).html("Hide");
            triggeredToast("Custom label berhasil dimunculkan!");
        }
        
     }

    $(".btn_tambah_row_inbound_header").click(function () {
        let label_inbound_header = $(".inbound-header-label").val();

        if(checkIsLabelExisted(daftar_custom_label_inbound_header,label_inbound_header.replace(/[^\w\s]/gi, '').replace(" ", ''))=== true)
        {
            Swal.fire({
                title: "Gagal!",
                text: "Label sudah terdaftar!",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
        else
        {
            if(label_inbound_header.replace(" ", '') != "")
            {
                let type_inbound_header = $(".inbound-header-type").val();
                let required_label_inbound_header = "";

                if ($(".inbound-header-required").prop('checked') == true) {
                    required_label_inbound_header = "required"
                }

                $(".tbl_inbound_header tbody").append(`
                    <tr class="custom_label_inbound_header ` + label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", '') + `" >
                        <th scope="row">` + parseInt(countRow('tbl_inbound_header') + 1) + `</th>
                        <td>` + label_inbound_header + `</td>
                        <td>` + type_inbound_header + `</td>
                        <td>` + required_label_inbound_header + `</td> 
                        <td>
                            <button class="btn btn-icon btn-danger button_remove_custom_label_inbound_header" onclick="deleteRow('inbound_header','tbl_inbound_header',` + generateIndex('tbl_inbound_header') + `,'` + label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", '') + `')" style="display:block">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-icon btn-outline-primary button_visible_custom_label_inbound_header btn_visible_${label_inbound_header.replace(/[^A-Z0-9]/ig, '')}" onclick="changeVisibleOfElementForm('form_inbound_header_custom_label' ,'${label_inbound_header.replace(/[^A-Z0-9]/ig, '')}')" style="display:none">
                                Hide
                            </button>
                        </td>
                    </tr>`);

                $(".form_inbound_header_custom_label").append(`
                    <div class="col-md-12 mb-2 custom_label_inbound_header ` + label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", '') + `" style="display:block">
                        <label for="${label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", '')}">` + label_inbound_header + `</label>
                        <input type="` + type_inbound_header + `" class="form-control ${required_label_inbound_header} ` + label_inbound_header.replace(/[^A-Z0-9]/ig, '') + `_input " id="${label_inbound_header.replace(/[^A-Z0-9]/ig, '')}" name="${label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", '')}" placeholder="` + label_inbound_header + `" ` + `  value="">
                    </div>`);

                daftar_custom_label_inbound_header.push({'nama_label' : label_inbound_header.replace(/[^A-Z0-9]/ig, '').replace(" ", ''), 'type_label' : type_inbound_header});
                $(".inbound-header-label").val("");
                triggeredToast("Custom label berhasil ditambahkan!");
            }
            else
            {
                error("Label inbound header")
            }
        }

        
    });

    $(".btn_tambah_row_detail_inbound").click(function () {
        let label_detail_inbound = $(".detail-inbound-label").val();

        if(checkIsLabelExisted(daftar_custom_label_detail_inbound,label_detail_inbound.replace(/[^A-Z0-9]/ig,'')) == true)
        {
            Swal.fire({
                title: "Gagal!",
                text: "Label sudah terdaftar!",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
        else
        {
            if(label_detail_inbound.replace(/[^A-Z0-9]/ig,'') != "")
            {
                let type_detail_inbound = $(".detail-inbound-type").val();
                let required_label_detail_inbound = "";

                if ($(".detail-inbound-required").prop('checked') == true) {
                    required_label_detail_inbound = "required";
                }

                $(".tbl_detail_inbound tbody").append(`
                                                    <tr class="custom_label_detail_inbound ` + label_detail_inbound.replace(/[^A-Z0-9]/ig,'') + `" >
                                                        <th scope="row">` + parseInt(countRow('tbl_detail_inbound') + 1) + `</th>
                                                        <td>` + label_detail_inbound + `</td>
                                                        <td>` + type_detail_inbound + `</td>
                                                        <td>` + required_label_detail_inbound + `</td>
                                                        <td>
                                                            <button class="btn btn-icon btn-danger button_remove_custom_label_detail_inbound" onclick="deleteRow('detail_inbound','tbl_detail_inbound',` + generateIndex('tbl_detail_inbound') + `,'` + label_detail_inbound.replace(/[^A-Z0-9]/ig,'') + `')" style="display:block">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <button class="btn btn-icon btn-outline-primary button_visible_custom_label_detail_inbound btn_visible_${label_detail_inbound.replace(/[^A-Z0-9]/ig,'')}" onclick="changeVisibleOfElementForm('form_detail_inbound_custom_label' ,'${label_detail_inbound.replace(/[^A-Z0-9]/ig,'')}')" style="display:none">
                                                                Hide
                                                            </button>
                                                        </td>
                                                    </tr>`);

                $(".form_detail_inbound_custom_label").append(`
                    <div class="col-md-6 mb-2  ` + label_detail_inbound.replace(/[^A-Z0-9]/ig,'') + `"  style="display:block">
                        <label for="${label_detail_inbound.replace(/[^A-Z0-9]/ig,'')}">` + label_detail_inbound + `</label>
                        <input type="` + type_detail_inbound + `" class="form-control custom_label_detail_inbound ` +
                    label_detail_inbound.replace(/[^A-Z0-9]/ig,'') + `_input" id="${label_detail_inbound.replace(/[^A-Z0-9]/ig,'')}" name="${label_detail_inbound.replace(/[^A-Z0-9]/ig,'')}" placeholder="` +
                    label_detail_inbound + `" ` + required_label_detail_inbound + ` value="">
                    </div>`);

                $(".th_detail_inbound").append(`<th class="custom_detail_inbound ` + label_detail_inbound.replace(/[^A-Z0-9]/ig,'') + `">` + label_detail_inbound + ` </th>`);

                daftar_custom_label_detail_inbound.push({'nama_label' : label_detail_inbound.replace(/[^A-Z0-9]/ig,''), 'type_label' : type_detail_inbound});
                $(".detail-inbound-label").val("");   
                triggeredToast("Custom label berhasil ditambahkan!");
            }
            else{
                error("Label detail inbound");
            }
        }

        
    
    });

    $(".btn_tambah_row_incoming_inbound").click(function () {
        let label_incoming_inbound = $(".incoming-inbound-label").val();

        if(checkIsLabelExisted(daftar_custom_label_incoming_inbound,label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')) == true)
        {
            Swal.fire({
                title: "Gagal!",
                text: "Label sudah terdaftar!",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }  
        else
        {
            if(label_incoming_inbound.replace(/[^A-Z0-9]/ig,'') != "")
            {
                let type_incoming_inbound = $(".incoming-inbound-type").val();
                let required_label_incoming_inbound = "";

                if ($(".incoming-inbound-required").prop('checked') == true) {
                    required_label_incoming_inbound = "required";
                }

                $(".tbl_incoming_inbound tbody").append(`
                                                <tr class="custom_label_incoming_inbound ` + label_incoming_inbound.replace(/[^A-Z0-9]/ig,'') + `" >
                                                    <th scope="row">` + parseInt(countRow('tbl_incoming_inbound') + 1) + `</th>
                                                    <td>` + label_incoming_inbound + `</td>
                                                    <td>` + type_incoming_inbound + `</td>
                                                    <td>` + required_label_incoming_inbound +`</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-danger button_remove_custom_label_incoming_inbound" onclick="deleteRow('incoming_inbound','tbl_incoming_inbound',` + generateIndex('tbl_incoming_inbound') + `,'` + label_incoming_inbound.replace(/[^A-Z0-9]/ig,'') + `')" style="display:block">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button class="btn btn-icon btn-outline-primary button_visible_custom_label_incoming_inbound btn_visible_${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}" onclick="changeVisibleOfElementForm('form_incoming_inbound_custom_label', '${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}')" style="display:none">
                                                            Hide
                                                        </button>
                                                    </td>
                                                </tr>`);
                $('.form_incoming_inbound_custom_label').append(`
                    <div class="col-sm-12 custom_label_incoming_inbound ${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}" style="display:block">
                        <div class="form-group">
                            <div class="controls">
                            <label for="${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}">${label_incoming_inbound}</label>
                            <input type="${type_incoming_inbound}" class="form-control ${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}_input input_field" id="${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}" name="${label_incoming_inbound.replace(/[^A-Z0-9]/ig,'')}" placeholder="${label_incoming_inbound}" data-validation-required-message="${label_incoming_inbound} harus diisi!" 
                            ${required_label_incoming_inbound} />
                            </div>
                        </div>
                    </div>
                `);
                daftar_custom_label_incoming_inbound.push({'nama_label' : label_incoming_inbound.replace(/[^A-Z0-9]/ig,''), 'type_label' : type_incoming_inbound});
                $(".incoming-inbound-label").val("");
                triggeredToast("Custom label berhasil ditambahkan!");
            }
            else
            {
                error("Label incoming inbound");
            }
        }
    });

    function error(argLocationError) { 
        Swal.fire({
            title: "Gagal!",
            text: argLocationError + " harus diisi!",
            type: "error",
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        });
     }

    function countRow(argNamaTabel) {
        let count_row = $("." + argNamaTabel + " tbody tr").length;
        return count_row;
    }

    function generateIndex(argNamaTabel) {
        let countrow = countRow(argNamaTabel);
        let index = countrow + 1;
        return index;
    }


    function deleteRow(argArray, argNamaTabel, argIndex, argNamaClassLabel = "") {
        // document.getElementsByClassName(argNamaTabel)[0].deleteRow(argIndex);
        $("." + argNamaClassLabel).remove();
        if(argArray == "inbound_header")
        {
            daftar_custom_label_inbound_header.splice(daftar_custom_label_inbound_header.indexOf(argNamaClassLabel), 1);
        }
        else if(argArray =="detail_inbound")
        {
            daftar_custom_label_detail_inbound.splice(daftar_custom_label_detail_inbound.indexOf(argNamaClassLabel), 1);
        }
        else
        {
            daftar_custom_label_incoming_inbound.splice(daftar_custom_label_incoming_inbound.indexOf(argNamaClassLabel), 1);
        }
        triggeredToast("Custom label berhasil dihapus!");
    }

    function checkIsLabelExisted(argNamaArray, argNamaLabel) { 
        let exist = false;
        argNamaArray.forEach(label => {
            if(label['nama_label'] == argNamaLabel)
            {
                exist = true;
                return exist;
            }
        });
        return exist;
     }

    $(document).ready(function () {
        daftar_custom_label_inbound_header = {!! ($template == "") ? json_encode([]) : json_encode($template->daftar_label_custom_inbound_header) !!};
        
        daftar_custom_label_detail_inbound = {!! ($template == "") ? json_encode([]) : json_encode($template->daftar_label_custom_detail_inbound) !!};

        daftar_custom_label_incoming_inbound = {!! ($template == "") ? json_encode([]) : json_encode($template->daftar_label_custom_incoming_inbound) !!};    
        
        $(".th_detail_inbound").append({!! ($template == "") ? "" : json_encode($template->th_detail_inbound) !!})

        $(".button_remove_custom_label_inbound_header").attr('style', 'display:none');
        $(".button_visible_custom_label_inbound_header").attr('style', 'display:block');
        
        $(".button_remove_custom_label_detail_inbound").attr('style', 'display:none');
        $(".button_visible_custom_label_detail_inbound").attr('style', 'display:block');

        $(".button_remove_custom_label_incoming_inbound").attr('style', 'display:none');
        $(".button_visible_custom_label_incoming_inbound").attr('style', 'display:block');
    });
</script>
@endsection