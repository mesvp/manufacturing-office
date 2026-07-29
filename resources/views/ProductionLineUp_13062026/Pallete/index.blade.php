@extends('includes.layout')

@section('pageHeading')
    Pallete Lists
@endsection

@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('success') }}
                </div>
            @endif

        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5><span class="text-muted fw-light"> Master/</span> Pallete Lists</h5>
            <div class="mb-2 text-end">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-2">
                        <h5 class="mb-0">Uploaded Pallete Lists</h5>
                        <div class="text-end">
                            <a href="#" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addModal"><span
                                    class="mdi mdi-playlist-plus me-1"></span> Add Pallete</a>


                            <div class="modal fade" id="addModal">
                              <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">

                                  <!-- Modal Header -->
                                  <div class="modal-header">
                                    <h4 class="modal-title">Upload CSV File</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <!-- Modal body -->
                                  <div class="modal-body">
                                    <form action="{{url('production-lineup/pallete/insert')}}" method="POST" enctype="multipart/form-data">
                                      @csrf
                                      
                                      <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="file" id="inputGroupFile" aria-label="Upload file" required>
                                        <button class="btn btn-primary" type="submit" id="button-addon1">Upload</button>
                                      </div>
                                    </form>
                                  </div>

                                 
                                </div>
                              </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <i>Download Sample CSV file <a href='{{url('sample/pallete.csv')}}' download><b><u>Click Here</u></b></a></i>
                      <table
                          class="d-block dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                          id="example" style="width:100%">
                          <thead class="table-secondary">
                              <tr>
                                  <td>SL No</td>
                                  <td>Pallete No</td>
                                  <td>Barcode</td>
                                  <td>Uploaded By</td>
                                  <td>Uploaded At</td>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($pallets as $key=>$pallet)
                              <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $pallet->pallete }}</td>
                                <td>{{ $pallet->serial }}</td>
                                <td>{{ $pallet->fullname }}</td>
                                <td>{{ $pallet->created_at }}</td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
@endsection
