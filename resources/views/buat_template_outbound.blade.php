@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Buat template outbound
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item active">Template Outbound
</li>
@endsection

@section('content')
<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Buat Template Outbound dan Outgoing Outbound</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="steps-wizard wizard-circle">

                            <!-- Step 1 -->
                            <h6>Outbound Header</h6>
                            <hr>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="firstName1">Label </label>
                                            <input type="text" class="form-control outbound-header-label"
                                                id="firstName1" placeholder="Label outbound header">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lastName1">Type</label>
                                            <fieldset class="form-group">
                                                <select class="form-control outbound-header-type" id="basicSelect">
                                                    <option value="text">Text</option>
                                                    <option value="date">Date</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="outbound-header-required" value="required">
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
                                            class="btn btn-outline-primary form-control btn_tambah_row_outbound_header">
                                            Tambah Label
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                            <h6>Outgoing Header</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="firstName1">Label </label>
                                            <input type="text" class="form-control outgoing-outbound-label"
                                                id="firstName1" placeholder="Label outgoing header">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="lastName1">Type</label>
                                            <fieldset class="form-group">
                                                <select class="form-control detail-inbound-type outgoing-outbound-type"
                                                    id="basicSelect">
                                                    <option value="text">Text</option>
                                                    <option value="date">Date</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="outgoing-outbound-required" value="required">
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
                                            class="btn btn-outline-primary form-control btn_tambah_row_outgoing_outbound">
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
                    <h4 class="card-title">Template Outbound - <b class="jenis_outbound">Outbound Header</b></h4>
                </div>
                <hr>
                <div class="card-content tbl_format">
                    <div class="card-body">
                        <div class="0" style="">
                            <div class="table-responsive ">
                                <table class="table tbl_outbound_header">
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
                                            <td>Date outbound</td>
                                            <td>Date</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>No Outbound</td>
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
                                    {!!$template->template_outbound_header!!}
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="1" style="display:none">
                            <div class="table-responsive">
                                <table class="table tbl_outgoing_outbound">
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
                                            <td>Date Outgoing</td>
                                            <td>Date</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>No Outgoing</td>
                                            <td>Text</td>
                                            <td></td>
                                            <td>-</td>
                                        </tr>

                                    </tbody>
                                    @else
                                    {!! $template->template_outgoing_outbound!!}
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="form_outbound_header_custom_label" id="number-tabs" style="display:none">
    @if ($template !="")
    {!! $template->form_outbound_header !!}
    @endif
</section>
<section class="form_outgoing_outbound_custom_label" id="number-tabs" style="display:none">
    @if ($template != "")
    {!! $template->form_outgoing_outbound !!}
    @endif
</section>

@endsection

