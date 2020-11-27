@extends('layout.master')

@section('content')
<svg id="barcode"></svg>
@endsection
@section('script_document_ready')
    <script>



        async function generateBarcode(argKodeItem, argNamaItem)
        {
            // $("#barcode_container").modal('show');
            JsBarcode(`#barcode`, argKodeItem, {
                text: argKodeItem + " - " + argNamaItem
            });
            window.print();
        }

        $(document).ready(function () {
            let kodeItem = "{!! $item[0]->kode_item !!}";
            let nama_item = "{!! $item[0]->nama_item !!}";
            generateBarcode(kodeItem, nama_item);
        });
    </script>
@endsection