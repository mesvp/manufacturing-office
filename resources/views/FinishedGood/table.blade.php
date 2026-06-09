@php
$i=1;
@endphp
@foreach($production as $value)
<tr>
    <td>{{$i++}}</td>
    <td>{{$admindata[$value->userID]}}</td>
    <td>{{$Manufacturing_unitdata[$value->Unit_Name]}}</td>
    <td>{{$plant_namedata[$value->Plant_Name]}}</td>
    <td>{{$orgdata[$value->Organization_Name]}}</td>
    <td>{{$BUdata[$value->BU_Name]}}</td>
    <td>{{$value->Shift}}</td>
    <td>{{$value->Production_Date}}</td>
    <td>{{$Raw_Materialdata[$value->Raw_Material]}}</td>
    <td>{{$value->UOM}}</td>
    <td>{{$value->Rate}}</td>
    <td>{{$value->Quantity}}</td>
    <td>{{get_batch($value->id)}}</td>
    <td>{{$value->created_at}}</td>
    <td>{{status($value->Approve_status)}}</td>
    <td  class="PendingColor">{{Pending_With(22,$value)}}</td>
    <td>
        @if(isset($value->status) && $value->status!=1)
        <a href="{{url('Production/InputerView/'.$value->id)}}" class="btn btn-primary"> <i class="fa-solid fa-eye"></i> View</a>
        @if($value->Approve_status == 'RECHECK' && isset($EXT[22]['inputer']))
        <a href="{{url('Production/Production/'.$value->id)}}" class="btn btn-secondary btn-sm">Edit</a>
        @elseif(hold($value,'App\Models\Production\ProductionApprove','productionID') > 0)
        <a href="{{url('Production/Release_Hold/'.$value->id)}}" class="btn btn-secondary">Release</a>
        @endif
        @else
        <a href="{{url('Production/Production/'.$value->id)}}" class="btn btn-warning">Draft</a>
        @endif
    </td>
</tr>
@endforeach