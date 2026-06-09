@foreach($approves as $key=>$val)
<tr>
    <td>{{$key+1}}</td>
    <td>
        @if(!empty($val->action))
        {{isset($val->action) && $val->action!=''?$val->action:''}}
        @else
        {{isset($val->pre_post_approval) && $val->pre_post_approval!=''?$val->pre_post_approval:''}}
        @endif
    </td>
    <td>{{$admin[$val->userID]}}</td>
    <td>{{isset($val->role) && $val->role!=''?$val->role:''}}</td>
    <td>{{isset($val->created_at) && $val->created_at!=''?date('d-m-Y H:i:s A',strtotime($val->created_at)):''}}</td>
    <td>{{isset($val->comment_text) && $val->comment_text!=''?$val->comment_text:''}}</td>
    <td>{{isset($val->ip_address) && $val->ip_address!=''?$val->ip_address:''}}</td>
    <td>{{isset($val->device_name) && $val->device_name!=''?$val->device_name:''}}</td>
</tr>
@endforeach