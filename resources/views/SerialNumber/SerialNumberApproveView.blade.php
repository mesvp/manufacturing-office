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
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="float-end mb-2 p-0">
                            <a href="{{url('SerialNumber/SerialApproveList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                            <a href="{{url('SerialNumber/SerialApproveList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 bdr p-2">
                        <form action="#" method="POST">
                            @csrf
                            <input disabled class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>
                                       Organization Name*
                                        </lable>
                                        <input readonly class="form-control form-control-sm" type="text" placeholder="Organisation Name" value="{{isset($edit->Organization_Name) && $edit->Organization_Name!=''?$edit->Organization_Name:''}}" required>
                                        {{-- <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial00" onclick="Material(0,0)" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach($Organization_Name as $val)
                                            <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->organization?'selected':''}}>{{$val->organization}}</option>
                                            @endforeach
                                        </select> --}}
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>FG Watt</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->fg_watt) && $edit->fg_watt!=''?$edit->fg_watt:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Bus Bar</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->bus_bar) && $edit->bus_bar!=''?$edit->bus_bar:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Serial Date</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->serial_date) && $edit->serial_date!=''?$edit->serial_date:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Shift Code</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->Shift_Name) && $edit->Shift_Name!=''?$edit->Shift_Name:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>TPCON</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="TPCON" value="{{isset($edit->TPCON) && $edit->TPCON!=''?$edit->TPCON:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Sl No. From</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->sl_no_from) && $edit->sl_no_from!=''?$edit->sl_no_from:''}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 form-group">
                                    <label>Sl No. To</label>
                                    <div class="field-wrap">
                                        <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="FG" value="{{isset($edit->sl_no_to) && $edit->sl_no_to!=''?$edit->sl_no_to:''}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    
                                </div>
                                <div class="col-sm-2 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 bdr p-2 mt-2">
                        <div class="table-responsive">
                            <table id="" class="table table-striped table-bordered example" style="width:100%">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="th-sm">SL NO.</th>
                                        <th class="th-sm">Created At.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($slnumbers as $key=>$valdtl)
                                    <tr>
                                        <td>{{isset($valdtl->sl_no) && $valdtl->sl_no!=''?$valdtl->sl_no:''}}</td>
                                        <td>{{isset($valdtl->created_at) && $valdtl->created_at!=''?$valdtl->created_at:''}}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
          
                @php
                $STEP = Session::get('STEP');
                $EXT = Session::get('EXT');
                @endphp
                @if($edit->Approve_status!='REJECT')
                <form action="{{url('SerialNumber/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="tab-content" id="myTabContent">
                        @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[8]['Forward']))
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
                                {{-- <div class="selecotr-item">
                                    <input type="radio" id="radio18" name="during_approval" class="selector-item_radio" value="RECHECK" required>
                                    <label for="radio18" class="selector-item_label">RECHECK</label>
                                </div> --}}
                                <div class="selecotr-item">
                                    <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'checked':''}} required>
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
                            <div id="showfields" class="row" style="display: {{isset($approvestatus->action) && $approvestatus->action=='HOLD'?'flex':'none'}};">
                                <div class="col-sm-4 form-group">
                                    <label>Days For Holding</lable>
                                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="{{isset($approvestatus->days_for_holding) && $approvestatus->days_for_holding!=''?$approvestatus->days_for_holding:''}}">
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
                    <a href="{{url('ProductCategories/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('ProductCategories/ProductApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
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
            </div>
        </section>
    </div>
</div>
@endsection
@push('custom-scripts')
<script>
    activeclass(14, 2);
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