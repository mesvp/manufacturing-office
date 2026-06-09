@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
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

    .tab1 {
        padding: 20px;
        border: 1px solid #a8adb1;
    }

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

    div#adaaishhhh {
        margin-left: 10px;
        margin-bottom: 20px;


        width: 98.5%;
    }

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
        justify-content: flex-start;
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

    button#diraj-button {
        background: transparent;
        border: 1px solid;
    }

    table#dynamic_field {
        margin-top: -14px;
    }

    div#example_filter {
        display: none;
    }

    .selector {

        display: flex;

    }

    .selecotr-item {
        position: relative;

        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .selector-item_radio {
        appearance: none;
        display: none;
    }

    .selector .selecotr-item {
        margin: 4px;
    }

    .selector-item_label {
        position: relative;
        /* height: 63%; */
        /* width: 53%; */
        text-align: center;
        border-radius: 9999px;
        /* line-height: 400%; */
        font-weight: 600 !important;
        transition-duration: .5s;
        transition-property: transform, color, box-shadow;
        transform: none;
        padding: 7px 10px;
        border-radius: 5px !important;
        border: 1px solid #CED4DA;
        text-transform: capitalize;
    }

    .selector-item_radio:checked+.selector-item_label {
        background: #6741D5;
        color: white;
    }


    input[type="radio"] {

        display: none !important;
    }

    .textt {
        font-weight: 600;
    }

    div#DataTables_Table_0_filter {
        display: none;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: none;
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
                <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <h5>Store Requistion Details</h5>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="">Inputer Name : {{auth()->user()->name}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('Storeissue/StoreissueList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('Storeissue/StoreissueList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12 col-md-12 col-sm-12 border">
                    <form action="{{url('Storeissue/IssueQty')}}" method="POST">
                        @csrf
                        <input class="form-control" type="hidden" name="edit"
                            value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                        <div class="row">
                            {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Work Order No.
                                </label>
                                <select disabled name="Work_Order_No" class="form-select form-select-sm">
                                    <option value="" selected disabled>Select</option>
                                    <option value="Test" {{isset($edit->Work_Order_No) &&
                                        $edit->Work_Order_No=='Test'?'selected':''}}>Test</option>
                                </select>
                            </div> --}}
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Organization Name*
                                </label>
                                <select  name="Organization_Name" id="org_id"
                                    class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" selected >Select</option>
                                    @foreach($Organization_Name as $val)
                                    @if(isset($orgid))
                                        <option value="{{$val->id}}" {{isset($orgid) &&
                                            $orgid==$val->id?'selected':''}}>{{$val->organisation}}
                                        </option>
                                    @else
                                        <option value="{{$val->id}}" {{isset($edit->Organization_Name) &&
                                            $edit->Organization_Name==$val->id?'selected':''}}>{{$val->organisation}}
                                        </option>
                                    @endif
                                    
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Manufacturing Unit*
                                </label>
                                <select disabled name="Manufacturing_Unit"
                                    class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Manufacturing_Unit as $val)
                                    <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) &&
                                        $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Plant Name*
                                </label>
                                <select disabled name="Plant_Name"
                                    class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Plant_Name as $val)
                                    <option value="{{$val->id}}" {{isset($edit->Plant_Name) &&
                                        $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Godown Name*
                                </label>
                                <select disabled name="Godown_Name" id="godown_id"
                                    class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Godown_Name as $val)
                                    <option value="{{$val->id}}" {{isset($edit->Godown_Name) &&
                                        $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($edit->req_type != 'Additional')
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Finished Good(FG)*
                                    </lable>
                                    <select disabled name="Raw_Material"
                                        class="form-select form-select-sm js-example-matcher-start js-example-matcher-start"
                                        id="RawMaterial" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Raw_Material as $val)
                                        <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) &&
                                            $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}
                                        </option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>HSN Code*</label>
                                <div class="field-wrap">
                                    <input readonly class="form-control form-control-sm" type="number"
                                        name="HSN_Code" id="HSNCode" placeholder="HSN Code"
                                        value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>UOM</label>
                                <div class="field-wrap">
                                    <input readonly class="form-control form-control-sm" type="text"
                                        name="UOM" id="uom" placeholder="HSN Code"
                                        value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}"
                                        required>
                                    {{-- <select disabled name="UOM" id="uom"
                                        class="form-select form-select-sm js-example-matcher-start js-example-matcher-start"
                                        required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($UOM as $val)
                                        <option value="{{$val->id}}" {{isset($edit->UOM) &&
                                            $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                            </div>
                            @endif
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>Recived By*</label>
                                <div class="field-wrap">
                                    <select name="recived_by" id="recived_by"
                                        class="form-select form-select-sm js-example-matcher-start js-example-matcher-start"
                                        required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($admin as $val)
                                        <option value="{{$val->id}}" {{(isset($recived_by) && $recived_by==$val->
                                            id)?'selected':''}}>{{$val->fullname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="Tabledata"
                                class="table table-striped table-bordered DataTable exampal no-footer"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No.</th>
                                        <th class="th-sm">
                                            <input type="checkbox" id="checkAllMaterials"> Material Name
                                        </th>
                                        <th class="th-sm">HSN Code</th>
                                        <th class="th-sm">UMO</th>
                                        <th class="th-sm">Available in Stock</th>
                                        <th class="th-sm">Required QTY</th>
                                        <th class="th-sm">Already issued QTY</th>
                                        <th class="th-sm">Pending QTY</th>
                                        <th class="th-sm">Issue QTY</th>
                                        <th class="th-sm">SHORT CLOSE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=0;
                                    @endphp
                                    @foreach($Materials as $key=>$MaterialVal)
                                    <tr id="remove{{$MaterialVal->id}}">
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <input type="hidden" value="{{$MaterialVal->mat_data->id??''}}" name="editmaterial[]">
                                            <input type="hidden" id="mat_id" value="{{$MaterialVal->Material_id??''}}">
                                            <input readonly type="checkbox" name="Material_id[]"
                                                class="form-control-md {{(isset($MaterialVal->mat_data->action) && $MaterialVal->mat_data->action=='RECHECK')?'checkdd':''}} cls{{$i}}"
                                                value="{{$MaterialVal->id}}" id="Material_id{{$MaterialVal->id}}"
                                                {{(isset($MaterialVal->mat_data->action) && $MaterialVal->mat_data->action=='RECHECK')?'checked':''}}>
                                            {{isset($MaterialVal->Material_Name) && $MaterialVal->Material_Name!=''?$MaterialVal->Material_Name:''}}
                                        </td>
                                        <td>
                                            <input readonly type="hidden" name="HSN_Code_Second[]"
                                                class="form-control form-control-sm"
                                                value="{{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}">
                                            {{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}
                                        </td>
                                        <td>
                                            <input readonly type="hidden" name="UOM_Second[]"
                                                class="form-control form-control-sm"
                                                value="{{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second!=''?$MaterialVal->UOM_Second:''}}">
                                            {{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second!=''?$MaterialVal->UOM_Second:''}}
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control form-control-sm mat_qty"
                                                value="{{$stock[$MaterialVal->Material_id]->Quantity??0}}"
                                                id="stock{{$MaterialVal->id}}">
                                        </td>
                                        <td>
                                            <input disabled type="text" name="QTY[]"
                                                class="form-control form-control-sm"
                                                value="{{isset($MaterialVal->QTY) && $MaterialVal->QTY!=''?$MaterialVal->QTY:''}}">
                                        </td>
                                        <td>
                                            <input disabled type="text" class="form-control form-control-sm"
                                                value="{{isset($MaterialVal->StoreIssue_Approve_qty) && $MaterialVal->StoreIssue_Approve_qty!=''?$MaterialVal->StoreIssue_Approve_qty:''}}">
                                        </td>
                                        <td>
                                            <input disabled type="text" name="" class="form-control form-control-sm"
                                                value="{{abs($MaterialVal->StoreIssue_Approve_qty-$MaterialVal->QTY)}}"
                                                id="pending{{$MaterialVal->id}}">
                                        </td>
                                        <td>
                                            <input type="tel" name="issueQTY[]" data-id="{{$MaterialVal->id}}"
                                                data-type="{{$MaterialVal->mat_data->action??''}}"
                                                data-value="{{(isset($MaterialVal->mat_data->action) && $MaterialVal->mat_data->action=='RECHECK')?$MaterialVal->mat_data->issueQTY:''}}"
                                                id="issue{{$MaterialVal->id}}" id="issue{{$MaterialVal->id}}"
                                                class="form-control form-control-sm issuemateria"
                                                value="{{(isset($MaterialVal->mat_data->action) && $MaterialVal->mat_data->action=='RECHECK')?$MaterialVal->mat_data->issueQTY:''}}"
                                                {{(isset($MaterialVal->mat_data->action) && $MaterialVal->mat_data->action=='RECHECK')?'':'readonly'}}>
                                        </td>
                                        <td>
                                            @if(!isset($MaterialVal->mat_data->action))
                                            <button type="button" data-id="{{$MaterialVal->id}}"
                                                class="btn btn-danger ForClose">SHORT CLOSE</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                    $i++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 form-group"></div>
                            <div class="col-sm-4 form-group">
                                <label for="State">Remarks:</label>
                                <input disabled type="text" name="remarks" cols="30" rows="5"
                                    class="form-control form-control-sm" placeholder="Remarks"
                                    value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                            </div>
                        </div>
                        <div style="overflow:auto;">
                            <div class="somras">
                                <a href="" class="btn btn1 float-right" style="margin: 5px;">Clear All</a>
                                <button type="submit" id="submitBtn" class="btn btn1 float-right"
                                    style="margin: 5px;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
@push('custom-scripts')
<script>
      document.getElementById('checkAllMaterials').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('input[type="checkbox"][name="Material_id[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const issueQTYField = document.getElementById('issue' + checkbox.value);
            issueQTYField.readOnly = !checkbox.checked;
            if (!checkbox.checked) {
                issueQTYField.value = '';
            }
        });
    });

    document.querySelectorAll('input[type="checkbox"][name="Material_id[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const issueQTYField = document.getElementById('issue' + this.value);
            issueQTYField.readOnly = !this.checked;
            if (!this.checked) {
                issueQTYField.value = '';
            }
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
    $(document).ready(function () {
        activeclass(23, 1);
    });
    $(".checkdd").change(function () {
        if ($(this).prop('checked') == false) {
            alert('You Can not Uncheck This Material !')
            $(this).prop('checked', true)
        }
    });
    $(".issuemateria").change(function () {
        
        id = $(this).attr('data-id');
        data_type = $(this).attr('data-type');
        data_value = $(this).attr('data-value');
        stock = parseFloat($("#stock" + id).val());
        //alert(stock);
        pending = parseFloat($("#pending" + id).val());
        qty = parseFloat($(this).val());
        if (qty > stock) {
            alert("Issue quantity Can not be more then Stock");
            if (data_type == 'RECHECK') {
                $(this).val(data_value);
                $("#Material_id" + id).prop('checked', true);
            }
            else {
                $(this).val(0);
                $("#Material_id" + id).prop('checked', false);
            }
            return false
        }

        if (qty > pending) {
            alert("Issue quantity Can not be more then Required Quantity Or Pending Quantity");
            if (data_type == 'RECHECK') {
                $(this).val(data_value);
                $("#Material_id" + id).prop('checked', true);
            }
            else {
                $("#Material_id" + id).prop('checked', false);
                $(this).val(0);
            }
            return false
        }
        $("#Material_id" + id).prop('checked', true);

    });
    $(".ForClose").click(function () {
        id = parseFloat($(this).attr('data-id'));
        if (id > 0) {
            if (confirm("Are You Sure ?")) {
                $.post("{{url('Storeissue/ForClose')}}", { id: id }, function (newdata) {

                    if (newdata.success == true) {
                        alert(newdata.message);
                        $("#remove" + id).remove()
                        elsee = $("input[name='issueQTY[]']").length
                        if (elsee) {
                            $("#recived_by").removeAttr('required');
                        }


                    }
                    else {
                        alert(newdata.message);
                    }
                });
            }
        }
        else {
            alert("Id missing");
            return false
        }
    });

    $("form").on('submit', function () {
        elsee = $("input[name='issueQTY[]']").length
        if (elsee > 0) {
            var Material_id = [];
            $.each($("input[name='Material_id[]']:checked"), function () {
                Material_id.push(parseFloat($(this).val()));
            });
            if (Material_id.length < 1) {
                alert("You Must Select Any Material First")
                return false
            }
            for (x in Material_id) {
                value = parseFloat($("#issue" + Material_id[x]).val())
                if (isNaN(value) || value == 0) {
                    //alert(value)
                    alert("QTY Can not be empty or zero")
                    return false
                }

            }
            var Material_id = [];

            $.each($("input[name='Material_id[]']"), function () {
                Material_id.push(parseFloat($(this).val()));
            });
            for (x in Material_id) {

                if ($("#Material_id" + Material_id[x]).prop('checked') == true) {
                    stock = parseFloat($("#stock" + Material_id[x]).val());
                    issue = parseFloat($("#issue" + Material_id[x]).val());
                    pending=parseFloat($("#pending" + Material_id[x]).val());
                    //alert(issue +','+ stock)
                    if (issue > stock) {
                        alert("Issue quantity Can not be more then Required Quantity Or Pending Quantity");
                        //alert(x+' , '+Material_id[x])
                        //break;
                        return false;
                    }
                    if (issue > pending) {
                        alert("Issue quantity Can not be more then Required Quantity Or Pending Quantity");
                        //alert(x+' , '+Material_id[x])
                        //break;
                        return false;
                    }
                }

            }
            for (x in Material_id) {
                $("#Material_id" + Material_id[x]).attr('name', 'Material_id[' + x + ']')

            }

            //return false;
        }

    });
</script>
<script>
      $(document).ready(function() {
        $('#org_id').change(function() {
            var OrgId = $(this).val();
            var Godwonid = $('#godown_id').val();

            $.ajax({
                url: "{{url('Storeissue/get-store-stock')}}" + '/' + OrgId,
                type: 'GET',
                data: {
                    OrgId: OrgId,
                    Godwonid: Godwonid
                },
                success: function(response) {
                    if (response.length === 0) {
                        alert('Empty Value Against This Organization');
                        // If the response is empty, set all mat_qty inputs to zero
                        $('input[id^="mat_id"]').each(function() {
                            var quantityInput = $(this).closest('tr').find('input.mat_qty');
                            quantityInput.val(0);
                        });
                    } else {
                        // Create a dictionary of material quantities from the response
                        var stockDetails = {};
                        $.each(response, function(index, stockdetail) {
                            stockDetails[stockdetail.Material] = stockdetail.Quantity;
                        });

                        // Update the table rows based on the response
                        $('input[id^="mat_id"]').each(function() {
                            var materialId = $(this).val();
                            var quantityInput = $(this).closest('tr').find('input.mat_qty');
                            
                            if (stockDetails[materialId] !== undefined) {
                                quantityInput.val(stockDetails[materialId]);
                            } else {
                                quantityInput.val(0);
                            }
                        });
                    }
                }
            });
        });
    });



</script>
@endpush
