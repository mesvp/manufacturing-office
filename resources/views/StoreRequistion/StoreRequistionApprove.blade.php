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

    div#Tabledata_length {
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
                                    <a href="{{url('StoreRequistion/StoreRequistionApproveList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                    <a href="{{url('StoreRequistion/StoreRequistionApproveList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-12 col-md-12 col-sm-12 border">
                        <form action="3" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                    <label>
                                        Work Order No.
                                    </label>
                                    <select disabled name="Work_Order_No" class="form-select form-select-sm">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Test" {{isset($edit->Work_Order_No) && $edit->Work_Order_No=='Test'?'selected':''}}>Test</option>
                                    </select>
                                </div> --}}
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 form-group">
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
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 orm-group">
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
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
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
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
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
                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 form-group">
                                    <label>
                                    Req Type
                                    </label>
                                          <select name="req_type_val" disabled id="req_type" class="form-select form-select-sm js-example-matcher-start" required>
                                             <option value="" {{ !isset($edit->req_type) ? 'selected' : '' }}>Select</option>
                                             <option value="Normal" {{ isset($edit->req_type) && $edit->req_type == 'Normal' ? 'selected' : '' }}>Normal</option>
                                             <option value="Additional" {{ isset($edit->req_type) && $edit->req_type == 'Additional' ? 'selected' : '' }}>Additional</option>
                                          </select>
                                          <input type="hidden" value="{{$edit->req_type}}" >
                                 </div>
                                 @if($edit && $edit->req_type != 'Additional')
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
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
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                        <label>HSN Code*</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                        <label>UOM</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                                            {{-- <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach($UOM as $val)
                                                <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                                @endforeach
                                            </select> --}}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL No.</th>
                                            <th class="th-sm">Material Name</th>
                                            <th class="th-sm">HSN Code</th>
                                            <th class="th-sm">UOM</th>
                                            <th class="th-sm">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach($Materials as $key=>$MaterialVal)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                @php
                                                 $material_name = App\Models\MaterialManagement\MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')->where('materialmanagement_add_material.id', $MaterialVal->Material_id)->first(); 
                                               @endphp

                                                @php  
                                                $materialname = isset($material_name->matname) ? $material_name->matname : ''; 
                                                @endphp
                                                <input readonly type="text" name="Material_Name[{{$i}}]" class="form-control form-control-sm" value="{{$materialname}}">
                                                <input type="hidden" name="Material_id[{{$i}}]" class="form-control form-control-sm" value="{{$MaterialVal->Material_id??0}}">
                                            </td>
                                            <td>
                                                <input readonly type="text" name="HSN_Code_Second[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}">
                                            </td>
                                            <td>
                                                <div class="field-wrap">
                                                    <input readonly type="text" name="UOM_Second[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second!=''?$MaterialVal->UOM_Second:''}}">
                                                    {{-- <select disabled name="UOM_Second[{{$i}}]" class="form-select form-select-sm js-example-matcher-start" required>
                                                        <option value="" selected disabled>Select</option>
                                                        @foreach($UOM as $value)
                                                        <option value="{{$value->id}}" {{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second==$value->id?'selected':''}}>{{$value->UOMs}}</option>
                                                        @endforeach
                                                    </select> --}}
                                                </div>
                                            </td>
                                            <td>
                                                <input disabled type="text" name="QTY[{{$i}}]" class="form-control form-control-sm" value="{{isset($MaterialVal->QTY) && $MaterialVal->QTY!=''?$MaterialVal->QTY:''}}">
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
                @php
                $STEP = Session::get('STEP');
                $EXT = Session::get('EXT');
                @endphp
                @if($edit->Approve_status!='REJECT')
                <form action="{{url('StoreRequistion/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[15]['Forward']))
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
                                    <input type="radio" id="radio5" name="during_approval" class="selector-item_radio" value="FORWARD" required>
                                    <label for="radio5" class="selector-item_label">FORWARD</label>
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
                            <div id="Forwords" class="row" style="display: none;">
                                <div class="col-sm-4 form-group">
                                    <label>Forward To</lable>
                                        <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                                            <option value="" selected disabled>Select</option>
                                            @foreach($employeeName as $val)
                                            <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</option>
                                            @endforeach
                                        </select>
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
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('StoreRequistion/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('StoreRequistion/StoreRequistionApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered example w-100">
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
                                <td>{{isset($val->user->fullname) && $val->user->fullname!=''?$val->user->fullname:''}}</td>
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

        </section>
    </div>
</div>

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
        activeclass(22, 2);
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
