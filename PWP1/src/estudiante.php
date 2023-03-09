<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>estudiantes</title>
</head>
<body>
<?php
session_start();
$user = $_SESSION['user_session'];
$id = $_SESSION['user_id'];

$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die(mysqli_connect_error());

//buscamos el codigo del estudiante
$sql = "SELECT estudiante_codigo FROM estudiantes
        WHERE estudiante_usuario = '$id'";
$query = mysqli_query($server,$sql);

$estudiante_codigo = mysqli_fetch_array($query);
$_SESSION['estudiante_codigo'] = $estudiante_codigo['estudiante_codigo'];

//menu del estudiante con las opciones de matricularse, realizar examenes y ver calificaciones
print ("<H2>Matriculación</H2>");
print ("<A HREF='matriculacion_estudiante.php'>realizar matricula</A>");
print ("<H2>Exámenes</H2>");
print ("<A HREF='examen.php'>realizar exámenes</A>");
print ("<H2>Calificaciones</H2>");
print ("<A HREF='visualizar_calificaciones_estudiante.php'>ver calificaciones</A>");
?>
</BR></BR></BR>
<a href='login.php'><button type='button'>VOLVER
</body>
</html>