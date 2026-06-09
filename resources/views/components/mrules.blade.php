@php
$currentTab = request()->input('typeaction');
if(empty($currentTab) || $currentTab == null) {
    $currentTab = 'ALL';
}
@endphp
<ul class="nav nav-pills" id="tabingdata" >
  <li class="nav-item">
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'ALL' ? 'active' : ''}}" data-type="ALL" id="Alls" >All  <span class="countss">{{$data['All']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'APPROVE' ? 'active' : ''}}" id="Approveds" data-type="APPROVE" >Approved <span class="countss">{{$data['Approved']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'Pendings' ? 'active' : ''}}" id="Pendings" data-type="Pendings">Pending <span class="countss">{{$data['Pending']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'HOLD' ? 'active' : ''}}" id="Holds" data-type="HOLD">Hold <span class="countss">{{$data['Hold']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'RECHECK' ? 'active' : ''}}" id="Rechecks"  data-type="RECHECK">Recheck <span class="countss">{{$data['Recheck']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'OBJECT' ? 'active' : ''}}" id="Objects"  data-type="OBJECT">Object <span class="countss">{{$data['Object']}}</span></a>
  </li>
  <li class="nav-item" >
    <a class="nav-link count tabingdata p-2 {{$currentTab == 'REJECT' ? 'active' : ''}}" id="Rejects" data-type="REJECT">Reject <span class="countss">{{$data['Reject']}}</span></a>
  </li>
</ul>


