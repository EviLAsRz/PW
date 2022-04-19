<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>preguntas_vf</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$texto = $_SESSION['texto_pregunta'];
$resp = $_POST['resp'];
//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die("ERROR: No se pudo establecer conexión con la base de datos.");

$query = mysqli_query($server,"SELECT * FROM preguntas WHERE pregunta_texto = '$texto'");
$datos_pregunta = mysqli_fetch_array($query);

    //si la respuesta es verdadera, unimos las respuestas con la pregunta
    //importante, en la base de datos hemos considerado las respuestas verdadero/falso como estáticas,
    //es decir, que tienen siempre el mismo valor (5,6)
    if ($resp == 'Verdadero')
    {
        $sql = "INSERT INTO pregunta_respuesta (pregunta_codigo,respuesta_codigo,resultado)
        VALUES ('{$datos_pregunta['pregunta_codigo']}',5,1)";
        mysqli_query($server, $sql);
        $sql = "INSERT INTO pregunta_respuesta (pregunta_codigo,respuesta_codigo,resultado)
        VALUES ('{$datos_pregunta['pregunta_codigo']}',6,0)";
        mysqli_query($server, $sql);
        $sql = "UPDATE preguntas
                SET pregunta_respuesta = 5
                WHERE pregunta_codigo = '{$datos_pregunta['pregunta_codigo']}'";
        mysqli_query($server,$sql);
    }else
    //idem para falso
    {
        $sql = "INSERT INTO pregunta_respuesta (pregunta_codigo,respuesta_codigo,resultado)
        VALUES ('{$datos_pregunta['pregunta_codigo']}',6,1)";
        mysqli_query($server, $sql);
        $sql = "INSERT INTO pregunta_respuesta (pregunta_codigo,respuesta_codigo,resultado)
        VALUES ('{$datos_pregunta['pregunta_codigo']}',5,0)";
        mysqli_query($server, $sql);
        $sql = "UPDATE preguntas
                SET pregunta_respuesta = 6
                WHERE pregunta_codigo = '{$datos_pregunta['pregunta_codigo']}'";
        mysqli_query($server,$sql);
    }
    $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
?>
<h2>La pregunta se ha creado exitosamente</h2>
<a href="profesor.php">volver al inicio</a>
</body>
</html>