<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Warehouse management system - sewagudang.id">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Warehouse Management System - Sewagudang.id</title>
    <link rel="apple-touch-icon" href="../images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('../images/logo/logo_sewagudangid.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/charts/apexcharts.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/extensions/tether-theme-arrows.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/extensions/tether.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/extensions/shepherd-theme-default.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/file-uploaders/dropzone.min.css')}}">
    --}}
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/tables/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('../vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/animate/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/extensions/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('../vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../vendors/css/extensions/toastr.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('../css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('../css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/dashboard-analytics.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/card-analytics.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/plugins/tour/tour.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/data-list-view.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/app-user.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/plugins/forms/wizard.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('../css/plugins/file-uploaders/dropzone.css')}}">
    --}}
    <link rel="stylesheet" type="text/css" href="{{asset('../css/plugins/forms/validation/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/plugins/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/invoice.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('../css/pages/error.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('../css/style.css?v=1')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    @yield('navbar')
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @yield('sidebar')
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-12 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">@yield('page_name')</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    @yield('breadcrum_list')
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="background-spinner"
        style="position: fixed; background-color: grey; width:100%; height:100%; opacity:0.3; display:none; left:0; top:0; margin : 0; z-index:2">
    </div>
    <div class="text-center" style="position: absolute; display:none; z-index:3; top:50%; left:50%">
        <div class="spinner-border mb-1" style="width: 9rem; height: 9rem; opacity : 1; color: firebrick;"
            role="status">
            <span class="sr-only">Loading...</span>

        </div>
        <p style="font-size: 30px;">Loading... </p>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span
                class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a
                    class="text-bold-800 grey darken-2" href="https://www.3pl.co.id/" target="_blank">Tiga Permata
                    Logistik,</a>All rights Reserved</span><span
                class="float-md-right d-none d-md-block">Sewagudang.id</span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i
                    class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('../vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('../vendors/js/charts/apexcharts.min.js')}}"></script>
    <script src="{{asset('../vendors/js/extensions/tether.min.js')}}"></script>
    {{-- <script src="{{asset('../vendors/js/extensions/dropzone.min.js')}}"></script> --}}
    <script src="{{asset('../vendors/js/ui/prism.min.js')}}"></script>
    {{-- <script src="{{asset('../vendors/js/tables/datatable/datatables.min.js')}}"></script> --}}
    <script src="{{asset('../js/scripts/datatable_keys.min.js')}}"></script>
    <script src="{{asset('../vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
    <script src="{{asset('../vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('../vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
    <script src="{{asset('../vendors/js/tables/datatable/dataTables.select.min.js')}}"></script>
    <script src="{{asset('../vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{asset('../vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('../vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('../vendors/js/extensions/polyfill.min.js')}}"></script>
    <script src="{{asset('../vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('../vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('../vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('../vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('../vendors/js/extensions/jquery.steps.min.js')}}"></script>
    <script src="{{asset('../vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('../vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
    <script src="{{asset('../vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('../vendors/js/extensions/toastr.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('../js/core/app-menu.js')}}"></script>
    <script src="{{asset('../js/core/app.js')}}"></script>
    <script src="{{asset('../js/scripts/components.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    {{-- <script src="{{asset('../js/scripts/pages/dashboard-analytics.js')}}"></script> --}}
    {{-- <script src="{{asset('../js/scripts/ui/data-list-view.js')}}"></script> --}}
    <script src="{{asset('../js/scripts/datatables/datatable.js')}}"></script>
    <script src="{{asset('../js/scripts/forms/select/form-select2.js')}}"></script>
    <script src="{{asset('../js/scripts/extensions/sweet-alerts.js')}}"></script>
    <script src="{{asset('../js/scripts/pages/app-user.js')}}"></script>
    <script src="{{asset('../js/scripts/navs/navs.js')}}"></script>
    <script src="{{asset('../js/scripts/pickers/dateTime/pick-a-datetime.js')}}"></script>
    <script src="{{asset('../js/scripts/forms/wizard-steps.js')}}"></script>
    <script src="{{asset('../js/scripts/forms/number-input.js')}}"></script>
    <script src="{{asset('../js/scripts/modal/components-modal.js')}}"></script>
    <script src="{{asset('../js/scripts/tooltip/tooltip.js')}}"></script>
    <script src="{{asset('../js/scripts/forms/validation/form-validation.js')}}"></script>

    <script src="{{asset('../js/scripts/extensions/toastr.js')}}"></script>
    {{-- <script src="{{asset('../js/scripts/extensions/dropzone.js')}}"></script> --}}
    {{-- <script src="{{asset("../js/scripts/forms/form-tooltip-valid.js")}}"></script> --}}
    <!-- END: Page JS-->

    <!-- BEGIN: CUSTOM SCRIPT -->
    <script src="{{asset('../js/app.js')}}"></script>
    <script src="{{asset('../js/saveAsExcel.js')}}"></script>
    <script src="{{asset('../js/excel-gen.js')}}"></script>
    <script src="{{asset('../js/FileSaver.js')}}"></script>
    <script src="{{asset('../js/jszip.js')}}"></script>
    <script src="{{asset('../js/owned_scripts/hidden_label.js')}}"></script>
    <script src="{{asset('../js/owned_scripts/toastr.js')}}"></script>
    <script src="{{asset('../js/scripts/jsBarcode.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"
        integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous">
    </script>
    <script src="{{asset('../js/momentjs.min.js')}}"></script>
    @yield('script_document_ready')
    @yield('script_pilih_project_dan_gudang')
    <!-- END: CUSTOM SCRIPT -->

</body>
<!-- END: Body-->

</html>