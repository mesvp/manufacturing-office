@php($i = 1)
@foreach($Materials as $val)
<tr>
    <td class="td-sm">{{$i}}</td>
    <td class="td-sm">{{$val->RawMaterial->matname}}
        <input id="materialID{{$i}}" type="hidden" value="{{$val->RawMaterial->id}}" name="materialID[]">
        <input id="materialName{{$i}}" type="hidden" value="{{$val->RawMaterial->Material_Name}}" name="Material_Name[]">
    </td>
    <?php 
        $totalqty = ($val->Material_QTY * request()->Quantity) + ($val->Scarp_QTY * request()->Quantity) + ($val->editdata->OtherQty ?? 0);
        $initialPlantStock = $val->Plantstock;
    ?>
    <td class="td-sm" ><input type="text" id="Plantstock{{$i}}" readonly class="form-control form-control-sm" value="{{$initialPlantStock - $totalqty}}" >
        <input id="Plantstock{{$i}}_hidden" type="hidden" value="{{$initialPlantStock - $totalqty}}" name="Plantstock[]" >
        <input id="initialPlantstock{{$i}}" type="hidden" value="{{$initialPlantStock}}">
        <input type="hidden" value="{{$i}}" name="idd[]">
    </td>
    <td class="td-sm">{{$val->RawMaterial->UOM}}
        <input name="UMO[]" type="hidden" value="{{$val->RawMaterial->UOM}}">
    </td>
    <td class="td-sm">{{$val->Material_QTY * request()->Quantity}}
        <input id="Material_QTY{{$i}}" type="hidden" value="{{$val->Material_QTY * request()->Quantity}}" name="Material_QTY[]">
    </td>
    <td class="td-sm">{{$val->Scarp_QTY * request()->Quantity}}
        <input id="Scarp_QTY{{$i}}" type="hidden" value="{{$val->Scarp_QTY * request()->Quantity}}" name="Scarp_QTY[]">
    </td>
    <td class="td-sm">
        <input id="otherQTY{{$i}}" data-id="{{$i}}" type="text" placeholder="Other Qty " name="otherQTY[]"  class="form-control form-control-sm otherqty" value="{{ $val->editdata->OtherQty ?? ''}}">
    </td>
    <td class="td-sm" id="totalQTY{{$i}}">
        {{$totalqty}}
    </td>
</tr>
@php($i++)
@endforeach

<script>
    $(document).ready(function() {
        // Object to store initialPlant_QTY values
        var initialPlantStockValues = {};
    
        $(".otherqty").each(function() {
            var id = $(this).attr('data-id');
            initialPlantStockValues[id] = parseInt($("#initialPlantstock" + id).val());
        });
    
        $(".otherqty").blur(function() {
            var id = $(this).attr('data-id');
    
            var Material_QTY = parseFloat($("#Material_QTY" + id).val());
            var initialPlantstockElem = $("#initialPlantstock" + id);
            var initialPlant_QTY = initialPlantStockValues[id];
            // var Scarp_QTY = parseInt($("#Scarp_QTY" + id).val());
            // var otherQTY = parseInt($(this).val());
            
            var Scarp_QTY = parseFloat($("#Scarp_QTY" + id).val());
            var otherQTY = parseFloat($(this).val());
    
            // Check if otherQTY is NaN, set it to 0 if it is
            otherQTY = isNaN(otherQTY) ? 0 : otherQTY;
    
            // Calculate total quantity
            var totalQTY = Material_QTY + Scarp_QTY + otherQTY;
            $("#totalQTY" + id).text(totalQTY);
            $("#Plantstock" + id).val(initialPlant_QTY - totalQTY);
    
            // Update the hidden Plantstock input value
            $("#Plantstock" + id + "_hidden").val(initialPlant_QTY - totalQTY);
        });
    });
</script>
