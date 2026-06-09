@foreach($InventoryManagement as $key=>$value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$admindata[$value->userID]}}</td>
                                    <td>{{$orgdata[$value->Organization_Name]}}</td>
                                    <td>{{$Manufacturing_unitdata[$value->Unit_Name]}}</td>
                                    <td>{{$plant_namedata[$value->Plant_Name]}}</td>
                                    <td>{{$BUdata[$value->BU_Name]}}</td>
                                    <td>{{$value->batch_no}}</td>
                                    <td>{{$value->QCCode}}</td>
                                    <td>{{$Raw_Materialdata[$value->Raw_Material]}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td>{{status($value->Approve_status)}}</td>
                                    <td  class="PendingColor">{{Pending_With(14,$value)}}</td>
                                    <td>
                                    @if(isset($value->status) && $value->status!=1)
                                        <a href="{{url('InventoryManagement/InputerView/'.$value->id)}}" class="btn btn-primary">View</a>
                                        @if($value->Approve_status == 'RECHECK' && isset($EXT[14]['inputer']))
                                        <a href="{{url('InventoryManagement/InventoryManagement/'.$value->id)}}" class="btn btn-secondary">Edit</a>
                                        @elseif(hold($value,'App\Models\InventoryManagement\Inventory_Management_Approve','Inventory_Management_id') > 0)
                                        <a href="{{url('InventoryManagement/Release_Hold/'.$value->id)}}" class="btn btn-secondary">Release</a>
                                        @endif
                                        @else
                                        <a href="{{url('InventoryManagement/InventoryManagement/'.$value->id)}}" class="btn btn-warning">Draft</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach