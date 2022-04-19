<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas_abcd</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
//obtencion de las 4 respuestas 
$texto_respuesta1 = $_POST['res1'];
$texto_respuesta2 = $_POST['res2'];
$texto_respuesta3 = $_POST['res3'];
$texto_respuesta4 = $_POST['res4'];
$correcta = $_POST['correcta'];
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$texto = $_SESSION['texto_pregunta'];
$pregunta_codigo = $_SESSION['pregunta_codigo'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die("ERROR: No se pudo establecer conexión con la base de datos.");

$query = mysqli_query($server,"SELECT * FROM preguntas WHERE pregunta_texto = '$texto'");
$datos_pregunta = mysqli_fetch_array($query);

//insertamos en la tabla respuestas las respuestas obtenidas
$sql = "INSERT INTO respuestas (respuesta_texto) VALUES ('$texto_respuesta1')";
mysqli_query($server,$sql);
$sql = "INSERT INTO respuestas (respuesta_texto) VALUES ('$texto_respuesta2')";
mysqli_query($server,$sql);
$sql = "INSERT INTO respuestas (respuesta_texto) VALUES ('$texto_respuesta3')";
mysqli_query($server,$sql);
$sql = "INSERT INTO respuestas (respuesta_texto) VALUES ('$texto_respuesta4')";
mysqli_query($server,$sql);

//buscamos sus datos
$query = mysqli_query($server,"SELECT * FROM respuestas WHERE respuesta_texto = '$texto_respuesta1'");
$datos_respuesta1 = mysqli_fetch_array($query);

$query = mysqli_query($server,"SELECT * FROM respuestas WHERE respuesta_texto = '$texto_respuesta2'");
$datos_respuesta2 = mysqli_fetch_array($query);

$query = mysqli_query($server,"SELECT * FROM respuestas WHERE respuesta_texto = '$texto_respuesta3'");
$datos_respuesta3 = mysqli_fetch_array($query);

$query = mysqli_query($server,"SELECT * FROM respuestas WHERE respuesta_texto = '$texto_respuesta4'");
$datos_respuesta4 = mysqli_fetch_array($query);

//buscamos que respuesta habia indicado el usuario como verdadera, y la modificamos
if($correcta == 1)
{
    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta1['respuesta_codigo']}','1')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta2['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta3['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta4['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "UPDATE preguntas
            SET pregunta_respuesta = '{$datos_respuesta1['respuesta_codigo']}'
            WHERE pregunta_codigo = '$pregunta_codigo'";
    mysqli_query($server,$sql);

}else if ($correcta == 2)
{
    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta1['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta2['respuesta_codigo']}','1')";
    mysqli_query($server,$sql);
    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta3['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta4['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "UPDATE preguntas
            SET pregunta_respuesta = '{$datos_respuesta2['respuesta_codigo']}'
            WHERE pregunta_codigo = '$pregunta_codigo'";
    mysqli_query($server,$sql);

}else if ($correcta == 3)
{
    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta1['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta2['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta3['respuesta_codigo']}','1')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta4['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "UPDATE preguntas
            SET pregunta_respuesta = '{$datos_respuesta3['respuesta_codigo']}'
            WHERE pregunta_codigo = '$pregunta_codigo'";
    mysqli_query($server,$sql);

}else
{
    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta1['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta2['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta3['respuesta_codigo']}','0')";
    mysqli_query($server,$sql);

    $sql = "INSERT INTO pregunta_respuesta 
            VALUES ('{$datos_pregunta['pregunta_codigo']}','{$datos_respuesta4['respuesta_codigo']}','1')";
    mysqli_query($server,$sql);

    $sql = "UPDATE preguntas
            SET pregunta_respuesta = '{$datos_respuesta4['respuesta_codigo']}'
            WHERE pregunta_codigo = '$pregunta_codigo'";
    mysqli_query($server,$sql);
}
$_SESSION['user_id'] = $id;
$_SESSION['user_session'] = $user;
?>
<H2>La pregunta se ha creado exitosamente</H2>
<A HREF="profesor.php">volver al inicio</A>
</body>
</html>