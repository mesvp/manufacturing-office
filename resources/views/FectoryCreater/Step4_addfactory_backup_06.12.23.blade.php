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
    margin-top: 2%;
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

  .col-sm-3 {
    width: 20% !important;
  }

  tbody,
  td,
  tfoot,
  th,
  thead,
  tr {
    border: none !important;
  }

  div#mainddd {
    padding-left: 20px;
  }

  /* .SelectDesignHandle span.select2-selection.select2-selection--single {
    width: 24em !important;
  } */
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
    <section class="section">
      <div class="addbtn extra">
        <a href="{{url('FactoryCreater/step3')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step4']}} active anchor" href="#">Plant & Machinery</a>
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
              <a class="nav-link {{$formdata['step8']}} anchor" href="{{url('FactoryCreater/step8')}}">Office Asset</a>
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
          <form id="savedraft" action="{{url('FactoryCreater/Plant_Machinery')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
              <div>
                <br>
                <div class="tabs">
                  <h5>Plant & Machinery</h5>
                  <div class="maindd" id='mainddd'>
                    @php
                    $i = 1;
                    @endphp
                    @if(count($plantmach)>0)
                    @foreach($plantmach as $plantmach)
                    <input class="form-control form-control-sm" type="hidden" name="edit[{{$i}}]" value="{{isset($plantmach->id) && $plantmach->id!=''?$plantmach->id:''}}">
                    <div id="removemachinepage{{$i}}" class="row">
                      <div class="col-sm-11 tab1">
                        <div class="row">
                          <div class="col-sm-3 form-group">
                            <label>Plant Name*</lable>
                              <div class="field-wrap">
                                <select name="Plant_Name[{{$i}}]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($masterplant as $val)
                                  <option value="{{$val->id}}" {{isset($plantmach->Plant_Name) && $plantmach->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Production Capacity*</lable>
                              <!-- <select name="Production_Capacity[{{$i}}]" class="form-select form-select-sm dropdowndisable" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($ProductionCapacity as $val)
                                <option value="{{$val->id}}" {{isset($plantmach->Production_Capacity) && $plantmach->Production_Capacity==$val->id?'selected':''}}>{{$val->Production_Capacity}}</option>
                                @endforeach
                              </select> -->
                              <p><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacity[{{$i}}]" value="{{isset($plantmach->Production_Capacity) && $plantmach->Production_Capacity!=''?$plantmach->Production_Capacity:''}}" required></p>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Product</lable>
                              <div class="field-wrap">
                                <select name="Product[{{$i}}]" class="form-select form-select-sm" id="product{{$i}}" onclick="product({{$i}})" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($product as $val)
                                  <option value="{{$val->id}}" {{isset($plantmach->Product) && $plantmach->Product==$val->id?'selected':''}}>{{$val->product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Sub product</lable>
                              <div class="field-wrap">
                                <select name="Sub_product[{{$i}}]" class="form-select form-select-sm" id="subproduct{{$i}}" onclick="subproduct({{$i}})" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($subproduct as $val)
                                  <option value="{{$val->id}}" {{isset($plantmach->Sub_product) && $plantmach->Sub_product==$val->id?'selected':''}}>{{$val->sub_product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Sub Sub product</lable>
                              <div class="field-wrap">
                                <select name="Sub_Sub_product[{{$i}}]" class="form-select form-select-sm" id="subsubproduct{{$i}}" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($subsubproduct as $val)
                                  <option value="{{$val->id}}" {{isset($plantmach->Sub_Sub_product) && $plantmach->Sub_Sub_product==$val->id?'selected':''}}>{{$val->sub_sub_product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3 form-group">
                            <label>UOM*</lable>
                              <select name="UOM[{{$i}}]" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($uom as $val)
                                <option value="{{$val->id}}" {{isset($plantmach->UOM) && $plantmach->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                @endforeach
                              </select>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Duration*</lable>
                              <!-- <select name="Duration[{{$i}}]" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($Duration as $val)
                                <option value="{{$val->id}}" {{isset($plantmach->Duration) && $plantmach->Duration==$val->id?'selected':''}}>{{$val->Duration}}</option>
                                @endforeach
                              </select> -->
                              <p><input class="form-control form-control-sm" name="Duration[{{$i}}]" value="{{isset($plantmach->Duration) && $plantmach->Duration!=''?$plantmach->Duration:''}}" required></p>
                          </div>
                        </div>
                        <table class="table table-bordered" id="dynamic_fieldaddmachine{{$i}}">
                          @php
                          $j = 1;
                          @endphp
                          @foreach($plantmach->machinename as $machinename)
                          <tr id="rowaddmachine{{$i}}{{$j}}">
                            <input class="form-control form-control-sm" type="hidden" name="machinenameID[{{$i}}][{{$j}}]" value="{{isset($machinename->id) && $machinename->id!=''?$machinename->id:''}}">
                            <td>
                              <div class="row">
                                <div class="col-sm-3 form-group">
                                  <label>Machine Name*</lable>
                                    <select name="Machine_Name[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($MachineName as $val)
                                      <option value="{{$val->id}}" {{isset($machinename->Machine_Name) && $machinename->Machine_Name==$val->id?'selected':''}}>{{$val->Machine_Name}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Attachement</lable>
                                    <p><input class="form-control form-control-sm" type="file" name="Attachement{{$i}}{{$j}}"></p>
                                    <div class="downloadfile" id="attachement">
                                      @foreach($plantmach->machinename as $val)
                                         <div>{{substr(isset($val->Attachement) && $val->Attachement!=''?$val->Attachement:'', 17)}}</div>
                                         <p><a href="{{url('../storage/app/public/'.(isset($val->Attachement) && $val->Attachement!=''?$val->Attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                         {{-- <div><a href="javascript:;" class="remove-file" onclick="removeFile('Attachement',{{ $val->id}})"><i class="fa fa-remove remove-file"></i> </a></div> --}}
                                     </div>
                                     @endforeach
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Machine Code*</lable>
                                    <select name="Machine_Code[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($Machine_Code as $val)
                                      <option value="{{$val->id}}" {{isset($machinename->Machine_Code) && $machinename->Machine_Code==$val->id?'selected':''}}>{{$val->Machine_Code}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Accessories*</lable>
                                    <select name="Accessories[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($Accessories as $val)
                                      <option value="{{$val->id}}" {{isset($machinename->Accessories) && $machinename->Accessories==$val->id?'selected':''}}>{{$val->Accessories}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Attachement</lable>
                                    <p><input class="form-control form-control-sm" type="file" name="Attachements{{$i}}{{$j}}"></p>
                                    <div class="downloadfile" id="attachement">
                                      @foreach($plantmach->machinename as $val)
                                         <div>{{substr(isset($val->Attachements) && $val->Attachements!=''?$val->Attachements:'', 17)}}</div>
                                         <p><a href="{{url('../storage/app/public/'.(isset($val->Attachements) && $val->Attachements!=''?$val->Attachements:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                                         {{-- <div><a href="javascript:;" class="remove-file" onclick="removeFile('Attachements',{{ $val->id}})"><i class="fa fa-remove remove-file"></i> </a></div> --}}
                                     </div>
                                     @endforeach
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Specification*</lable>
                                    <select name="Specification[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($Specification as $val)
                                      <option value="{{$val->id}}" {{isset($machinename->Specification) && $machinename->Specification==$val->id?'selected':''}}>{{$val->Specification}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Make & Model*</lable>
                                    <div class="field-wrap">
                                      <select name="Make_Model[{{$i}}][{{$j}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($Make_Model as $val)
                                        <option value="{{$val->id}}" {{isset($machinename->Make_Model) && $machinename->Make_Model==$val->id?'selected':''}}>{{$val->Make_Model}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                                @if($j==1)
                                <div class="col-sm-3 form-group">
                                  <button type="button" id="addmachine{{$i}}" onclick="addmachine({{$i}},{{$j}})" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                                @else
                                <div class="col-sm-3 form-group">
                                  <a href="javascript:;" onclick="removemachine({{$i}},{{$j}})" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a>
                                </div>
                                @endif
                              </div>
                            </td>
                          </tr>
                          @php
                          $j++;
                          @endphp
                          @endforeach
                        </table>
                        <table class="table table-bordered" id="dynamic_fieldaddwarnty{{$i}}">
                          @php
                          $k = 1;
                          @endphp
                          @foreach($plantmach->warrnty as $warrnty)
                          <tr id="rowremovewarnty{{$i}}{{$k}}">
                            <input class="form-control form-control-sm" type="hidden" name="warrntyID[{{$i}}]" value="{{isset($warrnty->id) && $warrnty->id!=''?$warrnty->id:''}}">
                            <td>
                              <div class="row">
                                <div class="col-sm-3 form-group">
                                  <label> Warranty*</lable>
                                    <select name="Warranty[{{$i}}][{{$k}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($Warranty as $val)
                                      <option value="{{$val->id}}" {{isset($warrnty->Warranty) && $warrnty->Warranty==$val->id?'selected':''}}>{{$val->Warranty}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Production Capacity*</lable>
                                    <!-- <select name="Production_Capacitys[{{$i}}][{{$k}}]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($ProductionCapacity as $val)
                                      <option value="{{$val->id}}" {{isset($warrnty->Production_Capacitys) && $warrnty->Production_Capacitys==$val->id?'selected':''}}>{{$val->Production_Capacity}}</option>
                                      @endforeach
                                    </select> -->
                                    <p><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacitys[{{$i}}]" value="{{isset($warrnty->Production_Capacitys) && $warrnty->Production_Capacitys!=''?$warrnty->Production_Capacitys:''}}" required></p>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>UOM*</lable>
                                    <div class="field-wrap">
                                      <select name="UOMs[{{$i}}][{{$k}}]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($uom as $val)
                                        <option value="{{$val->id}}" {{isset($warrnty->UOMs) && $warrnty->UOMs==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                                @if($k==1)
                                <div class="col-sm-3 form-group">
                                  <button type="button" id="addwarrnty{{$i}}" onclick="addwarnty({{$i}},{{$k}})" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                                @else
                                <div class="col-sm-3 form-group">
                                  <a href="javascript:;" onclick="removewarnty({{$i}},{{$k}})" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a>
                                </div>
                                @endif
                              </div>
                            </td>
                          </tr>
                          @php
                          $k++;
                          @endphp
                          @endforeach
                        </table>
                        <div class="row">
                          <div class="col-sm-3 form-group">
                            <label> Date Of Purchase*</lable>
                              <p><input class="form-control form-control-sm" type="date" max="{{ now()->toDateString('Y-m-d') }}" name="Date_Of_Purchase[{{$i}}]" value="{{isset($plantmach->Date_Of_Purchase) && $plantmach->Date_Of_Purchase!=''?$plantmach->Date_Of_Purchase:''}}" required></p>
                          </div>
                          <div class="col-sm-4 form-group">
                            <label>Machine Company Name*</lable>
                              <p><input class="form-control form-control-sm" name="Machine_Company_Name[{{$i}}]" value="{{isset($plantmach->Machine_Company_Name) && $plantmach->Machine_Company_Name!=''?$plantmach->Machine_Company_Name:''}}" required></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label>Others</lable>
                              <table class="table table-bordered" id="dynamic_fieldaddother{{$i}}">
                                <tr>
                                  <td><a href="javascript:;" type="button" id="addothers{{$i}}" onclick="addothers({{$i}},0)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                                </tr>
                                @php
                                $l = 1;
                                @endphp
                                @foreach($plantmach->other as $other)
                                <tr id="rowaddothers{{$i}}{{$l}}">
                                  <input class="form-control form-control-sm" type="hidden" name="otherID[]" value="{{isset($other->id) && $other->id!=''?$other->id:''}}">
                                  <td>
                                    <div class="row">
                                      <div class="field-wrap col-sm-6">
                                        <label>Add Field Name Manually</label>
                                        <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Add_Field_Name_Manually[{{$i}}][{{$l}}]" value="{{isset($other->Add_Field_Name_Manually) && $other->Add_Field_Name_Manually!=''?$other->Add_Field_Name_Manually:''}}" required />
                                      </div>
                                      <div class="field-wrap col-sm-6">
                                        <label>Enter Manually Details</label>
                                        <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Enter_Manually_Details[{{$i}}][{{$l}}]" value="{{isset($other->Enter_Manually_Details) && $other->Enter_Manually_Details!=''?$other->Enter_Manually_Details:''}}" required />
                                      </div>
                                    </div>
                                  </td>
                                  <td> <a href="javascript:;" onclick="removeothers({{$i}},{{$l}})" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a></td>
                                </tr>
                                @php
                                $l++;
                                @endphp
                                @endforeach
                              </table>
                          </div>
                          <div class="col-sm-6 form-group">
                            <label for="State">Remarks:</label>
                            <textarea name="Remarks[{{$i}}]" id="" cols="30" rows="5" class="form-control form-control-sm">{{isset($plantmach->Remarks) && $plantmach->Remarks!=''?$plantmach->Remarks:''}}</textarea>
                          </div>
                        </div>
                      </div>
                      @if($i==1)
                      <div class="col-sm-1">
                        <a href="javascript:;" id="addmachinepage" onclick="addmachinepage({{$i}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                      </div>
                      @else
                      <div class="col-sm-1">
                        <a href="javascript:;" onclick="removemachinepage({{$i}})" class="btn btn-danger btn-sm mt-4">X</a>
                      </div>
                      @endif
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row">
                      <div class="col-sm-11 tab1">
                        <div class="row">
                          <div class="col-sm-3 form-group">
                            <label>Plant Name*</lable>
                              <div class="field-wrap">
                                <select name="Plant_Name[0]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($masterplant as $val)
                                  <option value="{{$val->id}}">{{$val->spname}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Production Capacity*</lable>
                              <!-- <select name="Production_Capacity[0]" class="form-select form-select-sm dropdowndisable" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($ProductionCapacity as $val)
                                <option value="{{$val->id}}">{{$val->Production_Capacity}}</option>
                                @endforeach
                              </select> -->
                              <p><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacity[0]" required></p>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Material Name</lable>
                              <div class="field-wrap">
                                {{-- <select name="Product[0]" class="form-select form-select-sm" id="product0" onclick="product(0)" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($product as $val)
                                  <option value="{{$val->id}}">{{$val->product}}</option>
                                  @endforeach
                                </select> --}}
                                <select name="Material_Name" id="materialname" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($materials as $val)
                                  <option value="{{$val->id}}" {{isset($edit->Material_Name) && $edit->Material_Name==$val->id?'selected':''}}>{{$val->material_name}}</option>
                                  @endforeach
                              </select>
                              </div>
                          </div>
                          {{-- <div class="col-sm-3 form-group">
                            <label>Product</lable>
                              <div class="field-wrap">
                                <select name="Product[0]" class="form-select form-select-sm" id="product0" onclick="product(0)" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($product as $val)
                                  <option value="{{$val->id}}">{{$val->product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Sub product</lable>
                              <div class="field-wrap">
                                <select name="Sub_product[0]" class="form-select form-select-sm" id="subproduct0" onclick="subproduct(0)" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($subproduct as $val)
                                  <option value="{{$val->id}}">{{$val->sub_product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Sub Sub product</lable>
                              <div class="field-wrap">
                                <select name="Sub_Sub_product[0]" class="form-select form-select-sm" id="subsubproduct0" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($subsubproduct as $val)
                                  <option value="{{$val->id}}">{{$val->sub_sub_product}}</option>
                                  @endforeach
                                </select>
                              </div>
                          </div> --}}
                        </div>
                        {{-- <div class="row">
                          <div class="col-sm-3 form-group">
                            <label>UOM*</lable>
                              <select name="UOM[0]" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($uom as $val)
                                <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                @endforeach
                              </select>
                          </div>
                          <div class="col-sm-3 form-group">
                            <label>Duration*</lable>
                              <!-- <select name="Duration[0]" class="form-select form-select-sm" required>
                                <option value="" selected disabled>Select</option>
                                @foreach($Duration as $val)
                                <option value="{{$val->id}}">{{$val->Duration}}</option>
                                @endforeach
                              </select> -->
                              <p><input class="form-control form-control-sm" name="Duration[0]" required></p>
                          </div>
                        </div> --}}
                        <table class="table table-bordered" id="dynamic_fieldaddmachine0">
                          <div class="row">
                            <div class="col-sm-3 form-group">
                              <label>Machine Name*</lable>
                                <select name="Machine_Name[0][0]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($MachineName as $val)
                                  <option value="{{$val->id}}">{{$val->Machine_Name}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Attachement</lable>
                                <p><input class="form-control form-control-sm" type="file" name="Attachement00"></p>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Machine Code*</lable>
                                <select name="Machine_Code[0][0]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($Machine_Code as $val)
                                  <option value="{{$val->id}}">{{$val->Machine_Code}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Accessories*</lable>
                                <select name="Accessories[0][0]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($Accessories as $val)
                                  <option value="{{$val->id}}">{{$val->Accessories}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Attachement</lable>
                                <p><input class="form-control form-control-sm" type="file" name="Attachements00"></p>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Specification*</lable>
                                <select name="Specification[0][0]" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>Select</option>
                                  @foreach($Specification as $val)
                                  <option value="{{$val->id}}">{{$val->Specification}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label>Make & Model*</lable>
                                <div class="field-wrap">
                                  <select name="Make_Model[0][0]" class="form-select form-select-sm" required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach($Make_Model as $val)
                                    <option value="{{$val->id}}">{{$val->Make_Model}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                            <div class="col-sm-3 form-group">
                              <button type="button" id="addmachine0" onclick="addmachine(0,1)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </table>
                        <table class="table table-bordered" id="dynamic_fieldaddwarnty0">
                          <tr>
                            <td>
                              <div class="row">
                                <div class="col-sm-3 form-group">
                                  <label> Warranty*</lable>
                                    <select name="Warranty[0][0]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($Warranty as $val)
                                      <option value="{{$val->id}}">{{$val->Warranty}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Production Capacity*</lable>
                                    <!-- <select name="Production_Capacitys[0][0]" class="form-select form-select-sm" required>
                                      <option value="" selected disabled>Select</option>
                                      @foreach($ProductionCapacity as $val)
                                      <option value="{{$val->id}}">{{$val->Production_Capacity}}</option>
                                      @endforeach
                                    </select> -->
                                    <p><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control form-control-sm" name="Production_Capacitys[0][0]" required></p>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>UOM*</lable>
                                    <div class="field-wrap">
                                      <select name="UOMs[0][0]" class="form-select form-select-sm" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach($uom as $val)
                                        <option value="{{$val->id}}">{{$val->UOMs}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <button type="button" id="addwarrnty0" onclick="addwarnty(0,1)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                        <div class="row">
                          <div class="col-sm-3 form-group">
                            <label> Date Of Purchase*</lable>
                              <p><input class="form-control form-control-sm" type="date" max="{{ now()->toDateString('Y-m-d') }}" name="Date_Of_Purchase[0]" required></p>
                          </div>
                          <div class="col-sm-4 form-group">
                            <label>Machine Company Name*</lable>
                              <p><input class="form-control form-control-sm" name="Machine_Company_Name[0]" required></p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12 form-group">
                            <label>Others</lable>
                              <table class="table table-bordered" id="dynamic_fieldaddother0">
                                <tr>
                                  <td><a href="javascript:;" type="button" id="addothers0" onclick="addothers(0,0)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                                </tr>
                              </table>
                          </div>
                          <div class="col-sm-12 form-group">
                            <label for="State">Remarks:</label>
                            <textarea name="Remarks[0]" id="" cols="30" rows="5" class="form-control form-control-sm"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-1">
                        <a href="javascript:;" id="addmachinepage" onclick="addmachinepage(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
                <div style="overflow:auto;">
                  <div style="float:right;">
                    <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                    <a href="" class="btn btn1 float-right" style="margin: 5px;">Clear All</a>
                    <!-- <button type="button" class="btn btn1 float-right">Previous</button> -->
                    <button type="submit" class="btn btn1 float-right">Submit & Next</button>
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
  function addwarnty(i, j) {
    $('#dynamic_fieldaddwarnty' + i).append('<tr id="rowremovewarnty' + i + '' + j + '"> <td> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label> Warranty*</label><select name="Warranty[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($Warranty as $val) <option value="{{$val->id}}">{{$val->Warranty}}</option> @endforeach</select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Production Capacity*</label> <p><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control form-control-sm" name="Production_Capacitys[' + i + '][' + j + ']" required></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>UOM*</label> <div class="field-wrap"> <select name="UOMs[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($uom as $val) <option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach </select> </div></div><div class="col-sm-3 form-group SelectDesignHandle"> <a href="javascript:;" onclick="removewarnty(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a> </div></div></td></tr>');
    j++;
    $("#addwarrnty" + i).attr("onclick", 'addwarnty(' + i + ',' + j + ')');
    AppendSelect2();
  }

  function removewarnty(i, j) {
    $("#rowremovewarnty" + i + '' + j).remove();
  }
</script>
<script>
  function addmachine(i, j) {
    $('#dynamic_fieldaddmachine' + i).append('<tr id="rowaddmachine' + i + '' + j + '"><td> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label>Machine Name*</label><select name="Machine_Name[' + i + '][' + j + ']" id="machinename' + i + j + '" onclick="machinename(' + i + ',' + j + ')" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($MachineName as $val)<option value="{{$val->id}}">{{$val->Machine_Name}}</option> @endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Attachement</label><p><input class="form-control form-control-sm" type="file" name="Attachement' + i + '' + j + '"></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Machine Code*</label><select name="Machine_Code[' + i + '][' + j + ']" id="machinecode' + i + j + '" onclick="machinecode(' + i + ',' + j + ')" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($Machine_Code as $val)<option value="{{$val->id}}">{{$val->Machine_Code}}</option> @endforeach</select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Accessories*</label><select name="Accessories[' + i + '][' + j + ']" id="accessories' + i + j + '" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($Accessories as $val)<option value="{{$val->id}}">{{$val->Accessories}}</option>@endforeach</select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Attachement</label> <p><input class="form-control form-control-sm" type="file" name="Attachements' + i + '' + j + '"></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Specification*</label><select name="Specification[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($Specification as $val) <option value="{{$val->id}}">{{$val->Specification}}</option> @endforeach</select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Make & Model*</label> <div class="field-wrap"> <select name="Make_Model[' + i + '][' + j + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($Make_Model as $val) <option value="{{$val->id}}">{{$val->Make_Model}}</option> @endforeach </select> </div></div><div class="col-sm-3 form-group SelectDesignHandle"><a href="javascript:;" onclick="removemachine(' + i + ',' + j + ')"  class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a></div></div></td></tr>');
    j++;
    $("#addmachine" + i).attr("onclick", 'addmachine(' + i + ',' + j + ')');
    AppendSelect2();
  }

  function removemachine(i, j) {
    $("#rowaddmachine" + i + '' + j).remove();
  }
</script>
<script>
  function addothers(i, j) {
    $('#dynamic_fieldaddother' + i).append('<tr id="rowaddothers' + i + '' + j + '"><td> <div class="row"> <div class="field-wrap col-sm-6"> <label >Add Field Name Manually</label> <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Add_Field_Name_Manually[' + i + '][' + j + ']" required/> </div><div class="field-wrap col-sm-6"> <label >Enter Manually Details</label> <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="Enter_Manually_Details[' + i + '][' + j + ']" required/> </div></div></td><td> <a href="javascript:;" onclick="removeothers(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4 btn_remove mt-4">X</a></td></tr>');
    j++;
    $("#addothers" + i).attr("onclick", 'addothers(' + i + ',' + j + ')');
  }

  function removeothers(i, j) {
    $("#rowaddothers" + i + '' + j).remove();
  }

  function addmachinepage(i) {
    var a = ' <div class="row" style="margin-top:20px;" id="removemachinepage' + i + '"> <div class="col-sm-11 tab1"> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label>Plant Name*</label><div class="field-wrap"><select name="Plant_Name[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($masterplant as $val)<option value="{{$val->id}}">{{$val->plant_name}}</option>@endforeach</select></div></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Production Capacity*</label><p><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control form-control-sm" name="Production_Capacity[' + i + ']" required></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Product</label><div class="field-wrap"><select name="Product[' + i + ']" class="form-select form-select-sm js-example-matcher-start" id="product' + i + '" onclick="product(' + i + ')" required><option value="" selected disabled>Select</option>@foreach($product as $val)<option value="{{$val->id}}">{{$val->product}}</option>@endforeach</select></div> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Sub product</label><div class="field-wrap"><select name="Sub_product[' + i + ']" class="form-select form-select-sm js-example-matcher-start" id="subproduct' + i + '" onclick="subproduct(' + i + ')" required><option value="" selected disabled>Select</option>@foreach($subproduct as $val)<option value="{{$val->id}}">{{$val->sub_product}}</option>@endforeach</select></div></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Sub Sub product</label><div class="field-wrap"><select name="Sub_Sub_product[' + i + ']" class="form-select form-select-sm js-example-matcher-start" id="subsubproduct' + i + '" required><option value="" selected disabled>Select</option>@foreach($subsubproduct as $val)<option value="{{$val->id}}">{{$val->sub_sub_product}}</option>@endforeach</select></div> </div></div><div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label>UOM*</label> <select name="UOM[' + i + ']" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($uom as $val)<option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach</select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Duration*</label><p><input class="form-control form-control-sm" name="Duration[' + i + ']" required></p></div></div><table class="table table-bordered" id="dynamic_fieldaddmachine' + i + '"> <tr> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label>Machine Name*</label><select name="Machine_Name[' + i + '][0]" id="machinename' + i + '0" onclick="machinename(' + i + ',0)" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($MachineName as $val)<option value="{{$val->id}}">{{$val->Machine_Name}}</option> @endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Attachement</label> <p><input class="form-control form-control-sm" type="file" name="Attachement' + i + '0"></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Machine Code*</label><select name="Machine_Code[' + i + '][0]" id="machinecode' + i + '0" onclick="machinecode(' + i + ',0)" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option>@foreach($Machine_Code as $val)<option value="{{$val->id}}">{{$val->Machine_Code}}</option> @endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Accessories*</label><select name="Accessories[' + i + '][0]" id="accessories' + i + '0" class="form-select form-select-sm js-example-matcher-start" required><option value="" selected disabled>Select</option> @foreach($Accessories as $val)<option value="{{$val->id}}">{{$val->Accessories}}</option> @endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Attachement</label> <p><input class="form-control form-control-sm" type="file" name="Attachements' + i + '0"></p></div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Specification*</label><select name="Specification[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Specification as $val) <option value="{{$val->id}}">{{$val->Specification}}</option> @endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Make & Model*</label> <div class="field-wrap"> <select name="Make_Model[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Make_Model as $val)<option value="{{$val->id}}">{{$val->Make_Model}}</option>@endforeach </select></div></div><div class="col-sm-3 form-group SelectDesignHandle"> <button type="button" id="addmachine' + i + '" onclick="addmachine(' + i + ',1)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button> </div></div></tr></table> <table class="table table-bordered" id="dynamic_fieldaddwarnty' + i + '"> <tr> <td> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label> Warranty*</label><select name="Warranty[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($Warranty as $val)<option value="{{$val->id}}">{{$val->Warranty}}</option>@endforeach </select> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>Production Capacity*</label><p><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control form-control-sm" name="Production_Capacitys[' + i + '][0]" required></p> </div><div class="col-sm-3 form-group SelectDesignHandle"> <label>UOM*</label> <div class="field-wrap"> <select name="UOMs[' + i + '][0]" class="form-select form-select-sm js-example-matcher-start" required> <option value="" selected disabled>Select</option> @foreach($uom as $val) <option value="{{$val->id}}">{{$val->UOMs}}</option> @endforeach </select> </div></div><div class="col-sm-3 form-group SelectDesignHandle"> <button type="button" id="addwarrnty' + i + '" onclick="addwarnty(' + i + ',1)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button> </div></div></td></tr></table> <div class="row"> <div class="col-sm-3 form-group SelectDesignHandle"> <label> Date Of Purchase*</label> <p><input class="form-control form-control-sm"  type="date" max="{{ now()->toDateString(' + Y - m - d + ') }}" name="Date_Of_Purchase[' + i + ']" required></p></div><div class="col-sm-4 form-group"> <label>Machine Company Name*</label> <p><input class="form-control form-control-sm"  name="Machine_Company_Name[' + i + ']" required></p></div></div><div class="row"> <div class="col-sm-12 form-group"> <label>Others</label> <table class="table table-bordered" id="dynamic_fieldaddother' + i + '"> <tr> <td><a href="javascript:;" type="button" id="addothers' + i + '" onclick="addothers(' + i + ',0)" class="btn btn-success btn-sm mt-4 mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></td></tr></table> </div><div class="col-sm-12 form-group"> <label for="State">Remarks:</label> <textarea name="Remarks[' + i + ']" id="" cols="30" rows="5" class="form-control form-control-sm"></textarea> </div></div></div><div class="col-sm-1"> <a href="javascript:;"  onclick="removemachinepage(' + i + ')" class="btn btn-danger btn-sm mt-4">X</a> </div></div>';
    i++;
    $("#mainddd").append(a);
    $("#addmachinepage").attr("onclick", "addmachinepage(" + i + ")");
    AppendSelect2();
  }

  function removemachinepage(i) {
    $("#removemachinepage" + i).remove();
  }
</script>
<script>
  function product(i) {
    $('#product' + i).change(function() {
      var productID = $(this).val();
      $('#subproduct' + i).empty().prop('disabled', true);
      $('#subsubproduct' + i).empty().prop('disabled', true);

      if (productID) {
        $.ajax({
          url: 'get-subproduct/' + productID,
          type: 'GET',
          success: function(response) {
            var options = '';
            options += '<option value="" selected disabled>Select</option>';
            $.each(response, function(index, subproduct) {
              options += '<option value="' + subproduct.id + '">' + subproduct.sub_product + '</option>';
            });
            $('#subproduct' + i).html(options).prop('disabled', false);
          }
        });
      }
    });
  }

  function subproduct(i) {
    $('#subproduct' + i).change(function() {
      var subproductId = $(this).val();
      $('#subsubproduct' + i).empty().prop('disabled', true);

      if (subproductId) {
        $.ajax({
          url: 'get-subsubproduct/' + subproductId,
          type: 'GET',
          success: function(response) {
            var options = '';
            options += '<option value="" selected disabled>Select</option>';
            $.each(response, function(index, subsubproduct) {
              options += '<option value="' + subsubproduct.id + '">' + subsubproduct.sub_sub_product + '</option>';
            });
            $('#subsubproduct' + i).html(options).prop('disabled', false);
          }
        });
      }
    });
  }
</script>

<script>
  function machinename(i, j) {
    $('#machinename' + i + j).change(function() {
      var MachineName = $(this).val();
      $('#machinecode' + i + j).empty().prop('disabled', true);
      $('#accessories' + i + j).empty().prop('disabled', true);

      if (MachineName) {
        $.ajax({
          url: "{{url('FactoryCreater/get-machinecode')}}" + '/' + MachineName,
          type: 'GET',
          success: function(response) {
            var options = '';
            options += '<option value="" selected disabled>Select</option>';
            $.each(response, function(index, machinecode) {
              options += '<option value="' + machinecode.id + '">' + machinecode.Machine_Code + '</option>';
            });
            $('#machinecode' + i + j).html(options).prop('disabled', false);
          }
        });
      }
    });
  }

  function machinecode(i, j) {
    $('#machinecode' + i + j).change(function() {
      var Accessories = $(this).val();
      $('#accessories' + i + j).empty().prop('disabled', true);

      if (Accessories) {
        $.ajax({
          url: "{{url('FactoryCreater/get-accessories')}}" + '/' + Accessories,
          type: 'GET',
          success: function(response) {
            var options = '';
            options += '<option value="" selected disabled>Select</option>';
            $.each(response, function(index, accessories) {
              options += '<option value="' + accessories.id + '">' + accessories.Accessories + '</option>';
            });
            $('#accessories' + i + j).html(options).prop('disabled', false);
          }
        });
      }
    });
  }
</script>
@endpush