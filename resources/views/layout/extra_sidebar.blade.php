@section('navigation_project_has_gudang')
<li class=" navigation-header"><span>Pilih project dan gudang</span>
</li>
<li class="nav-item">
    <div class="row form-group" style="padding-left:12%; width: 100%;">
        <label for="">Project</label>
        <select class="form-control select2 pilih_navigasi_project" name="" id=""
            onchange="getListGudangFromProject(this.value, 'first')">

        </select>
    </div>
</li>
<li class="nav-item">
    <div class="row form-group" style="padding-left:12%; width:100%">
        <label for="">Gudang</label>
        <select class="form-control select2 pilih_navigasi_gudang" name="" id="">

        </select>
    </div>
</li>

<li class="nav-item">
    <div class="row form-group" style="padding-left : 12%; width:100%;">
        <button class="btn btn-outline-primary btn-sm form-control" onclick="refreshGudang()">Ubah</button>
    </div>
</li>
@endsection

@section('extra_sidebar')
@if(Auth::user()->role->nama_role =="Operator" || Auth::user()->role->nama_role =="Admin" ||
Auth::user()->role->nama_role =="admin" && Auth::user()->role->nama_role != "Customer")
<li class=" navigation-header"><span>Proses</span>
<li class="{{(request()->routeIs('inventory.index') ? "active" : "")}} nav-item"><a
        href="{{route('inventory.index',['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-home"></i><span class="menu-title" data-i18n="Colors">Inventory</span></a></li>
</li>
<li
    class="{{(request()->routeIs('item.index') || request()->routeIs('item.create') || request()->routeIs('item.edit') ? "active" : "")}} nav-item">
    <a href="{{route('item.index',['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-circle"></i><span class="menu-title" data-i18n="Colors">Items</span></a></li>
</li>
<li
    class="{{(request()->routeIs('inbound.index') || request()->routeIs('inbound.create') || request()->routeIs('inbound.edit') || request()->routeIs('incoming.index') || request()->routeIs('incoming.create') || request()->routeIs('incoming.edit') ? "active" : "")}} nav-item">
    <a href="{{route('inbound.index',['kodegudang'=> $kodegudang, 'kodeproject'=>$kodeproject])}}"><i
            class="feather icon-circle"></i><span class="menu-title" data-i18n="Colors">Inbound</span></a>
</li>
<li class="{{(request()->routeIs('putaway.index') ? "active" : "")}} nav-item">
    <a href="{{route('putaway.index', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-circle"></i><span class="menu-title" data-i18n="Colors">Put Away</span></a>
</li>
<li
    class="{{(request()->routeIs('outbound.index') || request()->routeIs('outbound.create') || request()->routeIs('outbound.edit') || request()->routeIs('outgoing.index') || request()->routeIs('outgoing.create') || request()->routeIs('outgoing.edit') ? "active" : "")}} nav-item">
    <a href="{{route('outbound.index',['kodegudang'=> $kodegudang, 'kodeproject'=>$kodeproject])}}"><i
            class="feather icon-circle"></i><span class="menu-title" data-i18n="Colors">Outbound</span></a>
</li>
<li
    class="{{(request()->routeIs('stock.index') || request()->routeIs('stock.create') || request()->routeIs('stock.viewcheckstatusbaseitem') || request()->routeIs('stock.viewcheckstatusbaselabel') ? "active" : "")}} nav-item">
    <a href="{{route('stock.index', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-circle"></i><span class="menu-title" data-i18n="Colors">Stock Count</span></a>
</li>
@endif
<li class=" navigation-header"><span>Report</span>
</li>
<li class="{{request()->routeIs('report.pagereportinventory') ? "active" : ""}} nav-item"><a
        href="{{route('report.pagereportinventory', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-file-text"></i><span class="menu-title" data-i18n="Form Layout">Report
            Inventory</span></a>
</li>
<li class="{{request()->routeIs('report.pagereportinbound') ? "active" : ""}} nav-item"><a
        href="{{route('report.pagereportinbound',['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-file-text"></i><span class="menu-title">Report
            Inbound</span></a>
</li>
<li class="{{request()->routeIs('report.pagereportoutbound') ? "active" : ""}} nav-item"><a
        href="{{route('report.pagereportoutbound', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject])}}"><i
            class="feather icon-file-text"></i><span class="menu-title" data-i18n="Form Layout">Report
            Outbound</span></a>
