@extends('includes.layout')

@section('pageHeading')
    Approval Master | Surya Factory Portal
@stop

@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    <!-- Success and Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('failed'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('failed') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                <h5 class="mb-0"><span class="text-muted fw-light">Approval Matrix /</span> Approval Module</h5>
                <a href="#" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addModule">
                  Add Module
                </a>
            </div>
            <div class="card-body">
                <div class="row">
        
                  <!-- Table View -->
                  <div class="table-responsive text-nowrap">
                      <table class="dataTable no-footer table table-bordered text-nowrap w-100" id="example2">
                          <thead class="bg-label-dark">
                              <tr>
                                  <th>Sl. No.</th>
                                  <th>Stage Module</th>
                                  <th>Stage Module Name</th>
                                  <th>Created At</th>
                              </tr>
                          </thead>
                          <tbody id="approvalTableBody">
                            @foreach ($ModuleList as $key=>$module)
                              <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $module->title }}</td>
                                <td>{{ $module->tableName }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($module->created_at)) }}</td>
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
</div>
<!-- The Modal -->
<div class="modal fade" id="addModule">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Module</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="{{ url('approval-matrix/approval-module/insert') }}" method="post">
          @csrf
          <div class="mb-3 mt-3">
            <label for="email" class="form-label">Module Nmae:</label>
            <input type="text" class="form-control" placeholder="Enter Module Name" name="module">
          </div>
          <div class="mb-3">
            <label for="pwd" class="form-label">Table Name:</label>
            <input type="text" class="form-control" placeholder="Enter Table Name" name="table">
          </div>
          <right><button type="submit" class="btn btn-primary">Submit</button></right>
        </form>
      </div>

    </div>
  </div>
</div>

@stop
