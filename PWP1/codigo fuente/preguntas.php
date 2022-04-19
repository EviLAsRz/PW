<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>preguntas</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$asignatura=$_POST['asignatura'];
//obtencion de valores de POST
$opcion=$_POST['opcion'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem")
    or die("ERROR: No se pudo establecer conexión con la base de datos.");

//consulta para obtener el codigo de la asignatura
$asignatura_codigo_query = mysqli_query($server,"SELECT asignatura_codigo FROM asignaturas WHERE asignatura_nombre = '$asignatura'");
$asignatura_codigo = mysqli_fetch_array($asignatura_codigo_query);
//consulta para obtener el codigo del profesor
$profesor_codigo_query = mysqli_query($server,"SELECT profesor_codigo FROM profesores WHERE profesor_usuario = 
    (SELECT usuario_codigo FROM usuarios WHERE usuario_nif = '$user')");
$profesor_codigo = mysqli_fetch_array($profesor_codigo_query);

//lo guardamos en una variable de sesion para mejor acceso a este dato
$_SESSION['profesor_codigo'] = $profesor_codigo['profesor_codigo'];

//buscamos las asignaturas de ese profesor con ese codigo
$sql = "SELECT * FROM profesor_asignatura WHERE asignatura_codigo = {$asignatura_codigo['asignatura_codigo']}
    AND profesor_codigo = {$profesor_codigo['profesor_codigo']}";

$query = mysqli_query($server,$sql);
$nfilas=mysqli_num_rows($query);

//si no encuentra asignaturas, termina la sesion
if($nfilas==0){
    echo "Los datos introducidos no son correctos";
    mysqli_close($server);
    session_destroy();
    exit(1);
}
//segun sea el valor de opcion (crear, modificar, eliminar)
switch ($opcion){
    case "crear":
        ?>
        <form method='post' action='crear_preguntas.php'>
        <label for='texto'>pregunta</label>
        <textarea name='texto' cols='40' row='3' minlength='15' maxlength='200'></textarea></br>
        tema:
    <select name="tema">
    <?php

        $query = mysqli_query($server,"SELECT tema_nombre FROM temas WHERE tema_codigo IN
            (SELECT tema_codigo FROM asignatura_tema WHERE asignatura_codigo = {$asignatura_codigo['asignatura_codigo']})");
        $filas=mysqli_num_rows($query);
        if($filas>0)
        {
            for ($i=0; $i<$filas; $i++)
            {
                $fila=mysqli_fetch_array($query);
            //usamos un loop para obtener los datos
                ?>
                <option name='tema'> <?php echo $fila['tema_nombre']; ?> </option>
                <?php
            }
        }
?>
    </select>
    </br>
        Tipo:</br>
        <input name='opcion_tipo' type='radio' value='abcd' />ABCD</br>
        <input name='opcion_tipo' type='radio' value='v/f' checked />VF
    </br>
        Penalizacion:</br>
        <input name='opcion_pen' type='radio' value='medio' />0,5</br>
        <input name='opcion_pen' type='radio' value='cuarto' checked />0,25
    </br>
        <input type='submit' value='Enviar'>
        </form>
        <?php
        break;

    case "modificar":
        //dirige al profesor a modificar_pregunta
        header("Location: modificar_pregunta.php?");break;
    case "eliminar":
        //dirige al profesor a eliminar_pregunta
        header("Location: eliminar_pregunta.php?");break;
}
?>
</body>
</html>