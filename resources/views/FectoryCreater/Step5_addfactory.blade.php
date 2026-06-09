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
        <a href="{{url('FactoryCreater/step4')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step5']}} active anchor" href="#">Amenities</a>
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
          <form id="submitform" action="{{url('FactoryCreater/Amenities')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
              <br>
              <div class="tabs">
                <h5>Amenities</h5> <br>
                <div class="tab1">
                  <div class="row">
                    <input class="form-control" type="hidden" name="edit" value="{{isset($Amenitiess->id) && $Amenitiess->id!=''?$Amenitiess->id:''}}">
                    <div class="col-sm-3 form-group">
                      <label>Toilet Count</lable>
                        <p>
                          <input class="form-control form-control-sm" id="totalCount" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Toilet Count" name="Toilet_Count" value="{{isset($Amenitiess->Toilet_Count) && $Amenitiess->Toilet_Count!=''?$Amenitiess->Toilet_Count:''}}">
                        </p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>For Men</lable>
                        <p><input class="form-control form-control-sm" id="men" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="For Men" name="For_Men" value="{{isset($Amenitiess->For_Men) && $Amenitiess->For_Men!=''?$Amenitiess->For_Men:''}}"></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>For Women</lable>
                        <p>
                          <input class="form-control form-control-sm" id="women" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="For Women" name="For_Women" value="{{isset($Amenitiess->For_Women) && $Amenitiess->For_Women!=''?$Amenitiess->For_Women:''}}">
                        </p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>WashBasin Count</lable>
                        <p><input class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="WashBasin Count" name="WashBasin_Count" value="{{isset($Amenitiess->WashBasin_Count) && $Amenitiess->WashBasin_Count!=''?$Amenitiess->WashBasin_Count:''}}"></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Urinals</lable>
                        <p><input class="form-control form-control-sm" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" placeholder="Urinals" name="Urinals" value="{{isset($Amenitiess->Urinals) && $Amenitiess->Urinals!=''?$Amenitiess->Urinals:''}}"></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label>Others</lable>                                       
                        <table class="table table-bordered" id="dynamic_field8">   
                          <tr>
                            <td><a href="javascript:;" name="add" id="add8" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                          </tr>                      
                          @php
                          $i = 1;
                          @endphp
                          @foreach($amentOther as $val)
                          <tr id="row{{$i}}">
                            <input type="hidden" name="other[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                            <td>
                              <div class="field-wrap">
                                <label style="display:flex;">Add Field Name Manually</label>
                                <input class="form-control form-control-sm" type="text" autocomplete="off" name="Add_Field_Name_Manually[]" value="{{isset($val->Add_Field_Name_Manually) && $val->Add_Field_Name_Manually!=''?$val->Add_Field_Name_Manually:''}}">
                              </div>
                            </td>
                            <td>
                              <div class="field-wrap">
                                <label style="display:flex;">Add Count Manually</label>
                                <input class="form-control form-control-sm" type="text" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" name="Add_Count_Manually[]" value="{{isset($val->Add_Count_Manually) && $val->Add_Count_Manually!=''?$val->Add_Count_Manually:''}}">
                              </div>
                            </td>
                            {{-- <td>
                              <a href="javascript:;" onclick="remove({{$i}})" class="btn btn-danger btn_remove mt-4">X</a>
                            </td> --}}
                          </tr>
                          @php
                          $i++;
                          @endphp
                          @endforeach
                        </table>
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($Amenitiess->id) && $Amenitiess->id != ''?'none':'block'}}">Clear All</a>               
                  <button type="submit" id="submitbutton" class="btn btn1 float-right">Submit & Next</button>
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
    $("#totalCount, #men, #women").on("input", function() {
      var totalCount = parseInt($("#totalCount").val());
      var men = parseInt($("#men").val());
      var women = parseInt($("#women").val());
      if (men > totalCount) {
        $("#men").val("");
        alert("The sum of 'Men' and 'Women' cannot exceed the 'Total Count'.");
      } else if (women > totalCount) {
        $("#women").val("");
        alert("The sum of 'Men' and 'Women' cannot exceed the 'Total Count'.");
      } else if (men + women > totalCount) {
        $("#women").val("");
        alert("The sum of 'Men' and 'Women' cannot exceed the 'Total Count'.");
      }
    });
  });
  $('#submitbutton').click(function() {
    var totalCount = parseInt($("#totalCount").val());
    var men = parseInt($("#men").val());
    var women = parseInt($("#women").val());
    if (men + women != totalCount) {
      alert("The sum of 'Men' and 'Women' cannot less then 'Total Count'.");
      return false;
    } else {
      $('submitform').submit();
    }
  });
</script>
<script>
  $(document).ready(function() {
    var i = 1;
    $('#add8').click(function() {
      i++;
      $('#dynamic_field8').append('<tr id="row' + i + '"><td><div class="field-wrap" ><label style="display:flex;">Add Field Name Manually</label><input class="form-control form-control-sm"  type="text" autocomplete="off" name="Add_Field_Name_Manually[]" required></div></td><td ><div  class="field-wrap"><label style="display:flex;">Add Count Manually</label><input class="form-control form-control-sm"  type="text" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) ||(event.charCode == 46))" name="Add_Count_Manually[]" required></div></td><td><a href="javascript:;" onclick="remove(' + i + ')" class="btn btn-danger btn_remove mt-4">X</a></td></tr>');
    });
  });

  function remove(id) {
    $("#row" + id).remove();
  }
</script>
@endpush