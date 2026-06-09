<div class="row" id="row">
    <h6>QC FINISHED GOOD</h6>
    <div class="col-sm-12 row" id="adaaishhhh">
        <div class="col-sm-3 form-group">
            <label>
                Unit Name*
            </label>
            <select disabled name="Unit_Name" id="Unit_Name" class="form-select form-select-sm" required>
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
            <select disabled name="Plant_Name" id="Plant_Name" class="form-select form-select-sm" required>
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
            <select disabled name="Organization" id="Organization_Name" class="form-select form-select-sm" required>
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
            <select disabled name="BU" id="BU_Name" class="form-select form-select-sm" required>
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
                Finished Good(FG)*
                </lable>
                <select disabled name="Raw_Material" class="form-select form-select-sm js-example-matcher-start js-example-matcher-start" id="Raw_Material" required>
                    <option value="" selected disabled>Select</option>
                    @foreach($Raw_Material as $val)
                    <option value="{{$val->RawMaterial->id}}" {{isset($edit->Raw_Material) && $edit->Raw_Material==$val->RawMaterial->id?'selected':''}}>{{$val->RawMaterial->matname}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Batch No *
            </label>
            <select disabled name="batch_no" id="batch_no" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($batch as $val)
                <option value="{{$val->batch_no}}" {{isset($edit->batch_no) && $edit->batch_no==$val->batch_no?'selected':''}}>{{$val->batch_no}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 form-group">
            <label>
                Sample Collected By *
            </label>
            <select disabled name="SampleCollectedBy" class="form-select form-select-sm" required>
                <option value="" selected disabled>Select</option>
                @foreach($admin as $val)
                <option value="{{$val->id}}" {{isset($edit->SampleCollectedBy) && $edit->SampleCollectedBy==$val->id?'selected':'' }}>{{$val->fullname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 form-group">
            <label>
                QC Date *
            </label>
            <input type="date" disabled name="QCDate" class="form-control form-control-sm" value="{{isset($edit->QCDate) && $edit->QCDate!=''?$edit->QCDate:''}}" required>
        </div>
        <div class="col-sm-1 form-group">
            <label>
                QC Code *
            </label>
            <input type="text" disabled placeholder="auto" readonly title="Automatically Genrate" class="form-control form-control-sm" value="{{isset($edit->QCCode) && $edit->QCCode!=''?$edit->QCCode:''}}">
        </div>
    </div>
    <div class="col-sm-12 row" id="adaais455hhhh">

    </div>
</div>


<br>
<div class="table-responsive">
    <table class="table table-striped table-bordered dataTable no-footer example" style="width:100%">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">SL No.</th>
                <th class="th-sm">Result</th>
                <th class="th-sm">Remark</th>
            </tr>
        </thead>
        <tbody>
            @php ($i=1)
            @foreach($batch as $key=> $batchdata)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$batchdata->sl_no}}</td>
                <td>{{$batchdata->result}}</td>
                <td>{{$batchdata->remark}}</td>
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