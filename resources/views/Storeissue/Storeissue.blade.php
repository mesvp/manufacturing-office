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
                                    <a href="{{url('Storeissue/StoreissueList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                    <a href="{{url('Storeissue/StoreissueList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-12 col-sm-12 border">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Creater Name :- {{$val->Creater->fullname??'' }}
                                </label>

                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Request No. :- {{$val->Request_No??'' }}
                                </label>

                            </div>
                            {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Work Order No. :- {{$val->Work_Order_No??''}}
                                </label>

                            </div> --}}
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Organization Name :- {{$val->Organization->organisation ?? '' }}
                                </label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Manufacturing Unit :- {{$val->Manufacturing->pname ?? '' }}
                                </label>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Plant Name :- {{$val->Plant_name->spname ?? '' }}
                                </label>

                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 form-group">
                                <label>
                                    Godown Name :- {{$val->Godown_Name->inventory_name ?? '' }}
                                </label>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer w-100">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No.</th>
                                        <th class="th-sm">Material Name</th>
                                        <th class="th-sm">Available in Stock</th>
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
                                            <input readonly type="text" class="form-control form-control-sm" value="{{isset($MaterialVal->Material_Name) && $MaterialVal->Material_Name!=''?$MaterialVal->Material_Name:''}}">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control form-control-sm" value="{{$stock[$MaterialVal->Material_id]->Quantity??0}}">
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
                        <div class="table-responsive">
                            <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example w-100">
                                <thead>
                                    <tr>
                                        <th class="th-sm">SL No.</th>
                                        <th class="th-sm">Material Name</th>
                                        <th class="th-sm">HSN Code</th>
                                        <th class="th-sm">UOM</th>
                                        <th class="th-sm">Organization Used</th>
                                        <th class="th-sm">Available in Stock</th>
                                        <th class="th-sm">Required QTY</th>
                                        <th class="th-sm">Issued QTY</th>
                                        <th class="th-sm">Total Issued QTY</th>
                                        <th class="th-sm">Pending QTY</th>
                                        <th class="th-sm">Issue Raised By</th>
                                        <th class="th-sm">Issue By</th>
                                        <th class="th-sm">Recived By</th>
                                        <th class="th-sm">Approver</th>
                                        <th class="th-sm">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                    @foreach($Materialss as $key=>$MaterialVal)

                                    <tr id="remove{{$MaterialVal->id}}">
                                        <td>{{$key+1}}</td>
                                        <td>
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
                                        {{-- <td>
                                            {{$stock[$MaterialVal->Material_id]->Quantity}}
                                        </td> --}}
                                        <td>
                                            @if(isset($MaterialVal->material_data->orgid))
                                            <?php 
                                            $mat_stock = App\Models\Master\RawMaterial\Master_Raw_Material::select('Quantity')->where('Material', $MaterialVal->material_data->Material_id)->where('Organization',$MaterialVal->material_data->orgid)->where('Godown_Name',$edit->Godown_Name->id)->get();
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
                                            {{$admindata[$val->userID]??''}}
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
                        @if($val->u==1 && $type=='')
                        @if($edit->Store_issue_status==0 || $edit->Store_issue_status==3)
                        @if($edit->Approve_statusForIssue=='OBJECT' && $edit->issueBy==auth()->user()->id)
                        <form action="{{url('Storeissue/inputapprove')}}" method="POST">
                            @csrf
                            <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="form-group" id="u_rama">
                                <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @if(isset($nextID) && !empty($nextID))
                            <a href="{{url('StoreRequistion/StoreRequistion_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                            @else
                            <a href="{{url('StoreRequistion/StoreRequistionList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                            @endif
                        </form>
                        @else
                        <form action="{{url('Storeissue/inputapprove')}}" method="POST">
                            @csrf
                            <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <input type="hidden" name="non_acting" value="1">
                            <div class="button_div">
                                <div class="selector">
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT" required>
                                        <label for="radio6" class="selector-item_label">AUDIT</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION" required>
                                        <label for="radio8" class="selector-item_label">INTIMATION</label>
                                    </div>
                                    <div class="selecotr-item">
                                        <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY" required>
                                        <label for="radio9" class="selector-item_label">QUERY</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="u_rama">
                                <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Remarks" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @if(isset($nextID) && !empty($nextID))
                            <a href="{{url('StoreRequistion/StoreRequistion_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                            @else
                            <a href="{{url('StoreRequistion/StoreRequistionList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                            @endif
                        </form>
                        @endif
                        @endif
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
                        <br>
                    </div>
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
        activeclass(23, 1);
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
