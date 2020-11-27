@extends('layout.master')
<section class="card invoice-page">
    <div id="invoice-template" class="card-body">
        <!-- Invoice Company Details -->
        <div id="invoice-company-details" class="row">
            <div class="col-sm-4 col-12 text-left pt-1">
                <div class="media pt-1">
                    <img src="{{asset('../images/logo/3pl.png')}}" style="width: 200px; height:100px;"
                        alt="company logo" />

                </div>
                <h5 style="font-weight: bold;color: black" for="">PT Tiga Permata Logistik</h5>
            </div>
            <div class="col-sm-8 col-12 text-right">
                <h1>Laporan Outbound</h1>
                <div class="invoice-details mt-2">
                    <h6 style="font-weight: bold;color: black">Customer</h6>
                    <p style="color: black">{{$outbound[0]->nama_perusahaan}}</p>
                    <h6 style="font-weight: bold;color: black">No. Outbound</h6>
                    <p style="color: black">{{$outbound[0]->no_outbound}}</p>
                    <h6 class="mt-2" style="font-weight: bold;color: black">Tanggal Outbound</h6>
                    <p style="color: black">
                        {{date('d-m-Y', strtotime($outbound[0]->tanggal_outbound))}}</p>
                    <h6 class="mt-2" style="font-weight: bold;color: black">Destination/Tujuan</h6>
                    <p style="color: black">
                        {{$outbound[0]->tujuan}}</p>
                    <h6 style="font-weight: bold;color: black">Referensi</h6>
                    <p style="color: black">
                        {{($outbound[0]->referensi =="" || $outbound[0]->referensi == null) ? "-" : $outbound[0]->referensi}}
                    </p>
                </div>
            </div>
        </div>
        <!--/ Invoice Company Details -->

        <div id="invoice-items-details" class="pt-2 invoice-items-table">
            <div class="row">
                <div class="table-responsive col-12">
                    <table class="table table-bordered" style="">
                        <thead>
                            <tr>
                                @foreach (json_decode($outbound[0]->custom_field, true) as $label)
                                <th style="text-align: center">{{$label['custom_label']}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach (json_decode($outbound[0]->custom_field, true) as $label)
                                <td style="text-align: center">{{$label['value']}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Items Details -->
            <div id="invoice-items-details" class="invoice-items-table">
                <div class="row">
                    <div class="table-responsive col-12">
                        <table class="table table-bordered tbl_list_item" style="border: 3px solid black">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">No.</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Item Code</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Label inv</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Label Out</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Item Description</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Total Quantity</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">UOM</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">CBM</th>
                                    <th style="font-weight: bold;color: black; font-size: 16px;">Total CBM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound as $item)
                                <tr>
                                    <td style="font-weight: bold;color: black">{{$loop->index+1}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->kode_item}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->label_inv}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->label_out}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->nama_item}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->qty_load}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->nama_uom}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->cbm}}</td>
                                    <td style="font-weight: bold;color: black">{{$item->cbm * $item->qty_load}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="font-weight: bold;color: black" colspan="5">Total</td>
                                    <td style="font-weight: bold;color: black">{{$subtotalQty}}</td>
                                    <td></td>
                                    <td style="font-weight: bold;color: black">{{$subtotalCBM}}</td>
                                    <td style="font-weight: bold;color: black">{{$subtotalTotalCBM}}</td>
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
                                        <th width=" 33%"
                                            style="text-align: center;font-weight: bold;color: black; font-size: 16px;">
                                            Delivery's Signature
                                        </th>
                                        <th width=" 33%"
                                            style="text-align: center;font-weight: bold;color: black; font-size: 16px;">
                                            Administration Signature
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style=" height: 160px; text-align: center; top:150px;">
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