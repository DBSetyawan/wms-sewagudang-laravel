@extends('layout.master')
@extends('layout.sidebar')
@extends('layout.navbar')

@section('page_name')
History Log
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">History Log
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
                  <div class="col-md-6 mb-2">
                    <label for="validationTooltip01">Tanggal Awal</label>
                    <input type="text" class="form-control tanggal_min" value="">
                  </div>
                  <div class="col-md-6">
                    <label for="validationTooltip01">Tanggal Akhir</label>
                    <input type="text" class="form-control tanggal_max" value="">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-outline-primary btn_get_report" onclick="getLog()">Tampilkan
                      Log</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">History Log</div>
        <hr>
        <div class="card-content">
          <div class="card-body">
            <div id="log">
              <div class="log-list">
                @foreach ($timeline as $log)
                <div class="log-item">
                  <div class="log-icon status-intransit">
                    <i class="fa fa-circle"></i>
                  </div>
                  <div class="log-date">
                    {{date('d-m-Y', strtotime(explode(" ", $log->waktu_update)[0]))}}<span>{{date('H:i:s', strtotime(explode(" ", $log->waktu_update)[1]))}}</span>
                  </div>
                  <div class="log-content">{{$log->nama_user}} - {{$log->device_type}} ({{$log->device_model}})
                    <span>{!! $log->log !!}</span>
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
</section>
@endsection
@section('script_document_ready')
<script>
  let dt = "";
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
              [10, 20, 30, -1],
              [10, 20, 30, "All"]
          ],
          order: [
              [0, "desc"]
          ],
          bInfo: false,
          pageLength: 10,
          keys: true,
          select: {
              style: "single"
          },
          buttons: [],
          fnDrawCallBack : function(osettings){
              removeHiddenLabel();
          },
          initComplete: function (settings, json) {
              $(".dt-buttons .btn").removeClass("btn-secondary")
          }
      });

      return object;
  }

  function getLog() { 
    let tanggal_min = $(".tanggal_min").val();
    let tanggal_max = $(".tanggal_max").val();

    $.ajax({
       type: "get",
       url: "{!! route('log.getlog') !!}",
       data: {
         "tanggal_min" : tanggal_min,
         'tanggal_max' : tanggal_max
       },
       dataType: "json",
       success: function (response) {
         let result = response;
        //  dt.clear().draw();
        $(".log-list").html("");
         result.forEach(log => {
           $(".log-list").append(`
             <div class="log-item">
              <div class="log-icon status-intransit">
                <i class="fa fa-circle"></i>
              </div>
              <div class="log-date">
                ${moment(log['waktu_update'].split(" ")[0]).format('DD-MM-YYYY')}<span> ${log['waktu_update'].split(" ")[1]}</span>
              </div>
              <div class="log-content">${log['nama_user']} - ${log['device_type']} (${log['device_model']})
                <span>${ log['log'] }</span>
              </div>
            </div>
           `)
          // string = `<tr>
          //             <td>${log['waktu_update']}</td>
          //             <td>${log['log']}</td>
          //             <td>${log['nama_user']}</td>
          //           </tr>`
          // dt.rows.add($(string)).draw();
         });
       }
     });
  }
  
  $(document).ready(function () {
    $(".tanggal_min").val(moment().add(-30, 'days').format('DD-MM-YYYY'));
    $(".tanggal_max").val(moment().format('DD-MM-YYYY'));
    $(".tanggal_min").pickadate({
        format: 'dd-mm-yyyy',
        editable:true
    })
    $(".tanggal_max").pickadate({
        format: 'dd-mm-yyyy',
        editable:true
    })

    dt = createDataListView("tbl-log");
  });
</script>
@endsection