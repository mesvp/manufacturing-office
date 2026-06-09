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

  /* .col-sm-3 {
    width: 20% !important;
  } */



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
        <a href="{{url('FactoryCreater/step5')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step6']}} active anchor" href="#">Electricity</a>
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
          <form action="{{url('FactoryCreater/Electricity')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($addresssdetails->id) && $addresssdetails->id!=''?$addresssdetails->id:''}}">
            <div>
              <br>
              <div class="tabs">
                <h5>Electricity</h5>
                <div class="tab1">
                  <br>
                  <table class="table table-bordered" id="dynamic_field9">
                    @php
                    $i=1;
                    @endphp
                    @if(count($Electri)>0)
                    @foreach($Electri as $Electricity)
                    <tr id="remove{{$i}}">
                      <input type="hidden" name="electID[]" value="{{isset($Electricity->id) && $Electricity->id!=''?$Electricity->id:''}}">
                      <td>
                        <div class="row">
                          <div class="col-sm-2 form-group">
                            <label>Total Capacity*</lable>
                              <p><input class="form-control form-control-sm" id="totalcapacity{{$i}}" onchange="totalCapacity({{$i}})" placeholder="" name="Total_Capacity[]" value="{{isset($Electricity->Total_Capacity) && $Electricity->Total_Capacity!=''?$Electricity->Total_Capacity:''}}" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>Running Capacity*</lable>
                              <p><input class="form-control form-control-sm" id="runningcapacity{{$i}}" onchange="totalCapacity({{$i}})" placeholder="" name="Running_Capacity[]" value="{{isset($Electricity->Running_Capacity) && $Electricity->Running_Capacity!=''?$Electricity->Running_Capacity:''}}" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>No. of Meter*</lable>
                              <p><input type="number" class="form-control form-control-sm" placeholder="" name="Meter[]" value="{{isset($Electricity->Meter) && $Electricity->Meter!=''?$Electricity->Meter:''}}" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>No. of Sub Meter*</lable>
                              <p><input type="number" class="form-control form-control-sm" placeholder="" name="Sub_Meter[]" value="{{isset($Electricity->Sub_Meter) && $Electricity->Sub_Meter!=''?$Electricity->Sub_Meter:''}}" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>Source Of Electricity*</lable>
                              <p><input class="form-control form-control-sm" type="text" placeholder="" name="Source_Of_Electricity[]" value="{{isset($Electricity->Source_Of_Electricity) && $Electricity->Source_Of_Electricity!=''?$Electricity->Source_Of_Electricity:''}}"  />
                              </p>
                          </div>
                          @if($i==1)
                          <div class="col-sm-2 form-group">
                            <button type="button" name="add" id="add9" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                          @else
                          {{-- <div class="col-sm-2 form-group">
                            <a name="remove" onclick="remove({{$i}})" class="btn btn-danger btn-sm mt-4 btn_remove">X</a>
                          </div> --}}
                          @endif
                        </div>
                      </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @else
                    <tr>
                      <td>
                        <div class="row">
                          <div class="col-sm-2 form-group">
                            <label>Total Capacity*</lable>
                              <p><input class="form-control form-control-sm" id="totalcapacity0" onchange="totalCapacity(0)" placeholder="" name="Total_Capacity[]" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>Running Capacity*</lable>
                              <p><input class="form-control form-control-sm" id="runningcapacity0" onchange="totalCapacity(0)" placeholder="" name="Running_Capacity[]" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>No. of Meter*</lable>
                              <p><input type="number" class="form-control form-control-sm" placeholder="" name="Meter[]" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>No. of Sub Meter*</lable>
                              <p><input type="number" class="form-control form-control-sm" placeholder="" name="Sub_Meter[]" ></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <label>Source Of Electricity*</lable>
                              <p><input class="form-control form-control-sm" type="text" placeholder="" name="Source_Of_Electricity[]"  /></p>
                          </div>
                          <div class="col-sm-2 form-group">
                            <button type="button" name="add" id="add9" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endif
                  </table>
                  <table class="table table-bordered" id="dynamic_field10">

                    @php
                    $j = 1;
                    @endphp
                    @if(count($Electrigenrate)>0)
                    @foreach($Electrigenrate as $val)
                    <tr id="removegen{{$j}}">
                      <input type="hidden" name="generatorID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                      <td>
                        <div class="row">
                          <div class="col-sm-4 form-group">
                            <label>Generator*</lable>
                              <p><input type="text" class="form-control form-control-sm" placeholder="" name="generator[]" value="{{isset($val->generator) && $val->generator!=''?$val->generator:''}}" ></p>
                          </div>
                          <div class="col-sm-4 form-group">
                            <label>Generator Capacity*</lable>
                              <p><input class="form-control form-control-sm" placeholder="" name="Generator_Capacity[]" value="{{isset($val->Generator_Capacity) && $val->Generator_Capacity!=''?$val->Generator_Capacity:''}}" ></p>
                          </div>
                          @if($j == 1)
                          <div class="col-sm-2 form-group">
                            <button type="button" name="add" id="add10" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                          @else
                          {{-- <div class="col-sm-2 form-group">
                            <a name="remove" onclick="removegen({{$j}})" class="btn btn-danger btn-sm mt-4 btn_remove">X</a>
                          </div> --}}
                          @endif
                          <!-- <div class="col-sm-2 form-group"></div>
                          <div class="col-sm-2 form-group"></div> -->
                        </div>
                      </td>
                    </tr>
                    @php
                    $j++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label>Generator*</lable>
                          <p><input type="text" class="form-control form-control-sm" placeholder="" name="generator[]" ></p>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Generator Capacity*</lable>
                          <p><input class="form-control form-control-sm" placeholder="" name="Generator_Capacity[]" ></p>
                      </div>
                      <div class="col-sm-2 form-group">
                        <button type="button" name="add" id="add10" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    @endif
                  </table>
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label for="State">Remark:</label>
                      <textarea name="Electricity_remarks" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($addresssdetails->Electricity_remarks) && $addresssdetails->Electricity_remarks!=''?$addresssdetails->Electricity_remarks:''}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($Electricity->id) && $Electricity->id != ''?'none':'block'}}">Clear All</a>
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
  function totalCapacity(i) {
    $("#totalcapacity" + i + ", #runningcapacity" + i + "").on("input", function() {
      var totalcapacity = parseInt($("#totalcapacity" + i + "").val());
      var runningcapacity = parseInt($("#runningcapacity" + i + "").val());
      if (runningcapacity > totalcapacity) {
        $("#runningcapacity" + i + "").val("");
        alert("The Value of 'Running Capacity' cannot exceed the 'Total Capacity'.");
      }
    });
  }
