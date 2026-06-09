
<footer>
  <p>Copyright © 2023, <a href="http://groupsurya.co.in/">Suryam Group</a></p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>
<script src="https://techramindra.com/scripts/valid.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script>
  $(document).ready(function() {
    const className = 'js-example-matcher-start';

    function addClass(className) {
      $('select').each(function() {
        if (!$(this).hasClass(className)) {
          $(this).addClass(className);
        }
      });
    }

    addClass(className);
    AppendSelect2()
  });
</script>
<script>
  function AppendSelect2() {
    function matchCustom(params, data) {
      if ($.trim(params.term) === '') {
        return data;
      }
      if (typeof data.text === 'undefined') {
        return null;
      }

      var searchTerm = params.term.toLowerCase();
      var dataText = data.text.toLowerCase();
      if (dataText.indexOf(searchTerm) > -1) {
        var modifiedData = $.extend({}, data, true);
        return modifiedData;
      }
      return null;
    }
    $(".js-example-matcher-start").select2({
      matcher: matchCustom
    });
  }
</script>
<script>
  $(".js-example-basic-multiple-limit").select2({
    placeholder: 'Select',
    allowClear: true
  });
</script>
<script>
  setTimeout(function() {
    $('.alert').hide();
  }, 3000);
</script>
<script>
  document.onreadystatechange = function() {
    var loader = document.getElementById('loader');
    if (document.readyState === 'loading') {
      loader.style.display = 'flex';
    } else {
      loader.style.display = 'none';
    }
  };
</script>
<script>
  $(document).ready(function() {
    $('.example').DataTable({
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
</script>
<script>
 $(document).ready(function() {
    $('#example2').DataTable({
        dom: 'lBfrtip', // 'l' is for the length menu
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ], // Define the options for the dropdown
        buttons: [
            { extend: 'copy', className: 'btn btn-outline-primary' },
            { extend: 'csv', className: 'btn btn-outline-primary' },
            { extend: 'excel', className: 'btn btn-outline-primary' },
            { extend: 'pdf', className: 'btn btn-outline-primary' },
            { extend: 'print', className: 'btn btn-outline-primary' }
        ]
    });
});
</script>
<script>
  function sidebar() {
    if ($(".sidebar").hasClass('close')) {
      $(".sidebar").removeClass('close');
      $(".sidebar").addClass('show');
    } else {
      $(".sidebar").addClass('close');
      $(".sidebar").removeClass('show');
    }
  }
</script>
<script>
  var header = document.getElementById("myDIV");
  var btns = header.getElementsByClassName("luck");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("activejj");
      current[0].className = current[0].className.replace(" activejj", "");
      this.className += " activejj";
    });
  }
</script>
<script>
  function myFunctiontt(id) {
    $(".outsideav").not("#myDIVwwep" + id).hide();

    $("#myDIVwwep" + id).toggle();
    $(".outside").removeClass('activejj');
    $("#i" + id).addClass('activejj');

  }

  function myFunctionttss(id) {
    $(".inside").not("#myDIVwweps" + id).hide();

    $("#myDIVwweps" + id).toggle();
    $(".insideav").removeClass('activejj');
    $("#is" + id).addClass('activejj');
    myFunctiontt(id)
  }

  function activeclass(id, id2) {
    myFunctionttss(id)
    $("#pura" + id + '' + id2).addClass('activesle');
  }
</script>
<script>
  function checkmaster() {
    link = location.href;
    link = link.split('/');
    if (link.find(checkAge) == 'Master') {
      myFunctiontt(6)
    } else if (link.find(checkAge1) == 'FactoryCreater') {
      myFunctiontt(5)
    }

    function checkAge(age) {
      return age == 'Master';
    }

    function checkAge1(age) {
      return age == 'FactoryCreater';
    }

  }

  $(document).ready(function() {
    checkmaster();
  });
</script>
<script>
  $("#draft").click(function(e) {
    e.preventDefault();

    $('select[name^="UOM"]').prop('disabled', false);
    $("form select").removeAttr("required");
    $("form textarea").removeAttr("required");
    $("form input").removeAttr("required");
    $("form").append('<input type="hidden" name="draft" value="1"/>');
    $("form").submit();

  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const inputElements = document.querySelectorAll('.alphabet');

    inputElements.forEach(function(inputElement) {
      inputElement.addEventListener('input', function(event) {
        const inputValue = event.target.value;
        const alphabeticRegex = /^[a-zA-Z\s]*$/;

        if (!alphabeticRegex.test(inputValue)) {
          event.target.value = inputValue.replace(/[^a-zA-Z\s]/g, '');
        }
      });
    });
  });
</script>
<script>
  function Material(i, j) {
    $('#RawMaterial' + i + j).on('change', function() {
      var MaterialId = $(this).val();

      $.ajax({
        url: "{{url('RawMaterial/MaterialData')}}" + '/' + MaterialId,
        type: 'GET',
        data: {
          MaterialId: MaterialId
        },
        success: function(data) {
          $('#HSNCode' + i + j).val(data.data.HSN_Code);
          $('#uom' + i + j).val(data.data.UOM).change();
        }
      });
    });
  }
</script>
<script>
  $(document).ready(function() {
    $('#draft, #submitBtn').on('click', function() {     

      if ($(this).attr('id') === 'submitBtn') {
        var id=$(this).parent().parent().parent().attr('id')
        if (!checkRequiredFields(id)) {
          alert('Please fill in all required fields.');
          return false;
        }
      }
      
      var hasDuplicates = checkDuplicateMaterial();
      if (hasDuplicates) {
        return false;
      }
      if (typeof CheckMaterialQuantity === 'function') 
      {
        var QuantityMismatch = CheckMaterialQuantity();
        if (QuantityMismatch) {
        return false;
        }  
      }
         
      $('select[name^="UOM"]').prop('disabled', false);
      $('#Form').submit();
    });
  });

  function checkDuplicateMaterial() {
    var selectedMaterials = [];
    var hasDuplicate = false;

    $('select[name^="Raw_Material"]').each(function() {
      var materialValue = $(this).val();

      if (selectedMaterials.includes(materialValue)) {
        $(this).siblings('.error-message').text('Material is Already In Use').show();
        hasDuplicate = true;
      } else {
        $(this).siblings('.error-message').text('').hide();
        selectedMaterials.push(materialValue);
      }
    });

    return hasDuplicate;
  }

  function checkRequiredFields(id='') {
if(id!='')
{
  //alert(id)
  var requiredFields = $('#'+id+' input[required],#'+id+' select[required],#'+id+' textarea[required]');
}
else{
  var requiredFields = $('input[required], select[required], textarea[required]');
}
   

    for (var i = 0; i < requiredFields.length; i++) {
      if (!requiredFields[i].value.trim()) {
        return false;
      }
    }

    return true;
  }
</script>
</body>

@stack('custom-scripts')
<script>
  function onclickfun()
  {
    var elements = document.getElementsByClassName("paginate_button");
    var myFunction = function() {
    var attribute = this.getAttribute("aria-controls");
    filterTable()
    onclickfun()
    };
    for (var i = 0; i < elements.length; i++) {
    elements[i].addEventListener('click', myFunction, false);
    }

  }
$(document).ready(function(){
onclickfun()
ele=$(".addbtn.extra a:first-child");
ele.attr('href','#');
ele.click(function(event) {
    event.preventDefault();
    history.back(1);
});
});

</script>

<script>
  $(document).ready(function(){
      if(window.location.href.indexOf("Dashboard/dashboard") > -1) {
          $("#myDIVwwep5").hide();
      }
  });
</script>

</html>