<?php
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '');
define('DB_DATABASE', 'sparql');
 
$connexion = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
 
$html = '';
//$key = 'colom';//$_POST['key'];
 $key = htmlspecialchars($_POST['key']);
$result = $connexion->query(
    "SELECT * FROM paises p 
    
    WHERE  
     p.nombre LIKE '%$key%'"
    
);
$datos = array();

//ORDER BY nombres DESC LIMIT 0,5
//if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        //$html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'" id="product'.$row['iso'].'">'.utf8_encode($row['nombre']).'</a></div>';
        $datos[] = $row['nombre'];  
    }
//}
echo json_encode($datos);
//echo $html;
?>
