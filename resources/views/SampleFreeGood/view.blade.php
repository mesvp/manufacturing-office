<input class="form-control" type="hidden" name="edit" id="edit_id"
    value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
<div class="row">
    
    
    <div class="col-sm-3 form-group">
        <label>
            Manufacturing Unit*
        </label>
        <select name="Manufacturing_Unit" id="Manufacturing_Unit"
            class="form-select form-select-sm js-example-matcher-start" required>
            <option value="" selected disabled>Select</option>
            @foreach($Manufacturing_Unit as $val)
            <option value="{{$val->id}}" {{isset($edit->Manufacturing_Unit) &&
                $edit->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>
            Plant Name
        </label>
        <select name="Plant_Name" id="Plant_Name" class="form-select form-select-sm js-example-matcher-start">
            <option value="" selected>Select</option>
            @foreach($Plant_Name as $val)
            <option value="{{$val->id}}" {{isset($edit->Plant_Name) &&
                $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>
            Organization Name*
        </label>
        <select name="Organization_Name" id="Organization_Name"
            class="form-select form-select-sm js-example-matcher-start" required>
            <option value="" selected disabled>Select</option>
            @foreach($Organization_Name as $val)
            <option value="{{$val->id}}" {{isset($edit->Organization_Name) &&
                $edit->Organization_Name==$val->id?'selected':''}}>{{$val->organisation}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>
            BU Name*
        </label>
        <select name="BU" id="BU_Name" class="form-select form-select-sm" required>
            <option value="" selected disabled>Select</option>
            @foreach($BU as $val)
            <option value="{{$val->id}}" {{isset($edit->BU) &&
                $edit->BU==$val->id?'selected':''}}>{{$val->unit_name??''}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>
            Godown Name
        </label>
        <select name="Godown_Name" id="Godown_Name" class="form-select form-select-sm js-example-matcher-start">
            <option value="" selected>Select</option>
            @foreach($Godown_Name as $val)
            <option value="{{$val->id}}" {{isset($edit->Godown_Name) &&
                $edit->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>
            Finished Good(FG)*
            </lable>
            <select name="Raw_Material"
                class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="RawMaterial"
                required>
                <option value="" selected disabled>Select</option>
                @foreach($Raw_Material as $val)
                <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) &&
                    $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}
                </option>
                @endforeach
            </select>
    </div>
    <div class="col-sm-3 form-group">
        <label>Stock Availabe</label>
        <div class="field-wrap">
            <input readonly class="form-control form-control-sm" type="number" name="StockAvailabe" id="StockAvailabe"
                placeholder="Stock Availabe"
                value="{{isset($edit->HSN_Code) && $edit->HSN_Code!=''?$edit->HSN_Code:''}}" required>
        </div>
    </div>
    <div class="col-sm-3 form-group">
        <label>UOM</label>
        <div class="field-wrap">
            <input readonly class="form-control form-control-sm" type="text" name="UOM" id="uom"
                placeholder="UOM"
                value="{{isset($edit->UOM) && $edit->UOM!=''?$edit->UOM:''}}" required>
        </div>
        {{-- <div class="field-wrap">
            <select disabled name="UOM" id="uom"
                class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" required readonly>
                <option value="" selected disabled>Select</option>
                @foreach($UOM as $val)
                <option value="{{$val->id}}" {{isset($edit->UOM) &&
                    $edit->UOM==$val->id?'selected':''}}>{{$val->UOMs}}</option>
                @endforeach
            </select>
        </div> --}}
    </div>
    <div class="col-sm-3 form-group">
        <label>Quantity</label>
        <div class="field-wrap">
            <input class="form-control form-control-sm" type="number" name="Quantity" id="Quantity"
                placeholder="Quantity" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                value="{{isset($edit->Quantity) && $edit->Quantity!=''?$edit->Quantity:''}}" required>
        </div>
    </div>
    <div class="col-sm-3 form-group">
        <label>Date</label>
        <div class="field-wrap">
            <input class="form-control form-control-sm" type="date" name="date" id="date" placeholder="date"
                value="{{isset($edit->Date) && $edit->Date!=''?$edit->Date:''}}" required>
        </div>
    </div>
</div>
<br>
<br>
<div class="row">
    <h6>Customer Details</h6>
    <div class="col-sm-3 form-group">
        <label>Customer Name</label>
        <div class="field-wrap">
            <input class="form-control form-control-sm" type="text" name="Customer_Name" id="Customer_Name"
                placeholder="Customer Name"
                value="{{isset($edit->CustomerName) && $edit->CustomerName!=''?$edit->CustomerName:''}}" required>
        </div>
    </div>
    <div class="col-sm-3 form-group">
        <label>Customer Address</label>
        {{-- <div class="field-wrap">
            <input class="form-control form-control-sm" type="text" name="Customer_Address" id="Customer_Address"
                placeholder="Customer Address"
                value="{{isset($edit->CustomerAddress) && $edit->CustomerAddress!=''?$edit->CustomerAddress:''}}"
                required>
        </div> --}}
        <div class="field-wrap">
            <textarea class="form-control form-control-sm" style="height: 110px;" readonly name="Customer_Address" id="Customer_Address">{{isset($edit->CustomerAddress) && $edit->CustomerAddress!=''?$edit->CustomerAddress:''}}</textarea>
        </div>
    </div>
    <div class="col-sm-3 form-group">
        <label>Customer Phone</label>
        <div class="field-wrap">
            <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                class="form-control form-control-sm" type="text" name="Customer_Phone" id="Customer_Phone"
                placeholder="Customer Phone"
                value="{{isset($edit->CustomerPhone) && $edit->CustomerPhone!=''?$edit->CustomerPhone:''}}" required>
        </div>
    </div>
    <div class="col-sm-3 form-group">
        <label>Company Name</label>
        <div class="field-wrap">
            <input class="form-control form-control-sm" type="text" name="Company_Name" id="Company_Name"
                placeholder="Company Name"
                value="{{isset($edit->CompanyName) && $edit->CompanyName!=''?$edit->CompanyName:''}}" required>
        </div>
    </div>
</div>
<div class="row">
    <h6>Material Details :</h6>
    <div class="col-sm-12" id="materialdata">

    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <label>Reason For Free or sample Good : </label>
        <div class="field-wrap">
            <textarea name="reason" placeholder="Reason For Free or sample Good"
                class="form-control form-control-sm">{{$edit->Reason??''}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 form-group"></div>
    <div class="col-sm-4 form-group">
        <label for="State">Remarks:</label>
        <input type="text" name="remarks" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks"
            value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
    </div>
</div>
