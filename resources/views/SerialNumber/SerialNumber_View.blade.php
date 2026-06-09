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
                            <a href="{{url('SerialNumber/SerialnumberList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                            <a href="{{url('SerialNumber/SerialnumberList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
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
                                @if($edit->Approve_status == "APPROVE")
                                    <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 form-group">
                                        <label>TPCON</label>
                                        <div class="field-wrap">
                                            <input readonly class="form-control form-control-sm" type="text" name="Fg" id="uom00" placeholder="TPCON" value="{{isset($edit->TPCON) && $edit->TPCON!=''?$edit->TPCON:''}}" required>
                                        </div>
                                    </div>
                                @endif
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
                            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="th-sm">SL NO.</th>
                                        <th class="th-sm">Serial Number</th>
                                        <th class="th-sm">Created At.</th>
                                        <th class="th-sm">Created By</th>
                                        <th class="th-sm">Organization Name</th>
                                        <th class="th-sm">Production Date</th>
                                        <th class="th-sm">Shift</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($slnumbers as $key=>$valdtl)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        @if($edit->Approve_status == "APPROVE")
                                        <td>{{isset($valdtl->sl_no) && $valdtl->sl_no!=''?$valdtl->sl_no:''}}</td>
                                        @else
                                        <td>xxxxxxxxx(Not Approved)</td>
                                        @endif
                                        <td>{{isset($valdtl->created_at) && $valdtl->created_at!=''?$valdtl->created_at:''}}</td>
                                        <td>{{isset($edit->empname) && $edit->empname!=''?$edit->empname:''}}</td>
                                        <td>{{isset($edit->Organization_Name) && $edit->Organization_Name!=''?$edit->Organization_Name:''}}</td>
                                        <td>{{isset($edit->serial_date) && $edit->serial_date!=''?$edit->serial_date:''}}</td>
                                        <td>{{isset($edit->Shift_Name) && $edit->Shift_Name!=''?$edit->Shift_Name:''}}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                @if($edit->Approve_status=='OBJECT' && $edit->userID==auth()->user()->id)
                <form action="{{url('SerialNumber/approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                    <div class="form-group" id="u_rama">
                        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('SerialNumber/SerialNumber_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('SerialNumber/ProductList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @else
                <form action="{{url('SerialNumber/approve')}}" method="POST">
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
                        <textarea class="form-control m-0" name="comment_text" id="" rows="5" placeholder="Remarks" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @if(isset($nextID) && !empty($nextID))
                    <a href="{{url('SerialNumber/SerialNumber_View/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @else
                    <a href="{{url('SerialNumber/SerialnumberList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
                    @endif
                </form>
                @endif
                <div class="table-responsive">
                    <table  class="table table-striped table-bordered example w-100">
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
    activeclass(14, 1);
</script>
@endpush
