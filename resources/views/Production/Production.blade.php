@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
/* Show only 10 serial number records, rest scrollable */
#serial_append {
    max-height: 420px; /* Adjust for 10 records visually */
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 8px;
    background: #fff;
    display: block;
}
    .tab {
        display: none;
    }

    .btn1 {
        background-color: #95f3ff;
    }

    .btn1:hover {
        background-color: #e0f7fa;
    }

    button {
        background-color: #04AA6D;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: Raleway;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
    }

    #prevBtn {
        background-color: #bbbbbb;
    }

    /* .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    } */

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border: none !important;
    }


    table#dynamic_field {
        margin-top: -14px;
    }

    .downloadfile {
        display: flex;
    }

    .downloadfile div {
        margin: 0px 20px;
    }

    .downloadfile i.fa.fa-remove {
        color: red;
    }

    /* div#adaaishhhh {
        margin-left: 10px;
        margin-bottom: 20px;


        width: 98.5%;
    } */

    input.form-control.form-control-sm {
        margin-top: 10px;
    }

    hr {
        width: 99% !important;
    }

    div#adaais {
        margin-left: 10px;
        margin-bottom: 20px;

    }

    div#\a main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        align-content: center;
    }

    table#ssef {
        border: 1px solid;
        width: 50%;
    }


    tr.jaafgg td {
        padding: 10px !important;
    }

    tr.jaafgg {
        border-bottom: 1px solid !important;
    }

    .rm_tabe {
        display: flex;
    }


    div#lkjhhdggdg {
        margin-top: 40px;
    }

    table#ssef td {
        padding-left: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
    }


    input#logfgfau {
        height: 60px;
    }

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    table#ufkffguuyuffffu {
        margin-top: 30px;
        border: 1px solid #ddd;
    }




    table#ufkffguuyuffffu thead tr {
        padding: 10px !important;
    }

    table#ufkffguuyuffffu thead tr th.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    table#ufkffguuyuffffu thead tr td.th-sm {
        padding: 10px;
        border: 1px solid #ddd !important;
    }

    div#himmatwalaa {
        display: flex;
        align-items: center;
        justify-content: center;
        align-content: center;
    }

    div#main_btn_uddhan {
        display: flex;
        justify-content: flex-end;
    }

.myaccordion {
  box-shadow: 0 0 1px rgba(0,0,0,0.1);
}
.myaccordion .card-header {
  border-bottom-color: #EDEFF0;
  background: transparent;
}

.myaccordion .fa-stack {
  font-size: 18px;
}

.myaccordion .btn {
  width: 100%;
  font-weight: bold;
  color: #004987;
  padding: 0;
}

.myaccordion .btn-link:hover,
.myaccordion .btn-link:focus {
  text-decoration: none;
}

