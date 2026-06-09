@php
$STEP = Session::get('STEP');
$EXT = Session::get('EXT');
@endphp
@if($edit->Approve_status!='REJECT')
<form action="{{url('Production/approve')}}" method="POST">
    @csrf
    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
    <div class="tab-content" id="myTabContent">
        @if($edit->Approve_status!='APPROVE' && in_array(1, $STEP) || in_array(2, $STEP) || in_array(3, $STEP) || isset($EXT[17]['Forward']))
        <div class="button_div">
            <div class="selector">
                <div class="selecotr-item">
                    <input type="radio" id="radio1" name="during_approval" class="selector-item_radio" value="APPROVE" required>
                    <label for="radio1" class="selector-item_label">APPROVE</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio2" name="during_approval" class="selector-item_radio" value="REJECT" required>
                    <label for="radio2" class="selector-item_label">REJECT</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio18" name="during_approval" class="selector-item_radio" value="RECHECK" required>
                    <label for="radio18" class="selector-item_label">RECHECK</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio4" name="during_approval" class="selector-item_radio" value="HOLD" required>
                    <label for="radio4" class="selector-item_label">HOLD</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio7" name="during_approval" class="selector-item_radio" value="OBJECT" required>
                    <label for="radio7" class="selector-item_label">OBJECT</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio5" name="during_approval" class="selector-item_radio" value="FORWARD" required>
                    <label for="radio5" class="selector-item_label">FORWARD</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio15" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                    <label for="radio15" class="selector-item_label">AUDIT</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio16" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                    <label for="radio16" class="selector-item_label">INTIMATION</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio17" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                    <label for="radio17" class="selector-item_label">QUERY</label>
                </div>
            </div>
            <div id="showfields" class="row" style="display: none;">
                <div class="col-sm-4 form-group">
                    <label>Days For Holding</lable>
                        <input type="date" style="border-radius: 12px;" name="days_for_holding" placeholder="Days For Holding" min="{{date('Y-m-d')}}" class="form-control form-control-sm requireddd" value="">
                </div>
            </div>
            <div id="Forwords" class="row" style="display: none;">
                <div class="col-sm-4 form-group">
                    <label>Forward To</lable>
                        <select class="form-select form-select-sm requirrreddd" name="Forward_To">
                            <option value="" selected disabled>Select</option>
                            @foreach($employeeName as $val)
                            <option value="{{isset($val->id) && $val->id!=''?$val->id:''}}">{{isset($val->fullname) && $val->fullname!=''?$val->fullname:''}}</option>
                            @endforeach
                        </select>
                </div>
            </div>
        </div>
        @else
        <div class="button_div">
            <div class="selector">
                <div class="selecotr-item">
                    <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT">
                    <label for="radio6" class="selector-item_label">AUDIT</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION">
                    <label for="radio8" class="selector-item_label">INTIMATION</label>
                </div>
                <div class="selecotr-item">
                    <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY">
                    <label for="radio9" class="selector-item_label">QUERY</label>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="form-group" id="u_rama">
        <textarea class="form-control" name="comment_text" id="" rows="3" placeholder="Remarks" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    @if(isset($nextID) && !empty($nextID))
    <a href="{{url('StoreRequistion/view-approve/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @else
    <a href="{{url('StoreRequistion/StoreRequistionApproveList')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @endif
</form>
@endif
<script>
    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio4').is(':checked')) {
                $('#showfields').show();
                $('.requireddd').prop('required', true);
            } else {
                $('#showfields').hide();
                $('.requireddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#showfields').hide();
            $('.requireddd').prop('required', false);
        });
    });

    $(document).ready(function() {
        $('input[type=radio][name=during_approval]').on('click', function() {
            if ($('#radio5').is(':checked')) {
                $('#Forwords').show();
                $('.requirrreddd').prop('required', true);
            } else {
                $('#Forwords').hide();
                $('.requirrreddd').prop('required', false);
            }
        });

        $('input[type=radio][name=pre_post_approval]').on('click', function() {
            $('#Forwords').hide();
            $('.requirrreddd').prop('required', false);
        });
    });
</script>
<script>
    const prePostApprovalRadios = document.querySelectorAll('[name="pre_post_approval"]');
    const duringApprovalRadios = document.querySelectorAll('[name="during_approval"]');
    const duringApprovalFields = document.querySelector('.selector');

    prePostApprovalRadios.forEach(prePostRadio => {
        prePostRadio.addEventListener('change', () => {
            if (prePostRadio.checked) {
                duringApprovalRadios.forEach(duringRadio => {
                    duringRadio.checked = false;
                    duringRadio.removeAttribute('required');
                });

                duringApprovalFields.classList.add('disabled');
            }
        });
    });

    duringApprovalRadios.forEach(duringRadio => {
        duringRadio.addEventListener('change', () => {
            if (duringRadio.checked) {
                prePostApprovalRadios.forEach(prePostRadio => {
                    prePostRadio.checked = false;
                });

                duringApprovalFields.classList.remove('disabled');
            }
        });
    });
</script>