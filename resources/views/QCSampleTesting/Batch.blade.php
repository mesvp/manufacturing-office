<table>
    <thead>
        <tr>
            <th>
                Sl No.
            </th>
            <th>
                Result
            </th>
            <th>
                Remark
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($batch as $valuse)
        <tr>
            <td>
            {{$valuse->sl_no}}
            <input type="hidden" name="sl_no[{{$valuse->id}}]" value="{{$valuse->sl_no}}">
            </td>
            <td>
                <select name="result[{{$valuse->id}}]" class="form-select form-select-sm mt-3">
                    <option {{isset($resultdata[$valuse->id]->result) && $resultdata[$valuse->id]->result=='PASS'?'selected':''}}>PASS</option>
                    <option {{isset($resultdata[$valuse->id]->result) && $resultdata[$valuse->id]->result=='FAIL'?'selected':''}}>FAIL</option>
                </select>
            </td>
            <td>
                <input type="text" name="remarks[{{$valuse->id}}]" value="{{$resultdata[$valuse->id]->remark??''}}" placeholder="Remark" class="form-control form-control-sm ml-2">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<input type="hidden" name="productionID" value=" {{$valuse->productionID??''}}">