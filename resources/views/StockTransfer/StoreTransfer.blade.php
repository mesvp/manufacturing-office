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
      font-size: 17px;
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
      @if(session('error'))
         <div class="alert alert-danger">
            {{ session('error') }}
         </div>
      @endif
      <section class="section">
            <div class="container-fluid">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-2">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <h5>Request Transfer Details</h5>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                           <label for="">Inputer Name : {{auth()->user()->fullname}}</label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="">Date & Time : <span id="clock"></span></label>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="addbtn extra p-0">
                                <a href="{{url('StockTransfer/TransferRequestList')}}" class="btn btn-info mr-1 btn-sm"> <i class="fa fa-arrow-left"></i></a>
                                <a href="{{url('StockTransfer/TransferRequestList')}}" class="btn btn-info btn-sm"> <i class="fa fa-home"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

               <div class="col-xl-12 col-md-12 col-sm-12 border">
                  <form action="{{url('StockTransfer/StoreStockTransfer')}}" method="POST">
                     @csrf
                     <input class="form-control" type="hidden" name="edit" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
                     <div class="row">
                       
                        <div class="col-sm-3 form-group" id="finishedgooddiv">
                           <label>
                              Material
                              </lable>
                             
                              <input readonly class="form-control form-control-sm" type="text" name="Material" id="Material" placeholder="UOM" value="{{isset($edit->matname) && $edit->matname!=''?$edit->matname:''}}" required>
                              <input type="hidden" name="Material_id" value="{{isset($edit->Material) && $edit->Material!=''?$edit->Material:''}}" >
                              <input type="hidden" name="prj_Material_id" value="{{isset($edit->material_id) && $edit->material_id!=''?$edit->material_id:''}}" >
                        </div>
                        
                        <div class="col-sm-3 form-group" id="uomdiv">
                           <label>UOM</label>
                           <div class="field-wrap">
                              <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom" placeholder="UOM" value="{{isset($edit->uom) && $edit->uom!=''?$edit->uom:''}}" required>
                             
                           </div>
                        </div>
                        <div class="col-sm-3 form-group" id="hsncodediv">
                           <label>Purchase Date*</label>
                           <div class="field-wrap">
                               <input readonly class="form-control form-control-sm" type="text" name="purchahedate" id="purchahedate" placeholder="UOM" value="{{isset($edit->Mrn_Date) && $edit->Mrn_Date!=''?$edit->Mrn_Date:''}}" required>
                           </div>
                        </div>
                        <div class="col-sm-3 form-group" id="uomdiv">
                           <label>Purchase Qty</label>
                           <div class="field-wrap">
                              <input readonly class="form-control form-control-sm" type="text" name="purchase_qty" id="purchase_qty" placeholder="UOM" value="{{isset($edit->Quantity) && $edit->Quantity!=''?$edit->Quantity:''}}" required>
                           </div>
                        </div>
                        <div class="col-sm-3 form-group" id="uomdiv">
                           <label>To Organization*</label>
                           <div class="field-wrap">
                              <select name="Organization_Name" class="form-select form-select-sm" required>
                                    <option value="" disabled {{ old('Organization_Name', request()->Organization ?? '') == '' ? 'selected' : '' }}>Select</option>
                                    @foreach ($Organization_Name as $val)
                                          <option value="{{ $val->id }}" {{ old('Organization_Name', $edit->Organization ?? '') == $val->id ? 'selected' : '' }}>
                                             {{ $val->organisation ?? '' }}
                                          </option>
                                    @endforeach
                                 </select>
                           </div>
                        </div>
                       <div class="col-sm-3 form-group" id="uomdiv">
                           <label>To Godown*</label>
                           <div class="field-wrap">
                              <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" required>
                                    <option value="" {{ old('Godown_Name', $edit->Godown_Name ?? '') == '' ? 'selected' : '' }}>Select</option>
                                    @foreach($Godown_Name as $val)
                                    <option value="{{$val->id}}" {{ old('Godown_Name', $edit->Godown_Name ?? '') == $val->id ? 'selected' : '' }}>{{$val->inventory_name}}</option>
                                    @endforeach
                              </select>
                              @error('Godown_Name') <span class="text-danger">{{ $message }}</span> @enderror
                           </div>
                        </div>
                     <br>
                   @if($edit->trn_status != 1)
                     <div class="table-responsive">
                        <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                              <thead>
                                 <tr>
                                    <th class="th-sm">SL No.</th>
                                    <th class="th-sm">Serial No.</th>
                                    <th class="th-sm">Supplier</th>
                                    <th class="th-sm">DOP</th>
                                    <th class="th-sm">Make</th>
                                    <th class="th-sm">Brand</th>
                                 </tr>
                              </thead>
                              @php
                                $oldSerials = old('serial_no', []) ?? [];
                                $oldSuppliers = old('supplier', []) ?? [];
                                $oldSupplierIds = old('supplier_id', []) ?? [];
                                $oldDops = old('dop', []) ?? [];
                                $oldMakes = old('make', []) ?? [];
                                $oldBrands = old('brand', []) ?? [];
                                $qty = (int)($edit->Quantity ?? 0);
                              @endphp
                              @if(count($oldSerials) > 0)
                                <tbody>
                                  @for($i = 0; $i < count($oldSerials); $i++)
                                    <tr>
                                      <td>{{ $i + 1 }}</td>
                                      <td><input type="text" required name="serial_no[]" class="form-control form-control-sm" value="{{ old('serial_no.' . $i, $oldSerials[$i] ?? '') }}" placeholder="Enter Serial No." data-original="{{ old('serial_no.' . $i, $oldSerials[$i] ?? '') }}"></td>
                                      <td>
                                        <input readonly type="text" name="supplier[]" value="{{ old('supplier.' . $i, $oldSuppliers[$i] ?? ($edit->Supplier_Name ?? '')) }}" class="form-control form-control-sm">
                                        <input type="hidden" name="supplier_id[]" value="{{ old('supplier_id.' . $i, $oldSupplierIds[$i] ?? ($edit->Supplier_Id ?? '')) }}" class="form-control form-control-sm">
                                      </td>
                                      <td><input readonly type="text" name="dop[]" value="{{ old('dop.' . $i, $oldDops[$i] ?? ($edit->Mrn_Date ?? '')) }}" class="form-control form-control-sm"></td>
                                      <td><input type="text" name="make[]" class="form-control form-control-sm" value="{{ old('make.' . $i, $oldMakes[$i] ?? '') }}"></td>
                                      <td><input type="text" name="brand[]" class="form-control form-control-sm" value="{{ old('brand.' . $i, $oldBrands[$i] ?? '') }}"></td>
                                    </tr>
                                  @endfor
                                </tbody>
                              @elseif(isset($slnocheck) && $slnocheck != '')
                                <tbody id="table_body">
                                  {{-- This will be dynamically filled by JS if no old input --}}
                                </tbody>
                              @else
                                <tbody>
                                    <tr>
                                       <td colspan="6" class="text-center">
                                             <span style="color: red; font-weight: bold;">Serial No. Not Set</span>
                                       </td>
                                    </tr>
                                </tbody>
                              @endif
                        </table>
                     </div>
                  @else
                     <div class="table-responsive">
                        <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer" style="width:100%">
                              <thead>
                                 <tr>
                                    <th class="th-sm">SL No.</th>
                                    <th class="th-sm">Serial No.</th>
                                    <th class="th-sm">Supplier</th>
                                    <th class="th-sm">DOP</th>
                                    <th class="th-sm">Make</th>
                                    <th class="th-sm">Brand</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if(isset($slnocheck) && $slnocheck != '')
                                 @foreach($stockdetails as $key => $MaterialVal)
                                    <tr>
                                          <td>{{ $key + 1 }}</td>
                                          <td>
                                             <input type="text" required name="serial_no[]" class="form-control form-control-sm"
                                                   value="{{ $MaterialVal->serial_no ?? '' }}" data-original="{{ $MaterialVal->serial_no ?? '' }}">
                                          </td>
                                          <td>
                                             <input readonly type="text" name="supplier[]" class="form-control form-control-sm"
                                                   value="{{ $edit->Supplier_Name }}">
                                             <input type="hidden" name="supplier_id[]" value="{{ $edit->Supplier_Id }}">
                                          </td>
                                          <td>
                                             <input readonly type="text" name="dop[]" class="form-control form-control-sm"
                                                   value="{{ $MaterialVal->dop ?? '' }}">
                                          </td>
                                          <td>
                                             <input type="text" name="make[]" class="form-control form-control-sm"
                                                   value="{{ $MaterialVal->make ?? '' }}">
                                          </td>
                                          <td>
                                             <input type="text" name="brand[]" class="form-control form-control-sm"
                                                   value="{{ $MaterialVal->brand ?? '' }}">
                                          </td>
                                    </tr>
                                 @endforeach
                                 @endif
                              </tbody>
                        </table>
                     </div>
                  @endif

                     <br>
                     <div class="row">
                        <div class="col-sm-8 form-group"></div>
                        <div class="col-sm-4 form-group">
                           <label for="State">Remarks:</label>
                           <input type="text" name="remarks" cols="30" rows="5" required class="form-control form-control-sm" placeholder="Remarks" value="{{ old('remarks', isset($stockdata->remarks) && $stockdata->remarks!='' ? $stockdata->remarks : '') }}">
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
document.addEventListener('DOMContentLoaded', function () {
    const qty = parseInt(document.getElementById('purchase_qty').value) || 0;
    const tbody = document.getElementById('table_body');
    let html = '';

    for (let i = 0; i < qty; i++) {
        html += `
            <tr>
                <td>${i + 1}</td>
                <td><input type="text" required name="serial_no[]" class="form-control form-control-sm" placeholder="Enter Serial No."></td>
                <td>
                  <input readonly type="text" name="supplier[]" value="{{ $edit->Supplier_Name }}" class="form-control form-control-sm">
                  <input type="hidden" name="supplier_id[]" value="{{ $edit->Supplier_Id }}" class="form-control form-control-sm">
                </td>
                <td><input readonly type="text" name="dop[]" value="{{ $edit->Mrn_Date }}" class="form-control form-control-sm"></td>
                <td><input type="text" name="make[]" class="form-control form-control-sm"></td>
                <td><input type="text" name="brand[]" class="form-control form-control-sm"></td>
            </tr>
        `;
    }

    tbody.innerHTML = html;
});
</script>
<script>
   $(document).ready(function() {
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
       activeclass(29, 1);
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
       if(MaterialId=='')
       {
           return false;
       }

   $.ajax({
   url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
   type: 'GET',
   data: {
       MaterialId: MaterialId
   },
   success: function(data) {
       $('#HSNCode').val(data.data.HSN_Code);
       $('#uom').val(data.data.UOM).change();
   }
   });

   $.ajax({
   url: "{{url('orderRequirement/MaterialData')}}" + '/' + MaterialId,
   type: 'GET',
   data: {
       MaterialId: MaterialId
   },
   success: function(data) {
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
               console.log(data.data);
               for (var i = 0; i < data.data.length; i++) {
                     var rowData = data.data[i];
                     var uomSelect = '<select disabled name="UOM_Second[]" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>' + '<option value="" selected disabled>Select</option>';
                     @foreach($UOM as $val)
                     uomSelect += '<option value="{{$val->id}}" ' + (rowData.UOM == "{{$val -> id}}" ? "selected" : "") + '>{{$val->UOMs}}</option>';
                     @endforeach
                     uomSelect += '</select>';
                     var newRow = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><div class="tabtbs"><input id="check' + rowData.RawMaterial.id + '" type="checkbox" name="MaterialID[]" class="material-checkbox" value="' + rowData.RawMaterial.id + '" onchange="changedata(' + rowData.RawMaterial.id + ',1)"><input readonly type="text" name="Material_Name[]" class="form-control form-control-sm" value="' + rowData.RawMaterial.matname + '"><input type="hidden" name="Material_id[]" class="form-control form-control-sm" value="' + rowData.RawMaterial.id + '"></div></td>' +
                        '<td><input readonly type="text" name="HSN_Code_Second[]" class="form-control form-control-sm" value="' + rowData.HSN_Code_Second + '"></td>' +
                        '<td><input readonly type="text" name="UOM_Second[]" class="form-control form-control-sm" value="' + rowData.UOM + '"></td>' +
                        '<td><input type="text" name="QTY[]" id="qty' + rowData.RawMaterial.id + '" onchange="changedata(' + rowData.RawMaterial.id + ')" class="form-control form-control-sm insdfsdfd" value="")"></td>' +
                        '</tr>';
                     table.find('tbody').append(newRow);
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
               var newRow = '<tr>' +
                     '<td class="w-25"><select name="Material_id[]" onchange="Assetunit(' + idx + ');" class="form-select form-select-sm js-example-matcher-start" id="Material_' + idx + '" required><option value="" selected disabled>Select</option>@foreach($Materialdetails as $val)<option value="{{$val->id}}" data-matname="{{$val->matname}}">{{$val->matname}}</option>@endforeach</select><input type="hidden" name="Material_Name[]" id="mat_val_id_' + idx + '" class="form-control form-control-sm" readonly></td>' +
                     '<td><input readonly type="text" name="HSN_Code_Second[]" id="HSN_Code_Second_' + idx + '" class="form-control form-control-sm" value=""></td>' +
                     '<td><input readonly type="text" name="UOM_Second[]" id="UOM_Second_' + idx + '" class="form-control form-control-sm" value=""></td>' +
                     '<td><input type="text" name="QTY[]" id="qty_' + idx + '" class="form-control form-control-sm" value="" ></td>' +
                     '<td><a href="javascript:;" class="btn btn-success btn-sm mt-4 productappend mr-1" data-index="' + idx + '"><i class="fa fa-plus" aria-hidden="true"></i></a>' +
                     removeButton + '</td>' +
                     '</tr>';

               table.find('tbody').append(newRow);
               $('.js-example-matcher-start').select2();
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
                     row.find('[name="Material_Name[]"]').attr('id', 'Material_' + i).attr('onchange', 'Assetunit(' + i + ')');
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



               }
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
      var matName = selectedOption.data('matname');
      $('#mat_val_id_' + i).val(matName);

      var id = "#Material_" + i;
      var AssetId = $(id).val();
         $.ajax({
                  url: "{{url('RawMaterial/MaterialData')}}" + '/' + AssetId,
                  type: 'GET',
                  data: {
                     AssetId: AssetId
                  },
                  success: function(response) {
                     $.each(response, function(index, calculation) {
                           $('#HSN_Code_Second_' + i).val(calculation.HSN_Code);
                           $('#UOM_Second_' + i).val(calculation.UOM);
                     });
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

   // Handle duplicate serial numbers from controller validation
   $(document).ready(function() {
      // Check if there are validation errors for serial_no
      @if($errors->has('serial_no'))
         var errorMessage = @json($errors->first('serial_no'));
         console.log('Controller error message:', errorMessage);
         
         // Extract duplicate serial numbers from error message
         var duplicateSerials = [];
         
         // Check for different types of error messages
         if (errorMessage.includes('Duplicate serial numbers found')) {
            // Handle form submission duplicates - check all inputs for duplicates
            var serialInputs = document.querySelectorAll('input[name="serial_no[]"]');
            var values = [];
            var duplicates = [];
            
            serialInputs.forEach(function(input) {
               var value = input.value.trim();
               if (value !== '') {
                  if (values.includes(value)) {
                     duplicates.push(value);
                  }
                  values.push(value);
               }
            });
            
            duplicateSerials = [...new Set(duplicates)]; // Remove duplicates from duplicates array
         } else if (errorMessage.includes('Serial number conflicts found')) {
            // Extract serial numbers from controller conflict message
            var matches = errorMessage.match(/:\s*([0-9A-Za-z,\s]+)/g);
            if (matches) {
               matches.forEach(function(match) {
                  var serials = match.replace(':', '').trim().split(',');
                  serials.forEach(function(serial) {
                     var cleanSerial = serial.trim();
                     if (cleanSerial && !duplicateSerials.includes(cleanSerial)) {
                        duplicateSerials.push(cleanSerial);
                     }
                  });
               });
            }
         }
         
         console.log('Duplicate serials found:', duplicateSerials);
         
         // Find and highlight duplicate inputs - works for both create and edit mode
         if (duplicateSerials.length > 0) {
            // Wait a bit for all inputs to be rendered (especially in edit mode)
            setTimeout(function() {
               var serialInputs = document.querySelectorAll('input[name="serial_no[]"]');
               var firstDuplicateInput = null;
               
               console.log('Total serial inputs found:', serialInputs.length);
               
               serialInputs.forEach(function(input, index) {
                  var value = input.value.trim();
                  console.log('Input', index, 'value:', value);
                  
                  if (duplicateSerials.includes(value)) {
                     console.log('Highlighting duplicate:', value);
                     // Apply red background
                     input.style.backgroundColor = '#ffcccc';
                     input.style.border = '2px solid #ff0000';
                     
                     // Store first duplicate for focusing
                     if (firstDuplicateInput === null) {
                        firstDuplicateInput = input;
                     }
                  }
               });
               
               // Focus on first duplicate input
               if (firstDuplicateInput) {
                  console.log('Focusing on first duplicate input');
                  setTimeout(function() {
                     firstDuplicateInput.focus();
                     firstDuplicateInput.select();
                  }, 200);
               }
            }, 1000); // Wait longer for edit mode to fully load
         }
      @endif
      
      // Add event listener to clear red styling when user starts typing
      $(document).on('input', 'input[name="serial_no[]"]', function() {
         $(this).css({
            'backgroundColor': '',
            'border': ''
         });
      });
   });
</script>
<script>
$(document).on('blur', 'input[name="serial_no[]"]', function() {
   var input = $(this);
   var serialValue = input.val().trim();
   var originalValue = input.attr('data-original') || '';
   var row = input.closest('tr');
   var sideLabel = row.find('td:eq(0)').text().trim(); // SL No. column value
   var currentId = $('input[name="edit"]').val(); // Get current transfer id
   var isEditMode = !!currentId;
   var isDuplicateLocal = false;
   // In edit mode, if the value is unchanged, do not show error
   if (isEditMode && serialValue === originalValue) {
      input.css('border-color', '#28a745');
      return;
   }
   if (serialValue.length > 0) {
      // Check for duplicate serials in form (local check)
      var allInputs = $('input[name="serial_no[]"]');
      var count = 0;
      allInputs.each(function() {
         if ($(this).val().trim() === serialValue) {
            count++;
         }
      });
      if (count > 1) {
         isDuplicateLocal = true;
         input.css('border-color', '#dc3545');
         var msg = $('<div></div>')
            .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': Already used in another row.')
            .css({
               position: 'fixed',
               top: '20px',
               left: '50%',
               transform: 'translateX(-50%)',
               background: '#ffc107',
               color: '#222',
               padding: '10px 24px',
               borderRadius: '6px',
               zIndex: 9999,
               fontWeight: 'bold',
               fontSize: '18px',
               boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
            });
         $('body').append(msg);
         setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
         if (isEditMode) {
            input.val(originalValue);
            input.css('border-color', '#28a745');
         } else {
            input.val('');
            input.attr('data-original', '');
         }
         return; // Do not proceed to AJAX if local duplicate found
      }
      $.ajax({
         url: '/StockTransfer/CheckSerialNumber',
         method: 'POST',
         data: {
            serial_no: serialValue,
            current_id: currentId // Pass current transfer id for edit mode
         },
         success: function(response) {
            if (!response.valid) {
               if (response.conflict_id && response.conflict_id == currentId) {
                  // Serial exists in own record, do not clear, do not show message
                  input.css('border-color', '#28a745');
               } else {
                  // Serial exists in another record
                  if (isEditMode) {
                     var msg = $('<div></div>')
                        .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': ' + (response.message || 'Already exists in another record.'))
                        .css({
                           position: 'fixed',
                           top: '20px',
                           left: '50%',
                           transform: 'translateX(-50%)',
                           background: '#ffc107',
                           color: '#222',
                           padding: '10px 24px',
                           borderRadius: '6px',
                           zIndex: 9999,
                           fontWeight: 'bold',
                           fontSize: '18px',
                           boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                        });
                     $('body').append(msg);
                     setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                     input.val(originalValue);
                     input.css('border-color', '#dc3545');
                  } else {
                     var msg = $('<div></div>')
                        .text('Serial number [' + serialValue + '] for SL No. ' + sideLabel + ': ' + (response.message || 'Already exists in another record.'))
                        .css({
                           position: 'fixed',
                           top: '20px',
                           left: '50%',
                           transform: 'translateX(-50%)',
                           background: '#ffc107',
                           color: '#222',
                           padding: '10px 24px',
                           borderRadius: '6px',
                           zIndex: 9999,
                           fontWeight: 'bold',
                           fontSize: '18px',
                           boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
                        });
                     $('body').append(msg);
                     setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
                     input.val('');
                     input.attr('data-original', '');
                  }
               }
            } else {
               // Fresh serial or unchanged value
               input.css('border-color', '#dc3545');
               // If fresh, update data-original to new value
               if (serialValue !== originalValue) {
                  input.attr('data-original', serialValue);
               }
            }
         },
         error: function() {
            var msg = $('<div></div>')
               .text('Error checking serial number!')
               .css({
                  position: 'fixed',
                  top: '20px',
                  left: '50%',
                  transform: 'translateX(-50%)',
                  background: '#dc3545',
                  color: '#fff',
                  padding: '10px 24px',
                  borderRadius: '6px',
                  zIndex: 9999,
                  fontWeight: 'bold',
                  fontSize: '18px',
                  boxShadow: '0 2px 8px rgba(0,0,0,0.15)'
               });
            $('body').append(msg);
            setTimeout(function() { msg.fadeOut(400, function() { $(this).remove(); }); }, 3000);
         }
      });
   } else {
      input.css('border-color', '');
   }
});
</script>
@endpush
