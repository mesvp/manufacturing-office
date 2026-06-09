@extends('layout.main')
@section('main-container')
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<div class="card">
    <div class="app-content">
        <section class="section">
            <div class="addbtn extra">
                <a href="{{url('Master/Raw_Material')}}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> BACK</a>
                <a href="{{url('Master/Raw_Material')}}" class="btn btn-info" style="margin-left:10px"> <i class="fa fa-home"></i> Home</a>
            </div>
            <div class="row">
                <div class="tab2" id="tabledata">
                    <div class="row">
                        <div class="col-6">
                            <h5>Material Details Store Wise</h5>
                        </div>
                    </div>
                   
                    
                            <div class="table-responsive">
                                <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="th-sm">SL. No.</th>
                                            <th class="th-sm">Organization Name</th>
                                            <th class="th-sm">Godown Name</th>
                                            <th class="th-sm">Material</th>
                                            <th class="th-sm">Date</th>
                                            <th class="th-sm">Quantity</th>
                                            <th class="th-sm">Rate</th>
                                            <th class="th-sm">GST</th>
                                            <th class="th-sm">Amount</th>
                                            <th class="th-sm">Supplier Name</th>
                                            <th class="th-sm">Project</th>
                                            <th class="th-sm">Sub Project</th>
                                            <th class="th-sm">Mrn No.</th>
                                            <th class="th-sm">Invoice No.</th>
                                            <th class="th-sm">Mrn Date.</th>
                                            <th class="th-sm">Created By</th>
                                            <th class="th-sm">Created AT</th>
                                            <th class="th-sm">Narration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Godown_Material_details as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{isset($val->organization->organisation) && $val->organization->organisation!=''?$val->organization->organisation:''}}</td>
                                            <td>{{isset($val->Godown_Name->inventory_name) && $val->Godown_Name->inventory_name!=''?$val->Godown_Name->inventory_name:''}}</td>
                                            <td>{{isset($val->Material->matname) && $val->Material->matname!=''?$val->Material->matname:''}}</td>
                                            <td>{{isset($val->Date) && $val->Date!=''?$val->Date:''}}</td>
                                            <td>{{isset($val->Quantity) && $val->Quantity!=''?$val->Quantity:''}}</td>
                                            <td>{{isset($val->Rate) && $val->Rate!=''?$val->Rate:''}}</td>
                                            <td>{{isset($val->GST) && $val->GST!=''?$val->GST:''}}</td>
                                            <td>{{isset($val->Amount) && $val->Amount!=''?$val->Amount:''}}</td>
                                            <td>{{isset($val->Supplier_Name) && $val->Supplier_Name!=''?$val->Supplier_Name:''}}</td>
                                            <td>{{isset($val->Project) && $val->Project!=''?$val->Project:''}}</td>
                                            <td>{{isset($val->Sub_Project) && $val->Sub_Project!=''?$val->Sub_Project:''}}</td>
                                            <td>{{isset($val->Mrn_No) && $val->Mrn_No!=''?$val->Mrn_No:''}}</td>
                                            <td>{{isset($val->Invoice_No) && $val->Invoice_No!=''?$val->Invoice_No:''}}</td>
                                            <td>{{isset($val->Mrn_Date) && $val->Mrn_Date!=''?$val->Mrn_Date:''}}</td>
                                            <td>{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</td>
                                            <td>{{isset($val->created_at) && $val->created_at!=''?$val->created_at:''}}</td>
                                            <td>{{isset($val->through) && $val->through!=''?$val->through:''}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                       
                    
                </div>
            </div>
        </section>
    </div>
</div>
@endsection