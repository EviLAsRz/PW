<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login_db</title>
</head>
<body>
<?php
// Inciamos la sesion
session_start();
// Conexion con la base de datos
$server = mysqli_connect('127.0.0.1','casem_user','','casem') or die(mysqli_connect_error());
//almacenamiento de los datos desde el submit
$usuario=$_POST['uname'];
$passwd=$_POST['psw'];
//Consulta a la base de datos
$sql = "SELECT * FROM usuarios 
        WHERE usuario_nif='$usuario' AND usuario_password='$passwd'";
//ejecución de la consulta
$query=mysqli_query($server,$sql);
//obtenemos los datos de la consulta
$fila=mysqli_fetch_assoc($query);
$id = $fila['usuario_codigo'];
$filas=mysqli_num_rows($query);
if($filas>0)
{
    //si es estudiante, lo redireccionamos a la pagina de estudiantes
    if ($fila['usuario_rol'] == "estudiante")
    {
        //se guardan dos variables de sesion para facilitar la consulta de datos
        $_SESSION['user_session']=$usuario;
        $_SESSION['user_id']=$id;
        session_write_close();
        header("Location: estudiante.php?");
    }
    //si es profesor, lo redireccionamos a la pagina de profesores
    if ($fila['usuario_rol'] == "profesor")
    {
        $_SESSION['user_session']=$usuario;
        $_SESSION['user_id']=$id;
        session_write_close();
        header("Location: profesor.php?");
        #pagina profesor
    }
    //si es administrador, lo redireccionamos a la pagina de administradores
    if ($fila['usuario_rol'] == "admin")
    {
        header("Location: admin.php?");
        #pagina admin
    }
}
else
{
    echo "Fallo al insertar los datos.";
    header("Location: http://localhost/practica1/login.php?");
}
session_destroy();
mysqli_close($server);
?>
</body>
</html>