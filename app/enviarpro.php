<?php

include "conn.php";



$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$precio=$_POST['precio'];
$cantidad=$_POST['cantidad'];
$imagen=addslashes(file_get_contents($_FILES['imagen']['tmp_name']));



$insert="INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen)
values ('$nombre', '$descripcion', '$precio', '$cantidad', '$imagen')";



$query=mysqli_query($conectar, $insert);


if ($query) {
    echo "<script> alert('los datos registraron corectamente, desea continuar.')
    window.location.href='form_reg.php';</script>";
} else {
    echo "los datos no se guardaron en la base de datos, intentalo nuevamente.";
}

?>