</script>
<script>
  $(document).ready(function() {
    var i = 1;
    $('#add9').click(function() {
      i++;
      $('#dynamic_field9').append('<tr id="remove' + i + '"> <td><div class="row"><div class="col-sm-2 form-group"> <label>Total Capacity*</lable> <p><input class="form-control form-control-sm" id="totalcapacity' + i + '" onchange="totalCapacity(' + i + ')" placeholder="" name="Total_Capacity[]" required ></p></div><div class="col-sm-2 form-group"> <label>Running Capacity*</lable> <p><input class="form-control form-control-sm" id="runningcapacity' + i + '" onchange="totalCapacity(' + i + ')" placeholder="" name="Running_Capacity[]" required ></p></div><div class="col-sm-2 form-group"> <label>No. of Meter*</lable> <p><input class="form-control form-control-sm"  type="number" placeholder="Fill in No." name="Meter[]" required ></p></div><div class="col-sm-2 form-group"> <label>No. Of Sub Meter*</lable> <p><input class="form-control form-control-sm"  placeholder="Fill in No." type="number" name="Sub_Meter[]" ></p></div><div class="col-sm-2 form-group"> <label>Source Of Electricity*</lable> <p><input class="form-control form-control-sm" type="text"  placeholder="" name="Source_Of_Electricity[]" required /></p></div><div class="col-sm-2 form-group"><a name="remove" onclick="remove(' + i + ')" class="btn btn-danger btn-sm mt-4 btn_remove">X</a></div></div> </td></tr>');
    });
  });

  function remove(id) {
    $('#remove' + id).remove();
  }
</script>
<script>
  $(document).ready(function() {
    var i = 1;
    $('#add10').click(function() {
      i++;
      $('#dynamic_field10').append('<tr id="removegen' + i + '"><td><div class="row"><div class="col-sm-4 field-wrap" ><label style="display:flex;">Generator</label><input type="text" class="form-control form-control-sm" style="width: 90%;" type="text" autocomplete="off" name="generator[]" ></div><div  class="col-sm-4 field-wrap"><label style="display:flex;">Generator Capacity</label><input class="form-control form-control-sm"  type="text" style="width: 90%;"  name="Generator_Capacity[]" ></div><div class="col-sm-2 form-group"><a name="remove" onclick="removegen(' + i + ')" class="btn btn-danger btn-sm mt-4 btn_remove">X</a><div class="col-sm-2 form-group"></div><div class="col-sm-2 form-group"></div></div></td></tr>');
    });
  });

  function removegen(id) {
    $('#removegen' + id).remove();
  }
</script>
@endpush