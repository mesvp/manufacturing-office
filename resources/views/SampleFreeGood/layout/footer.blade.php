<footer>
  <p>Copyright © 2023, <a href="http://maestrosinfotech.com/">Maestros Infotech</a></p>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://techramindra.com/scripts/valid.js"></script>



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
  $('.iocn-link').click(function() {
    $('.sub-menu').toggle();
    if ($(".iocn-link").hasClass('active')) {
      $(".iocn-link").removeClass('active');
    } else {
      $(".iocn-link").addClass('active');
    }
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
  // Add active class to the current button (highlight it)
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
  link=location.href;
  link=link.split('/');
  if(link.find(checkAge)=='Master') 
  {
    myFunctiontt(6)
  }
  else if(link.find(checkAge1)=='FactoryCreater')
  {
    myFunctiontt(5)
  }
  function checkAge(age) 
  {
    return age == 'Master';
  }
  function checkAge1(age) 
  {
    return age == 'FactoryCreater';
  }
  
}
checkmaster()
</script>
</body>
@stack('custom-scripts')

</html>