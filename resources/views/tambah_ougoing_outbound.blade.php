@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Tambah Outgoing Outbound
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('extra_information')
&nbsp;<b>No. Outbound : </b>&nbsp; {{$outbound[0]->no_outbound}}, &nbsp;<b>Referensi/No. DO : </b>
&nbsp;{{$outbound[0]->referensi}}
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
        href="{{route('outbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Outbound
        ({{$nooutbound}})</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('outgoing.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound'=>$nooutbound])}}">Outgoing
        Outbound </a>
</li>
<li class="breadcrumb-item active">Tambah Outgoing Outbound
</li>
@endsection

@section('content')
<section id="simple-validation" id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Outgoing Outbound</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form_outgoing_outbound" novalidate>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="no_outgoing">No Outgoing</label>
                                            <input type="text" class="form-control nooutgoing" id="no_outgoing"
                                                placeholder="Kode Gudang"
                                                data-validation-required-message="Nomor outgoing harus diisi!"
                                                value="{{$new_nooutgoing}}" disabled required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="text" class="form-control tanggal" id="tanggal"
                                                placeholder="Tanggal"
                                                data-validation-required-message="Tanggal harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                                {!! $form_outgoing_outbound !!}
                            </div>
                            <button class="btn btn-primary" onclick="tambahOutgoingOutbound()">Submit</button>
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
    $(".form_outgoing_outbound").submit(false);
    
    function tambahOutgoingOutbound() {

        let required_is_filled = true;
        $("form.form_outgoing_outbound").find('input').each(function () { 
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
            let nodelist_custom_label = document.querySelectorAll('.custom_label_outgoing_outbound');
            let array_custom_label_value = [];

            nodelist_custom_label.forEach(element => {
                let split = element.className.split(" ");
                array_custom_label_value.push({
                    'custom_label' : split[3], 'value' : $("."  + split[3] + "_input" ).val()
                });
            })

            $.ajax({
                type: "get",
                url: "{!! route('outgoing.store', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound'=>$nooutbound]) !!}",
                data: {
                    'nooutgoingoutbound' : $(".nooutgoing").val(),
                    'tanggal_outgoing' : $(".tanggal").val(),
                    'outgoing_outbound_value' : JSON.stringify(array_custom_label_value)
                },
                dataType: "json",
                success: function (response) {
                    let title ="";
                    let text="";
                    let type_swal = "";

                    if(response=="success")
                    {
                        window.location = "{!! route('outgoing.index', ['kodegudang' =>$kodegudang, 'kodeproject'=>$kodeproject, 'nooutbound' => $nooutbound])!!}";
                    }
                    else
                    {
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
        
     }

    // function generateNoOutgoingOutbound() {
    //     $.ajax({
    //         type: "get",
    //         url: "{!! route('outgoing.generatenooutgoing', ['kodegudang'=> $kodegudang, 'kodeproject'=>$kodeproject, 'nooutbound',]) !!}",
    //         headers : {
    //             "X-CSRF-TOKEN" : "{{ csrf_token() }}"
    //         },
    //         success: function (response) {
    //             $(".nooutgoing").val(response);
    //         }
    //     });
    // }

    $(".tanggal").val(moment().format('DD-MM-YYYY'));

    $(document).ready(function () {
            // generateNoOutgoingOutbound();
            $(".tanggal").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })
    });
</script>
@endsection