@section('script_document_ready')
<script>
    var form = $(".steps-wizard").show();
    let daftar_custom_label_outbound_header = [];
    let daftar_custom_label_outgoing_outbound= [];
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
                    $(".jenis_outbound").html("Outbound Header");
                }
                else if(currentClassName == 1)
                {
                    $(".jenis_outbound").html("Outgoing Outbound Header")
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
                    $(".jenis_outbound").html("Outbound Header");
                }
                else if(currentClassName == 1)
                {
                    $(".jenis_outbound").html("Outgoing Outbound Header")
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
            let queryselector_custom_label_outbound_header = document.querySelectorAll(
                ".custom_label_outbound_header");
            let custom_label_outbound_header = [];
            queryselector_custom_label_outbound_header.forEach(element => {
                custom_label_outbound_header.push(element);
            });

            let queryselector_custom_label_outgoing_outbound = document.querySelectorAll(
                ".custom_label_outgoing_outbound");
            let custom_label_outgoing_outbound = [];
            queryselector_custom_label_outgoing_outbound.forEach(element => {
                custom_label_outgoing_outbound.push(element);
            });

            let outbound_header_custom_label = $(".form_outbound_header_custom_label").html();
            let outgoing_outbound_custom_label = $(".form_outgoing_outbound_custom_label").html();
            $.ajax({
                type: 'post',
                url: "{!! route('outbound.simpantemplateoutbound', ['kodeproject'=> $kodeproject]) !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                dataType: "json",
                data: {
                    'template': JSON.stringify({
                        'template_outbound_header': $(".tbl_outbound_header").html(),
                        'template_outgoing_outbound': $(".tbl_outgoing_outbound").html(),
                        'form_outbound_header': outbound_header_custom_label,
                        'form_outgoing_outbound': outgoing_outbound_custom_label,
                        'daftar_custom_label_outbound_header' : daftar_custom_label_outbound_header,
                        'daftar_custom_label_outgoing_outbound' : daftar_custom_label_outgoing_outbound
                    }),
                },
                success: function (data) {
                    let title = "";
                    let text = "";
                    let typeSwal = "";
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
        if($(`.${argNamaForm} .${argNamaLabel}`).css('display') =="block")
        {
            $(`.${argNamaForm} .${argNamaLabel}`).attr('style', 'display:none');
            $(`.btn_visible_${argNamaLabel}`).html("Visible");
            triggeredToast("Custom label berhasil disembunyikan!");
        }
        else
        {
            $(`.${argNamaForm} .${argNamaLabel}`).attr('style', 'display:block');
            $(`.btn_visible_${argNamaLabel}`).html("Hide");
            triggeredToast("Custom label berhasil dimunculkan!");
        }
     }

    $(".btn_tambah_row_outbound_header").click(function () {
        let label_outbound_header = $(".outbound-header-label").val();
        if(checkIsLabelExisted(daftar_custom_label_outbound_header, label_outbound_header.replace(/[^A-Z0-9]/ig,'')) == true)
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
            if(label_outbound_header.replace(/[^A-Z0-9]/ig,'') == "")
            {
                error("Label outbound header");
            }
            else
            {
                let type_outbound_header = $(".outbound-header-type").val();
                let required_label_outbound_header = "";

                if ($(".outbound-header-required").prop('checked') == true) {
                    required_label_outbound_header = "required"
                }

                $(".tbl_outbound_header tbody").append(`
                                                <tr class="custom_label_outbound_header ` + label_outbound_header.replace(/[^A-Z0-9]/ig,'') + `" >
                                                    <th scope="row">` + parseInt(countRow('tbl_outbound_header') + 1) + `</th>
                                                    <td>` + label_outbound_header + `</td>
                                                    <td>` + type_outbound_header + `</td>
                                                    <td>` + required_label_outbound_header + `</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-danger button_remove_custom_label_outbound_header" onclick="deleteRow('outbound_header','tbl_outbound_header',` + generateIndex('tbl_outbound_header') + `,'` + label_outbound_header.replace(/[^A-Z0-9]/ig,'') + `')" style="display:block">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button class="btn btn-icon btn-outline-primary button_visible_custom_label_outbound_header btn_visible_${label_outbound_header.replace(/[^A-Z0-9]/ig,'')}" onclick="changeVisibleOfElementForm('form_outbound_header_custom_label' ,'${label_outbound_header.replace(/[^A-Z0-9]/ig,'')}')" style="display:none">
                                                            Hide
                                                        </button>
                                                    </td>
                                                </tr>`);

                $(".form_outbound_header_custom_label").append(`
                    <div class="col-md-12 mb-2 custom_label_outbound_header ` + label_outbound_header.replace(/[^A-Z0-9]/ig,'') + `" style="display:block">
                        <label for="${label_outbound_header.replace(/[^A-Z0-9]/ig,'')}">` + label_outbound_header + `</label>
                        <input type="` + type_outbound_header + `" class="form-control ${required_label_outbound_header} ` + label_outbound_header.replace(/[^A-Z0-9]/ig,'') + `_input " id="${label_outbound_header.replace(/[^A-Z0-9]/ig,'')}" name="${label_outbound_header.replace(/[^A-Z0-9]/ig,'')}" placeholder="` + label_outbound_header + `" ` + `  value="">
                    </div>`)

                daftar_custom_label_outbound_header.push({'nama_label' : label_outbound_header.replace(/[^A-Z0-9]/ig,''), 'type_label' : type_outbound_header});
                $(".outbound-header-label").val("");
                triggeredToast("Custom label berhasil ditambahkan!");
            }
        }
    });

    $(".btn_tambah_row_outgoing_outbound").click(function () {

        let label_outgoing_outbound = $(".outgoing-outbound-label").val();
        if(checkIsLabelExisted(daftar_custom_label_outgoing_outbound, label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')) == true)
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
            if(label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'') == "")
            {
                error("Label outgoing outbound");
            }
            else
            {
                let type_outgoing_outbound = $(".outgoing-outbound-type").val();
                let required_label_outgoing_outbound = "";

                if ($(".outgoing-outbound-required").prop('checked') == true) {
                    required_label_outgoing_outbound = "required";
                }

                $(".tbl_outgoing_outbound tbody").append(`
                                                <tr class="custom_label_outgoing_outbound ` + label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'') + `" >
                                                    <th scope="row">` + parseInt(countRow('tbl_outgoing_outbound') + 1) + `</th>
                                                    <td>` + label_outgoing_outbound + `</td>
                                                    <td>` + type_outgoing_outbound + `</td>
                                                    <td>` + required_label_outgoing_outbound + `</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-danger button_remove_custom_label_outgoing_outbound" onclick="deleteRow('outgoing_outbound','tbl_outgoing_outbound',` + generateIndex('tbl_outgoing_outbound') + `,'` + label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'') + `')" style="display:block">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button class="btn btn-icon btn-outline-primary button_visible_custom_label_outgoing_outbound btn_visible_${label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')}" onclick="changeVisibleOfElementForm('form_outgoing_outbound_custom_label', '${label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')}')" style="display:none">
                                                            Hide
                                                        </button>
                                                    </td>
                                                </tr>`);

                $(".form_outgoing_outbound_custom_label").append(`
                    <div class="col-md-12 mb-2 custom_label_outgoing_outbound ` + label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'') + `" style="display:block">
                        <div class="form-group">
                            <div class="controls">
                                <label for="${label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')}">` + label_outgoing_outbound + `</label>
                                <input type="` + type_outgoing_outbound + `" class="form-control ` + label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'') + `_input" id="${label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')}" name="${label_outgoing_outbound.replace(/[^A-Z0-9]/ig,'')}" placeholder="` + label_outgoing_outbound + `" data-validation-required-message="${label_outgoing_outbound} harus diisi!" ${required_label_outgoing_outbound} >
                            </div>
                        </div>
                    </div>`)

                daftar_custom_label_outgoing_outbound.push({'nama_label' : label_outgoing_outbound.replace(/[^A-Z0-9]/ig,''), 'type_label' : type_outgoing_outbound})
                $(".outgoing-outbound-label").val("");
                triggeredToast("Custom label berhasil dimunculkan!");
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
       if(argArray == "outbound_header")
       {
            daftar_custom_label_outbound_header.splice(daftar_custom_label_outbound_header.indexOf(argNamaClassLabel), 1);
       }
       else
       {
            daftar_custom_label_outgoing_outbound.splice(daftar_custom_label_outgoing_outbound.indexOf(argNamaClassLabel), 1);
       }
       triggeredToast("Custom label berhasil dihapus!");
    }

    function checkIsLabelExisted(argNamaArray, argNamaLabel) { 
        let exist = (argNamaArray.indexOf(argNamaLabel) > -1);
        return exist;
     }

    $(document).ready(function () {
        daftar_custom_label_outbound_header = {!! ($template == "") ? json_encode([]) : json_encode($template->daftar_custom_label_outbound_header) !!};
        daftar_custom_label_outgoing_outbound = {!! ($template == "") ? json_encode([]) : json_encode($template->daftar_custom_label_outgoing_outbound) !!};
        
        $(".button_remove_custom_label_outbound_header").attr('style', 'display:none');
        $(".button_visible_custom_label_outbound_header").attr('style', 'display:block');

        $(".button_remove_custom_label_outgoing_outbound").attr('style', 'display:none');
        $(".button_visible_custom_label_outgoing_outbound").attr('style', 'display:block');

        
    })

</script>
@endsection