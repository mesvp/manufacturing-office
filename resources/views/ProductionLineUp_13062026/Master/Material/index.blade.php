@extends('includes.layout')

@section('pageHeading')
    Material Master List
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Material Master List :</h5>
                        <div class="text-end">
                            <button type="button" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <span class="mdi mdi-playlist-plus me-1"></span> Add</a>
                            </button>
                        </div>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('production-lineup/material/insert') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Material</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="material_name" class="form-label">Material Name</label>
                                                <input type="text" class="form-control" id="material_name" name="material_name"
                                                    placeholder="Enter Material Name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="uom" class="form-label">UOM</label>
                                                <input type="text" class="form-control" id="uom" name="uom"
                                                    placeholder="Enter UOM" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Material</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                            id="example7">
                            <thead class="table-secondary">
                                <tr>
                                    <td>SL No</td>
                                    <td>Material Name</td>
                                    <td>UOM</td>
                                    <td>Created At</td>
                                    <td>Created By</td>
                                    {{-- <td>Operation</td> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materials as $key => $material)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $material->title }}</td>
                                        <td>{{ $material->uom }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($material->created_at)) }}</td>
                                        <td>{{ $admindata[$material->created_by] ?? '' }}</td>
                                        {{-- <td>
                                            <a href="" class="btn btn-warning btn-xs text-capitalize waves-effect waves-light"><i class="mdi mdi-pencil"></i></a>
                                            <form action="" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs text-capitalize waves-effect waves-light"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td> --}}
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
