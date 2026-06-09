<form action="" method="POST" id="stock_fieldsformfilter" class="stock_fields">
    @csrf
    <div class="row" id="row">
        <div class="tab1 col-sm-12 row" id="adaaishhhh">
            <!-- <h5>Stock List</h5> -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    Organization Name
                </label>
                <select name="Organization" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Organization as $val)
                    <option value="{{$val->id}}" {{isset($editStock->Organization) && $editStock->Organization==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    BU Name
                </label>
                <select name="BU_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($BU as $val)
                    <option value="{{$val->id}}" {{isset($editStock->BU_Name) && $editStock->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    Godown Name
                </label>
                <select name="Factory_Godown_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Godown_Name as $val)
                    <option value="{{$val->id}}" {{isset($editStock->Factory_Godown_Name) && $editStock->Factory_Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    Unit Name
                </label>
                <select name="Unit_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Manufacturing_unit as $val)
                    <option value="{{$val->id}}" {{isset($editStock->Unit_Name) && $editStock->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    Plant Name
                </label>
                <select name="Plant_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($plant_name as $val)
                    <option value="{{$val->id}}" {{isset($editStock->Plant_Name) && $editStock->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label> Expected Date</label>
                <div class="field-wrap">
                    <input class="form-control form-control-sm" type="date" name="Expected_Date" placeholder="Order Date" value="{{isset($editStock->Expected_Date) && $editStock->Expected_Date!=''?$editStock->Expected_Date:''}}" >
                </div>
            </div>
            {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label> Company Name</label>
                <div class="field-wrap">
                    <select name="Company_Name" class="form-select form-select-sm js-example-matcher-start" >
                        <option value="" selected disabled>Select</option>
                        @foreach($Company_Name as $val)
                        <option value="{{$val->id}}" {{isset($editStock->Company_Name) && $editStock->Company_Name==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>
                    Finished Good(FG)
                    </lable>
                    <select name="Raw_Material" class="form-select form-select-sm js-example-matcher-start" id="RawMaterial" >
                        <option value="" selected disabled>Select</option>
                        @foreach($Raw_Material as $val)
                        <option value="{{$val->RawMaterial->id}}" {{isset($editStock->Raw_Material) && $editStock->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>QTY</label>
                <div class="field-wrap">
                    <input class="form-control form-control-sm" type="number" name="QTY" id="QTY" placeholder="QTY" value="{{isset($editStock->QTY) && $editStock->QTY!=''?$editStock->QTY:''}}" >
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group">
                <label>PR No</label>
                <div class="field-wrap">
                    <input class="form-control form-control-sm" type="text" name="Sales_Order_No" id="Sales_Order_No" placeholder="PR No" value="" >
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 form-group mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                <a href=""><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
            </div>
        </div>
        <br>
    </div>
</form>
