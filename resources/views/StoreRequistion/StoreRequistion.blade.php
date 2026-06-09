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
   .downloadfile {
   display: flex;
   }
   .downloadfile div {
   margin: 0px 20px;
   }
   .downloadfile i.fa.fa-remove {
   color: red;
   }
   div#adaaishhhh {
   margin-left: 10px;
   margin-bottom: 20px;
   width: 98.5%;
   }
   input.form-control.form-control-sm {
   margin-top: 10px;
   }
   hr {
   width: 99% !important;
   }
   div#adaais {
   margin-left: 10px;
   margin-bottom: 20px;
   }
   div#\a main_btn_uddhan {
   display: flex;
   justify-content: flex-start;
   align-items: center;
   align-content: center;
   }
   table#ssef {
   border: 1px solid;
   width: 50%;
   }
   tr.jaafgg td {
   padding: 10px !important;
   }
   tr.jaafgg {
   border-bottom: 1px solid !important;
   }
   .rm_tabe {
   display: flex;
   }
   div#lkjhhdggdg {
   margin-top: 40px;
   }
   table#ssef td {
   padding-left: 10px;
   padding-top: 10px;
   padding-bottom: 10px;
   }
   button#diraj-button {
   background: transparent;
   border: 1px solid;
   }
   .tabtbs {
   display: flex;
   }
   .tabtbs input {margin-left: 10px;}
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
                <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <h5>Store Requistion Details</h5>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Inputer Name : {{auth()->user()->name}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('StoreRequistion/StoreRequistionList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('StoreRequistion/StoreRequistionList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

               <div class="col-xl-12 col-md-12 col-sm-12 border">
                  <form action="{{url('StoreRequistion/AddStoreRequistion')}}" method="POST">
                     @csrf
                     <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                     <div class="row">
                        {{--
                        <div class="col-sm-3 form-group">
                           <label>
                           Work Order No.
                           </label>
                           <select name="Work_Order_No" class="form-select form-select-sm">
                              <option value="" selected disabled>Select</option>
                              <option value="Test" {{isset($edit->Work_Order_No) && $edit->Work_Order_No=='Test'?'selected':''}}>Test</option>
                           </select>
                        </div>
                        --}}
                        <div class="col-sm-3 form-group">
                           <label>
                           Manufacturing Unit*
                           </label>
                           <select name="Manufacturing_Unit" id="Manunit" class="form-select form-select-sm js-example-matcher-start" required>
                              <option value="" selected disabled>Select</option>
                              @foreach($Manufacturing_Unit as $val)
                              <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) && $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-sm-3 form-group">
                           <label>
                           Plant Name*
                           </label>
                           <select name="Plant_Name" id="plan_uni_id" class="form-select form-select-sm js-example-matcher-start" required>
                              <option value="" selected disabled>Select</option>
                              {{-- @foreach($Plant_Name as $val)
                              <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                              @endforeach --}}
                              @foreach($Plant_Name as $val)
                              <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-sm-2 form-group">
                           <label>
                           Organization Name*
                           </label>
                           <div class="field-wrap">
                              <input class="form-control form-control-sm" type="text" id="org_name" value="{{isset($edit->organisation) && $edit->organisation!=''?$edit->organisation:''}}" name="Organization_Name" readonly value="" required>
                              <input class="form-control form-control-sm" value="{{isset($edit->Organization_Name) && $edit->Organization_Name!=''?$edit->Organization_Name:''}}" type="hidden" id="org_id" name="Organization_id" readonly value="">
                           </div>
                          
                        </div>
                        <div class="col-sm-2 form-group">
                           <label>
                           Godown Name*
                           </label>
                           <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>
                              <option value="" selected disabled>Select</option>
                              @foreach($Godown_Name as $val)
                              <option value="{{$val->id}}" {{isset($edit->Godown_Name) && $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-sm-2 form-group">
                           <label>
                           Req Type
                           </label>
                              @if(!isset($edit->req_type))
                              <select  name="req_type" id="req_type" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                                 <option value="" selected>Select</option>
                                 <option value="Normal">Normal</option>
                                 <option value="Additional">Additional</option>
                              </select>
                              @else
                                 <select name="req_type_val" disabled id="req_type" class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" {{ !isset($edit->req_type) ? 'selected' : '' }}>Select</option>
                                    <option value="Normal" {{ isset($edit->req_type) && $edit->req_type == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Additional" {{ isset($edit->req_type) && $edit->req_type == 'Additional' ? 'selected' : '' }}>Additional</option>
                                 </select>
                                 <input type="hidden" name="req_type" value="{{$edit->req_type}}" >
                              @endif
                             
                        </div>
                        @if(!isset($edit) || (isset($edit) && $edit->req_type != 'Additional'))
                        <div class="col-sm-3 form-group" id="finishedgooddiv">
                           <label>
                              Finished Good(FG)*
                              </lable>
                              <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                                 <option value="" selected disabled>Select</option>
                                 @foreach($Raw_Material as $val)
                                 <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                                 @endforeach
                              </select>
                        </div>
                        <div class="col-sm-3 form-group" id="hsncodediv">
                        <label>HSN Code*</label>
                        <div class="field-wrap">
                        <input readonly class="form-control form-control-sm" type="number" name="HSN_Code" id="HSNCode" placeholder="HSN Code" value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
                        </div>
                        </div>
                        <div class="col-sm-3 form-group" id="uomdiv">
                           <label>UOM</label>
                           <div class="field-wrap">
                              <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
                              {{--
                              <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required readonly>
                                 <option value="" selected disabled>Select</option>
                                 @foreach($UOM as $val)
                                 <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                                 @endforeach
                              </select>
                              --}}
                           </div>
                        </div>
                        @endif
                     </div>
                     <br>
                     <div class="table-responsive">
                        <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                           <thead>
                              <tr>
                                 <th class="th-sm" id="slno">SL No.</th>
                                 <th class="th-sm">
                                    @if(!isset($edit))<input type="checkbox" id="checkAll"> @endif Material Name
                                 </th>
                                 <th class="th-sm">HSN Code</th>
                                 <th class="th-sm">UOM</th>
                                 <th class="th-sm">QTY</th>
                              </tr>
                           </thead>
                           <tbody>
                              @php
                              $i=1;
                              @endphp
                              @foreach($Materials as $key=>$MaterialVal)
                              <tr>
                                 <td>{{$key+1}}</td>
                                 <td>
                                    <div class="tabtbs">
                                       <input id="check{{$MaterialVal->Material_id??0}}" checked  type="checkbox" name="MaterialID[]" class="" value="{{$MaterialVal->Material_id??0}}" onchange="changedata('{{$MaterialVal->Material_id??0}}',1)">
                                       <input readonly type="text" name="Material_Name[]" class="form-control form-control-sm" value="{{isset($MaterialVal->Material_Name) && $MaterialVal->Material_Name!=''?$MaterialVal->Material_Name:''}}">
                                       <input type="hidden" name="Material_id[]" class="form-control form-control-sm" value="{{$MaterialVal->Material_id??0}}" id="Material_id{{$MaterialVal->Material_id??0}}">
                                    </div>
                                 </td>
                                 <td>
                                    <input readonly type="text" name="HSN_Code_Second[]" class="form-control form-control-sm" value="{{isset($MaterialVal->HSN_Code_Second) && $MaterialVal->HSN_Code_Second!=''?$MaterialVal->HSN_Code_Second:''}}">
                                 </td>
                                 <td>
                                    <div class="field-wrap">
                                       <input readonly type="text" name="UOM_Second[]" class="form-control form-control-sm" value="{{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second!=''?$MaterialVal->UOM_Second:''}}">
                                       {{--
                                       <select disabled name="UOM_Second[]" class="form-select form-select-sm js-example-matcher-start" required>
                                          <option value="" selected disabled>Select</option>
                                          @foreach($UOM as $value)
                                          <option value="{{$value->id}}" {{isset($MaterialVal->UOM_Second) && $MaterialVal->UOM_Second==$value->id?'selected':''}}>{{$value->UOMs}}</option>
                                          @endforeach
                                       </select>
                                       --}}
                                    </div>
                                 </td>
                                 <td>
                                    <input type="text" name="QTY[]" id="qty{{$MaterialVal->Material_id??0}}" onchange="changedata('{{$MaterialVal->Material_id??0}}')" class="form-control form-control-sm insdfsdfd" value="{{isset($MaterialVal->QTY) && $MaterialVal->QTY!=''?$MaterialVal->QTY:''}}" inputmode="decimal" pattern="^\d*\.?\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')">
                                 </td>
                              </tr>
                              @php
                              $i++;
                              @endphp
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-sm-8 form-group"></div>
                        <div class="col-sm-4 form-group">
                           <label for="State">Remarks:</label>
                           <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
                        </div>
                     </div>
                     <div style="overflow:auto;">
                        <div class="somras">
                           {{-- <button type="button" id="draft" class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button> --}}
                           <a href="" class="btn btn1 float-right" style="margin: 5px; display: {{isset($edit->id) && $edit->id != ''?'none':'block'}}">Clear All</a>
                           <button type="submit" id="submitBtn" class="btn btn1 float-right" style="margin: 5px;">Submit</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>

      </section>
   </div>
</div>

@endsection
@push('custom-scripts')

<script>
   // Add global error handler for debugging
   $(document).ajaxError(function(event, xhr, settings, thrownError) {
      console.error('AJAX Error:', {
         url: settings.url,
         status: xhr.status,
         statusText: xhr.statusText,
         responseText: xhr.responseText,
         thrownError: thrownError
      });
   });
   
   $(document).ready(function() {
       console.log('Store Requisition page loaded');
       
       $('#Manunit').change(function() {
           $('#org_name').val('');
           var ManunitId = $(this).val();

           if (ManunitId) {
               $.ajax({
                   url: "{{url('PPFinishedGood/get-plantnamedetails')}}" + '/' + ManunitId,
                   type: 'GET',
                   data: {
                       ManunitId : ManunitId
                     },
                   success: function(response) {
                       $('#plan_uni_id').empty();
                       $('#plan_uni_id').append('<option value="" selected disabled>Select</option>');
                       $.each(response, function(index, plantdetails) {
                           var option = $('<option>');
                           option.val(plantdetails.id);
                           option.text(plantdetails.spname);
                           $('#plan_uni_id').append(option);
                       });
                   }
               });
           }
       });
   });
   $(document).ready(function() {
       $('#plan_uni_id').change(function() {

           var plantId = $(this).val();

           if (plantId) {
               $.ajax({
                   url: "{{url('PPFinishedGood/get-orgnames')}}" + '/' + plantId,
                   type: 'GET',
                   data: {
                       plantId : plantId
                     },
                   success: function(response) {
                       $.each(response, function(index, plantdetails) {
                           $("#org_name").val(plantdetails.organisation);
                           $("#org_id").val(plantdetails.orgid);
                       });
                   }
               });
           }
       });
   });
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
       activeclass(22, 1);
   });
</script>
<script>
      document.getElementById('checkAll').addEventListener('click', function() {
         var checkboxes = document.querySelectorAll('.material-checkbox');
         for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
            changedata(checkbox.value, 1); // Call the changedata function to update the state
         }
      });
   $('#RawMaterial,#req_type').on('change', function() {
       var MaterialId = $('#RawMaterial').val();
       var ptypeValue = $('#req_type').val();
       
       console.log('Material/Req Type changed:', {
          MaterialId: MaterialId,
          ptypeValue: ptypeValue,
          RawMaterialExists: $('#RawMaterial').length > 0,
          req_typeExists: $('#req_type').length > 0
       });
       
       if(MaterialId=='')
       {
           console.log('No material selected, returning');
           return false;
       }

   $.ajax({
   url: "{{url('RawMaterial/MaterialData')}}/" + encodeURIComponent(MaterialId),
   type: 'GET',
   data: {
       MaterialId: MaterialId
   },
   success: function(data) {
       console.log('Material data loaded successfully:', data);
       if(data && data.data) {
           $('#HSNCode').val(data.data.HSN_Code || '');
           $('#uom').val(data.data.UOM || '').change();
       }
   },
   error: function(xhr, status, error) {
       console.error('Error loading material data:', error);
       console.error('Response:', xhr.responseText);
       alert('Error loading material data. Please try again.');
   }
   });

   $.ajax({
   url: "{{url('orderRequirement/MaterialData')}}/" + encodeURIComponent(MaterialId),
   type: 'GET',
   data: {
       MaterialId: MaterialId
   },
   success: function(data) {
       console.log('Order requirement data loaded successfully:', data);
         if (ptypeValue == "Normal") {
               $('#slno').show();
               $('#finishedgooddiv').show();
               $('#hsncodediv').show();
               $('#uomdiv').show();
               $('#checkAll').show();
               var table = $('#Tabledata');
                  if ($.fn.DataTable.isDataTable(table)) {
                     table.DataTable().destroy(); // Destroy the DataTable if it exists
                  }
                  
           
               table.find('tbody').empty();
               var Total = 0;
               console.log('Processing normal material data:', data.data);
               if(data && data.data && Array.isArray(data.data)) {
                   for (var i = 0; i < data.data.length; i++) {
                         var rowData = data.data[i];
                         if(rowData && rowData.RawMaterial) {
                             var materialName = (rowData.RawMaterial.matname || '').replace(/'/g, "&#39;").replace(/"/g, "&quot;");
                             var hsnCode = rowData.HSN_Code_Second || '';
                             var uom = rowData.UOM || '';
                             
                             var newRow = '<tr>' +
                                '<td>' + (i + 1) + '</td>' +
                                '<td><div class="tabtbs"><input id="check' + rowData.RawMaterial.id + '" type="checkbox" name="MaterialID[]" class="material-checkbox" value="' + rowData.RawMaterial.id + '" onchange="changedata(' + rowData.RawMaterial.id + ',1)"><input readonly type="text" name="Material_Name[]" class="form-control form-control-sm" value="' + materialName + '"><input type="hidden" name="Material_id[]" class="form-control form-control-sm" value="' + rowData.RawMaterial.id + '"></div></td>' +
                                '<td><input readonly type="text" name="HSN_Code_Second[]" class="form-control form-control-sm" value="' + hsnCode + '"></td>' +
                                '<td><input readonly type="text" name="UOM_Second[]" class="form-control form-control-sm" value="' + uom + '"></td>' +
                                '<td><input type="text" name="QTY[]" id="qty' + rowData.RawMaterial.id + '" onchange="changedata(' + rowData.RawMaterial.id + ')" class="form-control form-control-sm insdfsdfd" value="" pattern="^\\d*\\.?\\d*$" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*?)\\..*/g, \'$1\')"></td>' +
                                '</tr>';
                             table.find('tbody').append(newRow);
                         }
                   }
               }
               // table.DataTable({
               //       "ordering": false
               // });
      }
      var index = 0;

         if (ptypeValue == "Additional") {
            $('#slno').hide();
            $('#finishedgooddiv').hide();
            $('#hsncodediv').hide();
            $('#uomdiv').hide();
            $('#checkAll').hide();
            removeRequiredFields();
            var table = $('#Tabledata');
            if ($.fn.DataTable.isDataTable(table)) {
               table.DataTable().destroy();
            }
            table.find('tbody').empty();

            function appendRow(idx) {
               var removeButton = idx > 0 ? '<a href="javascript:;" class="btn btn-danger btn-sm mt-4 productremove" data-index="' + idx + '"><i class="fa fa-minus" aria-hidden="true"></i></a>' : '';
               var materialOptions = '<option value="" selected disabled>Select</option>';
               
               // Build material options safely
               var materialData = [
                   @foreach($Materialdetails as $val)
                   {
                       id: "{{$val->id}}",
                       name: @json($val->matname)
                   }@if(!$loop->last),@endif
                   @endforeach
               ];
               
               for(var m = 0; m < materialData.length; m++) {
                   var material = materialData[m];
                   materialOptions += '<option value="' + material.id + '" data-matname="' + material.name.replace(/"/g, '&quot;') + '">' + material.name.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</option>';
               }
               
               var newRow = '<tr>' +
                     '<td class="w-25"><select name="Material_id[]" onchange="Assetunit(' + idx + ');" class="form-select form-select-sm js-example-matcher-start" id="Material_' + idx + '" required>' + materialOptions + '</select><input type="hidden" name="Material_Name[]" id="mat_val_id_' + idx + '" class="form-control form-control-sm" readonly></td>' +
                     '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + idx + '" class="form-control form-control-sm" value=""></td>' +
                     '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + idx + '" class="form-control form-control-sm" value=""></td>' +
                     '<td><input type="text" name="QTY[]" id="qty_' + idx + '" class="form-control form-control-sm" value="" ' +
                        'inputmode="decimal" pattern="^\\d*\\.?\\d*$" ' +
                        'oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*?)\\..*/g, \'$1\');">' +
                     '</td>' +
                     '<td><a href="javascript:;" class="btn btn-success btn-sm mt-4 productappend mr-1" data-index="' + idx + '"><i class="fa fa-plus" aria-hidden="true"></i></a>' +
                     removeButton + '</td>' +
                     '</tr>';

               table.find('tbody').append(newRow);
               
               // Initialize Select2 with proper configuration
               $('#Material_' + idx).select2({
                   placeholder: "Select Material",
                   allowClear: true,
                   width: '100%'
               });
            }

            appendRow(index);

            $(document).on('click', '.productappend', function() {
               index++;
               appendRow(index);
            });

            $(document).on('click', '.productremove', function() {
               $(this).closest('tr').remove();

               // Recalculate indices after removing a row
               index = 0;
               table.find('tbody tr').each(function(i) {
                     var row = $(this);
                     row.find('.productappend').attr('data-index', i);
                     row.find('.productremove').attr('data-index', i);
                     row.find('[name="Material_id[]"]').attr('id', 'Material_' + i).attr('onchange', 'Assetunit(' + i + ')');
                     row.find('[name="HSN_Code_Second[]"]').attr('id', 'HSN_Code_Second_' + i);
                     row.find('[name="UOM_Second[]"]').attr('id', 'UOM_Second_' + i);
                     row.find('[name="QTY[]"]').attr('id', 'qty_' + i);

                     if (i === 0) {
                        row.find('.productremove').remove(); // Remove the remove button for the first row
                     }
                     index = i;
               });
            });

            // Initialize DataTable
            table.DataTable({
               "ordering": false
            });
         }
   },
   error: function(xhr, status, error) {
       console.error('Error loading order requirement data:', error);
       console.error('Response:', xhr.responseText);
       alert('Error loading material list. Please try again.');
   }
   });
   });
   
   // Initialize Select2 for existing elements with proper accessibility settings
   $(document).ready(function() {
       $('.js-example-matcher-start').select2({
           placeholder: "Select an option",
           allowClear: true,
           width: '100%',
           dropdownAutoWidth: true
       });
   });
   
   //     rawmaterialdata()
   // });
   // $(document).ready(function(){
   //     rawmaterialdata()
   // });
</script>
<script>
   function removeRequiredFields() {
        $('#RawMaterial').removeAttr('required');
        $('#HSNCode').removeAttr('required');
        $('#uom').removeAttr('required');
    }
   function Assetunit(i) {
      var selectedOption = $('#Material_' + i + ' option:selected');
      var matName = selectedOption.data('matname') || selectedOption.text();
      
      // Safely set the material name
      if(matName) {
         $('#mat_val_id_' + i).val(matName);
      }

      var id = "#Material_" + i;
      var AssetId = $(id).val();
      
      if(!AssetId || AssetId == '') {
         console.warn('No Asset ID selected for index:', i);
         // Clear the fields if no material selected
         $('#HSN_Code_Second_' + i).val('');
         $('#UOM_Second_' + i).val('');
         return;
      }
      
      console.log('Loading material data for Asset ID:', AssetId, 'at index:', i);
      
         $.ajax({
                  url: "{{url('RawMaterial/MaterialData')}}/" + encodeURIComponent(AssetId),
                  type: 'GET',
                  data: {
                     AssetId: AssetId
                  },
                  success: function(response) {
                     console.log('Asset material data loaded successfully:', response);
                     if(response && response.data) {
                        $('#HSN_Code_Second_' + i).val(response.data.HSN_Code || '');
                        $('#UOM_Second_' + i).val(response.data.UOM || '');
                     } else if(response && (response.HSN_Code || response.UOM)) {
                        // Handle different response formats
                        $('#HSN_Code_Second_' + i).val(response.HSN_Code || '');
                        $('#UOM_Second_' + i).val(response.UOM || '');
                     } else {
                        console.warn('Invalid response format:', response);
                     }
                  },
                  error: function(xhr, status, error) {
                     console.error('Error loading asset material data:', error);
                     console.error('Asset ID:', AssetId, 'Index:', i);
                     console.error('Response:', xhr.responseText);
                     
                     // Clear fields on error
                     $('#HSN_Code_Second_' + i).val('');
                     $('#UOM_Second_' + i).val('');
                     
                     alert('Error loading material details. Please try again.');
                  }
         });

   }
   function changedata(id,type=0)
   {
       value=$("#qty"+id).val()

      if(type==1)
      {
           if($("#check"+id).prop('checked')==true)
           {
               $("#qty"+id).attr('required',true)
           }
           else{
               $("#qty"+id).attr('required',false)
               $("#qty"+id).val('')
           }

      }
      else{
           if(value>0)
           {
               $("#check"+id).prop('checked',true);
           }
           else{
               $("#check"+id).prop('checked',false);
           }
      }
   }
   $('#submitBtn').on('click',function(){
       $('select[name^="UOM"]').prop('disabled', true);
           var favorite = [];
           $.each($("input[name='QTY[]']"), function(){
               value=parseFloat($(this).val())
               if(value>0)
               {
                   favorite.push(value);
               }
           });
           var MaterialID=[]
           $.each($("input[name='MaterialID[]']:checked"), function(){
               value=parseInt($(this).val())
               if(value>0)
               {
                   MaterialID.push(value);
               }
           });

           if(favorite.length<1)
           {
               alert('Please Choose any material first and enter quantity')
               return false
           }
           for (x in MaterialID)
           {
               qty=$("#qty"+MaterialID[x]).val();
               if(qty=='' || parseInt(qty)<0)
               {
                   alert('Please enter quantity')
                   return false
               }
           }
           // if(favorite.length!=MaterialID.length)
           // {
           //     alert('Please enter quantity')
           //     return false
           // }
           var Material_id = [];
           $.each($("input[name='MaterialID[]']"), function(){
               Material_id.push(parseInt($(this).val()));
           });
           for (x in Material_id)
           {
               $("#check"+Material_id[x]).attr('name','MaterialID['+x+']')

           }
           $('select[name^="UOM"]').prop('disabled', true);

   });
</script>
@endpush
