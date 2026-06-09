@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
    :root {
        --bg-success-clr: #95f3ff;
        --borcolor: 1px solid #a8adb1;
    }
    .btn-bgclr{
        background-color: var(--bg-success-clr);
    }
    .bdr{
        border: var(--borcolor);
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
                          <h6>Add Serail Number</h6>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Inputer Name : {{auth()->user()->fullname}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('SerialNumber/SerialnumberList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('SerialNumber/SerialnumberList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>

                    <form action="{{route('SerialNumber.store')}}" method="POST" id="sales-fields">
                        @csrf
                        <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                   
                            <!-- <h6>Production</h6> -->
                            <div class="my-2 row" id="adaaishhhh">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <select name="Organization_Name" id="org_id" class="form-select form-select-sm" onchange="updateOrgName()" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization as $val)
                                        <option value="{{$val->organisation}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="org_name" id="org_name" value="">
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        FG WATT*
                                    </label>
                                    <div class="field-wrap">
                                        <input type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="fg_watt" id="fg_watt" value="{{$edit->Rate??''}}" placeholder="FG WATT" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        BUSS BAR*
                                    </label>
                                    <div class="field-wrap">
                                        <input type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="bus_bar" id="Rate" value="{{$edit->Rate??''}}" placeholder="BUS BAR" class="form-control form-control-sm" required>
                                    </div>
                                    
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        DATE*
                                    </label>
                                    <input type="date" value="{{$edit->Production_Date??''}}" placeholder="Serial Date" name="serial_date" id= "serial_date" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        Shift*
                                    </label>
                                    <select name="Shift_Name" id="Shift_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        
                                            @foreach($Shift as $val)
                                            <option value="{{$val->shift_code}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->shift}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="adaaishhhh">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>
                                        TPCON*
                                    </label>
                                    <select name="TPCON" id="TPCON" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                            <option value="YES" {{isset($edit->TPCON) && $edit->TPCON=='YES'?'selected':''}}>YES</option>
                                            <option value="NO" {{isset($edit->TPCON) && $edit->TPCON=='NO'?'selected':''}}>NO</option>
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Sl No. From*</label>
                                    <div class="field-wrap">
                                        <input type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="sl_no_from" id="sl_no_from" id="Rate" value="{{$edit->Rate??''}}" placeholder="SL NO. FROM" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                                    <label>Sl No. To*</label>
                                    <div class="field-wrap">
                                        <input type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="sl_no_to" id="sl_no_to" value="{{$edit->Rate??''}}" placeholder="SL NO. TO" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                 {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12">
                                    <div class="d-flex mt-3">
                                        <button id="showpreveousdata"  class="btn bg-primary text-bg-primary">Show Serial Numbers</button>
                                    </div>
                                </div> --}}
                            </div>
                        <div class="table-responsive" id="tabpreveous" style="display:none;">
                            <table class="table table-striped table-bordered no-footer w-100">
                                <thead>
                                    <tr>
                                        <th class="th-sm">Date</th>
                                        <th class="th-sm">Serial Number</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previousSerialNumbers as $key=>$valdtl)
                                    <tr>
                                        <td>{{isset($valdtl->sl_no) && $valdtl->sl_no!=''?$valdtl->sl_no:''}}</td>
                                        <td>{{isset($valdtl->created_at) && $valdtl->created_at!=''?$valdtl->created_at:''}}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="d-flex float-end mt-3">
                                    <button type="submit" id="submitdata"  class="btn btn-bgclr">Submit</button>
                                </div>
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
    $(document).ready(function() {
        activeclass(14, 1);
    });
</script>
<script>
    $(document).ready(function() {
        $("#showpreveousdata").click(function() {
            $("#tabpreveous").toggle();
        });
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

                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                table.find('tbody').empty();

                var Total = 0;
                $("#Tabledata tbody").html(data)
                table.DataTable({
                    "ordering": false
                });
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
        Rate=(parseInt($("#Rate").val()));
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
            // for(x in idd)
            // {
            //     stock=parseInt($("#Plantstock"+idd[x]).val())
            //     totl=parseInt($("#totalQTY"+idd[x]).text())
            //     if(totl>stock)
            //     {
            //         alert("Total Quantity Can not be more then available stock")
            //         return false
            //     }
            // }
        //alert(idd)
        //return false
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
        function updateOrgName() {
            var select = document.getElementById('org_id');
            var orgName = select.options[select.selectedIndex].text;
            document.getElementById('org_name').value = orgName;
        }
</script>
@endpush
