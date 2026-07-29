@extends('includes.layout')

@section('pageHeading')
    Glass Feeding Request View
@endsection

@section('content')

    <?php
      foreach($glassFeedDtls as $details);
    ?>
    
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0">Glass Feeding Request view page :</h5>
                <div class="text-end">
                    <a href="javascript: history.go(-1)" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span
                            class="mdi mdi-keyboard-backspace"></span></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Btch No : </label>
                            <span>{{ $details->batchNo }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Date : </label>
                            <span>{{ date('d/m/Y', strtotime($details->date)) }}</span>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Shift :</label>
                            <span>{{ $details->shiftdtl }}</span>
                        </div>
                    </div>

                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Plant No. :</label>
                            <span>{{ $details->plant }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Wattage :</label>
                            <span>{{ $details->wattage }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Effiencey :</label>
                            <span>{{ $details->efficiency }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Cell company Name :</label>
                            <span>{{ $details->brand }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Glass Size :</label>
                            <span>{{ $details->glassSize }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Operator :</label>
                            <span>{{ $details->operatorName }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Checker :</label>
                            <span>{{ $details->checkerName }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="mb-3">
                            <label class="fw-medium text-black">Finished Good :</label>
                            <span>{{ $details->matname ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                      <div class="table-responsive border rounded-4 border-bottom-0">
                          <table class="table m-0" id="">
                              <thead class="bg-label-hover-dark">
                                  <tr>
                                      <td>SL No</td>
                                      <td>Material</td>
                                      <td>Size</td>
                                      <td>Time</td>
                                      <td>Production Qty</td>
                                      <td>Rejection Qty</td>
                                      <td>Reason</td>
                                      <td>Defect Category</td>
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($glassFeedMtrl as $key=>$List)
                                  <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $List->title }}</td>
                                    <td>{{ $List->size }}</td>
                                    <td>{{ $List->time }}</td>
                                    <td>{{ $List->productionQty }}</td>
                                    <td>{{ $List->RejectQty }}</td>
                                    <td>{{ $List->reason }}</td>
                                    <td>{{ $List->defectCat }}</td>
                                  </tr>
                                @endforeach                                    
                              </tbody>
                          </table>
                      </div>
                  </div>

                  <div class="col-12 mt-5">
                      <div class="table-responsive text-nowrap">
                          <table class="table table-bordered">
                              <thead class="bg-gradient-start-1">
                                  <tr>
                                      <th>Sl.NO</th>
                                      <th>Stage Remark</th>
                                      <th>Approval Action By</th>
                                      <th>Created Date and Time</th>
                                      <th>Message</th>
                                      <th>IP Address</th>
                                  </tr>
                              </thead>
                              <tbody class="table-border-bottom-0">
                                @foreach($glassFeedTrail as $key=>$TList)
                                  <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $TList->action }}</td>
                                    <td>{{ $TList->fullname }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($TList->created_at)) }}</td>
                                    <td>{{ $TList->remarks }}</td>
                                    <td>{{ $TList->ip }}</td>
                                  </tr>
                                @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection
