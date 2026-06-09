@if($edit->Approve_status=='OBJECT' && $edit->userID==auth()->user()->id)
<form action="{{url('FinishedGood/approve')}}" method="POST">
    @csrf
    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
    <div class="form-group" id="u_rama">
        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Reply" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    @if(isset($nextID) && !empty($nextID))
    <a href="{{url('FinishedGood/FinishedGoodView/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @else
    <a href="{{url('FinishedGood/Finished_Good_List')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @endif
</form>
@else
<form action="{{url('FinishedGood/approve')}}" method="POST">
    @csrf
    <input type="hidden" name="approveID" value="{{isset($edit->id) && $edit->id!=''?$edit->id:''}}">
    <input type="hidden" name="non_acting" value="1">
    <div class="button_div">
        <div class="selector">
            <div class="selecotr-item">
                <input type="radio" id="radio6" name="pre_post_approval" class="selector-item_radio" value="AUDIT" required>
                <label for="radio6" class="selector-item_label">AUDIT</label>
            </div>
            <div class="selecotr-item">
                <input type="radio" id="radio8" name="pre_post_approval" class="selector-item_radio" value="INTIMATION" required>
                <label for="radio8" class="selector-item_label">INTIMATION</label>
            </div>
            <div class="selecotr-item">
                <input type="radio" id="radio9" name="pre_post_approval" class="selector-item_radio" value="QUERY" required>
                <label for="radio9" class="selector-item_label">QUERY</label>
            </div>
        </div>
    </div>
    <div class="form-group" id="u_rama">
        <textarea class="form-control" name="comment_text" id="" rows="5" placeholder="Remarks" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    @if(isset($nextID) && !empty($nextID))
    <a href="{{url('FinishedGood/FinishedGoodView/'.$nextID)}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @else
    <a href="{{url('FinishedGood/Finished_Good_List')}}"><button type="button" class="btn btn-secondary">NEXT</button></a>
    @endif
</form>
@endif