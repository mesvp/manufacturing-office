@if(sizeof($data)>0)
@foreach($data as $i=> $rackdata)
<div class="row main-row" id="remss{{$remove[$i]}}">
    <div class="col-sm-10 appending">
        <table class="table">
            <thead>
                <tr>
                    <th>Rack No</th>
                    <th>Sub Rack No</th>
                    <th>Bin No</th>
                    <th>Sub Bin No</th>
                </tr>
                <tr>
                    <td>
                        <select target-id={{$remove[$i]}} name="Rack_No[{{$remove[$i]}}]" id="Rack_No{{$remove[$i]}}" class="form-select form-select-sm Rack_No" required>
                        <option value="" >Select Rack No</option>
                            @foreach($rack_no as $val)
                            <option value="{{$val->id}}" {{isset($rackdata->Rack_No) && $rackdata->Rack_No==$val->id?'selected':'' }}>{{$val->Rack_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove[$i]}} name="Sub_Rack_No[{{$remove[$i]}}]" id="Sub_Rack_No{{$remove[$i]}}" class="form-select form-select-sm Sub_Rack_No" required>
                        <option value="" >Select Sub Rack No</option>
                            @foreach($rack_sub_no as $val)
                            <option value="{{$val->id}}" {{isset($rackdata->Sub_Rack_No) && $rackdata->Sub_Rack_No==$val->id?'selected':'' }}>{{$val->Sub_Rack_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove[$i]}} name="Bin_No[{{$remove[$i]}}]" id="Bin_No{{$remove[$i]}}" class="form-select form-select-sm Bin_No" required>
                        <option value="" >Select Bin No</option>
                            @foreach($bin_no as $val)
                            <option value="{{$val->id}}" {{isset($rackdata->Bin_No) && $rackdata->Bin_No==$val->id?'selected':'' }}>{{$val->Bin_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove[$i]}} name="Sub_Bin_No[{{$remove[$i]}}]" id="Sub_Bin_No{{$remove[$i]}}" class="form-select form-select-sm Sub_Bin_No" required>
                        <option value="" >Select Sub Bin No</option>
                            @foreach($sub_bin_no as $val)
                            <option value="{{$val->id}}" {{isset($rackdata->Sub_Bin_No) && $rackdata->Sub_Bin_No==$val->id?'selected':'' }}>{{$val->Sub_Bin_No}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>SL No</th>
                    <th>QC Result</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch as $key=> $value)
                <tr>
                    <td>{{$key<1?$value->batch_no:''}}</td>
                    <td>{{$value->sl_no}}</td>
                    <td>{{$value->result}}</td>
                    <td><input type="checkbox" {{in_array($value->sl_no,$rackdata->sl_no[$rackdata->id])?'checked':''}} name="manage[{{$remove[$i]}}][]" value="{{$value->sl_no}}" id="manage{{$remove[$i]}}{{$value->sl_no}}" target-id={{$remove[$i]}} class="checkbox {{$value->sl_no}}"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-sm-2">
        @if($i==0)
        <button type="button" class="btn btn-success" onclick="manage()">+</button>
        @else
        <button type="button" class="btn btn-danger" onclick="removedd('{{$remove[$i]}}')">-</button>
        @endif
    </div>
</div>
@endforeach
@else
<div class="row main-row" id="remss{{$remove}}">
    <div class="col-sm-10 appending">
        <table class="table">
            <thead>
                <tr>
                    <th>Rack No</th>
                    <th>Sub Rack No</th>
                    <th>Bin No</th>
                    <th>Sub Bin No</th>
                </tr>
                <tr>
                    <td>
                        <select target-id={{$remove}} name="Rack_No[{{$remove}}]" id="Rack_No{{$remove}}" class="form-select form-select-sm Rack_No" required>
                        <option value="" >Select Rack No</option>
                            @foreach($rack_no as $val)
                            <option value="{{$val->id}}">{{$val->Rack_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove}} name="Sub_Rack_No[{{$remove}}]" id="Sub_Rack_No{{$remove}}" class="form-select form-select-sm Sub_Rack_No" required>
                        <option value="" >Select Sub Rack No</option>
                            @foreach($rack_sub_no as $val)
                            <option value="{{$val->id}}">{{$val->Sub_Rack_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove}} name="Bin_No[{{$remove}}]" id="Bin_No{{$remove}}" class="form-select form-select-sm Bin_No" required>
                        <option value="" >Select Bin No</option>
                            @foreach($bin_no as $val)
                            <option value="{{$val->id}}">{{$val->Bin_No}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select target-id={{$remove}} name="Sub_Bin_No[{{$remove}}]" id="Sub_Bin_No{{$remove}}" class="form-select form-select-sm Sub_Bin_No" required>
                        <option value="" >Select Sub Bin No</option>
                            @foreach($sub_bin_no as $val)
                            <option value="{{$val->id}}">{{$val->Sub_Bin_No}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>Batch No</th>
                    <th>SL No</th>
                    <th>QC Result</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch as $key=> $value)
                <tr>
                    <td>{{$key<1?$value->batch_no:''}}</td>
                    <td>{{$value->sl_no}}</td>
                    <td>{{$value->result}}</td>
                    <td><input type="checkbox" name="manage[{{$remove}}][]" value="{{$value->sl_no}}" id="manage{{$remove}}{{$value->sl_no}}" target-id={{$remove}} class="checkbox {{$value->sl_no}}"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-sm-2">
        @if(request()->type==1)
        <button type="button" class="btn btn-success" onclick="manage()">+</button>
        @else
        <button type="button" class="btn btn-danger" onclick="removedd('{{$remove}}')">-</button>
        @endif
    </div>
</div>
@endif
<input type="hidden" id="totalslno" value="{{sizeof($batch)}}">