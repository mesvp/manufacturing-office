@php($i = 1)
@foreach($Materials as $val)
<tr>
    <td class="td-sm">{{$i++}}</td>
    <td class="td-sm">{{$val->RawMaterial->matname}}
        <input id="materialID{{$i}}" type="hidden" value="{{$val->RawMaterial->id}}" name="materialID[]">
        <input id="materialID{{$i}}" type="hidden" value="{{$val->RawMaterial->Material_Name}}" name="Material_Name[]">
    </td>

    <td class="td-sm">{{$val->Plantstock}}
        <input id="Plantstock{{$i}}" type="hidden" value="{{$val->Plantstock}}" name="Plantstock[]">
        <input  type="hidden" value="{{$i}}" name="idd[]">
    </td>
    <td class="td-sm">{{$val->RawMaterial->UOM}}
        <input name="UMO[]" type="hidden" value="{{$val->RawMaterial->UOM}}">
    </td>
    <td class="td-sm">{{$val->Material_QTY*request()->Quantity}}
        <input id="Material_QTY{{$i}}" type="hidden" value="{{$val->Material_QTY*request()->Quantity}}" name="Material_QTY[]">
    </td>
    <td class="td-sm">{{$val->Scarp_QTY*request()->Quantity}}
        <input id="Scarp_QTY{{$i}}" type="hidden" value="{{$val->Scarp_QTY*request()->Quantity}}" name="Scarp_QTY[]">
    </td>
    <td class="td-sm">
        <input id="otherQTY{{$i}}" data-id="{{$i}}" type="text" placeholder="Other Qty " name="otherQTY[]" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" class="form-control form-control-sm otherqty" value="{{ $val->editdata->OtherQty??''}}">
    </td>
    <td class="td-sm" id="totalQTY{{$i}}">
        {{($val->Material_QTY*request()->Quantity)+($val->Scarp_QTY*request()->Quantity)+($val->editdata->OtherQty??0)}}
    </td>
</tr>
@endforeach
<script>
    $(".otherqty").blur(function() {
        id = $(this).attr('data-id');
        Material_QTY = parseInt($("#Material_QTY" + id).val());
        Scarp_QTY = parseInt($("#Scarp_QTY" + id).val());
        otherQTY = parseInt($("#otherQTY" + id).val());
        $("#totalQTY" + id).text((Material_QTY + Scarp_QTY + otherQTY))
    });
</script>