@if($update==0)
<div class="row" id="remove{{$dy_id}}">
    <div class="col-sm-5 form-group">
        <label> Batch No </label>
        <div class="field-wrap">
            <select name="batch_no[]" id="batch_no{{$dy_id}}"
                class="form-select form-select-sm js-example-matcher-start " onchange="batch_no(this.value,'{{$dy_id}}')">
                <option value="" selected>Select Batch No</option>
                @foreach($batch as $value)
                <option value="{{$value}}" >{{$value}}</option>
                @endforeach
                
            </select>
        </div>
    </div>
    <div class="col-sm-5 form-group">
        <label>SL No</label>
        <div class="field-wrap">
            <select name="sl_no[]" multiple id="sl_no{{$dy_id}}"
                class="form-select form-select-sm js-example-matcher-start alldata" required>
            </select>
        </div>
    </div>
    @if($type==1)
    <div class="col-sm-2 form-group mt-3">
        <button type="button" class="btn btn-success" onclick="rawmaterial()">+</button>
    </div>
    @else
    <div class="col-sm-2 form-group mt-3">
        <button type="button" class="btn btn-danger" onclick="removeele('{{$dy_id}}')">-</button>
    </div>
    @endif
</div>
@else
@php
$i=0;
@endphp
@foreach($sl_no as $key=> $slNoData)

@php
$dy_id=strtotime('now').rand(1,1000000);
$i++;
@endphp
<div class="row" id="remove{{$dy_id}}">
    <div class="col-sm-5 form-group">
        <label> Batch No  </label>
        <div class="field-wrap">
            <select name="batch_no[]" id="batch_no{{$dy_id}}"
                class="form-select form-select-sm js-example-matcher-start " onchange="batch_no(this.value,'{{$dy_id}}')">
                <option value="" selected>Select Batch No</option>
                @foreach($batch as $value)
                <option value="{{$value}}" {{$key==$value?'selected':''}} >{{$value}}</option>
                @endforeach
                
            </select>
        </div>
    </div>
    <div class="col-sm-5 form-group">
        <label>SL No</label>
        <div class="field-wrap">
            <select name="sl_no[{{$key}}][]" multiple id="sl_no{{$dy_id}}"
                class="form-select form-select-sm js-example-matcher-start alldata" >
                @foreach($allsl as $valusss)
               <option {{in_array($valusss->sl_no,$slNoData)?'selected':''}}>{{$valusss->sl_no}}</option>
               @endforeach
            </select>
        </div>
    </div>
    @if($i==1)
    <div class="col-sm-2 form-group mt-3">
        <button type="button" class="btn btn-success" onclick="rawmaterial()">+</button>
    </div>
    @else
    <div class="col-sm-2 form-group mt-3">
        <button type="button" class="btn btn-danger" onclick="removeele('{{$dy_id}}')">-</button>
    </div>
    @endif
</div>
@endforeach
@endif