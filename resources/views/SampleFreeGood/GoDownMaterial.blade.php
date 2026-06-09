@if(request()->update==0)

@php
$quantity=request()->quantity;
if($type!=1)
{
    $quantity=1;
}
@endphp
@for($i=0;$i<$quantity; $i++)
   @php
   $dy_id=strtotime('now').rand(1,10000000)
   @endphp 
<div class="row" id="remove{{$dy_id}}">
    <div class="col-sm-5 form-group">
        <label> Shlef No </label>
        <div class="field-wrap">
            <input name="batch_no[]" id="batch_no{{$dy_id}}"
                class="form-control form-control-sm" placeholder="Batch No">
        </div>
    </div>
    <div class="col-sm-5 form-group">
        <label>SL No</label>
        <div class="field-wrap">
            <input name="sl_no[]" id="sl_no{{$dy_id}}"
                class="form-control form-control-sm alldata" required placeholder="Sl No">
               
           
        </div>
    </div>
</div>
@endfor
@else
@foreach($sl_no as $key=> $slNoData)
@php
   $dy_id=strtotime('now').rand(1,10000000)
   @endphp 
<div class="row" id="remove{{$dy_id}}">
    <div class="col-sm-5 form-group">
        <label> Shlef No </label>
        <div class="field-wrap">
            <input name="batch_no[]" id="batch_no{{$dy_id}}"
                class="form-control form-control-sm" value="{{$key}}" placeholder="Batch No">
        </div>
    </div>
    <div class="col-sm-5 form-group">
        <label>SL No</label>
        <div class="field-wrap">
            <input name="sl_no[]" id="sl_no{{$dy_id}}"
                class="form-control form-control-sm alldata" value="{{$slNoData}}" required placeholder="Sl No">
               
           
        </div>
    </div>
   
</div>
@endforeach
@endif