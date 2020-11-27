@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Report Inbound
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
<li class="breadcrumb-item active">Report Inbound
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Filter</div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="validationTooltip01">Base On</label>
                                        <select name="" class="form-control base_on">
                                            <option value="Item"> Item </option>
                                            <option value="Label"> Label </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Awal</label>
                                        <input type="text" class="form-control tanggal_awal"
                                            value="{{date('d-m-Y', strtotime($tanggal_min))}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationTooltip01">Tanggal Akhir</label>
                                        <input type="text" class="form-control tanggal_akhir"
                                            value="{{date('d-m-Y', strtotime($tanggal_max))}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-outline-primary btn_get_report"
                                            onclick="getReport()">Tampilkan Report</button>
                                        {{-- <button class="btn btn-outline-primary" onclick="exportToxlsx()">Download
                                            Report</button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 checkbox_container">
                                <label for="">Filter label</label>
                                <div class="row">

                                    @foreach ($daftar_label_detail_inbound as $label)
                                    <div class="col-3 {{$label['nama_label']}}">
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox"
                                                class="inbound-header-required {{$label['nama_label']}}_checkbox"
                                                name="{{$label['nama_label']}}" value="{{$label['nama_label']}}"
                                                onclick="hideLabel()" disabled>
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">{{$label['nama_label']}}</span>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card content_report">
                <div class="card-header">
                    <div class="col-6">
                        Report Inbound - Gudang : <b>{{$kodegudang}}</b>, Project : <b>{{$kodeproject}}</b>
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table tbl_report" id="tbl_report" width="100%">
                                <thead class="header_report_inbound">

                                </thead>
                                <tbody class="content_report_inbound">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="hidden_table_td_container" style="display:none">

</div>
<div class="hidden_table_th_container" style="display:none">

</div>
@endsection

@section('script_document_ready')
<script>
    let dt = "";
    let hidden_td_label = [];
    
    function hideLabel()
    {   
        
        try {
            let checkbox_checked = document.querySelectorAll(".inbound-header-required:checked");
            if(checkbox_checked.length > 0)
            {
                checkbox_checked.forEach(element => {
                    // hideLabel(element.getAttribute('name'));
                    $(`tr .${element.getAttribute('name')}`).attr('style', 'display:none');
                });
            }

            let checkbox_unchecked = document.querySelectorAll('input[type=checkbox]:not(:checked)');
            if(checkbox_unchecked.length > 0)
            {
                checkbox_unchecked.forEach(element => {
                    // hideLabel(element.getAttribute('name'));
                    $(`tr .${element.getAttribute('name')}`).attr('style', 'display:visible');
                });
            }
            // let status = $(`.${argLabel}_checkbox`).is(':checked')
            // if(status == true)
            // {
            //     $(`th.${argLabel}`).addClass(`hidden_th_label_${argLabel}`);
            //     let hidden_th = $(`th.${argLabel}`);
            //     let hidden_td = $(`td.${argLabel}`);
            //     let object = {};
            //     let firstTimeInsert = true;
            //     let lastInsertedIndex = 0;
            //     for(let i =0; i<hidden_td.length; i++)
            //     {
            //         let object = {};
            //         object[argLabel] = hidden_td[i].outerHTML;
            //         hidden_td_label.push(object);
            //     }
               
            //     $(".hidden_table_th_container").append(hidden_th);
            //     $(`.hidden_th_label_${argLabel}`).removeClass(`${argLabel}`)
            //     $(`th.${argLabel}`).remove();
            //     $(`td.${argLabel}`).remove();
                
            // }
            // else
            // {
            //     $(`.hidden_th_label_${argLabel}`).addClass(argLabel);
            //     let hidden_th = $(`.hidden_th_label_${argLabel}`);
            //     $(".header_report_inbound tr").append(hidden_th);

            //     let length = 0;
            //     let arrayIndexOf = [];
            //     hidden_td_label.filter(function(td){
                    
            //         if(td[argLabel] != null)
            //         {
            //             arrayIndexOf.push(hidden_td_label.indexOf(td));
            //             $(`.row_${length}`).append(td[argLabel]);
            //             length ++;
            //         }
                    
                    
            //     });
                
            //     let i =0;
            //     let count = arrayIndexOf.length;
                
            //     hidden_td_label.splice(arrayIndexOf[0], count);
            //     arrayIndexOf = [];
                
            // }
        } catch (error) {
            console.log(error);
        }
    }
    function exportToxlsx()
    {
        
        let current_date = "{!! date('d-m-Y') !!}";
        let namaFile = "Inbound"+ " - " + "{!! $kodegudang !!}"+ " - " + "{!! $kodeproject !!}" + " - "  + current_date
        excel = new ExcelGen({
            "src_id" : "tbl_report",
            "show_header": true,
            "exclude_selecter": ".hidden_label"
        });
        excel.generate(namaFile);
    }

    function changeHeaderReport()
    {
        let template_json = {!! json_encode($json) !!};
        let base_on = $(".base_on").val();
        $(".header_report_inbound").html("");
        if(base_on == "Label")
        {
            $(".header_report_inbound").html(` <tr class="header">
                                        <th>Tanggal Inbound</th>
                                        <th>Kode Inbound</th>
                                        <th>Referensi/No. DO</th>
                                        <th>Asal/Origin</th>
                                        <th>Label</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Quantity Doc</th>
                                        <th>Quantity Aktual</th>
                                        <th>UOM</th>
                                        <th>CBM</th>
                                        <th>Total Weight</th>
                                        <th>Locator</th>
                                        ${template_json['th_detail_inbound']}
                                    </tr>`)
        }
        else
        {
            $(".header_report_inbound").html(` <tr class="header">
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Referensi</th>
                                        <th>Asal/Origin</th>
                                        <th>Quantity</th>
                                        <th>Qty Aktual</th>
                                        <th>UOM</th>
                                        <th>CBM</th>
                                        <th>Total Weight</th>
                                        ${template_json['th_detail_inbound']}
                                    </tr>`)
        }
        // $(".header").append(template_json['th_detail_inbound'])
        
        hideHiddenLabel();
        let hidden_th = document.querySelectorAll(".hidden_label");
        let classname = "";

        hidden_th.forEach((element) => {
            classname = element.className.split(" ");

            $(`.${classname[1]}`).attr("style", "display:none");
        });
    }

    async function getReport()
    {
        dt.destroy();
        changeHeaderReport();
        $(".content_report_inbound").html("");
        dt = createDataListView('tbl_report');
        let filter = $(".base_on").val();
        let tanggal_awal = $(".tanggal_awal").val();
        let tanggal_akhir = $(".tanggal_akhir").val();
        $.ajax({
            type: "get",
            url: "{!! route('inbound.displayreport', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            data: {
                'tanggal_awal' : tanggal_awal,
                'tanggal_akhir' : tanggal_akhir,
                'filter' : filter
            },
            dataType: "json",
            success: function (result) {
                console.log(result);
                // dt.clear().draw();
                let i = 0;
                let string = "";
                if(filter == "Label")
                {
                    result.forEach(item => {
                        // $(".content_report_inbound").append(`<tr class="row_${i} row_dt">
                        //             <td>${item['tanggal_inbound']}</td>
                        //             <td>${item['no_inbound']}</td>
                        //             <td>${item['label']}</td>
                        //             <td>${item['kode_item']}</td>
                        //             <td>${item['nama_item']}</td>
                        //             <td>${item['qty_doc']}</td>
                        //             <td>${item['qty_aktual']}</td>
                        //             <td>${item['nama_uom']}</td>
                        //             <td>${item['cbm']}</td>
                        //             ${item['custom_field_td']}
                        //         </tr>`)
                        string = `<tr class="row_${i} row_dt">
                                    <td>${item['tanggal_inbound']}</td>
                                    <td>${item['no_inbound']}</td>
                                    <td>${item['referensi']}</td>
                                    <td>${item['origin']}</td>
                                    <td>${item['label']}</td>
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['qty_doc']}</td>
                                    <td>${item['qty_aktual']}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm']}</td>
                                    <td>${item['berat_kotor'] * item['qty_aktual']}</td>
                                    <td>${item['nama_locator']}</td>
                                    ${item['custom_field_td']}
                                </tr>`
                        dt.rows.add($(string)).draw();
                        i++
                    });
                }
                else
                {
                    
                    result.forEach(item => {
                        // $(".content_report_inbound").append(`<tr class="row_${i} row_dt">
                        //             <td>${item['kode_item']}</td>
                        //             <td>${item['nama_item']}</td>
                        //             <td>${item['qty_doc']}</td>
                        //             <td>${item['qty_aktual']}</td>
                        //             <td>${item['nama_uom']}</td>
                        //             <td>${item['cbm']}</td>
                        //             ${item['custom_field_td']}
                        //         </tr>`);

                            string = `<tr class="row_${i} row_dt">
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['referensi']}</td>
                                    <td>${item['origin']}</td>
                                    <td>${item['qty_doc']}</td>
                                    <td>${item['qty_aktual']}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm']}</td>
                                    <td>${item['berat_kotor'] * item['qty_aktual']}</td>
                                    ${item['custom_field_td']}
                                </tr>`
                        dt.rows.add($(string)).draw();
                        i++
                    });
                }
                $("input[type=checkbox]").prop('disabled',false);
                hideHiddenLabel();
                dt.cell(':eq(0)').focus();
                
            }
        });
    }
    $('#tbl_report').on( 'page.dt', async function () {
        // await hideLabel();
        await hideHiddenLabel();
        let checkbox_checked = document.querySelectorAll(".inbound-header-required:checked");
        if(checkbox_checked.length > 0)
        {
            checkbox_checked.forEach(element => {
                hideLabel(element.getAttribute('name'));
                $(`tr .${element.getAttribute("name")}`).attr('style', 'display:none');
            });
        }

        let checkbox_unchecked = document.querySelectorAll('input[type=checkbox]:not(:checked)');
        if(checkbox_unchecked.length > 0)
        {
            checkbox_unchecked.forEach(element => {
                hideLabel(element.getAttribute('name'));
                // $(`tr .${element.getAttribute('name')}`).attr('style', 'display:block');
            });
        }
    } );

    function getTableHeaderIndex(argNamaClass) { 
        let idx = 0;
        $(".tbl_report thead tr th").each(function(){
            console.log(this.className);
            if(this.getAttribute('name') == argNamaClass)
            {
                console.log('aloha');
                return idx;
            }
            idx++;
        })
     }

    function createDataListView(argNamaClass)
    {
        let object = $("." + argNamaClass).DataTable({
            responsive: true,
            columnDefs: [{
                orderable: true,
                targets: 0,
                checkboxes: {
                    selectRow: true
                }
            }],
            dom: '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
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
            bInfo: false,
            pageLength: 4,
            keys: true,
            select: {
                style: "single"
            },
            buttons: [{
                extend : 'excelHtml5',
                text : 'Download Report',
                className:'btn btn-outline-primary',
                exportOptions : {
                    modifier : {
                        page : 'all',      // 'all',     'current'
                    },
                    
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells
                    $('row c', sheet).each(function() {
                    //select the index of the row
                        var numero=$(this).parent().index() ;
                        var residuo = numero%2;
                        let header = 1;
                        if(numero ==2 )
                        {
                            $(this).attr('s','22');
                        }
                        else if (numero>2){
                            if(residuo !=0  ){//'is t',
                                $(this).attr('s','5');
                            }
                            
                            
                        }
                    });
                },
                title : "Report Inbound - Project : {!! $project[0]->nama_project !!}, Gudang : {!! $gudang[0]->nama_gudang !!}",
                messageTop : "Periode : " + $(".tanggal_awal").val() + " - " + $(".tanggal_akhir").val(),
            }],
            fnDrawCallBack : function(osettings){
                removeHiddenLabel();
            },
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    function removeHiddenLabel() {
        let hidden_th = document.querySelectorAll(".hidden_label");
        let classname = "";

        hidden_th.forEach((element) => {
            classname = element.className.split(" ");

            $(`.${classname[1]}`).remove();
        });
    }

    

    $(document).ready(function(){
        $(".tanggal_awal").val(moment().add(-30, 'days').format('DD-MM-YYYY'));
        $(".tanggal_akhir").val(moment().format('DD-MM-YYYY'));
        changeHeaderReport();
        $(".tanggal_awal").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })
        $(".tanggal_akhir").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })

        let hidden_th = document.querySelectorAll(".hidden_label");
        let classname = "";


        dt = createDataListView('tbl_report');
        $('.tbl_report').on('key-focus.dt', function(e, datatable, cell){
        // Select highlighted row
        console.log('aloha');
            $(datatable.row(cell.index().row).node()).addClass('selected');
        });

        // Handle event when cell looses focus
        $('.tbl_report').on('key-blur.dt', function(e, datatable, cell){
            // Deselect highlighted row
            $(datatable.row(cell.index().row).node()).removeClass('selected');
        });
            
        // Handle key event that hasn't been handled by KeyTable
        $('.tbl_report').on('key.dt', function(e, datatable, key, cell, originalEvent){
            // If ENTER key is pressed
            if(key === 13){
                // Get highlighted row data
                var data = datatable.row(cell.index().row).data();
                console.log(data);
            }
        });      
        
        
    });


</script>
@endsection