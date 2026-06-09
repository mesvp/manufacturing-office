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
            <div class="addbtn extra">
                <a href="{{url('StoreRequistion/StoreRequistionList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('StoreRequistion/StoreRequistionList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Store Requistion Details</h5>
                                </div>
                                <div class="col">
                                    <label for="">Inputer Name : {{auth()->user()->name}}</label>
                                </div>
                                <div class="col">
                                    <label for="">Date & Time : <span id="clock"></span></label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="tab1">
                        <form action="{{url('Storeissue/IssueQty')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                {{-- <div class="col-sm-3 form-group">
                                    <label>
                                        Work Order No.
                                    </label>
                                    <select disabled name="Work_Order_No" class="form-select form-select-sm">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Test" {{isset($edit->Work_Order_No) && $edit->Work_Order_No=='Test'?'selected':''}}>Test</option>
                                    </select>
                                </div> --}}
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Organization Name*
                                    </label>
                                    <select disabled name="Organization_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Organization_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{$val->organisation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Manufacturing Unit*
                                    </label>
                                    <select disabled name="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Manufacturing_Unit as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) && $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Plant Name*
                                    </label>
                                    <select disabled name="Plant_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Plant_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Godown Name*
                                    </label>
                                    <select disabled name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Godown_Name as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($edit && $edit->req_type != 'Additional')
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Raw Material(FG)*
                                        </lable>
                                        <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Raw_Material as $val)
                                            <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>HSN Code*</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>UOM</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="HSN Code" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                        {{-- <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($UOM as $val)
                                            <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-3 form-group">
                                    <label>Recived By*</label>
                                    <div class="field-wrap">
                                        <select disabled name="recived_by" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($admin as $val)
                                            <option value="{{$val->id}}" {{auth()->user()->id==$val->id?'selected':''}}>{{$val->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL No.</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UMO</th>
                                            <th class="th-sm">Organization Used</th>
                                            <th class="th-sm">Available in Stock</th>
                                            <th class="th-sm">Required QTY</th>
                                            <th class="th-sm">Issued QTY</th>
                                            <th class="th-sm">Total Issued QTY</th>
                                            <th class="th-sm">Pending QTY</th>
                                            <th class="th-sm">Issue By</th>
                                            <th class="th-sm">Received By</th>
                                            <th class="th-sm">Approver</th>
                                            <th class="th-sm">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach($Materials as $key=>$MaterialVal)

                                        <tr id="remove{{$MaterialVal->id}}">
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <input type="checkbox" value="{{$MaterialVal->material_data->id}}" class="inids" name="materialid[]">
                                                {{isset($MaterialVal->Material_Name) && $MaterialVal->Material_Name!=''?$MaterialVal->Material_Name:''}}
                                            </td>
                                            <td>
                                                {{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}
                                            </td>
                                            <td>
                                                {{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second!=''?$MaterialVal->UOM_Second:''}}
                                            </td>
                                            {{-- <td>
                                                {{$uom_data[$MaterialVal->UOM_Second]['UOMs']}}
                                            </td> --}}
                                            <td>
                                                @if(isset($MaterialVal->material_data->organisation))
                                                {{isset($MaterialVal->material_data->organisation) && $MaterialVal->material_data->organisation!=''?$MaterialVal->material_data->organisation:''}}
                                                @else
                                                <?php 
                                                $orgname=App\Models\FactoryCreater\prj_organisation::select('organisation')->where('id',$edit->Organization_Name)->get();
                                                ?>
                                                    {{$orgname[0]->organisation}}
                                                @endif
                                                
                                            </td>
                                            <td>
                                                @if(isset($MaterialVal->material_data->orgid))
                                        <?php 
                                        $mat_stock = App\Models\Master\RawMaterial\Master_Raw_Material::select('Quantity')->where('Material', $MaterialVal->Material_id)->where('Organization',$MaterialVal->material_data->orgid)->where('Godown_Name',$edit->Godown_Name)->get();
                                        //print_r($mat_stock);
                                         ?>
                                                {{$mat_stock[0]->Quantity}}
                                            @else
                                                {{$stock[$MaterialVal->Material_id]->Quantity}}
                                            @endif
                                                
                                            </td>
                                            <td>
                                               {{isset($MaterialVal->QTY) && $MaterialVal->QTY!=''?$MaterialVal->QTY:''}}
                                            </td>
                                            <td>
                                               {{isset($MaterialVal->material_data->issueQTY) && $MaterialVal->material_data->issueQTY!=''?$MaterialVal->material_data->issueQTY:''}}
                                            </td>
                                            <td>
                                               {{isset($MaterialVal->StoreIssue_Approve_qty) && $MaterialVal->StoreIssue_Approve_qty!=''?$MaterialVal->StoreIssue_Approve_qty:''}}
                                            </td>
                                            <td>
                                                {{abs($MaterialVal->StoreIssue_Approve_qty-$MaterialVal->QTY)}}
                                            </td>
                                            <td>
                                                {{$admindata[$MaterialVal->material_data->userID]??''}}
                                            </td>
                                            <td>
                                            {{$admindata[$MaterialVal->material_data->recived_by]??''}}
                                            </td>
                                            <td>
                                            {{$admindata[$MaterialVal->material_data->recived_ApproverID]??''}}
                                            </td>
                                            <td>

                                                {{status($MaterialVal->material_data->action)}}

                                            </td>
                                        </tr>

                                        @php
                                        $i++;
                                        @endphp

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                @if($edit->Approve_status!='REJECT')
                <form action="{{url('Storeissue/approve')}}" method="POST" id="formss">
                    <input type="hidden" value="" id="sadfsfsf" name="materialid">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if(sizeof($approves1)>0)
                        <div class="button_div">
                            <div class="selector">
                                <div class="selecotr-item">
                                    <input type="radio" id="radio1" name="during_approval" class="selector-item_radio" value="APPROVE" required>
                                    <label for="radio1" class="selector-item_label">APPROVE</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio2" name="during_approval" class="selector-item_radio" value="REJECT" required>
                                    <label for="radio2" class="selector-item_label">REJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio18" name="during_approval" class="selector-item_radio" value="RECHECK" required>
                                    <label for="radio18" class="selector-item_label">RECHECK</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" required>
                                    <label for="radio4" class="selector-item_label">HOLD</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio7" name="during_approval" class="selector-item_radio" value="OBJECT" required>
                                    <label for="radio7" class="selector-item_label">OBJECT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio15" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                    <label for="radio15" class="selector-item_label">AUDIT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio16" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                    <label for="radio16" class="selector-item_label">INTIMATION</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio17" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                    <label for="radio17" class="selector-item_label">QUERY</label>
                                </div>
                            </div>
                            <div id="showfields" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Days For Holding</lable>
                                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="button_div">
                            <div class="selector">
                                <div class="selecotr-item">
                                    <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                                    <label for="radio6" class="selector-item_label">AUDIT</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                                    <label for="radio8" class="selector-item_label">INTIMATION</label>
                                </div>
                                <div class="selecotr-item">
                                    <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                                    <label for="radio9" class="selector-item_label">QUERY</label>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(nextid($edit->id)>0)
                    <a href="{{url('Storeissue/ViewStoreissueApprover/')}}/{{nextid($edit->id)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('Storeissue/StoreissueApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered example" style="width:100%">
                        <thead>
                            <tr>
                                <th class="th-sm">SL NO.</th>
                                <th class="th-sm">Action</th>
                                <th class="th-sm">Action By</th>
                                <th class="th-sm">Role. (Reviewer,Approver,ETC)</th>
                                <th class="th-sm">Date & time</th>
                                <th class="th-sm">comment</th>
                                <th class="th-sm">IP Address</th>
                                <th class="th-sm">Device ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approves as $key=>$val)

                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    @if(!empty($val->action))
                                    {{isset($val->action) && $val->action!=''?$val->action:''}}
                                    @else
                                    {{isset($val->pre_post_approval) && $val->pre_post_approval!=''?$val->pre_post_approval:''}}
                                    @endif
                                </td>
                                <td>{{isset($val->user->name) && $val->user->name!=''?$val->user->name:''}}</td>
                                <td>{{isset($val->role) && $val->role!=''?$val->role:''}}</td>
                                <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
                                <td>{{isset($val->comment_text) && $val->comment_text!=''?$val->comment_text:''}}</td>
                                <td>{{isset($val->ip_address) && $val->ip_address!=''?$val->ip_address:''}}</td>
                                <td>{{isset($val->device_name) && $val->device_name!=''?$val->device_name:''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
</section>
@endsection
@push('custom-scripts')
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
        activeclass(23, 2);
    });
    $(".issuemateria").change(function() {
        id = $(this).attr('data-id');
        stock = parseInt($("#stock" + id).val());
        pending = parseInt($("#pending" + id).val());
        qty = parseInt($(this).val());
        if (qty > stock) {
            alert("Issue quantity Can not be more then Stock");
            $(this).val(0);
            $("#Material_id" + id).prop('checked', false);
            return false
        }

        if (qty > pending) {
            alert("Issue quantity Can not be more then Required Quantity Or Pending Quantity");
            $("#Material_id" + id).prop('checked', false);
            $(this).val(0);
            return false
        }
        $("#Material_id" + id).prop('checked', true);

    });
    $(".ForClose").click(function() {
        id = parseInt($(this).attr('data-id'));
        if (id > 0) {
            if (confirm("Are You Sure ?")) {
                $.post("{{url('Storeissue/ForClose')}}", {
                    id: id
                }, function(newdata) {

                    if (newdata.success == true) {
                        alert(newdata.message);
                        $("#remove" + id).remove()
                    } else {
                        alert(newdata.message);
                    }
                });
            }
        } else {
            alert("Id missing");
            return false
        }
    });

    $("#formss").on('submit',function(){

        var pre_post_approval =$("input[name='pre_post_approval']:checked").val();
        var during_approval = $("input[name='during_approval']:checked").val();

           if(pre_post_approval=='INTIMATION' || pre_post_approval=='AUDIT' || pre_post_approval=='QUERY' )
           {

           }
           else if(during_approval=='OBJECT')
           {

           }
           else
           {
                var Material_id = [];
                $.each($("input[name='materialid[]']:checked"), function(){
                    Material_id.push(parseInt($(this).val()));
                });
                if(Material_id.length<1)
                {
                    alert("You Must Select Any Material First")
                    return false
                }
           }

            $("#sadfsfsf").val(Material_id.join(","))

    });
</script>
<script>
    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio4').is(':checked')) {
                $('#showfields').show();
                $('.requireddd').prop('required', true);
            } else {
                $('#showfields').hide();
                $('.requireddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#showfields').hide();
            $('.requireddd').prop('required', false);
        });
    });

    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio5').is(':checked')) {
                $('#Forwords').show();
                $('.requirrreddd').prop('required', true);
            } else {
                $('#Forwords').hide();
                $('.requirrreddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#Forwords').hide();
            $('.requirrreddd').prop('required', false);
        });
    });
</script>
<script>
    const prePostApprovalRadios = document.querySelectorAll('[name="pre_post_approval"]');
    const duringApprovalRadios = document.querySelectorAll('[name="during_approval"]');
    const duringApprovalFields = document.querySelector('.selector');

    prePostApprovalRadios.forEach(prePostRadio => {
        prePostRadio.addEventListener('change', () => {
            if (prePostRadio.checked) {
                duringApprovalRadios.forEach(duringRadio => {
                    duringRadio.checked = false;
                    duringRadio.removeAttribute('required');
                });

                duringApprovalFields.classList.add('disabled');
            }
        });
    });

    duringApprovalRadios.forEach(duringRadio => {
        duringRadio.addEventListener('change', () => {
            if (duringRadio.checked) {
                prePostApprovalRadios.forEach(prePostRadio => {
                    prePostRadio.checked = false;
                });

                duringApprovalFields.classList.remove('disabled');
            }
        });
    });
</script>
@endpush
