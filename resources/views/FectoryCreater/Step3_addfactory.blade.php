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

  select.form-control form-control-sm {
    width: 200px;
  }

  tbody,
  td,
  tfoot,
  th,
  thead,
  tr {
    border: none !important;
  }

  .selecttt {
    display: block !important;
    width: 185px !important;
  }

  td.forDesign {
    width: 40% !important;
    padding: 13px 6px !important;
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
    <section class="section">
      <div class="addbtn extra">
        <a href="{{url('FactoryCreater/step2')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step3']}} active anchor" href="#">Land & Building</a>
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
          <form id="formsubmit" action="{{url('FactoryCreater/Land_Building')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($landbulding->id) && $landbulding->id!=''?$landbulding->id:''}}">
            <div>
              <br>
              <div class="tabs">
                <h5>Land & Building:</h5>
                <div class="tab1">
                  <div class="row">
                    <div class="col-sm-3 form-group Select2Handle">
                      <label>Land Type*</lable>
                        <select name="land_type" class="form-select form-select-sm js-example-matcher-start" >
                          <option value="" selected disabled>Select</option>
                          @foreach($masterland as $val)
                          <option value="{{$val->id}}" {{isset($landbulding->land_type) && $landbulding->land_type==$val->id?'selected':''}}>{{$val->land_type}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Land Area(Sq. Ft.)*</lable>
                        <p><input type="number" class="form-control form-control-sm" id="landarea" name="land_area" placeholder="Land Area(Sq. Ft.)" value="{{isset($landbulding->land_area) && $landbulding->land_area!=''?$landbulding->land_area:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Open Area(Sq. Ft.)*</lable>
                        <p><input type="number" class="form-control form-control-sm" id="openarea" name="open_area" placeholder="Open Area(Sq. Ft.)" value="{{isset($landbulding->open_area) && $landbulding->open_area!=''?$landbulding->open_area:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Cover Area(Sq. Ft.)*</lable>
                        <p><input type="number" class="form-control form-control-sm" id="coverarea" name="cover_area" placeholder="Cover Area(Sq. Ft.)" value="{{isset($landbulding->cover_area) && $landbulding->cover_area!=''?$landbulding->cover_area:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Building Area(Sq. Ft.)</lable>
                        <p><input class="form-control form-control-sm" type="number" id="buildingarea" name="building_area" placeholder="Building Area(Sq. Ft.)" value="{{isset($landbulding->building_area) && $landbulding->building_area!=''?$landbulding->building_area:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-3 form-group">
                      <label>Building Type*</lable>
                        <p><input class="form-control form-control-sm" name="building_type" placeholder="Building Type" value="{{isset($landbulding->building_type) && $landbulding->building_type!=''?$landbulding->building_type:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Boundary Height(Sq. Ft.)*</lable>
                        <p><input class="form-control form-control-sm" type="number" name="boundary_height" placeholder="Boundary Height(Sq. Ft.)" value="{{isset($landbulding->boundary_height) && $landbulding->boundary_height!=''?$landbulding->boundary_height:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Boundary Width(Sq. Ft.)*</lable>
                        <p><input class="form-control form-control-sm" name="boundary_width" placeholder="Boundary Width(Sq. Ft.)" value="{{isset($landbulding->boundary_width) && $landbulding->boundary_width!=''?$landbulding->boundary_width:''}}" ></p>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label>Boundary Type*</lable>
                        <table class="table table-bordered" id="dynamic_field2">
                          @php
                          $i = 1;
                          @endphp
                          @if(count($landtype)>0)
                          @foreach($landtype as $val)
                          <tr id="rows{{$i}}">
                            <input class="form-control form-control-sm" type="hidden" name="boundaryID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                            <td class="forDesign">
                              <div class="field-wrap">
                                <select name="boundary_type[]" class="form-select form-select-sm js-example-matcher-start selecttt" >
                                  <option value="" selected disabled>Select</option>
                                  @foreach($boundarytype as $valss)
                                  <option value="{{$valss->id}}" {{isset($val->boundary_type) && $val->boundary_type==$valss->id?'selected':''}}>{{$valss->Boundary_Type}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </td>
                            <td>
                              <div class="field-wrap">
                                <input class="form-control form-control-sm" type="hidden" name="idd[]" value="{{$i}}">
                                <input class="form-control form-control-sm" type="file" autocomplete="off" class="form-control form-control-sm" name="attachement1" />
                                <div>{{substr(isset($val->attachement) && $val->attachement!=''?$val->attachement:'',13)}}</div>
                                <p><a href="{{url('storage/'.(isset($val->attachement) && $val->attachement!=''?$val->attachement:''))}}" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i></a></p>
                              </div>
                            </td>
                            @if($i==1)
                            <td><a href="javascript:;" id="add2" class="btn btn-success btn btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                            @else
                            <td><a href="javascript:;" onclick="removeFile('boundary_attachement',{{ $val->id}})" class="btn btn-danger btn btn-sm btn_remove">X</a></td>
                            @endif
                          </tr>
                          @php
                          $i++;
                          @endphp
                          @endforeach
                          @else
                          <tr>
                            <td class="forDesign">
                              <div class="field-wrap">
                                <select name="boundary_type[]" class="form-select form-select-sm js-example-matcher-start selecttt" >
                                  <option value="" selected disabled>Select</option>
                                  @foreach($boundarytype as $val)
                                  <option value="{{$val->id}}">{{$val->Boundary_Type}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </td>
                            <td>
                              <div class="field-wrap">
                                <input class="form-control form-control-sm" type="hidden" name="idd[]" value="0">
                                <input class="form-control form-control-sm" type="file" autocomplete="off" class="form-control form-control-sm" name="attachement0"  />
                              </div>
                            </td>
                            <td><a href="javascript:;" id="add2" class="btn btn-success btn btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                          </tr>
                          @endif
                        </table>
                    </div>
                    <div class="col-sm-3 form-group"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-3 form-group">
                      <label>Window*</lable>
                        <p><input class="form-control form-control-sm" name="window" placeholder="Window" value="{{isset($landbulding->window) && $landbulding->window!=''?$landbulding->window:''}}" ></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label>Gate*</lable>
                        <p><input class="form-control form-control-sm" name="gate" placeholder="Gate" value="{{isset($landbulding->gate) && $landbulding->gate!=''?$landbulding->gate:''}}" ></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label>Other*</lable>
                        <table class="table table-bordered" id="dynamic_field4">
                          <tr>
                            <td><a href="javascript:;" id="add4" class="btn btn-success btn btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                          </tr>
                          @php
                          $i = 1;
                          @endphp
                          @foreach($landother as $val)
                          <tr id="row{{$i}}">
                            <input class="form-control form-control-sm" type="hidden" name="otherID[]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                            <td>
                              <div class="field-wrap"><label style="display:flex;">Add Field Name Manually</label>
                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="add_field_name_manually[]" placeholder="Add Field Name Manually" value="{{isset($val->add_field_name_manually) && $val->add_field_name_manually!=''?$val->add_field_name_manually:''}}"  />
                              </div>
                              <br>
                              <div class="field-wrap"><label style="display:flex;">Enter Manually Details</label>
                                <input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="enter_manually_details[]" placeholder="Enter Manually Details" value="{{isset($val->enter_manually_details) && $val->enter_manually_details!=''?$val->enter_manually_details:''}}"  />
                              </div>
                            </td>
                            {{-- <td><a href="javascript:;" onclick="removeother({{$i}})" class="btn btn-danger btn btn-sm btn_remove">X</a></td> --}}
                          </tr>
                          @php
                          $i++;
                          @endphp
                          @endforeach
                        </table>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label for="State">Remark:</label>
                      <input type="text" name="remark" id="" value="{{isset($landbulding->remark) && $landbulding->remark!=''?$landbulding->remark:''}}" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks">
                    </div>
                  </div>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($landbulding->id) && $landbulding->id != ''?'none':'block'}}">Clear All</a>
                  <button type="submit" id="submitbtn" class="btn btn1 float-right">Submit & Next</button>
                </div>
              </div>
          </form>
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
  
    function removeFile(name, fileId) {
      $.ajax({
          url: 'remove-file_boundary/' + fileId + '/' + name,
          method: 'GET',
          success: function(response) {
              $('#' + name).remove();
              alert(response.message); // Show success message
              window.location.reload();
          },
          error: function(xhr, status, error) {
              alert('Error: ' + error); // Show error message
          }
      });
    }
  $(document).ready(function() {
    $("#landarea, #openarea, #coverarea, #buildingarea").on("input", function() {
      var landarea = parseInt($("#landarea").val());
      var openarea = parseInt($("#openarea").val());
      var coverarea = parseInt($("#coverarea").val());
      var buildingarea = parseInt($("#buildingarea").val());
      if (openarea > landarea) {
        $("#openarea").val("");
        alert("The Value of 'Open Area' cannot exceed the 'Land Area'.");
      } else if (coverarea > landarea) {
        $("#coverarea").val("");
        alert("The Value of 'Cover Area' cannot exceed the 'Land Area'.");
      } else if (buildingarea > landarea) {
        $("#buildingarea").val("");
        alert("The Value of 'Building Area' cannot exceed the 'Land Area'.");
      } else if (openarea + coverarea + buildingarea > landarea) {
        $("#buildingarea").val("");
        alert("The sum of 'Open Area', 'Cover Area' and 'Building Area' cannot exceed the 'Land Area'.");
      }
    });
  });

  $('#submitbtn').click(function() {
    var fields = $("input[required], select[required], textarea[required]");
    var allFieldsFilled = true;

    fields.each(function() {
      if ($(this).val().trim() === "") {
        alert("Please fill in all the required fields.");
        allFieldsFilled = false;
        return false;
      }
    });

    if (!allFieldsFilled) {
      return false;
    }

    var landarea = parseInt($("#landarea").val());
    var openarea = parseInt($("#openarea").val());
    var coverarea = parseInt($("#coverarea").val());
    var buildingarea = parseInt($("#buildingarea").val());

    if (openarea + coverarea + buildingarea != landarea) {
      alert("The sum of 'Open Area', 'Cover Area' and 'Building Area' is not equal to the 'Land Area'.");
      return false;
    } else {
      $('#formsubmit').submit();
    }
  });
</script>
<script>
  $(document).ready(function() {
    var landtypecount = parseInt('{{isset($landtypecount)?$landtypecount:1}}');
    var i = landtypecount;
    $('#add2').click(function() {
      i++;
      $('#dynamic_field2').append('<tr id="rows' + i + '"><td class="forDesign"><div class="field-wrap"><div class="field-wrap"><select name="boundary_type[]" class="form-select form-select-sm js-example-matcher-start selecttt required"><option value="" selected disabled>Select</option>@foreach($boundarytype as $val)<option value="{{$val->id}}">{{$val->Boundary_Type}}</option>@endforeach</select></div></td><td><div class="field-wrap"><input class="form-control form-control-sm" type="hidden" name="idd[]" value="' + i + '"><input class="form-control form-control-sm" type="file" autocomplete="off" required class="form-control form-control-sm" name="attachement' + i + '" /></div></td><td><a href="javascript:;" onclick="remove(' + i + ')" class="btn btn-danger btn btn-sm btn_remove">X</a></td></tr>');
      AppendSelect2();
    });
  });

  function remove(id) {
    $("#rows" + id).remove();
  }
</script>
<script>
  $(document).ready(function() {
    var landothercount = parseInt('{{isset($landothercount)?$landothercount:1}}');

    var i = landothercount;
    $('#add4').click(function() {
      i++;
      $('#dynamic_field4').append('<tr id="row' + i + '"><td><div class="field-wrap" ><label style="display:flex;">Add Field Name Manually</label><input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="add_field_name_manually[]" placeholder="Add Field Name Manually" required/></div><br><div class="field-wrap" ><label style="display:flex;">Enter Manually Details</label><input class="form-control form-control-sm" type="text" autocomplete="off" class="form-control form-control-sm" name="enter_manually_details[]" placeholder="Enter Manually Details" required/></div></td></div><td><a href="javascript:;" onclick="removeother(' + i + ')" class="btn btn-danger btn btn-sm btn_remove">X</a></td></tr>');
    });
  });

  function removeother(id) {
    $("#row" + id).remove();
  }
</script>
@endpush