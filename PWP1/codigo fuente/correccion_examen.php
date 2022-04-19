<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correccion examen</title>
</head>
<body>
<?php
error_reporting(E_NOTICE);
session_start();
$user = $_SESSION['user_session'];
$id = $_SESSION['user_id'];
$preguntas = $_SESSION['array_preguntas'];
$estudiante_codigo = $_SESSION['estudiante_codigo'];
$tema = $_SESSION['tema_examen'];
//se obtienen las 10 respuestas del formulario anterior
$respuesta_1 = $_POST['1'];
$respuesta_2 = $_POST['2'];
$respuesta_3 = $_POST['3'];
$respuesta_4 = $_POST['4'];
$respuesta_5 = $_POST['5'];
$respuesta_6 = $_POST['6'];
$respuesta_7 = $_POST['7'];
$respuesta_8 = $_POST['8']; 
$respuesta_9 = $_POST['9'];
$respuesta_10 = $_POST['10'];
//se guardan en un array para poder iterar las respuestas
$respuestas = array($respuesta_1,$respuesta_2,$respuesta_3,$respuesta_4,$respuesta_5,
$respuesta_6,$respuesta_7,$respuesta_8,$respuesta_9,$respuesta_10);

$server = mysqli_connect("127.0.0.1","casem_user","","casem") or die (mysqli_connect_error());
//se busca el nombre del estudiante
$sql_nombre_estudiante = "SELECT usuario_nombre FROM usuarios WHERE usuario_codigo = '$id'";
$query_nombre_estudiante = mysqli_query($server,$sql_nombre_estudiante);
$nombre_estudiante = mysqli_fetch_array($query_nombre_estudiante);

//buscamos las preguntas pasandole la variable de sesion que contiene el array de preguntas
$sql = "SELECT * FROM preguntas WHERE pregunta_codigo IN ($preguntas)";
$query = mysqli_query($server,$sql);
$preguntas_query = mysqli_query($server,$sql);
$filas_pregunta = mysqli_num_rows($preguntas_query);
//guardamos la calificacion y el numero de preguntas incorrectas
$calificacion = 0;
$preguntas_mal = 0;
if ($filas_pregunta > 0)
{
    //se recorre ambos arrays y se comparan las respuestas del estudiante con las respuestas correctas
    for($i=0; $i<$filas_pregunta; $i++)
    {
        $indice = $i+1;
        $pregunta = mysqli_fetch_array($preguntas_query);
        $sql = "SELECT * FROM respuestas WHERE respuesta_codigo = '$respuestas[$i]'";
        $query = mysqli_query($server,$sql);
        $res = mysqli_fetch_array($query);
        //si es correcta, sumamos 1 punto
        if ($res['respuesta_codigo'] == $pregunta['pregunta_respuesta'])
        {
        $calificacion ++;
        }else if ($res['respuesta_codigo'] == null)
        //si la respuesta está en blanco, no se suma nada
        {
        $calificacion + 0;
        //se guarda esa pregunta en la tabla para almacenar las preguntas incorrectas de ese examen de ese alumno
        $sql = "INSERT INTO estudiante_pregunta VALUES ('$estudiante_codigo','$tema','{$pregunta['pregunta_codigo']}')";
        mysqli_query($server,$sql);
        }else
        {
            $preguntas_mal ++;
            $sql = "INSERT INTO estudiante_pregunta VALUES ('$estudiante_codigo','$tema','{$pregunta['pregunta_codigo']}')";
            mysqli_query($server,$sql);
            //si la penalizacion de la pregunta era de 0.5, se le resta eso a la nota
            if ($pregunta['pregunta_penalizacion'] == 'medio')
            {
                $calificacion = $calificacion - 0.5;
            }else
            {
                //si la penalizacion de la pregunta era de 0.25, se le resta eso a la nota
                $calificacion = $calificacion - 0.25;
            }
        }
    }
}
//finalmente, dependiendo de la calificacion, se le almancena en la tabla de estudiante_examen
//si la nota era menor que 0, le asignamos un 0 para que no haya valores negativos
if ($calificacion < 0)
{
    $sql = "INSERT INTO estudiante_examen VALUES ('$estudiante_codigo','$tema','0','{$nombre_estudiante['usuario_nombre']}')";
    mysqli_query($server,$sql);
}else
{
    $sql = "INSERT INTO estudiante_examen VALUES ('$estudiante_codigo','$tema','$calificacion','{$nombre_estudiante['usuario_nombre']}')";
    mysqli_query($server,$sql);
}
    print ("<H4>Examen guardado con éxito</H4>");
    $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
    print ("<A HREF=estudiante.php>volver al inicio</A>");
?>
</body>
</html>