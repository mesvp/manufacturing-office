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
        <li class="breadcrumb-item active text-" aria-current="page">Inputer List </li>
      </ol> -->
      <div class="addbtn extra">
        <a href="{{url('FactoryCreater/step6')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step7']}} active anchor" href="#">Warehouse & Room</a>
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
          <form id="formSubmit" action="{{url('FactoryCreater/WareHouse_Room')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($warehousetotal->id) && $warehousetotal->id!=''?$warehousetotal->id:''}}">
            <div>
              <br>
              <div class="tabs">
                <h5>WareHouse & Room</h5>
                <br>
                <div class="tab1">
                  <div class="row">
                    <div class="col-sm-10 form-group">
                      <label>Total Warehouse*</lable>
                        <p><input class="form-control form-control-sm" placeholder="Total Warehouse" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" name="Total_Warehouse" id="total_warehouse" value="{{isset($warehousetotal->Total_Warehouse) && $warehousetotal->Total_Warehouse!=''?$warehousetotal->Total_Warehouse:''}}" ></p>
                    </div>
                  </div>
                  <div id="dynamic_field11">
                    @php
                    $i = 1;
                    @endphp
                    @if(count($warehouse)>0)
                    @foreach($warehouse as $val)
                    <input class="form-control form-control-sm" type="hidden" name="warehouseID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                    <div class="row counttt" id="row{{$i}}">
                      <div class="col-sm-3 form-group">
                        <label>Warehouse Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Warehouse_Name[]" value="{{isset($val->Warehouse_Name) && $val->Warehouse_Name!=''?$val->Warehouse_Name:''}}" ></p>
                      </div>
                      <div class="col-sm-3 form-group">
                        <label>Count*</lable>
                          <p><input type="number" class="form-control form-control-sm" placeholder="" name="Count[]" value="{{isset($val->Count) && $val->Count!=''?$val->Count:''}}" ></p>
                      </div>
                      <div class="col-sm-3 form-group">
                        <label>Warehouse Type*</lable>
                          <p><input type="text" class="form-control form-control-sm" placeholder="" onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) ||(event.charCode >= 97 && event.charCode <= 122) ||(event.charCode == 32 ))" name="Warehouse_Type[]" value="{{isset($val->Warehouse_Type) && $val->Warehouse_Type!=''?$val->Warehouse_Type:''}}" ></p>
                      </div>
                      @if($i==1)
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add11" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                      @else
                      <div class="col-sm-2 form-group">
                        <a name="remove" onclick="remove({{$i}})" class="btn btn-danger btn-sm mt-4 btn_remove">X</a>
                      </div>
                      @endif
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row counttt" id="row">
                      <div class="col-sm-3 form-group">
                        <label>Warehouse Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Warehouse_Name[]" ></p>
                      </div>
                      <div class="col-sm-3 form-group">
                        <label>Count*</lable>
                          <p><input type="number" class="form-control form-control-sm" placeholder="" name="Count[]" ></p>
                      </div>
                      <div class="col-sm-3 form-group">
                        <label>Warehouse Type*</lable>
                          <p><input class="form-control form-control-sm" onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) ||(event.charCode >= 97 && event.charCode <= 122) ||(event.charCode == 32 ))" placeholder="" name="Warehouse_Type[]" ></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add11" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    @endif
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-sm-10 form-group">
                      <label>Total Room*</lable>
                        <p><input class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Total Room" name="Total_Room" id="total_room" value="{{isset($warehousetotal->Total_Room) && $warehousetotal->Total_Room!=''?$warehousetotal->Total_Room:''}}" ></p>
                    </div>
                  </div>
                  <div id="dynamic_field12">
                    @php
                    $j = 1;
                    @endphp
                    @if(count($warehouseroom)>0)
                    @foreach($warehouseroom as $val)
                    <input class="form-control form-control-sm" type="hidden" name="roomID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                    <div class="row countts" id="rows{{$j}}">
                      <div class="col-sm-4 form-group">
                        <label>Room Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Room_Name[]" value="{{isset($val->Room_Name) && $val->Room_Name!=''?$val->Room_Name:''}}" ></p>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Room Count*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Room_Count[]" value="{{isset($val->Room_Count) && $val->Room_Count!=''?$val->Room_Count:''}}" ></p>
                      </div>
                      @if($j==1)
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add12" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                      @else
                      <div class="col-sm-2 form-group">
                        <a name="remove" onclick="removeroom({{$j}})" class="btn btn-danger btn-sm mt-4 btn_remove">X</a>
                      </div>
                      @endif
                    </div>
                    @php
                    $j++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row countts">
                      <div class="col-sm-4 form-group">
                        <label>Room Name*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Room_Name[]" ></p>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Room Count*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Room_Count[]" ></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add12" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="State">Remark:</label>
                      <textarea name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($warehousetotal->Remark) && $warehousetotal->Remark!=''?$warehousetotal->Remark:''}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($warehousetotal->id) && $warehousetotal->id != ''?'none':'block'}}">Clear All</a>
                  <button type="submit" id="submitButton" class="btn btn1 float-right">Submit & Next</button>
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
{{-- <script>
  $('#submitButton').click(function() {

    var warehouseVal = parseInt($('#total_warehouse').val());
    var field = $('.counttt').length;

    if (warehouseVal != field) {
      alert('Please make sure that Total Warehouse is equal to Append fields');
      return false;
    }

    var RoomVal = parseInt($('#total_room').val());
    var fields = $('.countts').length;
    if (RoomVal == fields) {
      $('#formSubmit').submit();
    } else {
      alert('Please make sure that Total Room is equal to Append fields');
      return false;
    }

  });
</script> --}}
<script>
  $(document).ready(function() {
    var i = 1;
    $('#add11').click(function() {
      var totalWarehouse = parseInt($('#total_warehouse').val());
      var appendedFields = $('#dynamic_field11').find('.row').length;
      if (appendedFields >= totalWarehouse) {
        alert('Cannot add more fields. Total Warehouse limit exceeded!');
        return;
      }
      i++;
      var a = '<div class="row counttt" id="row' + i + '" > <div class="col-sm-3 form-group"> <label>Warehouse Name*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Warehouse_Name[]" ></p></div><div class="col-sm-3 form-group"> <label>Count*</lable> <p><input type="number" class="form-control form-control-sm" placeholder="" name="Count[]" ></p></div><div class="col-sm-3 form-group"> <label>Warehouse Type*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Warehouse_Type[]" onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) ||(event.charCode >= 97 && event.charCode <= 122) ||(event.charCode == 32 ))"  ></p></div><div class="col-sm-2 form-group"> <a name="remove" onclick="remove(' + i + ')" class="btn btn-danger btn-sm mt-4 btn_remove">X</a> </div></div>';
      $('#dynamic_field11').append(a);
    });

  });

  function remove(id) {
    $('#row' + id).remove();
  }
</script>
<script>
  $(document).ready(function() {
    var i = 1;
    $('#add12').click(function() {
      var totalRoom = parseInt($('#total_room').val());
      var appendedFields = $('#dynamic_field12').find('.row').length;
      if (appendedFields >= totalRoom) {
        alert('Cannot add more fields. Total Room limit exceeded!');
        return;
      }
      i++;
      var a = '<div class="row countts" id="rows' + i + '"> <div class="col-sm-4 form-group"> <label>Room Name*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Room_Name[]" ></p></div><div class="col-sm-4 form-group"> <label>Room Count*</lable> <p><input class="form-control form-control-sm" placeholder="" name="Room_Count[]" ></p></div><div class="col-sm-2 form-group"> <a name="remove" onclick="removeroom(' + i + ')" class="btn btn-danger btn-sm mt-4 btn_remove">X</a> </div></div>';
      $('#dynamic_field12').append(a);
    });
  });

  function removeroom(id) {
    $('#rows' + id).remove();
  }
</script>
@endpush