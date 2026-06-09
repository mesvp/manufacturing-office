<form action="" method="POST">
    @csrf
    <div class="row">
        {{---
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                Work Order No.
            </label>
            <select name="Work_Order_No" class="form-select form-select-sm">
                <option value="" selected disabled>Select</option>
                <option value="Test" {{isset(request()->Work_Order_No) &&
                    request()->Work_Order_No=='Test'?'selected':''}}>Test</option>
            </select>
        </div>---}}
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                From Date
            </label>
            <input type="date" name="from_date" class="form-control form-control-sm">

        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                To Date
            </label>
            <input type="date" name="to_date" class="form-control form-control-sm">
        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                Organization Name*
            </label>
            <select name="Organization_Name" class="form-select form-select-sm js-example-matcher-start" >
                <option value="" selected disabled>Select</option>
                @foreach($Organization_Name as $val)
                <option value="{{$val->id}}" {{isset(request()->Organization_Name) &&
                    request()->Organization_Name==$val->id?'selected':''}}>{{$val->organisation}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12form-group">
            <label>
                Manufacturing Unit*
            </label>
            <select name="Manufacturing_Unit" class="form-select form-select-sm js-example-matcher-start" >
                <option value="" selected disabled>Select</option>
                @foreach($Manufacturing_Unit as $val)
                <option value="{{$val->id}}" {{isset(request()->Manufacturing_Unit) &&
                    request()->Manufacturing_Unit==$val->id?'selected':''}}>{{$val->pname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                Plant Name*
            </label>
            <select name="Plant_Name" class="form-select form-select-sm js-example-matcher-start" >
                <option value="" selected disabled>Select</option>
                @foreach($Plant_Name as $val)
                <option value="{{$val->id}}" {{isset(request()->Plant_Name) &&
                    request()->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 form-group">
            <label>
                Godown Name*
            </label>
            <select name="Godown_Name" class="form-select form-select-sm js-example-matcher-start" >
                <option value="" selected disabled>Select</option>
                @foreach($Godown_Name as $val)
                <option value="{{$val->id}}" {{isset(request()->Godown_Name) &&
                    request()->Godown_Name==$val->id?'selected':''}}>{{$val->inventory_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12 mt-2">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            <a href=""><button type="button" class="btn btn-secondary"><i
                        class="fa fa-refresh"></i></button></a>
        </div>
        <!-- <div class="col-xl-10 col-lg-6 col-md-6 col-sm-12 mt-2">
            <div class="FilterButtonnn sales_fields">
                <div class="raone">
                    <p class="raho MyToggle" id="MyToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z">
                            </path>
                        </svg>
                    </p>
                    <div class="ukom myFilter" id="myFilter">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ToggleCheck" onclick="toggleCheckboxes()">
                            <label class="form-check-label" for="ToggleCheck">All</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="NO" value="SL. No." onclick="filterTable(this)">
                            <label class="form-check-label" for="NO">SL. No.</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Creater_Name" value="Creater Name" onclick="filterTable(this)">
                            <label class="form-check-label" for="Creater_Name">Creater Name</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Request_No" value="Request No" onclick="filterTable(this)">
                            <label class="form-check-label" for="Request_No">Request No</label>
                        </div>
                        {{-- <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="WorkOrderNo" value="Work Order No" onclick="filterTable(this)">
                            <label class="form-check-label" for="WorkOrderNo">Work Order No</label>
                        </div> --}}
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Organization_Name" value="Organization Name" onclick="filterTable(this)">
                            <label class="form-check-label" for="Organization_Name">Organization
                                Name</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ManufacturingUnit" value="Manufacturing Unit" onclick="filterTable(this)">
                            <label class="form-check-label" for="ManufacturingUnit">Manufacturing
                                Unit</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Plant_Name" value="Plant Name" onclick="filterTable(this)">
                            <label class="form-check-label" for="Plant_Name">Plant Name</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="GodownName" value="Godown Name" onclick="filterTable(this)">
                            <label class="form-check-label" for="GodownName">Godown Name</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Date_Time" value="Date &amp; Time" onclick="filterTable(this)">
                            <label class="form-check-label" for="Date_Time">Date &amp; Time</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Status" value="Status" onclick="filterTable(this)">
                            <label class="form-check-label" for="Status">Status</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="Operation" value="Operation" onclick="filterTable(this)">
                            <label class="form-check-label" for="Operation">Operation</label>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</form>
