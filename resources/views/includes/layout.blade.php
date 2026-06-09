<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
  data-theme="theme-default" data-assets-path="assets_new/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>
    @section('pageHeading')
    @show
  </title>
  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets_new/img/branding/logo.png" />
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
    rel="stylesheet" />
  <!-- Icons -->
  <link href="https://kit-pro.fontawesome.com/releases/v6.2.0/css/pro.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    crossorigin="anonymous">
  <link rel="stylesheet" href="{{url('assets_new/vendor/fonts/materialdesignicons.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/fonts/flag-icons.css')}}" />

  <!-- Menu waves for no-customizer fix -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/node-waves/node-waves.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/css/rtl/theme-default.css')}}"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{url('assets_new/css/demo.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/css/custom.css')}}" />
  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/typeahead-js/typeahead.css')}}" />
  
  <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/tagify/tagify.css')}}" />
  <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />

  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/apex-charts/apex-charts.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/swiper/swiper.css')}}" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/css/pages/cards-statistics.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/css/pages/cards-analytics.css')}}" />
  
  <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/flatpickr/flatpickr.css')}}" />
  <!-- Helpers -->
  <!-- Vendor -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
  
  <!--Date Picker Deenabandhu-->
    <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
    <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
    <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
    <link rel="stylesheet" href="{{ url('assets_new/vendor/libs/pickr/pickr-themes.css')}}" />
    <!--Date Picker Deenabandhu-->
  
  
  <link rel="stylesheet" href="{{ url('assets_new/vendor/css/pages/app-invoice.css')}}" />
  
  <link rel="stylesheet" href="{{url('assets_new/vendor/css/pages/page-auth.css')}}" />
  
  <script src="{{url('assets_new/vendor/js/helpers.js')}}"></script>
  
  <script src="{{url('assets_new/js/config.js')}}"></script>
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/animate-css/animate.css')}}" />
  
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <!--Datatables css -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap5.min.css">

  <!-- <link rel="stylesheet" href="{{url('assets_new/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" /> -->
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
  <link rel="stylesheet" href="{{url('assets_new/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
  <!--End datatables css -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
  
  <style>
    .cell-wrap {
        max-width: 200px;
        white-space: normal;
        word-break: break-word;
    }
    .select2-container {
        width: 100% !important;
    }
    html, body {
        overflow-x: hidden;
    }

    .table-responsive {
        overflow-x: auto;
        overflow-y: visible !important;
    }
  </style>
  
</head>

<body>

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      @include('includes.side_menu')
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        @include('includes.header')
        <!-- / Navbar -->
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          {{-- <div class="container-fluid container-p-y flex-grow-1"> --}}
          @section('content')
          @show
          {{-- </div> --}}
          <!-- / Content -->

          <!-- Footer -->
          @include('includes.footer')
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
  <!-- Core JS -->
  <!-- build:js assets_new/vendor/js/core.js -->
  <script src="{{url('assets_new/vendor/libs/jquery/jquery.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/popper/popper.js')}}"></script>
  <script src="{{url('assets_new/vendor/js/bootstrap.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/node-waves/node-waves.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/hammer/hammer.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/i18n/i18n.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/typeahead-js/typeahead.js')}}"></script>
  <script src="{{url('assets_new/vendor/js/menu.js')}}"></script>
  
  <!--Date Picker Deenabandhu-->
  <script src="{{url('assets_new/vendor/libs/moment/moment.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/flatpickr/flatpickr.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
  <script src="{{url('assets_new/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>

   <script src="{{url('assets_new/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/pickr/pickr.js')}}"></script>

<!--Date Picker Deenabandhu-->

  <!-- select2 js Deenabandhu -->
  <script src="{{url('assets_new/vendor/libs/select2/select2.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/tagify/tagify.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/typeahead-js/typeahead.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/bloodhound/bloodhound.js')}}"></script>

  <script src="{{url('assets_new/js/main.js')}}"></script>
  <script src="{{url('assets_new/js/forms-selects.js')}}"></script>
  <script src="{{url('assets_new/js/forms-tagify.js')}}"></script>
  <script src="{{url('assets_new/js/forms-typeahead.js')}}"></script>
