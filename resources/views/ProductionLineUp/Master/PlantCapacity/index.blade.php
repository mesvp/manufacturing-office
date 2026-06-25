@extends('includes.layout')

@section('pageHeading')
    Plant Capacity Master List
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Plant Capacity Master List :</h5>
                        <div class="text-end">
                            <button type="button" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <span class="mdi mdi-playlist-plus me-1"></span> Add</a>
                            </button>
                        </div>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('production-lineup/master/plant_capacity/insert') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Set Capacity</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="mb-3 col-sm-12">
                                                <label for="material_name" class="form-label">Plant Name</label>
                                                <input type="text" class="form-control" id="material_name" name="plant_name"
                                                    placeholder="Enter Material Name" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Hourly Capacity Nos</label>
                                                <input type="text" class="form-control" id="uom" name="hcapacitynos"
                                                    placeholder="Enter Hourly Capacity Nos" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Hourly Capacity MW</label>
                                                <input type="text" class="form-control" id="uom" name="hcapacitymw"
                                                    placeholder="Enter Hourly Capacity MW" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Daily Capacity Nos</label>
                                                <input type="text" class="form-control" id="uom" name="dcapacitynos"
                                                    placeholder="Enter Daily Capacity Nos" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Daily Capacity MW</label>
                                                <input type="text" class="form-control" id="uom" name="dcapacitymw"
                                                    placeholder="Enter Daily Capacity MW" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Monthly Capacity Nos</label>
                                                <input type="text" class="form-control" id="uom" name="mcapacitynos"
                                                    placeholder="Enter Monthly Capacity Nos" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Monthly Capacity MW</label>
                                                <input type="text" class="form-control" id="uom" name="mcapacitymw"
                                                    placeholder="Enter Monthly Capacity MW" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Yearly Capacity Nos</label>
                                                <input type="text" class="form-control" id="uom" name="ycapacitynos"
                                                    placeholder="Enter Yearly Capacity Nos" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Yearly Capacity MW</label>
                                                <input type="text" class="form-control" id="uom" name="ycapacitymw"
                                                    placeholder="Enter Yearly Capacity MW" required>
                                            </div>
                                            <div class="mb-3 col-sm-12">
                                                <label for="uom" class="form-label">Effective from Date</label>
                                                <input type="date" class="form-control" name="effectDate" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
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
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
