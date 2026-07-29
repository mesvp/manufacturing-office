@extends('includes.layout')

@section('pageHeading')
    Dashboard
@endsection

@section('content')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart1);
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
            ['Stage', 'Total Production'],
            ['LayUp',     {{($Bushing[0]->Rejected >0)?($Bushing[0]->Rejected/($Bushing[0]->Passed + $Bushing[0]->Rejected))*100:0}}],
            ['ELQC',      {{($EL[0]->Rejected >0)?($EL[0]->Rejected/($EL[0]->Passed + $EL[0]->Rejected))*100:0}}],
            ['90degree QC',  {{($Ninetydeg[0]->Rejected >0)?($Ninetydeg[0]->Rejected/($Ninetydeg[0]->Passed + $Ninetydeg[0]->Rejected))*100:0}}],
            ['JB', {{($JB[0]->Rejected >0)?($JB[0]->Rejected/($JB[0]->Passed + $JB[0]->Rejected))*100:0}}],
            ['FQC', {{($FQC[0]->Rejected >0)?($FQC[0]->Rejected/($FQC[0]->Passed + $FQC[0]->Rejected))*100:0}}]
        ]);

        var options = {
            title: 'Rejection Percentage',
            is3D: false,
            pieSliceText: 'value',
            
            chartArea: { 
                left: '15%',   // Shifts the chart area to the right, aligning it with the bar chart's grid
                width: '80%',  // Leaves breathing room for titles and legends
                height: '80%' 
            },
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
        chart.draw(data, options);
    }
    
    function drawChart2() {
    // Define your data (Keep your dynamic Laravel values here)
    var data = google.visualization.arrayToDataTable([
        ['Stage', 'Total Production', { role: 'annotation' }], // Added annotation role for text on bars
        ['LayUp', {{$Bushing[0]->Passed + $Bushing[0]->Rejected}}, '{{$Bushing[0]->Passed + $Bushing[0]->Rejected}}'],
        ['ELQC', {{$EL[0]->Passed + $EL[0]->Rejected}}, '{{$EL[0]->Passed + $EL[0]->Rejected}}'],
        ['90degree QC', {{$Ninetydeg[0]->Passed + $Ninetydeg[0]->Rejected}}, '{{$Ninetydeg[0]->Passed + $Ninetydeg[0]->Rejected}}'],
        ['JB', {{$JB[0]->Passed + $JB[0]->Rejected}}, '{{$JB[0]->Passed + $JB[0]->Rejected}}'],
        ['FQC', {{$FQC[0]->Passed + $FQC[0]->Rejected}}, '{{$FQC[0]->Passed + $FQC[0]->Rejected}}']
    ]);

    // Chart options tailored for a clean bar look
    var options = {
        title: 'Total Production',
        legend: { position: 'none' }, // Hides legend since stages are on the X-axis
        bar: { groupWidth: '60%' },   // Spaces out the bars nicely
        vAxis: { 
            title: 'Units Produced',
            minValue: 0 
        },
        hAxis: { 
            title: 'Production Stage' 
        }
    };

    // FIX: Changed from visualization.PieChart to visualization.ColumnChart
    var chart = new google.visualization.ColumnChart(document.getElementById('piechart2'));
    chart.draw(data, options);
}

    // Fix: Redraws the chart when browser window size changes
    window.addEventListener('resize', drawChart1);
    window.addEventListener('resize', drawChart2);
