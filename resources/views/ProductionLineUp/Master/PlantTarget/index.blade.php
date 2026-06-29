@extends('includes.layout')

@section('pageHeading')
    Plant Target Master List
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
                        <h5 class="mb-0">Plant Target Master List :</h5>
                        <div class="text-end">
                            <button type="button" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <span class="mdi mdi-playlist-plus me-1"></span> Add</a>
                            </button>
                        </div>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ url('production-lineup/master/plant_target/insert') }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Set Target</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="mb-3 col-sm-12">
                                                <label for="material_name" class="form-label">Plant Name</label>
                                                <select class="select2 form-select" name="plant_name" required>
                                                    <option value="">Select Plant</option>
                                                    @foreach($PlantMaster as $plant)
                                                    <option value="{{ $plant->mstr_type_name }}">{{ $plant->mstr_type_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Target in Nos</label>
                                                <input type="text" class="form-control" id="uom" name="targetnos"
                                                    placeholder="Enter Target in Nos" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Target in MW</label>
                                                <input type="text" class="form-control" id="uom" name="targetmw"
                                                    placeholder="Enter Target in MW" required>
                                            </div>
                                            
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Effective from Date</label>
                                                <input type="date" class="form-control" name="effectFromDate" required>
                                            </div>
                                            <div class="mb-3 col-sm-6">
                                                <label for="uom" class="form-label">Effective to Date</label>
                                                <input type="date" class="form-control" name="effectToDate" required>
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
                    <div class="card-body table-responsive">
                        <table class="dataTable no-footer table table-bordered table-responsive text-nowrap w-100"
                            id="example7">
                            <thead class="table-secondary">
                              <tr>
                                <td>SL No</td>
                                <td>Plant Name Name</td>
                                <td>Target in Nos</td>
                                <td>Target in MW</td>
                                <td>Effected From</td>
                                <td>Effected To</td>
                                <td>Created At</td>
                                <td>Created By</td>
                                {{-- <td>Operation</td> --}}
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($AllLists as $key=>$dataList)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $dataList->plantNo }}</td>
                                    <td>{{ $dataList->targetNos }}</td>
                                    <td>{{ $dataList->targetMW }}</td>
                                    <td>{{ $dataList->startDate }}</td>
                                    <td>{{ $dataList->endDate }}</td>
                                    <td>{{ $dataList->created_at }}</td>
                                    <td>{{ $dataList->createdByName }}</td>
                                </tr>
                                @endforeach;
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
