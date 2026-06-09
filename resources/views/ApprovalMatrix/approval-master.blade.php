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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-label-primary py-1">
        <h5 class="mb-0"><span class="text-muted fw-light">Approval Matrix /</span>Approval Master</h5>
        @if(isset($_GET['stage_module']))<a href="{{url('approval-matrix/approval-master')}}" class="ms-2 btn  btn-primary btn-sm waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Back to list"><span class="mdi mdi-keyboard-backspace"></span></a>@endif
    </div>

    <div class="card-body">
        <div class="row">
            @if(isset($_GET['stage_module']))
                <h3>{{ $_GET['moduleName'] }}</h2><hr>

                <form action="{{ url('approval-matrix/approval-master/insertApprover') }}?&stage_module={{$_GET['stage_module']}}&moduleName={{$_GET['moduleName']}}" class="row" method="POST">
                    <input type="hidden" name="stage_module" value="<?=$_GET['stage_module']?>" >
                    <input type="hidden" name="moduleName" value="<?=$_GET['moduleName']?>" >
                    @csrf

              

                    <!-- Dynamic Approver Fields -->
                    <div id="approverFieldsContainer">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" id="rowWrapper" class="btn btn-outline-primary add-btn">+ Add Stage</button>
                            </div>
                        </div>
                        
            

                        <div class="row mb-3">
                          @php
                            $count = count($stageDetails);
                          @endphp
                          @foreach ($stageDetails as $key=>$stageDetail)
                            <div class="col-md-4">
                                <label class="form-label">Stage Name:</label>
                                <input type="text" name="stage_name[]" class="form-control" value="{{ $stageDetail->stage_title }}" readonly/>
                                <input type="hidden" name="stage_id[]" class="form-control" value="{{ $stageDetail->id }}" readonly/>
                                <input type="hidden" name="stage_pos[]" class="form-control" value="{{ $key + 1 }}" required />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Employee:</label>
                                <select name="stage_emp_{{ $key + 1 }}[]" class="select2 form-select" multiple required>
                                  <option value="">Select an Employee</option>
                                  @foreach ($userList as $user)
                                    @php
                                      $isSelected = collect($approverDetails)->contains(function ($item) use ($user, $stageDetail) {
                                        return $item->person_id == $user->id && $item->stage_id == $stageDetail->id;
                                      });
                                    @endphp
                                    <option value="{{ $user->id }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $user->fullname }}
                                    </option>
                                  @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Status:</label>
                                <select name="status[]" class="form-select" required>
                                    <option value="1" {{ ($stageDetail->stage_stat == 1)?'selected':'' }}>Enable</option>
                                    <option value="0" {{ ($stageDetail->stage_stat == 0)?'selected':'' }}>Disable</option>
                                </select>
                            </div>
                          @endforeach
                            

                        </div>



                        <hr>
                        
                        <div class="row approverFieldGroup ">
                            
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            @else

              <!-- Table View -->
              <div class="table-responsive text-nowrap">
                <table class="dataTable no-footer table table-bordered text-nowrap w-100" id="example2">
                  <thead class="bg-label-dark">
                    <tr>
                      <th>Sl. No.</th>
                      <th>Stage Module</th>
                      <th>Stage Module Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="approvalTableBody">
                    @foreach ($ModuleList as $key=>$module)
                      <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $module->title }}</td>
                        <td>{{ $module->tableName }}</td>
                        <td><a class="btn btn-primary btn-xs text-capitalize waves-effect waves-light" href="?moduleName={{ $module->title }}&stage_module={{ $module->id }}" role="button"><i class="mdi mdi-eye"></i> VIEW</a></td>
                      </tr>                    
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
        </div>
    </div>
</div>
</div>

<script>
  let appendCount = {{ count($stageDetails) }};

  document.addEventListener('DOMContentLoaded', function () {
      $('.select2').select2();

      document.querySelector('#rowWrapper').addEventListener('click', addRow);

      const initialRemoveBtn = document.querySelector('.remove-btn');
      if (initialRemoveBtn) {
          initialRemoveBtn.addEventListener('click', function () {
              this.closest('.approverFieldGroup').remove();
              updateStagePositions();
              updateRemoveButtons();
          });
      }

      updateRemoveButtons();
  });

  function addRow() {
    appendCount++;

    const uniqueId = Date.now() + appendCount;

    const container = document.getElementById("approverFieldsContainer");

    const inputRow = document.createElement("div");
    inputRow.className = "row approverFieldGroup mb-3 align-items-end";
    inputRow.setAttribute("id", uniqueId); // Set unique ID to the row

    inputRow.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Stage Name:</label>
            <input type="text" name="stage_name[]" class="form-control" required />
            <input type="hidden" name="stage_id[]" class="form-control" value="${uniqueId}" required />
            <input type="hidden" name="stage_pos[]" class="form-control" value="${appendCount}" required />
        </div>

        <div class="col-md-4">
            <label class="form-label">Employee:</label>
            <select name="stage_emp_${appendCount}[]" class="select2 form-select" multiple required>
                <option value="">Select an Employee</option>
                ${getEmployeeOptions()}
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Status:</label>
            <select name="status[]" class="form-select" required>
                <option value="1">Enable</option>
                <option value="0">Disable</option>
            </select>
        </div>

        <div class="col-md-1 d-flex align-items-end gap-1 button-container">
            <button type="button" class="btn btn-danger btn-sm remove-btn">-</button>
        </div>
    `;

    container.appendChild(inputRow);
    $(inputRow).find('.select2').select2();

    inputRow.querySelector('.remove-btn').addEventListener('click', function () {
        inputRow.remove();
        updateRemoveButtons();
        appendCount--;
    });

    updateRemoveButtons();
  }

  // function updateStagePositions() {
  //     const rows = document.querySelectorAll('.approverFieldGroup');
  //     rows.forEach((row, index) => {
  //         const stagePosInput = row.querySelector('input[name="stage_pos[]"]');
  //         if (stagePosInput) {
  //             stagePosInput.value = index + 1;
  //         }
  //     });
  // }

  function updateRemoveButtons() {
      const allRemoveBtns = document.querySelectorAll('.remove-btn');
      allRemoveBtns.forEach((btn, index) => {
          btn.disabled = index !== allRemoveBtns.length - 1;
      });
  }

  function getEmployeeOptions() {
      const users = @json($userList);
      return users.map(user => `<option value="${user.id}">${user.fullname}</option>`).join('');
  }
</script>

@stop