</script>
    
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5><span class="text-muted fw-light"> Production Line Up /</span> Dashboard</h5>
            <div class="mb-2 text-end">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
               
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">Dashboard</h5>
                        
                    </div>
                    <div class="card-body">

                      <div class="row">
                          
                        <div class="col-md-7">
                          <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h5><span class="text-muted fw-light">Today's Production (<?=Date('d/m/Y')?>)</h5>
                            <div class="mb-2 text-end">
                              <a href="dashboard/daily-production" class="btn btn-outline-primary">View Details</a>
                            </div>
                          </div>
                            <table
                                class="table table-sm table-bordered table-responsive text-nowrap w-100 align-top"
                                id="" 
                                style="width: 100%; font-size: 16px;">
                                
                                <thead class="table-secondary">
                                  <tr>
                                    <th style="width: 5%;">SL No</th>
                                    <th style="width: 10%;">Shift</th>
                                    <th style="width: 45%;">Material</th>
                                    <th style="width: 10%;">Watt</th>
                                    <th style="width: 10%;">M.W./P</th>
                                    <th style="width: 10%;">Total No.</th>
                                    <th style="width: 10%;">Total M.W.</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($AllLists as $key=>$dataList)
                                    <tr>
                                      <td>{{++$key}}</td>
                                      <td>{{$dataList->shift}}</td>
                                      <td class="text-wrap text-break" style="max-width: 250px;">
                                          {{$dataList->finish_good_name}}
                                      </td>
                                      <td>{{$dataList->wattage}}</td>
                                      <td>{{(int)$dataList->wattage/1000000}}</td>
                                      <td>{{$dataList->totalMatNo}}</td>
                                      <td>{{((int)$dataList->wattage/1000000)*$dataList->totalMatNo}}</td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                          
                          <hr>
                          
                          <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <h5><span class="text-muted fw-light">Today's Rejection Percentage (<?=Date('d/m/Y')?>)</h5>
                            <div class="mb-2 text-end">
                              <a href="dashboard/rejection-percentage" class="btn btn-outline-primary">View Details</a>
                            </div>
                          </div>
                            <table
                                class="table table-sm table-bordered table-responsive text-nowrap w-100 align-middle"
                                id="" 
                                style="width:100%; font-size: 16px;">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>#</th>
                                        <th>Stage</th>
                                        <th>Passed</th>
                                        <th>Reject</th>
                                        <th>Rework</th>
                                        <th>Total</th>
                                        <th>Rejection %</th>
                                        <th>Rework %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Bushing as $dataList)
                                        @php 
                                            $totalBushing = $dataList->Passed + $dataList->Rejected; 
                                            $rejectBushingPct = ($totalBushing > 0) ? ($dataList->Rejected / $totalBushing) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>1</td>
                                            <td>Layup</td>
                                            <td>{{$dataList->Passed}}</td>
                                            <td>{{$dataList->Rejected}}</td>
                                            <td>0</td>
                                            <td>{{$totalBushing}}</td>
                                            <td>{{ number_format($rejectBushingPct, 2) }} %</td>
                                            <td>0.00 %</td>
                                        </tr>
                                    @endforeach
                                    
                                    @foreach($EL as $dataList)
                                        @php 
                                            $totalEL = $dataList->Passed + $dataList->Rejected; 
                                            $rejectELPct = ($totalEL > 0) ? ($dataList->Rejected / $totalEL) * 100 : 0;
                                            $reworkELPct = ($totalEL > 0) ? ($dataList->Rework / $totalEL) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>2</td>
                                            <td>ELQC</td>
                                            <td>{{$dataList->Passed}}</td>
                                            <td>{{$dataList->Rejected}}</td>
                                            <td>{{$dataList->Rework}}</td>
                                            <td>{{$totalEL}}</td>
                                            <td>{{ number_format($rejectELPct, 2) }} %</td>
                                            <td>{{ number_format($reworkELPct, 2) }} %</td>
                                        </tr>
                                    @endforeach
                            
                                    @foreach($Ninetydeg as $dataList)
                                        @php 
                                            $total90 = $dataList->Passed + $dataList->Rejected; 
                                            $reject90Pct = ($total90 > 0) ? ($dataList->Rejected / $total90) * 100 : 0;
                                            $rework90Pct = ($total90 > 0) ? ($dataList->Rework / $total90) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>3</td>
                                            <td>Ninetydegree QC</td>
                                            <td>{{$dataList->Passed}}</td>
                                            <td>{{$dataList->Rejected}}</td>
                                            <td>{{$dataList->Rework}}</td>
                                            <td>{{$total90}}</td>
                                            <td>{{ number_format($reject90Pct, 2) }} %</td>
                                            <td>{{ number_format($rework90Pct, 2) }} %</td>
                                        </tr>
                                    @endforeach
                            
                                    @foreach($JB as $dataList)
                                        @php 
                                            $totalJB = $dataList->Passed + $dataList->Rejected; 
                                            $rejectJBPct = ($totalJB > 0) ? ($dataList->Rejected / $totalJB) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>4</td>
                                            <td>junction Box</td>
                                            <td>{{$dataList->Passed}}</td>
                                            <td>{{$dataList->Rejected}}</td>
                                            <td>0</td>
                                            <td>{{$totalJB}}</td>
                                            <td>{{ number_format($rejectJBPct, 2) }} %</td>
                                            <td>0.00 %</td>
                                        </tr>
                                    @endforeach
                            
                                    @foreach($FQC as $dataList)
                                        @php 
                                            $totalFQC = $dataList->Passed + $dataList->Rejected; 
                                            $rejectFQCPct = ($totalFQC > 0) ? ($dataList->Rejected / $totalFQC) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>5</td>
                                            <td>Final QC</td>
                                            <td>{{$dataList->Passed}}</td>
                                            <td>{{$dataList->Rejected}}</td>
                                            <td>0</td>
                                            <td>{{$totalFQC}}</td>
                                            <td>{{ number_format($rejectFQCPct, 2) }} %</td>
                                            <td>0.00 %</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                
                            
                            
                          
                        </div>
                        
                        <div class="col-md-5">
                            <div id="piechart2" style="width: 100%; min-height: 400px;"></div>
                            <hr>
                            <div id="piechart1" style="width: 100%; min-height: 400px;"></div>
                        </div>
                        
                        
                        <div class="col-md-12 table-responsive">
                             <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h5><span class="text-muted fw-light">Today's Line Efficienct Report Shift Wise Production </h5>
                                <!--<div class="mb-2 text-end">-->
                                <!--  <a href="dashboard/daily-production" class="btn btn-outline-primary">View Details</a>-->
                                <!--</div>-->
                              </div>
                          
                            <table
                                class="table table-sm table-bordered table-responsive text-nowrap w-100 align-middle"
                                id="examplee" 
                                style="width:100%; font-size: 16px;">
                                <thead class="table-secondary">
                                    <tr>
                                        <th rowspan = 2>#</th>
                                        <th rowspan = 2>Stage</th>
                                        <th rowspan = 2>Batch No</th>
                                        @foreach($ShiftMaster as $shift)
                                        <th colspan = 2>Shift {{ $shift->shift }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($ShiftMaster as $shift)
                                        <th>in Nos</th>
                                        <th>in MW</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <!--Lay Up    -->
                                    
                                    @php
                                    $i = 1;
                                    foreach($efficiencyBushing as $dataList){
                                        echo '<tr style="background-color: #e6f2ff;"><td>'.$i.'</td>
                                    <td><b><u>Layup</u></b></td><td>'.$dataList->bushing_batchNo.'</td>';
                                        foreach($ShiftMaster as $shift){
                                           if($shift->id == $dataList->bushing_shift){
                                            $flag = 1;
                                            echo '<td>'.$dataList->totalNo.'</td>';
                                            echo '<td>'.number_format(((int)$dataList->wattage/1000000)*$dataList->totalNo,7).'</td>';
                                           }
                                           else{
                                            $flag = 0;
                                           }
                                           if($flag == 0){
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                           }
                                        }
                                     echo '</tr>';   
                                     $i++;
                                    }
                                    @endphp
                                        
                                    
                                    <!--EL-->
                                
                                    @php
                                    foreach($efficiencyEL as $dataList){
                                        echo '<tr style="background-color: #eafaf1;"><td>'.$i.'</td>
                                    <td><b><u>EL QC</u></b></td><td>'.$dataList->elqc_batchNo.'</td>';
                                        foreach($ShiftMaster as $shift){
                                           if($shift->id == $dataList->elqc_shift){
                                            $flag = 1;
                                            echo '<td>'.$dataList->totalNo.'</td>';
                                            echo '<td>'.number_format(((int)$dataList->wattage/1000000)*$dataList->totalNo,7).'</td>';
                                           }
                                           else{
                                            $flag = 0;
                                           }
                                           if($flag == 0){
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                           }
                                        }
                                     echo '</tr>';   
                                     $i++;
                                    }
                                    @endphp
                                        
                                    
                                    <!--90 Degree-->
                                
                                    @php
                                    foreach($efficiencyNinety as $dataList){
                                        echo '<tr style="background-color: #fef9e7;"><td>'.$i.'</td>
                                    <td><b><u>90 Degree QC</u></b></td><td>'.$dataList->ninetydeg_batchNo.'</td>';
                                        foreach($ShiftMaster as $shift){
                                           if($shift->id == $dataList->ninetydeg_shift){
                                            $flag = 1;
                                            echo '<td>'.$dataList->totalNo.'</td>';
                                            echo '<td>'.number_format(((int)$dataList->wattage/1000000)*$dataList->totalNo,7).'</td>';
                                           }
                                           else{
                                            $flag = 0;
                                           }
                                           if($flag == 0){
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                           }
                                        }
                                     echo '</tr>'; 
                                     $i++;
                                    }
                                    @endphp
                                        
                                    
                                    <!--Junction Box-->
                                
                                    @php
                                    foreach($efficiencyJB as $dataList){
                                        echo '<tr style="background-color: #f5eef8;"><td>'.$i.'</td>
                                    <td><b><u>Junction Box</u></b></td><td>'.$dataList->jb_batchNo.'</td>';
                                        foreach($ShiftMaster as $shift){
                                           if($shift->id == $dataList->jb_shift){
                                            $flag = 1;
                                            echo '<td>'.$dataList->totalNo.'</td>';
                                            echo '<td>'.number_format(((int)$dataList->wattage/1000000)*$dataList->totalNo,7).'</td>';
                                           }
                                           else{
                                            $flag = 0;
                                           }
                                           if($flag == 0){
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                           }
                                        }
                                     echo '</tr>';   
                                     $i++;
                                    }
                                    @endphp
                                        
                                        
                                    
                                    <!--Final QC-->
                                
                                    @php
                                    foreach($efficiencyFQC as $dataList){
                                        echo '<tr style="background-color: #fdf2e9;"><td>'.$i.'</td>
                                    <td><b><u>Final QC</u></b></td><td>'.$dataList->fqc_batchNo.'</td>';
                                        foreach($ShiftMaster as $shift){
                                           if($shift->id == $dataList->fqc_shift){
                                            $flag = 1;
                                            echo '<td>'.$dataList->totalNo.'</td>';
                                            echo '<td>'.number_format(((int)$dataList->wattage/1000000)*$dataList->totalNo,7).'</td>';
                                           }
                                           else{
                                            $flag = 0;
                                           }
                                           if($flag == 0){
                                            echo '<td>0</td>';
                                            echo '<td>0</td>';
                                           }
                                        }
                                     echo '</tr>';   
                                     $i++;
                                    }
                                    @endphp
                                        
                                    
                                    
                                </tbody>
                            </table>
                        </div>

                        
                        
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
@endsection
