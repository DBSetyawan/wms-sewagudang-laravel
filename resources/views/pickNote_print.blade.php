@extends('layout.master')
<section class="card invoice-page">
    <div id="invoice-template" class="card-body">
        <!-- Invoice Company Details -->
        <div id="invoice-company-details" class="row">

            <div class="col-sm-8 col-12 text-right">
                <h1>Pick Note - <b>{{$outbound->outbound_detail[0]->referensi}}</b></h1>

            </div>
        </div>
        <!--/ Invoice Company Details -->

        <div id="invoice-items-details" class="pt-2 invoice-items-table">
            <!-- Invoice Items Details -->
            <div id="invoice-items-details" class="invoice-items-table">
                <div class="row">
                    <div class="table-responsive col-12">
                        <table class="table table-bordered tbl_list_item" style="border: 3px solid black">
                            <thead>
                                <tr style="font-weight: bold;color: black;">
                                    <th>No. DO / Referensi</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Quantity</th>
                                    <th>Locator</th>
                                    {!!$template_inbound->th_detail_inbound!!}
                                </tr>
                            </thead>
                            <tbody style="font-weight: bold;color: black">
                                @foreach ($outbound->outbound_detail as $item)
                                <tr>
                                    <td>{{$item->referensi}}</td>
                                    <td>{{$item->kode_item}}</td>
                                    <td>{{$item->nama_item}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->nama_locator}}</td>
                                    {!!$item->inbound_custom_field_td!!}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>

@section('script_document_ready')
<script>
    $(document).ready(function () {
        window.print();
    });
</script>
@endsection