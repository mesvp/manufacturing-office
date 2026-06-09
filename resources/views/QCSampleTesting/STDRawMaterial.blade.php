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
                <a href="{{url('QCSampleTesting/STDRawMaterialList')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('QCSampleTesting/STDRawMaterialList')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col">
                                    <h5>Sample Testing Details Raw Material</h5>
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
                        <form action="{{url('QCSampleTesting/AddSTDRawMaterial')}}" method="POST">
                            @csrf
                            <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <label>Invoice No.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Invoice_no" placeholder="Invoice No." value="{{isset($edit->Invoice_no) && $edit->Invoice_no!=''?$edit->Invoice_no:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>PO No.*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="PO_NO" placeholder="PO No" value="{{isset($edit->PO_NO) && $edit->PO_NO!=''?$edit->PO_NO:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Material Code*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Material_Code" placeholder="Material Code" value="{{isset($edit->Material_Code) && $edit->Material_Code!=''?$edit->Material_Code:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Material Name*
                                    </label>
                                    <select name="Material_Name" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($RawMaterial as $val)
                                        <option value="{{$val->id}}" {{isset($edit->Material_Name) && $edit->Material_Name==$val->id?'selected':''}}>{{$val->Raw_Material}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Material Type*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Material_Type" placeholder="Material Type" value="{{isset($edit->Material_Type) && $edit->Material_Type!=''?$edit->Material_Type:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        HNS Code*
                                    </label>
                                    <select name="HNS_Code" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="test" {{isset($edit->HNS_Code) && $edit->HNS_Code=='test'?'selected':''}}>test</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>
                                        Quality Checking Status*
                                    </label>
                                    <select name="QC_Status" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($QCStatus as $val)
                                        <option value="{{$val->id}}" {{isset($edit->QC_Status) && $edit->QC_Status==$val->id?'selected':''}}>{{$val->quality_check}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Parameter*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Parameter_one" placeholder="Parameter" value="{{isset($edit->Parameter_one) && $edit->Parameter_one!=''?$edit->Parameter_one:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Result</label>
                                    <div class="field-wrap">
                                        <select name="result_one" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="Pass" {{isset($edit->result_one) && $edit->result_one=='Pass'?'selected':''}}>Pass</option>
                                            <option value="Fail" {{isset($edit->result_one) && $edit->result_one=='Fail'?'selected':''}}>Fail</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Remarks.*</label>
                                    <div class="field-wrap">
                                        <input type="text" name="remarks_one" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks_one) && $edit->remarks_one!=''?$edit->remarks_one:''}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Parameter 2*</label>
                                    <div class="field-wrap">
                                        <input class="form-control form-control-sm" type="text" name="Parameter_two" placeholder="Parameter" value="{{isset($edit->Parameter_two) && $edit->Parameter_two!=''?$edit->Parameter_two:''}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Result</label>
                                    <div class="field-wrap">
                                        <select name="Result_two" class="form-select form-select-sm" required>
                                            <option value="" selected disabled>Select</option>
                                            <option value="Pass" {{isset($edit->Result_two) && $edit->Result_two=='Pass'?'selected':''}}>Pass</option>
                                            <option value="Fail" {{isset($edit->Result_two) && $edit->Result_two=='Fail'?'selected':''}}>Fail</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Remarks.*</label>
                                    <div class="field-wrap">
                                        <input type="text" name="remarks_two" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks_two) && $edit->remarks_two!=''?$edit->remarks_two:''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 form-group"></div>
                                <div class="col-sm-4 form-group">
                                    <label for="State">Remarks:</label>
                                    <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                                </div>
                            </div>
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="submit" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
@endpush