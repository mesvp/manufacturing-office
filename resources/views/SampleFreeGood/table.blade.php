@php
                                $i=1;
                                @endphp
                                @foreach($Sample_data as $value)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$admindata[$value->userID]}}</td>
                                    <td>{{$Manufacturing_unitdata[$value->Manufacturing_Unit]}}</td>
                                    <td>{{$plant_namedata[$value->Plant_Name]??''}}</td>
                                    <td>{{$Godown_data[$value->Godown_Name]??''}}</td>
                                    <td>{{$orgdata[$value->Organization_Name]}}</td>
                                    <td>{{$BUdata[$value->BU]}}</td>
                                    <td>{{$Raw_Materialdata[$value->Raw_Material]}}</td>
                                    <td>{{$uom_data[$value->UOM]}}</td>
                                    <td>{{$value->Quantity??''}}</td>
                                    <td>{{$value->Date??''}}</td>
                                    <td>{{$value->CustomerName??''}}</td>
                                    <td>{{$value->CustomerAddress??''}}</td>
                                    <td>{{$value->CustomerPhone??''}}</td>
                                    <td>{{$value->CompanyName??''}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td>{{status($value->Approve_status)}}</td>
                                    <td  class="PendingColor">{{Pending_With(10,$value)}}</td>
                                    <td>
                                        @if(isset($value->status) && $value->status!=1)
                                        <a href="{{url('SampleFreeGood/InputerView/'.$value->id)}}" class="btn btn-primary">View</a>
                                        @if($value->Approve_status == 'RECHECK' && isset($EXT[10]['inputer']))
                                        <a href="{{url('SampleFreeGood/SampleFreeGood/'.$value->id)}}" class="btn btn-secondary">Edit</a>
                                        @elseif(hold($value,'App\Models\SampleFreeGood\SampleFreeGoodApprove','SampleFreeGoodID') > 0)
                                        <a href="{{url('SampleFreeGood/Release_Hold/'.$value->id)}}" class="btn btn-secondary">Release</a>
                                        @endif
                                        @else
                                        <a href="{{url('SampleFreeGood/SampleFreeGood/'.$value->id)}}" class="btn btn-warning">Draft</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach