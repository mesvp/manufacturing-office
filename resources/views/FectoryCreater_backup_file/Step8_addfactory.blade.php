@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
  * {
    box-sizing: border-box;
  }

  body {
    background-color: #f1f1f1;
  }

  #regForm {
    background-color: #ffffff;
    /*margin: 100px auto;*/
    font-family: Raleway;
    /*padding: 40px;*/
    width: 100%;
    /*min-width: 300px;*/
  }

  h1 {
    text-align: center;
  }

  input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
  }

  /* Mark input class="form-control form-control-sm" boxes that gets an error on validation: */
  input .invalid {
    background-color: #ffdddd;
  }

  /* Hide all steps by default: */
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

  /* Make circles that indicate the steps of the form: */
  .step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
  }

  .step.active {
    opacity: 1;
  }

  /* Mark the steps that are finished and valid: */
  .step.finish {
    background-color: #04AA6D;
  }

  .tab {
    padding: 20px;
    background-color: white;
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
</style>
<!--<br><br>-->
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
    <section class="section">
      <!-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" class="text-muted">Factory Creation</a></li>
        <li class="breadcrumb-item active text-" aria-current="page">input class="form-control form-control-sm"er List </li>
      </ol> -->
      <div class="addbtn extra">
        <a href="{{url('FactoryCreater/step7')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
        <a href="{{url('FactoryCreater/List')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
      </div>
      <div class="tab-for-fac">
        <div class="line"></div>
        <div class="ul-div">
          <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link {{$formdata['step1']}} anchor" aria-current="page" href="{{url('FactoryCreater/step1')}}">Address</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step2']}} anchor" href="{{url('FactoryCreater/step2')}}">Statutory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step3']}} anchor" href="{{url('FactoryCreater/step3')}}">Land & Building</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step4']}} anchor" href="{{url('FactoryCreater/step4')}}">Plant & Machinery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step5']}} anchor" href="{{url('FactoryCreater/step5')}}">Amenities</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step6']}} anchor" href="{{url('FactoryCreater/step6')}}">Electricity</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step7']}} anchor" href="{{url('FactoryCreater/step7')}}">Warehouse & Room</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step8']}} active anchor" href="#">Office Asset</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step9']}} anchor" href="{{url('FactoryCreater/step9')}}">Power House</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step10']}} anchor" href="{{url('FactoryCreater/step10')}}">Store</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="container">
          <form id="formsubmit" action="{{url('FactoryCreater/Office_Asset')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($officeasst->id) && $officeasst->id!=''?$officeasst->id:''}}">
            <div>
              <br>
              <div class="tabs">
                <h5>Office Asset</h5><br>
                <div class="col-sm-2 form-group">
                  <label>Asset Category</lable>
                    <p><input class="form-control form-control-sm" name="Asset_Category" value="{{isset($officeasst->Asset_Category) && $officeasst->Asset_Category!=''?$officeasst->Asset_Category:''}}"></p>
                </div>
                <div class="tab1">
                  <br>
                  <div id="dynamic_field13">
                    @php
                    $i = 1;
                    @endphp
                    @if(count($assettypee)>0)
                    @foreach($assettypee as $val)
                    <div id="rows{{$i}}">
                      <input class="form-control form-control-sm" type="hidden" name="typeID[{{$i}}]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                      <div class="row">
                        <div class="col-sm-2 form-group">
                          <label>Asset Type*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Asset_Type[{{$i}}]" value="{{isset($val->Asset_Type) && $val->Asset_Type!=''?$val->Asset_Type:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Asset Name*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Asset_Name[{{$i}}]" value="{{isset($val->Asset_Name) && $val->Asset_Name!=''?$val->Asset_Name:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Asset SL No.*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Asset_SL_No[{{$i}}]" value="{{isset($val->Asset_SL_No) && $val->Asset_SL_No!=''?$val->Asset_SL_No:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Date Of Purchase*</lable>
                            <p><input class="form-control form-control-sm" type="date" max="{{ date('Y-m-d') }}" placeholder="" name="Date_Of_Purchase[{{$i}}]" value="{{isset($val->Date_Of_Purchase) && $val->Date_Of_Purchase!=''?date('Y-m-d',strtotime($val->Date_Of_Purchase)):''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Supplier Name*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Supplier_Name[{{$i}}]" value="{{isset($val->Supplier_Name) && $val->Supplier_Name!=''?$val->Supplier_Name:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Invoice No.*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="invoice_No[{{$i}}]" value="{{isset($val->invoice_No) && $val->invoice_No!=''?$val->invoice_No:''}}" required></p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-2 form-group">
                          <label>QTY*</lable>
                            <p><input type="number" class="form-control form-control-sm" placeholder="" name="QTY[{{$i}}]" value="{{isset($val->QTY) && $val->QTY!=''?$val->QTY:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Organization*</lable>
                            <select class="form-select form-select-sm js-example-matcher-start" name="Organization[{{$i}}]" required>
                              <option value="" selected disabled>Select Option </option>
                              @foreach($Organization as $vals)
                              <option value="{{$vals->id}}" {{isset($val->Organization) && $val->Organization==$vals->id?'selected':''}}>{{$vals->organization}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Use By*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Use_By[{{$i}}]" value="{{isset($val->Use_By) && $val->Use_By!=''?$val->Use_By:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Use In*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Use_In[{{$i}}]" value="{{isset($val->Use_In) && $val->Use_In!=''?$val->Use_In:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Location*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Location[{{$i}}]" value="{{isset($val->Location) && $val->Location!=''?$val->Location:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Furniture Type*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Type[{{$i}}]" value="{{isset($val->Furniture_Type) && $val->Furniture_Type!=''?$val->Furniture_Type:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Furniture Name*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Name[{{$i}}]" value="{{isset($val->Furniture_Name) && $val->Furniture_Name!=''?$val->Furniture_Name:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Furniture SL. No.*</lable>
                            <p><input class="form-control form-control-sm" type="number" placeholder="" name="Furniture_SL_No[{{$i}}]" value="{{isset($val->Furniture_SL_No) && $val->Furniture_SL_No!=''?$val->Furniture_SL_No:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Date Of Purchase*</lable>
                            <p><input class="form-control form-control-sm" type="date" max="{{ date('Y-m-d') }}" placeholder="" name="Date_Of_Purchase_Second[{{$i}}]" value="{{isset($val->Date_Of_Purchase_Second) && $val->Date_Of_Purchase_Second!=''?$val->Date_Of_Purchase_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Supplier Name*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Supplier_Name_Second[{{$i}}]" value="{{isset($val->Supplier_Name_Second) && $val->Supplier_Name_Second!=''?$val->Supplier_Name_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Invoice No*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Invoice_No_Second[{{$i}}]" value="{{isset($val->Invoice_No_Second) && $val->Invoice_No_Second!=''?$val->Invoice_No_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>QTY*</lable>
                            <p><input class="form-control form-control-sm" type="number" placeholder="" name="QTY_Second[{{$i}}]" value="{{isset($val->QTY_Second) && $val->QTY_Second!=''?$val->QTY_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Organization*</lable>
                            <select class="form-select form-select-sm js-example-matcher-start" name="Organization_Second[{{$i}}]" required>
                              <option value="" selected disabled>Select Option </option>
                              @foreach($Organization as $vals)
                              <option value="{{$vals->id}}" {{isset($val->Organization_Second) && $val->Organization_Second==$vals->id?'selected':''}}>{{$vals->organization}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Location*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Location_Second[{{$i}}]" value="{{isset($val->Location_Second) && $val->Location_Second!=''?$val->Location_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Use By*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Use_By_Second[{{$i}}]" value="{{isset($val->Use_By_Second) && $val->Use_By_Second!=''?$val->Use_By_Second:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Used For*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Used_For[{{$i}}]" value="{{isset($val->Used_For) && $val->Used_For!=''?$val->Used_For:''}}" required></p>
                        </div>
                        <div class="col-sm-2 form-group">
                          <label>Other Item Details*</lable>
                            <p><input class="form-control form-control-sm" placeholder="" name="Other_Item_Details[{{$i}}]" value="{{isset($val->Other_Item_Details) && $val->Other_Item_Details!=''?$val->Other_Item_Details:''}}" required></p>
                        </div>
                        @if($i == 1)
                        <div class="col-sm-2 form-group">
                          <button type="button" name="add" id="add13" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                        @else
                        <div class="col-sm-2 form-group">
                          <a name="remove" onclick="remove({{$i}})" class="btn btn-danger btn-sm mt-4 btn_remove">X</a>
                        </div>
                        @endif
                      </div>
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row">
                      <div class="col-sm-2 form-group">
                        <label>Asset Type*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Asset_Type[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Asset Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Asset_Name[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Asset SL No.*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Asset_SL_No[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Date Of Purchase*</lable>
                          <p><input class="form-control form-control-sm" type="date" max="{{ date('Y-m-d') }}" placeholder="" name="Date_Of_Purchase[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Supplier Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Supplier_Name[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Invoice No.*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="invoice_No[0]" required></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-2 form-group">
                        <label>QTY*</lable>
                          <p><input type="number" class="form-control form-control-sm" placeholder="" name="QTY[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Organization*</lable>
                          <select class="form-select form-select-sm js-example-matcher-start" name="Organization[0]" required>
                            <option value="" selected disabled>Select Option </option>
                            @foreach($Organization as $vals)
                            <option value="{{$vals->id}}">{{$vals->organization}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Use By*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Use_By[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Use In*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Use_In[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Location*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Location[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Furniture Type*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Type[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Furniture Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Name[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Furniture SL. No.*</lable>
                          <p><input class="form-control form-control-sm" type="number" placeholder="" name="Furniture_SL_No[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Date Of Purchase*</lable>
                          <p><input class="form-control form-control-sm" type="date" max="{{ date('Y-m-d') }}" placeholder="" name="Date_Of_Purchase_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Supplier Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Supplier_Name_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Invoice No*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Invoice_No_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>QTY*</lable>
                          <p><input class="form-control form-control-sm" type="number" placeholder="" name="QTY_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Organization*</lable>
                          <select class="form-select form-select-sm js-example-matcher-start" name="Organization_Second[0]" required>
                            <option value="" selected disabled>Select Option </option>
                            @foreach($Organization as $vals)
                            <option value="{{$vals->id}}">{{$vals->organization}}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Location*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Location_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Use By*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Use_By_Second[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Used For*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Used_For[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <label>Other Item Details*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Other_Item_Details[0]" required></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add13" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="State">Remark:</label>
                      <textarea name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($officeasst->Remark) && $officeasst->Remark!=''?$officeasst->Remark:''}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($officeasst->id) && $officeasst->id != ''?'none':'block'}}">Clear All</a>
                  <button type="submit" id="submitbtn" class="btn btn1 float-right">Submit & Next</button>
                </div>
              </div>
          </form>
        </div>
      </div>
  </div>
  <br> <br>
</div>
</div>
</div>
</section>
@endsection
@push('custom-scripts')
<script>
  $(document).ready(function() {
    var i = parseInt('{{isset($assettypee)?count($assettypee):1}}');
    var currentDate = "{{date('Y-m-d')}}";
    $('#add13').click(function() {
      i++;
      $('#dynamic_field13').append('<tr id="rows' + i + '"><td> <div class="row"><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"   name="Asset_Type[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Name</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"  name="Asset_Name[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;">Asset SL No.</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"  name="Asset_SL_No[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Date Of Purchase</label><input class="form-control form-control-sm"  type="date" max="' + currentDate + '"  autocomplete="off"   name="Date_Of_Purchase[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Supplier Name</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"  name="Supplier_Name[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Invoice No.</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"   name="invoice_No[' + i + ']" required/></div><br></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;">QTY</label><input class="form-control form-control-sm"  type="number"  autocomplete="off"   name="QTY[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Organization</label><select class="form-select form-select-sm js-example-matcher-start" name="Organization[0]" required><option value="" selected disabled>Select Option </option>@foreach($Organization as $vals)<option value="{{$vals->id}}">{{$vals->organization}}</option>@endforeach</select></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Use By</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"  name="Use_By[' + i + ']" required/></div></div> <div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Use In</label><input class="form-control form-control-sm" type="text" autocomplete="off" name="Use_In[' + i + ']" required/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;">Location</label><input class="form-control form-control-sm"  type="text"  autocomplete="off"   name="Location[' + i + ']" required/></div></div><div class="col-sm-2 form-group"> <label>Furniture Type*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Type[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Furniture Name*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Furniture_Name[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Furniture SL. No.*</lable> <p><input class="form-control form-control-sm" type="number" placeholder="" name="Furniture_SL_No[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Date Of Purchase*</lable> <p><input class="form-control form-control-sm" type="date" max="' + currentDate + '" placeholder="" name="Date_Of_Purchase_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Supplier Name*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Supplier_Name_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Invoice No*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Invoice_No_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>QTY*</lable> <p><input class="form-control form-control-sm" type="number" placeholder="" name="QTY_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Organization*</lable> <select class="form-select form-select-sm js-example-matcher-start" name="Organization_Second[0]" required> <option value="" selected disabled>Select Option </option> @foreach($Organization as $vals)<option value="{{$vals->id}}">{{$vals->organization}}</option>@endforeach</select></div><div class="col-sm-2 form-group"> <label>Location*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Location_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Use By*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Use_By_Second[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Used For*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Used_For[' + i + ']" required></p></div><div class="col-sm-2 form-group"> <label>Other Item Details*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Other_Item_Details[' + i + ']" required></p></div><div class="col-sm-2 form-group" style=""><div class="field-wrap"><a name="remove" onclick="remove(' + i + ')" class="btn btn-danger btn-sm mt-4 btn_remove">X</a></div></div></div><br></tr>');

      AppendSelect2();
    });

  });

  function remove(id) {
    $('#rows' + id).remove();
  }
</script>
@endpush