<!-- select2 js Deenabandhu End -->
  
  <!-- endbuild -->
  <!-- Vendors JS-->
  <script src="{{url('assets_new/vendor/libs/apex-charts/apexcharts.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/swiper/swiper.js')}}"></script>
  <!-- Main JS -->
  <script src="{{url('assets_new/js/main.js')}}"></script>
  <!-- Page JS -->
  <script src="{{url('assets_new/js/dashboards-analytics.js')}}"></script>
  <!-- Vendors JS -->
  <script src="{{url('assets_new/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
  <!-- datatables-bootstrap5 -->
  <script src="{{url('assets_new/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script src="{{url('assets_new/vendor/libs/datatables/buttons.colVis.min.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/datatables/pdfmake.min.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/datatables/vfs_fonts.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/datatables/jszip.min.js')}}"></script>

  <!-- Page JS -->
  <script src="{{url('assets_new/js/pages-auth.js')}}"></script>
  <script src="{{url('assets_new/js/dashboards-crm.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/cleavejs/cleave.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
  <script src="{{url('assets_new/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
  <!-- Page JS -->
  <script src="{{url('assets_new/js/offcanvas-send-invoice.js')}}"></script>
  <script src="{{url('assets_new/js/app-invoice-add.js')}}"></script>
  <script src="{{url('assets_new/js/form-layouts.js')}}"></script>
  <script src="{{url('assets_new/js/form-validation.js')}}"></script>
  <script src="{{url('assets_new/js/forms-pickers.js')}}"></script>

  <script>
  $(document).ready(function() {
    const tableIds = ['#example', '#example2', '#example3', '#example4', '#example5', '#example6'];
    tableIds.forEach(function(tableId) {
      $(tableId).DataTable({
        dom: "lBfrtip",
        buttons: [{
            extend: "excel",
            exportOptions: {
              columns: ":not(.noDWExport, :last-child)",
            },
          },
          {
            extend: "pdf",
            orientation: "landscape",
            pageSize: "A2",
            exportOptions: {
              columns: ":not(.noDWExport, :last-child)",
            },
          },
          {
            extend: "colvis",
            text: "",
          },
        ],
        lengthMenu: [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, "All"],
        ],
        
        
        "fnDrawCallback": function (oSettings) {
            var api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
              cell.innerHTML = i + 1;
            });
        }
        
        
      });
    });
  });
  </script>

  <script>
  $(document).ready(function() {
    const tableIds = ['#example7'];
    tableIds.forEach(function(tableId) {
      $(tableId).DataTable({
        dom: "lBfrtip",
        buttons: [{
            extend: "excel",
            exportOptions: {
              columns: ":visible",
            },
          },
          {
            extend: "pdf",
            orientation: "landscape",
            pageSize: "A3",
            exportOptions: {
              columns: ":visible",
            },
            customize: function(doc) {
              // Apply custom styling to prevent wrapping in the thead
              doc.styles.tableHeader = {
                bold: true,
                fontSize: 10,
                alignment: "center",
                color: "#ffffff",
                fillColor: "#666cff",
                noWrap: true, // This ensures no wrapping in the header
              };
              // Set column widths to prevent content wrapping
              const colCount = doc.content[1].table.body[0].length;
              doc.content[1].table.widths = Array(colCount).fill(
              'auto'); // Or use fixed widths like ['15%', '20%', ...]
              // Reduce font size for better fitting
              doc.defaultStyle.fontSize = 9;
            },
          },
          {
            extend: "colvis",
            text: "",
          },
        ],
        lengthMenu: [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, "All"],
        ],
        
        
        "fnDrawCallback": function (oSettings) {
            var api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
              cell.innerHTML = i + 1;
            });
        }
        
        
      });
    });
  });
  </script>
  
    <script>
        $(document).ready(function() {
            const tableIds = ['#example8'];
            tableIds.forEach(function(tableId) {
                $(tableId).DataTable({
                    dom: "lBfrtip",
                    buttons: [{
                        extend: "colvis",
                        text: "columns",
                    }, ],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"],
                    ],
                    
                    "fnDrawCallback": function (oSettings) {
                        var api = this.api();
                        api.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                          cell.innerHTML = i + 1;
                        });
                    }
                });
            });
        });
    </script>

  @section('pageScript')
  @show

     
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Restore last active tab from localStorage
        let activeTab = localStorage.getItem("activeTab");
        if (activeTab) {
            let tabTrigger = document.querySelector('[data-bs-target="' + activeTab + '"]');
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        }

        // Save the active tab whenever it changes
        let tabElements = document.querySelectorAll('button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]');
        tabElements.forEach(function (el) {
            el.addEventListener("shown.bs.tab", function (e) {
                localStorage.setItem("activeTab", e.target.getAttribute("data-bs-target"));
            });
        });
    });
</script>

</body>

</html>