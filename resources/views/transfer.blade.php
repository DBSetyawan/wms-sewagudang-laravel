@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Transfer
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
<li class="breadcrumb-item active">Transfer
</li>
@endsection

@section('content')
<section id="data-thumb-view" class="data-thumb-view-header">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Transfer</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="steps-wizard wizard-circle">

                            <!-- Step 1 -->
                            <h6>Outbound Header</h6>

                            <fieldset>
                                <div class="outbound_header row">
                                    <div class="col-sm-12 nooutbound_container" style="display:none">
                                        <div class="form-group">
                                            <label for="nooutbound">No Outbound</label>
                                            <input type='text' class="form-control nooutbound" placeholder=""
                                                disabled />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="tanggal_inbound">Tanggal Outbound</label>
                                            <input type='text' class="form-control tanggal_outbound"
                                                placeholder="Tanggal Inbound" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstName1">Referensi</label>
                                            <input type="text" class="form-control referensi" id="firstName1"
                                                placeholder="Referensi">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstName1">Tujuan Outbound</label>
                                            <select name="" class="tujuan select2" placeholder="Gudang Tujuan">
                                                @foreach ($result_project as $gudang)
                                                <option value="{{$gudang->nama_gudang}}">{{$gudang->nama_gudang}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {!! $template->form_outbound_header !!}
                                </div>
                            </fieldset>

                            <!-- Step 2 -->
                            <h6>Detail Outbound</h6>
                            <fieldset>

                                <div class="detail_outbound row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Pilih Item</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="contain">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <h6>Jenis filter</h6>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="radio" name="filter_value" class="nama_item"
                                                                value="nama_item" onchange="changeFilter('item')"
                                                                checked> Nama item
                                                            <input type="radio" name="filter_value" class="label"
                                                                value="label" onchange="changeFilter('label')"> Label
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 d-flex align-items-center">
                                                            <h6 class=" ">Cari item</h6>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="" id="" class="form-control pilih_item"
                                                                placeholder="Pilih Item" onchange="refreshTableItem()"
                                                                value="">
                                                                <option value="" disabled selected>Pilih item</option>
                                                                @foreach ($list_item as $item)
                                                                <option value='{{$item->kode_item}}'>
                                                                    {{$item->kode_item }} - {{$item->nama_item}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table tbl_display_list_item" width="100%">
                                            <thead>
                                                <tr class="th_detail_outbound">
                                                    <th style="display:none">Id Item</th>
                                                    <th>Kode Item</th>
                                                    <th>Nama Item</th>
                                                    <th>Label </th>
                                                    {{-- <th>Batch</th> --}}
                                                    <th>Locator</th>
                                                    <th>Quantity Available</th>
                                                    {!! $template_inbound->th_detail_inbound !!}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list_item">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="data-thumb-view" class="data-thumb-view-header">
    <div class="row 1" style="display:none">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Outbound</h4>
                </div>
                <div class="card-content tbl_format">
                    <div class="card-body">
                        <div class="1">
                            <div class="table-responsive ">
                                <table class="table tbl_detail_outbound" id="detail_outbound" width="100%">
                                    <thead>
                                        <tr class="th_detail_outbound">
                                            <th style="display:none">Id Item</th>
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Label</th>
                                            {{-- <th>Batch</th> --}}
                                            <th>Locator</th>
                                            <th>Quantity</th>
                                            {!! $template_inbound->th_detail_inbound !!}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="content_detail_outbound">

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

<section id="form-and-scrolling-components">
    <div class="modal-size-sm mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="proses_item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="" id="" class="id_inbound_detail" style="display: none">
                        <input type="text" name="" id="" class="id_item" style="display: none">
                        <div class="row" style="display:none">
                            <div class="col-md-6 col-12 mb-1">
                                <label></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="idx">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none">
                            <div class="col-md-6 col-12 mb-1">
                                <label></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="idinventory">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none">
                            <div class="col-md-6 col-12 mb-1">
                                <label></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="idinventorydetail">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none">
                            <div class="col-md-6 col-12 mb-1">
                                <label></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="idlocator">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none">
                            <div class="col-md-6 col-12 mb-1">
                                <label></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="qty_max">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Quantity</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="quantity" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-outline-primary btn-pick">Pick</button>
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
    var form = $(".steps-wizard").show();
    let dt_list_outbound = "";
    let dt_detail_outbound = "";
    let daftar_item = [];
    let custom_field_td = "";
    let last_row_count= 0;
    $("#add_new_item").on('show.bs.modal',function(){
        $(".pilih_item").select2('close');
    })

    $(".quantity").keyup(function(){
        let limit_available = $(".qty_max").val();
        if(parseInt($(this).val()) > parseInt(limit_available))
        {
            // console.log(($(this).val().trim().length > limit_available.length));
            $(this).val(limit_available);
        }
        else
        {
            return;
        }
    })

    $(".btn-pick").on('click',function(){
        let qty = $(".quantity").val();
        let idx = $(".idx").val();
        let idlocator = $(".idlocator").val();
        let idinventory = $(".idinventory").val();
        let idinventorydetail = $(".idinventorydetail").val();
        let kodeitem = $(".kodeitem").val();
        let customfield = $(".custom_field_td").val();
        let nama_locator = $(".nama_locator").val();
        let kode_item = $(".kode_item").val();
        let nama_item = $(".nama_item").val();

        let idx_content = $("." + idx).html();
        

        // daftar_item.push({
        //     'qty' : qty,
        //     'id_item' : info_item[0],
        //     'idlocator' : idlocator,
        //     'idinventory' : idinventory,
        //     'idinventorydetail' : idinventorydetail,
        // });

        // console.log(daftar_item);
        $.ajax({
            type: "get",
            url: "{!! route('outbound.updateqty', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                qty : qty,
                item : $('.pilih_item').val(),
                idlocator : idlocator,
                idinventory : idinventory,
                idinventorydetail: idinventorydetail,
                action : 'allocated',
                filter : $("input[name=filter_value]:checked").val(),
                nooutbound : sessionStorage.getItem('nooutbound'),
               
            },
            dataType: "json",
            success: function (response) {
                if(response['status']=="success")
                {
                    let rowCount = $(".content_detail_outbound tr").length;
                    let string = `
                        <tr id="row_${response['id_outbound_detail']}">
                            ${idx_content}
                        </tr>
                    `;
                    dt_detail_outbound.rows.add($(string)).draw();
                    dt_detail_outbound.page('last').draw('page');
                    $('.' + last_row_count + "-quantity").html(qty);
                    $("." + last_row_count + "-label").html(response['label']);
                    $('.' + last_row_count + "-action").html(`<button type="button" class="btn btn-outline-primary btn-icon btn-sm" onclick="removeItemFromDetailOutbound('${response['id_outbound_detail']}','${idinventorydetail}', '${$('.pilih_item').val()}', '${qty}', this)"><i class="fa fa-minus"></i></button>`);

                    if(response['quantity_available'] == 0)
                    {
                        $("#proses_item").modal('hide');
                    }
                    $(".quantity").trigger("touchspin.updatesettings", {max : parseInt(response['quantity_available'])})
                    $(".quantity").val(parseInt(response['quantity_available']))
                    last_row_count +=1;
                    triggeredToast("Item berhasil dipick!");
                }
                else
                {
                    Swal.fire({
                        title: "Gagal!",
                        text: response['status'],
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
               refreshTableItem();
            }
        });
    })

    $(".steps-wizard").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        enableAllSteps:false,
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'Simpan outbound detail transfer',
            next : 'Simpan outbound header transfer'
        },
        onStepChanging:function(event,currentIndex,newIndex){
            if (currentIndex > newIndex) {
                return true;
            }

            let currentClassName = document.getElementsByClassName(newIndex)[0].className.split(' ');

            if($("." + currentClassName[1]).css('display')=='none')
            {
                $("." + currentClassName[1]).attr('style','display:block');
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();

        },
        onStepChanged: function (event, currentIndex, newIndex) {
            //Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                if(sessionStorage.getItem('nooutbound') == null)
                {
                    let tanggal_outbound = $(".tanggal_outbound").val();
                    let tujuan = $(".tujuan").val();
                    let referensi = $(".referensi").val();
                    let nodelist_custom_label_outbound_header = document.querySelectorAll('.custom_label_outbound_header');
                    let array_custom_label_value = [];

                    nodelist_custom_label_outbound_header.forEach(element => {
                        let split = element.className.split(" ");
                        array_custom_label_value.push({'custom_label' : split[3], 'value' : $("." + split[3] + "_input").val()});
                    });

                    $.ajax({
                        type:'get',
                        url : "{!! route('outbound.store', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject]) !!}",
                        headers : {
                            'X-CSRF-TOKEN' : "{{ csrf_token() }}"
                        },
                        dataType : "json",
                        data : {
                            tanggal_outbound : tanggal_outbound,
                            tujuan : tujuan,
                            outbound_header_value : JSON.stringify(array_custom_label_value),
                            referensi : referensi,
                            action : 'save_outbound_header',
                            type_outbound : 'Transfer'
                        },
                        success: function (response) {
                            console.log(response);
                            if(sessionStorage.getItem('nooutbound') == null)
                            {
                                sessionStorage.setItem('nooutbound', `${response}`);
                            }
                            triggeredToast("Outbound header berhasil disimpan!");
                            $(".nooutbound").val(sessionStorage.getItem('nooutbound'));
                            $(".nooutbound_container").attr('style','display:block');

                            $(".pilih_item").select2('open');
                        }
                    });
                }
            }

            let previousClassName = document.getElementsByClassName(newIndex)[0].className.split(' ');

            if($("." + previousClassName[1]).css('display')=='block')
            {
                $("." + previousClassName[1]).attr('style','display:none');
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            simpanOutbound();
        }
    });

    function removeItemFromDetailOutbound(argIdOutboundDetail ,argIdInventoryDetail, argIdItem, argQty, argObjectRow)
    {
        let url = "{!! route('outbound.removeitemfromoutbound', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'nooutbound' => 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', sessionStorage.getItem('nooutbound'));
        $.ajax({
            type: "get",
            url: url,
            headers : { 
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'idoutbounddetail' : argIdOutboundDetail,
                'idinventorydetail' : argIdInventoryDetail,
                'iditem' : argIdItem,
                'qty' : argQty
            },
            dataType: "json",
            success: function (response) {
                if(response == "success")
                {
                    dt_detail_outbound.row($(argObjectRow).parents('tr')).remove().draw();
                    // $("#row_" + argIdOutboundDetail).remove();
                    triggeredToast("Item berhasil dihapus dari list!");
                    refreshTableItem(); 
                }
                else
                {
                    Swal.fire({
                        title: "Gagal!",
                        text: response,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }
            }
        });
    }

    function openModal(argIdInventory, argIdLocator, argMaxLimit, argKodeItem, argIdInventoryDetail, argIdx)
    {
        let idx = $(".detail_outbound tr").length;
        $("#proses_item").modal('show');
        $(".qty_max").val(argMaxLimit);
        $(".idlocator").val(argIdLocator);
        $(".idinventory").val(argIdInventory);
        $('.kodeitem').val(argKodeItem);
        $(".idinventorydetail").val(argIdInventoryDetail)
        $(".idx").val(argIdx);
        minMaxSpin();
        $(".quantity").trigger("touchspin.updatesettings", {max : parseInt(argMaxLimit)})
        $(".quantity").val(argMaxLimit);
        $(".quantity").focus();
    }

    function minMaxSpin()
    {
        let touchspinValue = $(".quantity"),
            counterMin = 1,
            counterMax = 1;
        if (touchspinValue.length > 0) {
            touchspinValue.TouchSpin({
            min: counterMin,
            max: counterMax
            }).on('touchspin.on.startdownspin', function () {
            var $this = $(this);
            $('.bootstrap-touchspin-up').removeClass("disabled-max-min");
            if ($this.val() == counterMin) {
                $(this).siblings().find('.bootstrap-touchspin-down').addClass("disabled-max-min");
            }
            }).on('touchspin.on.startupspin', function () {
            var $this = $(this);
            $('.bootstrap-touchspin-down').removeClass("disabled-max-min");
            if ($this.val() == counterMax) {
                $(this).siblings().find('.bootstrap-touchspin-up').addClass("disabled-max-min");
            }
            });
        }
    }

    function changeFilter(argAction)
    {
        if(argAction == "label")
        {
            refreshItemBaseLabel();
        }
        else
        {
            refreshItem();
        }
    }

    function refreshItemBaseLabel()
    {
        $.ajax({
            type: "get",
            url: "{!! route('item.getallitembaselabel', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) !!}",
            headers :{
                "X-CSRF-TOKEN" :"{{ csrf_token() }}"
            },
            success: function (response) {
                if(response.length != 0 || response != "")
                {
                    let result = response;
                    $(".pilih_item").html("");
                    $(".pilih_item").append(`<option disabled selected value> Pilih item </option>`)
                    result.forEach(item => {
                        $(".pilih_item").append(`<option value='` + item['label'] + `'>` + item['label'] +`</option>`);
                    });
                }
            }
        });
    }

    function refreshItem()
    {
        $.ajax({
            type:'get',
            url : `{!! route('item.getallitem',["kodegudang" => $kodegudang, "kodeproject"=>$kodeproject]) !!}`,
            headers:{
                "X-CSRF-TOEKN" : "{{ csrf_token() }}"
            },
            success: function(data){
                if(data.length != 0 || data != "")
                {
                    let result = JSON.parse(data);
                    $(".pilih_item").html("");
                    $(".pilih_item").append(`<option disabled selected value> Pilih item </option>`)
                    result.forEach(item => {
                        $(".pilih_item").append(`<option value='` + item['kode_item'] +  `'>` + item['kode_item'] + ` - ` + item['nama_item'] +`</option>`);
                    });
                }

            }
        })
    }

    function loadCustomTemplate()
    {
        $.ajax({
            type:'get',
            url:"{!! route('outbound.loadtemplateoutbound', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            headers: {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success : function(data){
                let result = data
                $(".outbound_header").append(result['form_outbound_header']);
            }
        })

        $.ajax({
            type:'get',
            url:"{!! route('inbound.loadtemplateinbound', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            headers: {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success : function(data){
                let result = JSON.parse(data)
                $(".th_detail_outbound").append(result['th_detail_inbound']);
                $(".th_detail_outbound").append("<th>Action</th>")
            }
        })

    }

    function refreshTableItem()
    {
        let filter_value = $(".pilih_item").val();
        let filter = $("input[name=filter_value]:checked").val();
        $.ajax({
            type: "get",
            url: "{!! route('item.infofrominbounddetail', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            headers  : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data : {
                "filter" : filter,
                "filter_value" : filter_value

            },
            dataType : "json",
            success: function (response) {
                if(response.length != 0 || response != "")
                {
                    dt_list_outbound.clear().draw();
                    let result = response
                    let idx = 0;
                    let string = "";
                    let object = "";
                    // let currentCountRow = $(".content_detail_outbound tr").length;
                    result.forEach(item => {
                        object = {a:item['id_inventory'], b:item['id_locator'], c:item['available'], d:item['kode_item'], e:item['id_inventory_detail'], f:idx};
                        string = `
                            <tr class="${idx}">
                                <td style="display:none">${item['id_item']}</td>
                                <td>${item['kode_item']}</td>
                                <td>${item['nama_item']}</td>
                                <td class="${last_row_count}-label">${item['label']}</td>
                                <td>${item['nama_locator']}</td>
                                <td class="${last_row_count}-quantity">${item['available']}</td>
                                ${item['custom_field_td']}
                                <td class="${last_row_count}-action"><a href="#" data-toggle="modal" onclick="openModal(${item['id_inventory']}, ${item['id_locator']}, ${item['available']}, '${item['kode_item']}', ${item['id_inventory_detail']}, ${idx})">Picked</a></td>
                            </tr>
                        `;
                        dt_list_outbound.rows.add($(string)).draw();
                        $(`.${idx}`).data('value', object);
                        idx++;
                    });
                    hideHiddenLabel();
                }
                else
                {
                    $(".list_item").html("");
                }
                $("#DataTables_Table_0_filter input").focus();
                dt_list_outbound.cell(':eq(1)').focus();
            }
        });
       
    }

    function simpanOutbound()
    {
        let no_outbound = $(".nooutbound").val();
        let tanggal_outbound = $(".tanggal_outbound").val();
        let tujuan = $(".tujuan_outbound").val();
        let nodelist_custom_label_outbound_header = document.querySelectorAll('.custom_label_outbound_header');
        let array_custom_label_value = [];

        nodelist_custom_label_outbound_header.forEach(element => {
            let split = element.className.split(" ");
            array_custom_label_value.push({'custom_label' : split[3], 'value' : $("." + split[3] + "_input").val()});
        });

        $.ajax({
            type:'get',
            url : "{!! route('outbound.store', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject]) !!}",
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            dataType : "json",
            data : {
                nooutbound : no_outbound,
                tanggal_outbound : tanggal_outbound,
                tujuan : tujuan,
                list_item : JSON.stringify(daftar_item),
                outbound_header_value : JSON.stringify(array_custom_label_value),
                action : 'insert_list_item'
            },
            success : function(data){

                let text = "";
                let type_swal = "";
                let title = "";
                if(data == "success")
                {
                    window.location = "{!! route('outbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject]) !!}"
                }
                else
                {
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

    function getOutboundHeader(argNoOutbound)
    {
        let url = "{!! route('outbound.show', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound'=> 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
        $.ajax({
            type: "get",
            url: url,
            data: {
                nooutbound : argNoOutbound
            },
            dataType: "json",
            success: function (response) {
                $(".tanggal_outbound").val(response[0]['tanggal_outbound']);
                $(".tujuan_outbound").val(response[0]['destination']);
                let custom_label_json = JSON.parse(response[0]['custom_field']);

                custom_label_json.forEach(element => {
                    $("." + element['custom_label'] + "_input").val(element['value']);
                });
            }
        });
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
            select: true,
            keys:true,
            buttons: [],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    window.addEventListener('unload', function(event){
        // sessionStorage.setItem('performance', performance.navigation.type)
        sessionStorage.removeItem('nooutbound');
    })

    $(document).ready(function(){
        hideHiddenLabel();
        
        $(".tanggal_outbound").val(moment().format('DD-MM-YYYY'));
        $('.tanggal_outbound').pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });
        $(".pilih_item").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
        dt_list_outbound = createDataListView('tbl_display_list_item');
        dt_detail_outbound = createDataListView('tbl_detail_outbound');
        
        $('.tbl_display_list_item').on('key-focus.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).addClass('selected');
        });

        $('.tbl_display_list_item').on('key-blur.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).removeClass('selected');
        });
            
        $('.tbl_display_list_item').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                let row_index = cell.index().row;
                console.log(row_index);
                let value = $(`.${row_index}`).data('value');
                openModal(value.a, value.b, value.c, value.d, value.e, value.f);
            }
        });     
    });

</script>

@endsection