</li>
<li class="{{request()->routeIs('report.pagereportputaway') ? "active" : ""}} nav-item"><a
        href="{{ route('report.pagereportputaway', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang])}}"><i
            class="feather icon-file-text"></i><span class="menu-title" data-i18n="Form Layout">Report
            Putaway</span></a>
</li>
<li class="{{request()->routeIs('report.pagereporbalancetinventory') ? "active" : ""}} nav-item"><a
        href="{{route('report.pagereporbalancetinventory', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject])}}"><i
            class="feather icon-file-text"></i><span class="menu-title" data-i18n="Form Layout">Report Balance
            Inventory</span></a></li>
@endsection

@section('script_pilih_project_dan_gudang')
<script>
    $.ajax({
        type: "get",
        url: "{!! route('inventory.getaksesprojectgudang', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) !!}",
        headers: {
        "X-CSRF-TOKEN" : "{{ csrf_token() }}"
        },
        success: function (response) {
            
            $(".pilih_navigasi_project").html("");
            let result = response
            let selected = "";
            result.forEach( project => {
                selected = (project['kode_project'] == '{!! $kodeproject !!}') ? "selected" : "";
                $(".pilih_navigasi_project").append(`<option value="${project['id_project']} - ${project['kode_project']}" ${selected}>${project['nama_project']}</option>`);
                if(selected =="selected")
                {
                    getListGudangFromProject(project['id_project'] + " - " + project['kode_project'])
                }

            });
        }
    });

    function getListGudangFromProject(argObjectProject) {
        let project = argObjectProject.split(" - ");
        let list_project = [project[0]];
        console.log(JSON.stringify(list_project));
        $.ajax({
            type: "get",
            url: "{!! route('projecthasgudang.getgudangfromproject') !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                'list_project': JSON.stringify(list_project)
            },
            dataType: "json",
            success: function (response) {
                $(".pilih_navigasi_gudang").html("");
                let current_kode_gudang = "{!! $kodegudang !!}";
                let selected = "";
                let current_route_name = "{!! Route::currentRouteName() !!}";
                response.forEach(gudang => {
                    selected = gudang['kode_gudang'] == current_kode_gudang ? "selected" : "";
                    $(".pilih_navigasi_gudang").append(`<option value="${gudang['kode_gudang']}" ${selected}>${gudang['nama_gudang']}</option>`);
                });

                

            }
        });
    }
    function refreshGudang() { 
        let object_project = $(".pilih_navigasi_project").val();
        let project = object_project.split(" - ");
        let kode_gudang = $(".pilih_navigasi_gudang").val();

        // let parameter = "'kodeproject'=> 'KODE_PROJECT', 'kodegudang'=>'KODE_GUDANG'";
        
        let noinbound = "{!! $noinbound ?? '' ?? '' !!}";
        let noincoming = "{!! $noincoming ?? '' ?? '' !!}";
        let nooutbound = "{!! $nooutbound ?? '' ?? '' !!}";
        let nooutgoing = "{!! $nooutgoing ?? '' ?? '' !!}";
        let kodeitem = "{!! $kodeitem ?? '' ?? '' !!}";

        if(noinbound != "")
        {
            noinbound = `, 'noinbound'=>${noinbound}`;
        }
        if(noincoming != "")
        {
            noincoming = `, 'noincoming' => ${noincoming}`;
        }
        if(nooutbound != "")
        {
            nooutbound = `, 'nooutbound' => ${nooutbound}`;
        }
        if(nooutgoing != "")
        {
            nooutgoing = `, 'nooutgoing' => ${nooutgoing}`;
        }
        if(kodeitem != "")
        {
            kodeitem =`, 'kodeitem' => ${kodeitem}`;
        }

        let url = "{!! route(Route::currentRouteName(), ['kodeproject'=> 'KODE_PROJECT', 'kodegudang'=>'KODE_GUDANG', 'NOINBOUND', 'NOINCOMING', 'NOOUTBOUND', 'NOOUTGOING', 'KODEITEM']) !!}";
        url = url.replace('KODE_PROJECT', project[1]);
        url = url.replace('KODE_GUDANG', kode_gudang);
        url = url.replace(', NOINBOUND', noinbound);
        url = url.replace(', NOINCOMING', noincoming);
        url = url.replace(', NOOUTBOUND', nooutbound);
        url = url.replace(', NOOUTGOING', nooutgoing);
        url = url.replace(', KODEITEM', kodeitem);
        // url = url.replace('PARAMETER', parameter);

        window.location = url;
    }

</script>
@endsection