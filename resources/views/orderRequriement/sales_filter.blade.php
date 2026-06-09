<form action="" id="sales_fieldsformfilter" method="POST" class="sales_fields">
    @csrf
    <div class="row" id="row">
        <div class="tab1 col-sm-12 row" id="adaaishhhh">
        <h5>Sales List</h5>
            <hr>
            <div class="col-sm-3 form-group">
                <label>
                    Organization Name
                </label>
                <select name="Organization" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Organization as $val)
                    <option value="{{$val->id}}" {{isset($editSales->Organization) && $editSales->Organization==$val->id?'selected':''}}>{{isset($val->organization) && $val->organization!=''?$val->organization:''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label>
                    BU Name
                </label>
                <select name="BU_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($BU as $val)
                    <option value="{{$val->id}}" {{isset($editSales->BU_Name) && $editSales->BU_Name==$val->id?'selected':''}}>{{isset($val->BU) && $val->BU!=''?$val->BU:''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label>
                    Unit Name
                </label>
                <select name="Unit_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Manufacturing_unit as $val)
                    <option value="{{$val->id}}" {{isset($editSales->Unit_Name) && $editSales->Unit_Name==$val->id?'selected':''}}>{{$val->Manufacturing_unit}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label>
                    Plant Name
                </label>
                <select name="Plant_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($plant_name as $val)
                    <option value="{{$val->id}}" {{isset($editSales->Plant_Name) && $editSales->Plant_Name==$val->id?'selected':''}}>{{$val->plant_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label> Order Date</label>
                <div class="field-wrap">
                    <input class="form-control form-control-sm" type="date" name="Order_Date" placeholder="Order Date" value="{{isset($editSales->Order_Date) && $editSales->Order_Date!=''?$editSales->Order_Date:''}}" >
                </div>
            </div>
            <div class="col-sm-3 form-group">
                <label>Sales Order No.</label>
                <div class="field-wrap">
                    <input class="form-control form-control-sm" type="text" name="Sales_Order_No" placeholder="Sales Order No." value="" >
                </div>
            </div>
            <div class="col-sm-3 form-group">
                <label> Customer Name</label>
                <select name="Customer_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Customer_Name as $val)
                    <option value="{{$val->id}}" {{isset($editSales->Customer_Name) && $editSales->Customer_Name==$val->id?'selected':''}}>{{$val->Customer_Name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label> Company Name</label>
                <select name="Company_Name" class="form-select form-select-sm js-example-matcher-start" >
                    <option value="" selected disabled>Select</option>
                    @foreach($Company_Name as $val)
                    <option value="{{$val->id}}" {{isset($editSales->Company_Name) && $editSales->Company_Name==$val->id?'selected':''}}>{{$val->Company_Name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                <a href=""><button type="button" class="btn btn-secondary"><i class="fa fa-refresh"></i></button></a>
            </div>
        </div>
    </div>
</form>