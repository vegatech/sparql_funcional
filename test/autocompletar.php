<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Autocompletado de Mutiples campos Usando jQuery , Ajax , PHP y MySQL</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="stylesheet" href="style2.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
  <script type="text/javascript">
$(document).ready(function() {
    $('#key').on('keyup', function() {
        var key = $(this).val();		
        var dataString = 'key='+key;
	$.ajax({
            type: "POST",
            url: "consulta.php",
            data: dataString,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(10).html(data);
                //Al hacer click en alguna de las sugerencias
                $('.suggest-element').on('click', function(){
                        //Obtenemos la id unica de la sugerencia pulsada
                        var id = $(this).attr('id');
                        //Editamos el valor del input con data de la sugerencia pulsada
                        $('#key').val($('#'+id).attr('data'));
                        //Hacemos desaparecer el resto de sugerencias
                        $('#suggestions').fadeOut(1000);
                        alert('Has seleccionado el '+id+' '+$('#'+id).attr('data'));
                        return false;
                });
            }
        });
    });
}); 
</script>
</head>
<body>
 
<div class="ui-widget">
<form class="form-inline" method="post" action="#">
    <div class="input-group input-group-sm">
        <input class="search_query form-control" type="text" name="key" id="key" placeholder="Buscar...">
        <span class="input-group-btn">
            <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
        </span>
    </div>
</form>
<div id="suggestions"></div>
</div>
</body>
</html>