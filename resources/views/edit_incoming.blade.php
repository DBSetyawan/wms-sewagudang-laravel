@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Edit incoming inbound
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('extra_information')
,&nbsp;<b>No. Inbound : </b>&nbsp; {{$inbound[0]->no_inbound}}, &nbsp;<b>Referensi/No. DO : </b>
&nbsp;{{$inbound[0]->referensi}}
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
        href="{{route('inbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inbound
        ({{$noinbound}})</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('incoming.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'noinbound'=>$noinbound])}}">Incoming
        Inbound ({{$noincoming}})</a>
</li>
<li class="breadcrumb-item active">Edit Incoming Inbound
</li>
@endsection


@section('content')
<section id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Incoming Inbound</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="incoming_inbound" novalidate>
                            <div class="incoming_inbound row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="no_incoming">No Incoming Inbound</label>
                                        <input type="text" class="form-control no_incoming_inbound" id="no_incoming"
                                            value="{{$object_incoming[0]->no_incoming}}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="tanggal_inbound">Tanggal Incoming Inbound</label>
                                            <input type='text' class="form-control tanggal_incoming_inbound"
                                                value="{{$object_incoming[0]->tanggal}}" placeholder="Tanggal Inbound"
                                                data-validation-required-message="Tanggal incoming inbound harus diisi!"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                {!! $form_incoming_inbound !!}

                            </div>
                            <div class="row">
                                <div class="col-sm-2 form-group">
                                    <button type="submit" class="form-control btn btn-outline-primary"
                                        onclick="updateIncomingInbound()">
                                        Simpan
                                    </button>
                                </div>
                            </div>
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
    $(".incoming_inbound").submit(false);
    function loadCustomLabelValue() {
        let custom_label_json = {!! $object_incoming !!};
        let element_object = "";
        custom_label_json.forEach(element => {
            element_object = JSON.parse(element['custom_field']);
            element_object.forEach(object => {
                $("." + object['custom_label'] + "_input").val(object['value']);
            });
        });
    }

    function updateIncomingInbound() {

        let required_is_filled = true;
        $("form.incoming_inbound").find('input').each(function () { 
            if($(this).prop('required'))
            {
                console.log(this.value);
                if(this.value.trim() == "")
                {
                    console.log('aloha');
                    required_is_filled = false;
                    return required_is_filled;
                }
            }
        });

        if(required_is_filled == true)
        {
            let nodelist_custom_label = document.querySelectorAll('.custom_label_incoming_inbound');
            let array_custom_label_value = [];

            nodelist_custom_label.forEach(element => {
                let split = element.className.split(" ");
                array_custom_label_value.push({
                    'custom_label': split[2],
                    'value': $("." + split[2] + "_input").val()
                });
            })

            $.ajax({
                type: 'get',
                url: '{!! route("incoming.update",["kodegudang"=> $kodegudang, "kodeproject"=> $kodeproject, "noinbound"=> $noinbound, "noincoming"=>$noincoming]) !!}',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                dataType: 'json',
                data: {
                    no_incoming_inbound: $(".no_incoming_inbound").val(),
                    tanggal_incoming_inbound: $(".tanggal_incoming_inbound").val(),
                    incoming_inbound_value: JSON.stringify(array_custom_label_value)
                },
                success: function (data) {
                    let info = "";
                    let type_swal = "";
                    let status = "";
                    if (data == "success") {
                        window.location =
                            "{!! route('incoming.index', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'noinbound' => $noinbound]) !!}"

                    } else {
                        info = data;
                        status = "Gagal";
                        type_swal = "error";
                        Swal.fire({
                            title: status,
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

    $(document).ready(function () {

        loadCustomLabelValue();
        let date = $(".tanggal_incoming_inbound").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });

    })

</script>
@endsection