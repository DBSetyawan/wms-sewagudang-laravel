@extends('layout.master')
@extends('layout.sidebar')
@extends('layout.navbar')

@section('page_name')
Download Dokumentasi
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Download dokumentasi
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
 <div class="table-responsive">
  <table class="table download">
   <thead>
    <tr>
     <th>Nama file</th>
     <th>Action</th>
    </tr>
   </thead>
   <tbody>
    <tr>
     <td>User manual untuk sewagudang mobile apps</td>
     <td><a href="{{route('utility.downloaddokumentasi', ['platform'=>"Mobile"])}}">Download</a></td>
    </tr>
    <tr>
     <td>User manual untuk sewagudang web apps</td>
     <td><a href="{{route('utility.downloaddokumentasi', ['platform'=>"Web"])}}">Download</a></td>
    </tr>
   </tbody>
  </table>
 </div>
</section>
@endsection

@section('script_document_ready')
<script>
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
            buttons: [{

            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    $(document).ready(function () {
     createDataListView('download');
    });
</script>
@endsection