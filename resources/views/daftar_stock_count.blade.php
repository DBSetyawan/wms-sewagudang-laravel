@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar stock count
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
<li class="breadcrumb-item active">Daftar Stock Count
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Kode Stock Count</th>
                    <th>Tanggal Buat Stock Count</th>
                    <th>Tanggal Update</th>
                    <th>Nama User</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_stock_count as $stock_count)
                <tr>
                    <td>{{$stock_count->kode_stock_count}}</td>
                    <td>{{date('d-m-Y', strtotime($stock_count->tanggal_buat_stock))}}</td>
                    <td>{{date('d-m-Y H:i:s', strtotime($stock_count->tanggal_update))}}</td>
                    <td>{{$stock_count->nama_user}}</td>
                    <td>
                        <button title="Check stock count by item" class="btn btn-outline-primary btn-icon btn-sm"
                            onclick="window.location = '{!! route('stock.viewcheckstatusbaseitem', ['kodegudang'=> $kodegudang, 'kodeproject'=>$kodeproject, 'kodestock' => $stock_count->kode_stock_count]) !!}'"><i
                                class="fa fa-check"></i></button>
                        <button title="Check stock count by label" class="btn btn-outline-info btn-icon btn-sm"
                            onclick="window.location = '{!! route('stock.viewcheckstatusbaselabel', ['kodegudang'=> $kodegudang, 'kodeproject'=>$kodeproject, 'kodestock' => $stock_count->kode_stock_count]) !!}'"><i
                                class="fa fa-check"></i></button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@endsection

@section('script_document_ready')
<script>
    function dataThumbView()
        {
            let dataThumbView = $(".data-thumb-view").DataTable({
                responsive: false,
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
                    [10, 20, 30, -1],
                    [10, 20, 30, "All"]
                ],
                order: [
                    [0, "desc"]
                ],
                bInfo: false,
                pageLength: 10,
                buttons: [{
                    text: "Tambah Stock Count",
                    action: function () {
                        window.location.href= "{{route('stock.create', ['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject ])}}"
                    },
                    className: "btn-outline-primary"
                }],
                initComplete: function (settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                }
            });

        }
        
        $(document).ready(function () {
            dataThumbView();
        });
</script>
@endsection