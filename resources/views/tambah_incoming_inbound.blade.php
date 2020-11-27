@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Tambah Incoming Inbound
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('extra_information')
&nbsp;<b>No. Inbound : </b>&nbsp; {{$inbound[0]->no_inbound}}, &nbsp;<b>Referensi/No. DO : </b>
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
        Inbound</a>
</li>
<li class="breadcrumb-item active">Tambah Incoming Inbound
</li>
@endsection

@section('content')
<section id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Buat Incoming Inbound</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="incoming_inbound_form" novalidate>
                            <div class="incoming_inbound row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="no_incoming">No Incoming Inbound</label>
                                            <input type="text" class="form-control no_incoming_inbound" id="no_incoming"
                                                value="{{$new_noincoming}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="tanggal_inbound">Tanggal Incoming Inbound</label>
                                            <input type='text' class="form-control tanggal_incoming_inbound"
                                                id="tanggal_inbound" name="tangal_inbound" placeholder="Tanggal Inbound"
                                                data-validation-required-message="Tanggal incoming inbound harus diisi!"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                {!! $form_incoming_inbound!!}
                            </div>
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <button class="form-control btn btn-outline-primary"
                                        onclick="simpanIncomingInbound()">
                                        Buat incoming inbound
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
    $(".incoming_inbound_form").submit(function(e)
    {
        e.preventDefault();
    });

        $(".tanggal_incoming_inbound").pickadate({
            format: "dd-mm-yyyy",
            editable:true
        })

        function simpanIncomingInbound()
        {
            let required_is_filled = true;
            $("form.incoming_inbound_form").find('input').each(function () { 
                if($(this).prop('required'))
                {
                    if(this.value.trim() == "")
                    {
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
                    console.log(split);
                    array_custom_label_value.push({
                        'custom_label' : split[2], 'value' : $("."  + split[2] + "_input" ).val()
                    });
                })

                $.ajax({
                    type:'get',
                    url : '{!! route("incoming.store",["kodegudang"=> $kodegudang, "kodeproject"=> $kodeproject, "noinbound"=> $noinbound]) !!}',
                    headers : {
                        "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                    },
                    dataType : 'json',
                    data : {
                        no_incoming_inbound : $(".no_incoming_inbound").val(),
                        tanggal_incoming_inbound : $(".tanggal_incoming_inbound").val(),
                        incoming_inbound_value : JSON.stringify(array_custom_label_value)
                    },
                    success : function(data){
                        let info = "";
                        let type_swal ="";
                        let status = "";
                        if(data == "success")
                        {
                            window.location = "{!! route('incoming.index', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'noinbound' => $noinbound]) !!}";
                            
                        }
                        else
                        {
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
        $(".tanggal_incoming_inbound").val(moment().format('DD-MM-YYYY'));
        $(".tanggal_incoming_inbound").pickadate({
            editable : true,
            format : "dd-mm-yyyy"
        })
    });
</script>
@endsection