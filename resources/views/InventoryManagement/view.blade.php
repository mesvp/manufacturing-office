<div class="row" id="row">
    <h6>Inventory Management</h6>
    <div class="col-sm-12 row" id="adaaishhhh">
        <div class="col-sm-3 form-group">
            <label>
                Unit Name*
            </label>
            <select name="Unit_Name" disabled id="Unit_Name" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($Manufacturing_unit as $val)
                <option value="{{$val->id}}" {{isset($edit->Unit_Name) &&
                                                $edit->Unit_Name==$val->id?'selected':''}}>{{$val->pname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Plant Name*
            </label>
            <select name="Plant_Name" disabled id="Plant_Name" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($plant_name as $val)
                <option value="{{$val->id}}" {{isset($edit->Plant_Name) && $edit->Plant_Name==$val->id?'selected':''}}>{{$val->spname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Organization Name*
            </label>
            <select name="Organization" disabled id="Organization_Name" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($Organization as $val)
                <option value="{{$val->id}}" {{isset($edit->Organization_Name) && $edit->Organization_Name==$val->id?'selected':''}}>{{isset($val->organisation) && $val->organisation!=''?$val->organisation:''}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                BU Name*
            </label>
            <select name="BU" id="BU_Name" disabled class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($BU as $val)
                <option value="{{$val->id}}" {{isset($edit->BU_Name) && $edit->BU_Name==$val->id?'selected':''}}>{{isset($val->unit_name) && $val->unit_name!=''?$val->unit_name:''}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-12 row" id="adaaishhhh">
        <div class="col-sm-3 form-group">
            <label>
                Batch No *
            </label>
            <select name="batch_no" id="batch_no" disabled class="form-select form-select-sm" required>
                <option value="" selected>Select</option>
                @foreach($batch as $val)
                <option value="{{$val->batch_no}}" {{isset($edit->batch_no) && $edit->batch_no==$val->batch_no?'selected':''}}>{{$val->batch_no}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Finished Good
            </label>
            <input type="text" disabled name="" id="FinishedGood" placeholder="Finished Good" class="form-control form-control-sm" value="" required>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Production Date
            </label>
            <input type="text" disabled name="" placeholder="Production Date" id="ProductionDate" class="form-control form-control-sm" value="" required>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Production Shift
            </label>
            <input type="text" disabled placeholder="Production Shift" id="ProductionShift" class="form-control form-control-sm" value="" required>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                QC Code
            </label>
            <input type="text" disabled name="QCCode" id="QCCode" placeholder="QC Code" class="form-control form-control-sm">
        </div>
        <div class="col-sm-3 form-group">
            <label>
                QC Status
            </label>
            <input type="text" placeholder="QC Status" disabled id="QCStatus" class="form-control form-control-sm" value="" required>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Sample Collected By
            </label>
            <input type="text" disabled placeholder="Sample Collected By" id="SampleCollectedBy" class="form-control form-control-sm">
        </div>


    </div>
    <div class="col-sm-12 row" id="adaais455hhhh">
        <table class="table">
            <thead>
                <tr>
                    <th>Rack No</th>
                    <th>Sub Rack No</th>
                    <th>Bin No</th>
                    <th>Sub Bin No</th>
                    <th>Material Sl No</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $fetch)
                <tr>
                    <td>{{$fetch['Rack_No']}}</td>
                    <td>{{$fetch['Sub_Rack_No']}}</td>
                    <td>{{$fetch['Bin_No']}}</td>
                    <td>{{$fetch['Sub_Bin_No']}}</td>
                    <td>{{$fetch['sl_no']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-8 form-group"></div>
    <div class="col-sm-4 form-group">
        <label for="State">Remarks:</label>
        <input type="text" name="remark" cols="30" rows="5" class="form-control form-control-sm" placeholder="Remarks" value="{{isset($edit->remarks) && $edit->remarks!=''?$edit->remarks:''}}">
    </div>
</div>