.myaccordion li + li {
  margin-top: 10px;
}
</style>
<div class="card-form">
    <div class="app-content">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <section class="section">
                <div class="container-fluid">
                    <div class="border-bottom pb-1 row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                          <h6>Production Details</h6>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Inputer Name : {{auth()->user()->name}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('Production/ProductionList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('Production/ProductionList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>

                    <form action="{{route('Production.store')}}" method="POST" id="sales-fields">
                        @csrf
                        <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                        <div id="row">
                            <!-- <h6>Production</h6> -->
                            <div class="my-2 row" id="adaaishhhh">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Unit Name*
                                    </label>
                                    <select name="Unit_Name" id="Manunit" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_unit as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Unit_Name) &&
                                            $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Plant Name*
                                    </label>
                                    <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($edit->Plant_Name))
                                            @foreach($plant_name as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <div class="field-wrap">
                                        {{-- <input class="form-control form-control-sm" type="text" id="org_name" value="{{isset($edit->organisation) && $edit->organisation!=''?$edit->organisation:''}}" name="Organization_Name" readonly value="" required> --}}
                                        {{-- <input class="form-control form-control-sm" type="text" id="org_name" value="{{$Organization[$edit->Organization_Name]['organisation']??''}}" name="Organization_Name" readonly value="" required> --}}
                                        <input class="form-control form-control-sm" type="text" id="org_name" value="{{ isset($edit['Organization_Name']) && isset($Organization[$edit['Organization_Name']]['organisation']) ? $Organization[$edit['Organization_Name']]['organisation'] : '' }}" name="Organization_Name" readonly required>
                                        <input class="form-control form-control-sm" value="{{isset($edit->Organization_Name) && $edit->Organization_Name!=''?$edit->Organization_Name:''}}" type="hidden" id="org_id" name="Organization" readonly value="">
                                    </div>
                                    {{-- <select name="Organization" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organization) && $val->organization!=''?$val->organization:''}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                                {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        BU Name*
                                    </label>
                                    <select name="BU" id="bunameid" class="form-select form-select-sm" required {{ isset($edit->BU_Name) ? 'disabled' : '' }}>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($edit->BU_Name))
                                            @foreach($BU as $val)
                                            <option value="{{$val->id}}" {{isset($edit->BU_Name) && $edit->BU_Name==$val->id?'selected':''}} disabled>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> --}}
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>BU Name*</label>
                                    <select name="BU" id="bunameid" class="form-select form-select-sm" required {{ isset($edit->BU_Name) ? 'disabled' : '' }}>
                                        <option value="" selected disabled>Select</option>
                                        @if(isset($edit->BU_Name))
                                        @foreach($BU as $val)
                                        <option value="{{$val->id}}" {{isset($edit->BU_Name) && $edit->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if(isset($edit->BU_Name))
                                    <input type="hidden" name="BU" id="hidden_bunameid" value="{{ $edit->BU_Name }}">
                                    @endif
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Shift*
                                    </label>
                                    <select name="shift" id="shift" {{ isset($edit->Shift) ? 'disabled' : '' }} class="form-select form-select-sm" required>
                                        <option value="" selected >Select</option>
                                            @foreach($Shift as $val)
                                            <option value="{{$val->shift_code}}" {{isset($edit->Shift) && $edit->Shift==$val->shift_code?'selected':''}}>{{$val->shift}}</option>
                                            @endforeach
                                    </select>
                                    @if(isset($edit->Shift))
                                    <input type="hidden" name="shift" value="{{ $edit->Shift }}">
                                    @endif

                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Production Date
                                    </label>
                                    <input type="date" id="production_date" {{ isset($edit->Production_Date) ? 'readonly' : '' }} value="{{$edit->Production_Date??''}}" placeholder="Production Date" name="Production_Date" class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <div class="border row p-2" id="adaaishhhh">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Finished Good(FG)*
                                        </lable>
                                        <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required  {{isset($edit->Raw_Material)?'disabled':''}}>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                {{-- <div class="col-xl-1 col-lg-1 col-md-4 col-sm-12 form-group">
                                    <label>UOM *</label>
                                    <div class="field-wrap">
                                        <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-xl-1 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>UOM*</label>
                                    <div class="field-wrap">
                                        <input type="text" readonly name="UOM" id="uom" value="{{$edit->UOM??''}}" placeholder="UOM" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Rate*</label>
                                    <div class="field-wrap">
                                        <input type="text" name="Rate" id="Rate" value="{{$edit->Rate??''}}" placeholder="Rate" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Quantity*</label>
                                    <div class="field-wrap">
                                        <input type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Quantity" onchange="materialdata()" value="{{$edit->Quantity??''}}" {{isset($edit->Quantity)?'readonly':''}} placeholder="Quantity" id="Quantity" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Total amount*</label>
                                    <div class="field-wrap">
                                        <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Total_amount" value="{{$edit->Total_amount??''}}" id="Total_amount" placeholder="Rate*Quantity" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No.</th>
                                        <th class="th-sm">Raw Material Name</th>
                                        <th class="th-sm">Plant Stock</th>
                                        <th class="th-sm">UMO</th>
                                        <th class="th-sm">Consumtion Qty</th>
                                        <th class="th-sm">Scarp Qty</th>
                                        <th class="th-sm">Other Qty</th>
                                        <th class="th-sm">Total Qty</th>
                                    </tr>
                                </thead>
                                <tbody >

                                </tbody>
                            </table>
                        </div>
                        <div id="sddfsfs"></div>
                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                            </div>
                            <div class="col-xl-9 col-lg-4 col-md-4 col-sm-12"></div>
                            @if(!isset($edit->id))
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12">
                                <div id="accordion" class="myaccordion">
                                    <div class="card m-1 p-0">
                                        <div class="card-header p-1" id="headingOne">
                                            <h2 class="mb-0 p-1">
                                                <button type="button" class="d-flex align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                serial Number
                                                    <span class="fa-stack fa-sm">
                                                        <i class="fas fa-circle fa-stack-2x"></i>
                                                        <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseOne" class="collapse ml-0 w-100" aria-labelledby="headingOne" data-parent="#accordion">
                                            <!-- Search bar for serial numbers -->
                                            <div class="mb-2">
                                                <input type="text" id="serialSearch" class="form-control" placeholder="Search or scan serial number...">
                                            </div>
                                            <div class="card-body" id="serial_append">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div style="overflow:auto;">
                            <div class="somras">
                                <button type="submit" id="submitdata" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>

        </section>
    </div>
</div>

@endsection
@push('custom-scripts')

<script>
    $("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
  $(e.target)
    .prev()
    .find("i:last-child")
    .toggleClass("fa-minus fa-plus");
});
</script>

<script>
    $(document).ready(function() {
        activeclass(24, 1);
    });
</script>
<script>
    function displayTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById("clock").textContent = time + ', ' + date;
    }

    setInterval(displayTime, 1000);
</script>
<script>
    $(document).ready(function() {
        const storedMode = localStorage.getItem('selectedMode');

        const urlParams = new URLSearchParams(window.location.search);
        const mode = urlParams.get('mode');

        if (mode === 'sales') {
            $("#stock-fields").hide();
            $("#sales-fields").show();
        } else if (mode === 'stock') {
            $("#sales-fields").hide();
            $("#stock-fields").show();
        } else if (storedMode === 'sales') {
            $("#stock-fields").hide();
            $("#sales-fields").show();
        } else if (storedMode === 'stock') {
            $("#sales-fields").hide();
            $("#stock-fields").show();
        }

        $(".changeFields").on("click", function() {
            const selectedMode = $(this).data('mode');
            localStorage.setItem('selectedMode', selectedMode);

            if (selectedMode === 'sales') {
                $("#stock-fields").hide();
                $("#sales-fields").show();
            } else if (selectedMode === 'stock') {
                $("#sales-fields").hide();
                $("#stock-fields").show();
            }
        });
    });
</script>
<script>
    $('#RawMaterial').on('change', function() {
        materialdata()
    });
    $('#Plant_Name').on('change', function() {
        materialdata()
    });
    function materialdata()
    {

        var MaterialId = $('#RawMaterial').val();
        var PlantID = $('#Plant_Name').val();
        var Quantity = parseInt($("#Quantity").val());
        if(PlantID=='' || PlantID==0 || PlantID=='null' || PlantID==null)
        {
            alert('Please Select Plant First')
            return false;
        }
        if(MaterialId=='' || MaterialId==0 || MaterialId=='null' || MaterialId==null)
        {
            return false;
        }
        if(Quantity<1)
        {
            return false;
        }

        $.ajax({
            url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
            type: 'GET',
            data: {
                MaterialId: MaterialId,
            },
            success: function(data) {
                $('#HSNCode').val(data.data.HSN_Code);
                $('#uom').val(data.data.UOM).change();
            }
        });

        $.ajax({
            url: "{{url('Production/MaterialData')}}",
            type: 'POST',
            data: {
                productionID:'{{$edit->id??""}}',
                MaterialId: MaterialId,
                PlantID:PlantID,
                Quantity:Quantity??0,
            },
            success: function(data) {
                console.log(data);

                var table = $('#Tabledata');

                // if ($.fn.DataTable.isDataTable(table)) {
                //     table.DataTable().destroy();
                // }

                table.find('tbody').empty();

                var Total = 0;
                $("#Tabledata tbody").html(data)
                // table.DataTable({
                //     "ordering": false
                // });
            }
        });
    }
   $("#sales-fields").on('submit',function(){
        var materialID=[]
            $.each($("input[name='materialID[]']"), function(){
                materialID.push($(this).val());
            });
            Plantstock=[]
            $.each($("input[name='Plantstock[]']"), function(){
                value=$(this).val()
                if(value>0 && value!='')
                {
                    Plantstock.push(value)
                }

            });
            // if(materialID.length!=Plantstock.length)
            // {
            //     alert('Plant Stock Should Have Quantity')
            //     return false;
            // }
            $("#uom").attr('disabled',false);
   });
</script>
<script>

    $("#Quantity").blur(function(){
        ratecal()
    });
    $("#Rate").blur(function(){
        ratecal()
});
    function ratecal()
    {
        Quantity=parseInt($("#Quantity").val());
        Rate=(parseFloat($("#Rate").val()));
       $("#Total_amount").val(Quantity*Rate);
    }
    @if(isset($edit->id))
    $(document).ready(function(){
        materialdata()
    });
    @endif
</script>
<script>
    $("#submitdata").click(function(){
        idd=[]
        $.each($("input[name='idd[]']"), function(){
            idd.push($(this).val());
            });
           
    })
</script>
<script>
    $(document).ready(function() {
        $('#Manunit').change(function() {
            $('#org_name').val('');
            var ManunitId = $(this).val();

            if (ManunitId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
                    type: 'GET',
                    data: {
                        ManunitId : ManunitId
                      },
                    success: function(response) {
                        $('#Plant_Name').empty();
                        $('#Plant_Name').append('<option value="" selected disabled>Select</option>');
                        $.each(response, function(index, plantdetails) {
                            var option = $('<option>');
                            option.val(plantdetails.id);
                            option.text(plantdetails.spname);
                            $('#Plant_Name').append(option);
                        });
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        $('#Plant_Name').change(function() {

            var plantId = $(this).val();
            //alert(plantId);
            var prjid = $('#Manunit').val();
            var subprjid = $('#Plant_Name').val();

            if (plantId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-orgnames')}}" + '/' + plantId,
                    type: 'GET',
                    data: {
                        plantId : plantId
                      },
                    success: function(response) {
                        $.each(response, function(index, plantdetails) {
                            $("#org_name").val(plantdetails.organisation);
                            $("#org_id").val(plantdetails.orgid);
                        });
                    }
                });
            }
            if (plantId) {
                $.ajax({
                    url: "{{url('PPFinishedGood/get-budetails')}}" + '/' + plantId,
                    type: 'GET',
                    data: {
                        ManunitId: plantId,
                        prjid: prjid,
                        subprjid: subprjid
                    },
                    success: function(response) {
                        $('#bunameid').empty();
                        //$('#bunameid').append('<option value="" selected disabled>Select</option>');

                        if (response.length === 0) {
                            alert('This business unit is blank against your project and sub-project ID');
                        } else {
                            $.each(response, function(index, plantdetails) {
                                var option = $('<option>');
                                option.val(plantdetails.id);
                                option.text(plantdetails.unit_name);
                                $('#bunameid').append(option);
                            });
                        }
                    }
                });
            }



        });
    });

    $(document).ready(function() {
            // Initially disable the BU Name dropdown if it's an edit case
            @if(isset($edit->BU_Name))
                $('#bunameid').prop('disabled', true);
            @endif

            // Enable BU Name dropdown on Plant Name selection
            $('#Plant_Name').on('change', function() {
                // Enable the BU Name dropdown
                $('#bunameid').prop('disabled', false);

                // Remove the hidden field if it exists
                $('#hidden_bunameid').remove();

                // Optionally, you can load the BU names based on the selected Plant Name
                // Here you might want to use an AJAX call to fetch the BU names based on the selected plant
                var plantId = $(this).val();

                if (plantId) {
                    $.ajax({
                        url: '/get-bu-names/' + plantId, // Update with your actual endpoint
                        type: 'GET',
                        success: function(response) {
                            $('#bunameid').empty().append('<option value="" selected disabled>Select</option>');
                            $.each(response, function(index, bu) {
                                $('#bunameid').append('<option value="' + bu.id + '">' + bu.unit_name + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching BU names:', xhr);
                        }
                    });
                }
            });

            // Handle form submission to ensure hidden field is set if BU Name is disabled
            $('form').on('submit', function() {
                if ($('#bunameid').is(':disabled')) {
                    var selectedBU = $('#bunameid').val();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'hidden_bunameid',
                        name: 'BU_Name',
                        value: selectedBU
                    }).appendTo('form');
                }
            });
        });
        
        $(document).ready(function() {
            // Function to handle the AJAX call and DOM manipulation
            function fetchSerialNumberDetails() {
                var shiftid = $('#shift').val();
                var ManunitId = $('#production_date').val();

                if (ManunitId) {
                    $.ajax({
                        url: "{{url('Production/get-serialnumberdetails')}}" + '/' + ManunitId,
                        type: 'GET',
                        data: {
                            shiftid: shiftid
                        },
                        success: function(response) {
                            $('#serial_append').empty(); // Clear the previous content

                            // Add "Select All" checkbox
                            var selectAllCheckbox = '<div class="form-group form-check">' +
                                                    '<input type="checkbox" class="form-check-input" id="selectAll">' +
                                                    '<label class="form-check-label" for="selectAll">Select All (Total: 0)</label>' +
                                                    '</div>';
                            $('#serial_append').append(selectAllCheckbox);

                            // Add event listener for "Select All" checkbox
                            $('#selectAll').on('change', function() {
                                $('.serial-checkbox').prop('checked', $(this).prop('checked'));
                                updateTotalCount();
                                validateCheckboxSelection();
                            });

                            var checkboxIndex = 1; // Start checkbox index from 1

                            // Append serial number checkboxes
                            $.each(response, function(index, plantdetails) {
                                if (plantdetails.status !== "USED") {
                                    var checkboxHtml = '<div class="form-group form-check">' +
                                                    '<input type="checkbox" class="form-check-input serial-checkbox" name="serial_check[' + checkboxIndex + ']" value="' + plantdetails.sl_no + '" id="check' + checkboxIndex + '">' +
                                                    '<label class="form-check-label" for="check' + checkboxIndex + '">' + plantdetails.sl_no + '</label>' +
                                                    '</div>';
                                    $('#serial_append').append(checkboxHtml);
                                    checkboxIndex++; // Increment checkbox index
                                }
                            });

                            // Add event listener for individual checkboxes to manage "Select All" state
                            $(document).on('change', '.serial-checkbox', function(e) {
                                if ($('.serial-checkbox:checked').length === $('.serial-checkbox').length) {
                                    $('#selectAll').prop('checked', true);
                                } else {
                                    $('#selectAll').prop('checked', false);
                                }
                                updateTotalCount();
                                // Quantity limit logic for manual check
                                var quantity = parseInt($('#Quantity').val(), 10);
                                var selectedCount = $('.serial-checkbox:checked').length;
                                if (selectedCount > quantity && $(this).prop('checked')) {
                                    alert('Your quantity value and checkbox selection do not match. Only the last serial will be unchecked.');
                                    $(this).prop('checked', false);
                                    updateTotalCount();
                                }
                                validateCheckboxSelection();
                            });

                            // --- Search bar logic for manual entry and barcode scan ---
                            $('#serialSearch').off('input').on('input', function(e) {
                                var searchVal = $(this).val().trim();
                                if (searchVal !== '') {
                                    var found = false;
                                    var lastChecked = null;
                                       var alreadyChecked = false;
                                    // Only check the matching checkbox, keep previous checked
                                        $('.serial-checkbox').each(function() {
                                            if ($(this).val() === searchVal) {
                                                   if ($(this).prop('checked')) {
                                                       alreadyChecked = true;
                                                   } else {
                                                $(this).prop('checked', true);
                                                $(this)[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                                                found = true;
                                                lastChecked = $(this);
                                                   }
                                                // Highlight the label
                                                $(this).closest('.form-check').find('label').addClass('serial-highlight');
                                            } else {
                                                // Remove highlight from other labels
                                                $(this).closest('.form-check').find('label').removeClass('serial-highlight');
                                            }
                                        });
                                            if (alreadyChecked) {
                                                var msg = $('<div></div>')
                                                    .text('Already checked for this serial number')
                                                    .css({
                                                        position: 'fixed',
                                                        top: '20px',
                                                        left: '50%',
                                                        transform: 'translateX(-50%)',
                                                        background: '#ffc107',
                                                        color: '#222',
                                                        padding: '10px 24px',
                                                        borderRadius: '6px',
                                                        zIndex: 9999,
                                                        fontWeight: 'bold',
                                                        fontSize: '18px',
                                                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                                                    });
                                                $('body').append(msg);
                                                setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                                                $('#serialSearch').val('');
                                            } else if (found) {
                                                var msg = $('<div></div>')
                                                    .text('Yes, serial [*** ' + searchVal + ' ***] number found and checked!')
                                                    .css({
                                                        position: 'fixed',
                                                        top: '20px',
                                                        left: '50%',
                                                        transform: 'translateX(-50%)',
                                                        background: '#28a745',
                                                        color: '#fff',
                                                        padding: '10px 24px',
                                                        borderRadius: '6px',
                                                        zIndex: 9999,
                                                        fontWeight: 'bold',
                                                        fontSize: '18px',
                                                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                                                    });
                                                $('body').append(msg);
                                                setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                                                $('#serialSearch').val('');
                                                updateTotalCount();
                                                // Only uncheck the last checked if count > quantity
                                                var quantity = parseInt($('#Quantity').val(), 10);
                                                var selectedCount = $('.serial-checkbox:checked').length;
                                                if (selectedCount > quantity && lastChecked) {
                                                    alert('Your quantity value and checkbox selection do not match. Only the last serial will be unchecked.');
                                                    lastChecked.prop('checked', false);
                                                    updateTotalCount();
                                                }
                                                validateCheckboxSelection();
                                            } 
                                }
                            });
                        }
                    });
                }
            }

            // Attach blur event to #production_date
            $('#production_date').on('blur', function() {
                fetchSerialNumberDetails();
            });

            // Attach change event to #shift
            $('#shift').on('change', function() {
                fetchSerialNumberDetails();
            });

            // Attach submit event to form
            // if (!isset($edit->id)) {
                // Attach submit event to form
                @if(!isset($edit->id))
                $('form').on('submit', function(e) {
                    if (!validateAtLeastOneCheckbox() || !validateCheckboxSelection()) {
                        e.preventDefault();
                        alert('Please select at least one serial number and ensure the selected count matches the quantity.');
                    }
                });
                @endif
            //}
        });

        function updateTotalCount() {
            var totalChecked = $('.serial-checkbox:checked').length;
            $('#selectAll').next('label').text('Select All (Total: ' + totalChecked + ')');
        }

        // Function to validate the number of selected checkboxes
        function validateCheckboxSelection() {
            var quantity = parseInt($('#Quantity').val(), 10);
            var selectedCount = $('.serial-checkbox:checked').length;

            if (selectedCount > quantity) {
                alert('Your quantity value and checkbox selection do not match.');
                $('.serial-checkbox').prop('checked', false);
                $('#selectAll').prop('checked', false);
                updateTotalCount();
                return false; // Add this line to prevent form submission
            }
            return selectedCount === quantity; // Return true if the selected count matches the quantity
        }

        function validateAtLeastOneCheckbox() {
            return $('.serial-checkbox:checked').length > 0;
        }

        

</script>
@endpush
