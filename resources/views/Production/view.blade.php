<div id="row">
    <!-- <h6>Production</h6> -->
    <div class="my-2 row" id="adaaishhhh">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Unit Name *
            </label>
            <select disabled name="Unit_Name" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($Manufacturing_unit as $val)
                <option value="{{$val->id}}" {{isset($edit->Unit_Name) &&
                                                $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Plant Name*
            </label>
            <select disabled name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($plant_name as $val)
                <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Organization Name*
            </label>
            <select disabled name="Organization" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($Organization as $val)
                <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                BU Name*
            </label>
            <select disabled name="BU" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($BU as $val)
                <option value="{{$val->id}}" {{isset($edit->BU_Name) && $edit->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Shift*
            </label>
            <input disabled value="{{$edit->Shift??''}}" placeholder="Shift" name="shift" class="form-control form-control-sm" required>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Production Date
            </label>
            <input disabled type="date" value="{{$edit->Production_Date??''}}" placeholder="Production Date" name="Production_Date" class="form-control form-control-sm" required>
        </div>
    </div>
    <div class="border row p-2" id="adaaishhhh">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>
                Finished Good(FG)*
                </lable>
                <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial" required>
                    <option value="" selected disabled>Select</option>
                    @foreach($Raw_Material as $val)
                    <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-xl-1 col-lg-1 col-md-4 col-sm-12 form-group">
            <label>UOM *</label>
            <div class="field-wrap">
                <input disabled type="text" name="UOM" id="uom" value="{{$edit->UOM??''}}" placeholder="Rate" class="form-control form-control-sm" required>
                {{-- <select disabled name="UOM" id="uom" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required>
                    <option value="" selected disabled>Select</option>
                    @foreach($UOM as $val)
                    <option value="{{$val->id}}" {{isset($edit->UOM) && $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                    @endforeach
                </select> --}}
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>Rate*</label>
            <div class="field-wrap">
                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Rate" id="Rate" value="{{$edit->Rate??''}}" placeholder="Rate" class="form-control form-control-sm" required>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>Quantity*</label>
            <div class="field-wrap">
                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Quantity" onchange="materialdata()" value="{{$edit->Quantity??''}}" {{isset($edit->Quantity)?'readonly':''}} placeholder="Quantity" id="Quantity" class="form-control form-control-sm" required>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
            <label>Total amount*</label>
            <div class="field-wrap">
                <input disabled type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" name="Total_amount" value="{{$edit->Total_amount??''}}" id="Total_amount" placeholder="Rate*Quantity" class="form-control form-control-sm" required>
            </div>
        </div>
    </div>
    <br>
</div>

<div class="table-responsive">
    <table id="Tabledata" class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
        <thead>
            <tr>
                <th class="th-sm">SL No.</th>
                <th class="th-sm">Raw Material Name</th>
                <th class="th-sm">Plant Stock</th>
                <th class="th-sm">UOM</th>
                <th class="th-sm">Consumption Qty</th>
                <th class="th-sm">Scarp Qty</th>
                <th class="th-sm">Other Qty</th>
                <th class="th-sm">Total Qty</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<br>
<div class="table-responsive">
    <table  class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Batch No.</th>
                <th class="th-sm">ERP SL No.</th>
                <th class="th-sm">Factory SL No.</th>
            </tr>
        </thead>
        <tbody>
            @php ($i=1)
            @foreach($batch as $key=> $batchdata)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$batchdata->batch_no}}</td>
                    <td>{{$batchdata->sl_no}}</td>
                    <td>{{$batchdata->serail_check}}</td>
                </tr>
             @endforeach
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-sm-8 form-group"></div>
    <div class="col-sm-4 form-group">
        <label for="State">Remarks:</label>
        <input disabled type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
    </div>
</div>
