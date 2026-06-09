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
    /* width: 100%; */
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

  .sub-capacty {
    border-bottom: 1px solid #8080805e;
  }

  .inner-div {
    border: 1px solid #8080805e;
    padding: 10px;
  }

  .remarkssss {
    margin-bottom: 0;
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
      <div class="addbtn extra">
        <a href="{{url('FactoryCreater/step9')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
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
              <a class="nav-link {{$formdata['step8']}} anchor" href="{{url('FactoryCreater/step8')}}">Office Asset</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step9']}} anchor" href="{{url('FactoryCreater/step9')}}">Power House</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{$formdata['step10']}} active anchor" href="#">Store</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="container">
          <form action="{{url('FactoryCreater/Store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="form-control form-control-sm" type="hidden" name="edit" value="{{isset($storee->factory_id) && $storee->factory_id!=''?$storee->factory_id:''}}">
            <div>
              <br>
              <div class="tabs">
                <h5>Store</h5><br>
                <div class="row">
                  <div class="col-sm-3 form-group">
                    <label>Total Rack*</lable>
                      <p><input type="number" class="form-control form-control-sm" name="Total_Rack" value="{{isset($storee->Total_Rack) && $storee->Total_Rack!=''?$storee->Total_Rack:''}}" required></p>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label> Rack Capacity*</lable>
                      <p><input type="number" class="form-control form-control-sm" id="RackCapacityFirst" name="Rack_Capacity" value="{{isset($storee->Rack_Capacity) && $storee->Rack_Capacity!=''?$storee->Rack_Capacity:''}}" required></p>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Total Bin*</lable>
                      <p><input type="number" class="form-control form-control-sm" name="Total_Bin" value="{{isset($storee->Total_Bin) && $storee->Total_Bin!=''?$storee->Total_Bin:''}}" required></p>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Total Bin Capacity*</lable>
                      <p><input type="number" class="form-control form-control-sm" name="Total_Bin_Capacity" value="{{isset($storee->Total_Bin_Capacity) && $storee->Total_Bin_Capacity!=''?$storee->Total_Bin_Capacity:''}}" required></p>
                  </div>
                </div>
                <div class="tab1">
                  <div class="row">
                    <div class="col-sm-3 form-group">
                      <label> Rack No.*</lable>
                        <p><input type="number" class="form-control form-control-sm" name="Rack_No" value="{{isset($storee->Rack_No) && $storee->Rack_No!=''?$storee->Rack_No:''}}" required></p>
                    </div>
                    <div class="col-sm-3 form-group">
                      <label> Rack Capacity*</lable>
                        <p><input type="number" class="form-control form-control-sm" style="margin-left:8px;" id="RackCapacitySecond" name="Rack_Capacities" value="{{isset($storee->Rack_Capacities) && $storee->Rack_Capacities!=''?$storee->Rack_Capacities:''}}" required></p>
                    </div>
                  </div>
                  <h6>Details:</h6>
                  <hr>
                  <div id="dynamic_field16">
                    @php
                    $i=1;
                    @endphp
                    @if(count($storesubrack)>0)
                    @foreach($storesubrack as $val)
                    <input class="form-control form-control-sm" type="hidden" name="storesubrackID[{{$i}}]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                    <div class="row" id="maindiv{{$i}}">
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-5 form-group">
                            <label> Sub Rack No.*</lable>
                              <p><input type="number" class="form-control form-control-sm" name="Sub_Rack_No[{{$i}}]" value="{{isset($val->Sub_Rack_No) && $val->Sub_Rack_No!=''?$val->Sub_Rack_No:''}}" required></p>
                          </div>
                          <div class="col-sm-6 form-group">
                            <label> Sub Rack Capacity*</lable>
                              <p><input type="number" class="form-control form-control-sm" name="Sub_Rack_Capacity[{{$i}}]" value="{{isset($val->Sub_Rack_Capacity) && $val->Sub_Rack_Capacity!=''?$val->Sub_Rack_Capacity:''}}" required></p>
                          </div>
                        </div>
                        <div id="dynamic_field{{$i}}">
                          @php
                          $j=1;
                          @endphp
                          @foreach($val->storebin as $storebin)
                          <input class="form-control form-control-sm" type="hidden" name="storebinID[{{$i}}][{{$j}}]" value="{{isset($storebin->id) && $storebin->id!=''?$storebin->id:''}}">
                          <div class="sub-capacty" id="remsub-capacty{{$i}}{{$j}}">
                            <div class="row">
                              <div class="col-sm-5 form-group">
                                <label> Bin No.*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Bin_No[{{$i}}][{{$j}}]" value="{{isset($storebin->Bin_No) && $storebin->Bin_No!=''?$storebin->Bin_No:''}}" required></p>
                              </div>
                              <div class="col-sm-5 form-group">
                                <label> Bin Capacity*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Bin_Capacity[{{$i}}][{{$j}}]" value="{{isset($storebin->Bin_Capacity) && $storebin->Bin_Capacity!=''?$storebin->Bin_Capacity:''}}" required></p>
                              </div>
                              @if($j==1)
                              <div class="col-sm-2 form-group">
                                <a href="javascript:;" name="add" id="BinCapacity{{$i}}" onclick="addBinCapacity(0,{{isset($storebinCount) && $storebinCount==0?1:$storebinCount+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              </div>
                              @else
                              <div class="col-sm-2 form-group">
                                <a href="javascript:;" onclick="removeBinCapacity({{$i}},{{$j}})" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a>
                              </div>
                              @endif
                            </div>
                            <div id="dynamic_field{{$i}}{{$j}}">
                              @php
                              $k=1;
                              @endphp
                              @foreach($storebin->storesubbin as $storesubbin)
                              <input class="form-control form-control-sm" type="hidden" name="storesubbinID[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($storesubbin->id) && $storesubbin->id!=''?$storesubbin->id:''}}">
                              <div class="row" id="remSub_Bin_No{{$j}}{{$k}}">
                                <div class="col-sm-5 form-group">
                                  <label>Sub Bin No.*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Bin_No[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($storesubbin->Sub_Bin_No) && $storesubbin->Sub_Bin_No!=''?$storesubbin->Sub_Bin_No:''}}" required></p>
                                </div>
                                <div class="col-sm-5 form-group">
                                  <label>Sub Bin Capacity*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Bin_Capacity[{{$i}}][{{$j}}][{{$k}}]" value="{{isset($storesubbin->Sub_Bin_Capacity) && $storesubbin->Sub_Bin_Capacity!=''?$storesubbin->Sub_Bin_Capacity:''}}" required></p>
                                </div>
                                @if($k==1)
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" name="add" id="Sub_Bin_No{{$i}}{{$j}}" onclick="addSub_Bin_No(0,0,{{isset($storesubbinCount) && $storesubbinCount==0?1:$storesubbinCount+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" name="add" id="Sub_Bin_No0" onclick="removeSub_Bin_No({{$i}},{{$j}},{{$k}})" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a>
                                </div>
                                @endif
                              </div>
                              @php
                              $k++;
                              @endphp
                              @endforeach
                            </div>
                          </div>
                          @php
                          $j++;
                          @endphp
                          @endforeach
                        </div>
                      </div>
                      @if($i==1)
                      <div class="col-sm-2">
                        <a href="javascript:;" id="mainbtnbtn" onclick="appendmaidiv({{isset($storesubrackCount) && $storesubrackCount==0?1:$storesubrackCount+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                      </div>
                      @else
                      <div class="col-sm-2">
                        <a href="javascript:;" onclick="removedmaidiv({{$i}})" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a>
                      </div>
                      @endif
                    </div>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                    @else
                    <div class="row">
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-5 form-group">
                            <label> Sub Rack No.*</lable>
                              <p><input type="number" class="form-control form-control-sm" name="Sub_Rack_No[0]" required></p>
                          </div>
                          <div class="col-sm-6 form-group">
                            <label> Sub Rack Capacity*</lable>
                              <p><input type="number" class="form-control form-control-sm" name="Sub_Rack_Capacity[0]" required></p>
                          </div>
                        </div>
                        <div id="dynamic_field0">
                          <div class="sub-capacty">
                            <div class="row">
                              <div class="col-sm-5 form-group">
                                <label> Bin No.*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Bin_No[0][0]" required></p>
                              </div>
                              <div class="col-sm-5 form-group">
                                <label> Bin Capacity*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Bin_Capacity[0][0]" required></p>
                              </div>
                              <div class="col-sm-2 form-group">
                                <a href="javascript:;" name="add" id="BinCapacity0" onclick="addBinCapacity(0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              </div>
                            </div>
                            <div id="dynamic_field00">
                              <div class="row">
                                <div class="col-sm-5 form-group">
                                  <label>Sub Bin No.*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Bin_No[0][0][0]" required></p>
                                </div>
                                <div class="col-sm-5 form-group">
                                  <label>Sub Bin Capacity*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Bin_Capacity[0][0][0]" required></p>
                                </div>
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" name="add" id="Sub_Bin_No00" onclick="addSub_Bin_No(0,0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <a href="javascript:;" id="mainbtnbtn" onclick="appendmaidiv(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <br>
              <br>
              <div class="tab1 remarkssss">
                <div class="">
                  <h5>Shelf Details</h5> <br>
                  <div class="main-sheilf" id="main-sheilf">
                    <input type="hidden" name="shelfID" value="{{isset($shelf->id) && $shelf->id!=''?$shelf->id:''}}">
                    <div id="outer-div-sherif">
                      <div class="row">
                        <div class="col-sm-5 form-group">
                          <label>Total Shelf*</lable>
                            <p><input type="number" class="form-control form-control-sm" name="Total_Shelf" value="{{isset($shelf->Total_Shelf) && $shelf->Total_Shelf!=''?$shelf->Total_Shelf:''}}" required></p>
                        </div>
                        <div class="col-sm-6 form-group">
                          <label> Total Shelf Capacity*</lable>
                            <p><input type="number" class="form-control form-control-sm" name="Total_Shelf_Capacity" value="{{isset($shelf->Total_Shelf_Capacity) && $shelf->Total_Shelf_Capacity!=''?$shelf->Total_Shelf_Capacity:''}}" required></p>
                        </div>
                      </div>
                      <div id="dynamic_field_shelf" class="div-shelf">
                        @php
                        $i = 1;
                        @endphp
                        @if(count($shelfno)>0)
                        @foreach($shelfno as $val)
                        <input type="hidden" name="shelfNoID[{{$i}}]" value="{{isset($val->id) && $val->id!=''?$val->id:''}}">
                        <div class="row" id="removesubshelf{{$i}}">
                          <div class="col-sm-10  inner-div">
                            <div class="row">
                              <div class="col-sm-6 form-group">
                                <label>Shelf No.*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Shelf_No[{{$i}}]" value="{{isset($val->Shelf_No) && $val->Shelf_No!=''?$val->Shelf_No:''}}" required></p>
                              </div>
                              <div class="col-sm-6 form-group">
                                <label> Shelf Capacity*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Shelf_Capacity[{{$i}}]" value="{{isset($val->Shelf_Capacity) && $val->Shelf_Capacity!=''?$val->Shelf_Capacity:''}}" required></p>
                              </div>
                            </div>
                            <div id="dynamic_field_ravi{{$i}}">
                              @php
                              $j = 1;
                              @endphp
                              @foreach($val->subshelfsss as $subshelfsss)
                              <input type="hidden" name="SubshelfID[{{$i}}][{{$j}}]" value="{{isset($subshelfsss->id) && $subshelfsss->id!=''?$subshelfsss->id:''}}">
                              <div class="row" id="removeshelf{{$i}}{{$j}}">
                                <div class="col-sm-6 form-group">
                                  <label>Sub Shelf No.*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Shelf_No[{{$i}}][{{$j}}]" value="{{isset($subshelfsss->Sub_Shelf_No) && $subshelfsss->Sub_Shelf_No!=''?$subshelfsss->Sub_Shelf_No:''}}" required></p>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Sub Shelf Capacity*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Shelf_Capacity[{{$i}}][{{$j}}]" value="{{isset($subshelfsss->Sub_Shelf_Capacity) && $subshelfsss->Sub_Shelf_Capacity!=''?$subshelfsss->Sub_Shelf_Capacity:''}}" required></p>
                                </div>
                                @if($j==1)
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" id="addsubshelf0" onclick="addsubshelf({{$i}},{{isset($subshelfsssCount) && $subshelfsssCount==0?1:$subshelfsssCount+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                                @else
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" onclick="remsubshelf({{$i}},{{$j}})" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a>
                                </div>
                                @endif
                              </div>
                              @php
                              $j++;
                              @endphp
                              @endforeach
                            </div>
                          </div>
                          @if($i==1)
                          <div class="col-sm-2 form-group">
                            <a href="javascript:;" id="addsubshelf_details" onclick="addsubshelf_details({{isset($shelfnoCount) && $shelfnoCount==0?1:$shelfnoCount+1}})" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                          </div>
                          @else
                          <div class="col-sm-2 form-group">
                            <a href="javascript:;" onclick="removesubshelf_details({{$i}})" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a>
                          </div>
                          @endif
                        </div>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                        @else
                        <div class="row">
                          <div class="col-sm-10  inner-div">
                            <div class="row">
                              <div class="col-sm-6 form-group">
                                <label>Shelf No.*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Shelf_No[0]" required></p>
                              </div>
                              <div class="col-sm-6 form-group">
                                <label> Shelf Capacity*</lable>
                                  <p><input type="number" class="form-control form-control-sm" name="Shelf_Capacity[0]" required></p>
                              </div>
                            </div>
                            <div id="dynamic_field_ravi0">
                              <div class="row">
                                <div class="col-sm-6 form-group">
                                  <label>Sub Shelf No.*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Shelf_No[0][0]" required></p>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Sub Shelf Capacity*</lable>
                                    <p><input type="number" class="form-control form-control-sm" name="Sub_Shelf_Capacity[0][0]" required></p>
                                </div>
                                <div class="col-sm-2 form-group">
                                  <a href="javascript:;" id="addsubshelf0" onclick="addsubshelf(0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2 form-group">
                            <a href="javascript:;" id="addsubshelf_details" onclick="addsubshelf_details(1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <br>
              <div class="row">
                <div class="col-sm-12 form-group">
                  <label for="State">Remark:</label>
                  <textarea name="Remark" id="" cols="30" rows="3" class="form-control form-control-sm">{{isset($shelf->Remark) && $shelf->Remark!=''?$shelf->Remark:''}}</textarea>
                </div>
              </div>
              <div style="overflow:auto;">
                <div style="float:right;">
                  <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>
                  <a href="" class="btn btn1 float-right" style="margin: 5px; display:{{isset($storee->id) && $storee->id != ''?'none':'block'}}">Clear All</a>
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
  $(document).ready(function() {
    $("#RackCapacityFirst, #RackCapacitySecond").on("input", function() {
      var first = parseInt($("#RackCapacityFirst").val());
      var second = parseInt($("#RackCapacitySecond").val());

      if (second > first) {
        $("#RackCapacitySecond").val("");
        alert("Value entered Exceeds Field Rack Capacity!");
      }
    });
  });
</script>
<script>
  function addSub_Bin_No(i, j, k) {
    var a = '<div class="row" id="remSub_Bin_No' + i + '' + j + '"><div class="col-sm-5 form-group"><label>Sub Bin No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_No[' + i + '][' + j + '][' + k + ']" required></p></div><div class="col-sm-5 form-group"><label>Sub Bin Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_Capacity[' + i + '][' + j + '][' + k + ']" required></p></div><div class="col-sm-2 form-group"><a href="javascript:;" name="add" id="Sub_Bin_No0" onclick="removeSub_Bin_No(' + i + ',' + j + ',' + k + ')" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a></div></div>';
    $("#dynamic_field" + i + '' + j).append(a);
    k = 1 + k;
    $("#Sub_Bin_No" + i + "" + j).attr("onclick", "addSub_Bin_No(" + i + "," + j + "," + k + ")");
  }

  function removeSub_Bin_No(i, j) {
    $("#remSub_Bin_No" + i + '' + j).remove();
  }

  function addBinCapacity(i, j) {
    var a = '<div class="sub-capacty" id="remsub-capacty' + i + '' + j + '"><div class="row"><div class="col-sm-5 form-group"><label> Bin No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Bin_No[' + i + '][' + j + ']" required></p></div><div class="col-sm-5 form-group"><label> Bin Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Bin_Capacity[' + i + '][' + j + ']" required></p></div><div class="col-sm-2 form-group"><a href="javascript:;"  onclick="removeBinCapacity(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a></div></div><div id="dynamic_field' + i + '' + j + '"><div class="row"><div class="col-sm-5 form-group"><label>Sub Bin No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_No[' + i + '][' + j + '][0]" required></p></div><div class="col-sm-5 form-group"><label>Sub Bin Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_Capacity[' + i + '][' + j + '][0]" required></p></div><div class="col-sm-2 form-group"><a href="javascript:;" name="add" id="Sub_Bin_No' + i + '' + j + '" onclick="addSub_Bin_No(' + i + ',' + j + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></div></div></div></div>';
    $("#dynamic_field" + i).append(a);
    j = j + 1;
    $("#BinCapacity" + i).attr("onclick", 'addBinCapacity(' + i + ',' + j + ')');
  }

  function removeBinCapacity(i, j) {
    $("#remsub-capacty" + i + '' + j).remove();
  }

  function appendmaidiv(i) {
    var a = '<div class="row" id="maindiv' + i + '"><div class="col-sm-8"><div class="row"><div class="col-sm-5 form-group"><label> Sub Rack No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Rack_No[' + i + ']" required></p></div><div class="col-sm-6 form-group"><label> Sub Rack Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Rack_Capacity[' + i + ']" required></p></div></div><div id="dynamic_field' + i + '"><div class="sub-capacty"><div class="row"><div class="col-sm-5 form-group"><label> Bin No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Bin_No[' + i + '][0]" required></p></div><div class="col-sm-5 form-group"><label> Bin Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Bin_Capacity[' + i + '][0]" required></p></div><div class="col-sm-2 form-group"><a href="javascript:;" name="add" id="BinCapacity' + i + '" onclick="addBinCapacity(' + i + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></div></div><div id="dynamic_field' + i + '0"><div class="row"><div class="col-sm-5 form-group"><label>Sub Bin No.*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_No[' + i + '][0][0]" required></p></div><div class="col-sm-5 form-group"><label>Sub Bin Capacity*</lable><p><input type="number" class="form-control form-control-sm"  name="Sub_Bin_Capacity[' + i + '][0][0]" required></p></div><div class="col-sm-2 form-group"><a href="javascript:;" name="add" id="Sub_Bin_No' + i + '0" onclick="addSub_Bin_No(' + i + ',0,1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a></div></div></div></div></div></div><div class="col-sm-2"><a href="javascript:;"  onclick="removedmaidiv(' + i + ')" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a></div></div>';
    i++;
    $("#dynamic_field16").append(a);
    $("#mainbtnbtn").attr("onclick", 'appendmaidiv(' + i + ')');
  }

  function removedmaidiv(i) {
    $("#maindiv" + i).remove();
  }

  function addsubshelf(i, j) {
    var a = '<div class="row" id="removeshelf' + i + '' + j + '"> <div class="col-sm-6 form-group"> <label>Sub Shelf No.*</lable> <p><input type="number" class="form-control form-control-sm"  name="Sub_Shelf_No[' + i + '][' + j + ']" required></p></div><div class="col-sm-4 form-group"> <label>Sub Shelf Capacity*</lable> <p><input type="number" class="form-control form-control-sm"  name="Sub_Shelf_Capacity[' + i + '][' + j + ']" required></p></div><div class="col-sm-2 form-group"> <a href="javascript:;" onclick="remsubshelf(' + i + ',' + j + ')" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a> </div></div>';
    $("#dynamic_field_ravi" + i).append(a);
    $("#addsubshelf" + i).attr("onclick", "addsubshelf(" + i + "," + (j + 1) + ")");
  }

  function remsubshelf(i, j) {
    $("#removeshelf" + i + '' + j).remove();
  }

  function addsubshelf_details(i) {
    var a = '<div class=" row mt-5" id="removesubshelf' + i + '"><div class="col-sm-10 inner-div"> <div class="row"> <div class="col-sm-6 form-group"> <label>Shelf No.*</lable> <p><input type="number" class="form-control form-control-sm"  name="Shelf_No[' + i + ']" required></p></div><div class="col-sm-6 form-group"> <label> Shelf Capacity*</lable> <p><input type="number" class="form-control form-control-sm"  name="Shelf_Capacity[' + i + ']" required></p></div></div><div id="dynamic_field_ravi' + i + '"> <div class="row"> <div class="col-sm-6 form-group"> <label>Sub Shelf No.*</lable> <p><input type="number" class="form-control form-control-sm"  name="Sub_Shelf_No[' + i + '][0]" required></p></div><div class="col-sm-4 form-group"> <label>Sub Shelf Capacity*</lable> <p><input type="number" class="form-control form-control-sm"  name="Sub_Shelf_Capacity[' + i + '][0]" required></p></div><div class="col-sm-2 form-group"> <a href="javascript:;" id="addsubshelf' + i + '" onclick="addsubshelf(' + i + ',1)" class="btn btn-success btn-sm mt-4"><i class="fa fa-plus" aria-hidden="true"></i></a> </div></div></div></div><div class="col-sm-2 form-group"> <a href="javascript:;"  onclick="removesubshelf_details(' + i + ')" class="btn btn-danger btn-sm mt-4"><i class="fa fa-minus" aria-hidden="true"></i></a></div></div>';
    $("#dynamic_field_shelf").append(a);
    i = i + 1;
    $("#addsubshelf_details").attr("onclick", "addsubshelf_details(" + i + ")")
  }

  function removesubshelf_details(i) {
    $("#removesubshelf" + i).remove();
  }
</script>
@endpush