@extends('layout.master')
<section class="card invoice-page">
    <div id="invoice-template" class="card-body">
        <!-- Invoice Company Details -->
        <div id="" class="row">
            <div class="col-sm-4 text-left pt-1">
                <div class="media pt-1">
                    <img src="{{asset('../images/logo/3pl.png')}}" style="width: 200px; height:100px;"
                        alt="company logo" />
                </div>
                <h5 for="">PT Tiga Permata Logistik</h5>
            </div>
            <div class="col-8 text-right">
                <h1>Laporan Inbound</h1>
                <div class="mt-2">
                    <h6>Customer</h6>
                    <p>{{$inbound[0]->nama_perusahaan}}</p>
                    <h6>No. Inbound</h6>
                    <p>{{$inbound[0]->no_inbound}}</p>
                    <h6>Tanggal Inbound</h6>
                    <p>{{date('d-m-Y', strtotime($inbound[0]->tanggal_inbound))}}</p>
                    <h6>Asal</h6>
                    <p>{{$inbound[0]->origin}}</p>
                    <h6>Referensi</h6>
                    <p>{{($inbound[0]->referensi == "" || $inbound[0]->referensi == null) ? "-" : $inbound[0]->referensi}}
                    </p>

                </div>
            </div>
        </div>
        <!--/ Invoice Company Details -->

        <!-- Invoice Recipient Details -->
        <!--/ Invoice Recipient Details -->

        <!-- Invoice Items Details -->
        <div id="invoice-items-details" class="pt-1 invoice-items-table">
            <div class="row">
                <div class="table-responsive col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @foreach (json_decode($inbound[0]->custom_field, true) as $label)
                                <th style="text-align: center">{{$label['custom_label']}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach (json_decode($inbound[0]->custom_field, true) as $label)
                                <td style="text-align: center">{{$label['value']}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive col-12">
                    <table class="table table-bordered" style="border: 1px solid black">
                        <thead>
                            <tr>
                                <th style="font-weight: bold;color: black; font-size: 16px;">No.</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Label</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Item Code</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Item Description</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Total Quantity</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">UOM</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">CBM</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Total CBM</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Berat</th>
                                <th style="font-weight: bold;color: black; font-size: 16px;">Total Berat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inbound as $item)
                            <tr>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$loop->index+1}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->label}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->kode_item}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->nama_item}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->qty_aktual}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->nama_uom}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->cbm}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">
                                    {{$item->cbm * $item->qty_aktual}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$item->berat_kotor}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">
                                    {{$item->berat_kotor * $item->qty_aktual}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" style="font-weight: bold;color: black; font-size: 16px;">Total</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">
                                    {{$subtotalQty}}</td>
                                <td></td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$subtotalCBM}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$subtotalTotalCBM}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$subTotalBerat}}</td>
                                <td style="font-weight: bold;color: black; font-size: 16px;">{{$subtotalTotalBerat}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="33%"
                                        style="text-align: center;font-weight: bold;color: black; font-size: 16px;">
                                        Customer Signature
                                    </th>
                                    <th width="33%"
                                        style="text-align: center;font-weight: bold;color: black; font-size: 16px;">
                                        Delivery's Signature
                                    </th>
                                    <th width="33%"
                                        style="text-align: center;font-weight: bold;color: black; font-size: 16px;">
                                        Administration Signature
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height: 160px; text-align: center; top:150px;">
                                        <h6 for="" style="margin-top:150px;">(Name/Signature/Stamp)</h6>
                                    </td>
                                    <td style="height: 160px; text-align: center">
                                        <h6 style="margin-top:150px;">(Name/Signature)</h6>
                                    </td>
                                    <td style="height: 160px; text-align: center">
                                        <h6 style="margin-top: 150;">(Name/Signature)</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>