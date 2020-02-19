<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Autocompletado de Mutiples campos Usando jQuery , Ajax , PHP y MySQL</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
</head>
<body>
 
<div class="ui-widget">
<form class="form-inline" method="post" action="#">
    <div class="input-group input-group-sm">
        <input  type="text" name="key" id="key" placeholder="Buscar...">
        <span class="input-group-btn">
            <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
        </span>
    </div>
</form>
<div id="suggestions"></div>
</div>

  <script type="text/javascript">
$(document).ready(function() {
    $('#key').on('keyup', function() {
        var key = $(this).val();		
        var dataString = 'key='+key;
 $( "#key" ).autocomplete({
  source: function( request, response ) {
   // Fetch data
   $.ajax({
    url: "paises.php",
    type: 'POST',
    dataType: "json",
    data: {q:request.term},
    success: function( data ) {
     response( data );
    }
   });
  },
  select: function (event, ui) {
   // Set selection
   $('#autocomplete').val(ui.item.label); // display the selected text
   $('#selectuser_id').val(ui.item.value); // save selected id to input
   return false;
  }
 });
 });
 });
</script>
</body>
</html>