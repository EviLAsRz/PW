<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crear preguntas</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$texto = $_POST['texto'];
$tema = $_POST['tema'];
$opcion_tipo = $_POST['opcion_tipo'];
$opcion_pen = $_POST['opcion_pen'];
$profesor_codigo = $_SESSION['profesor_codigo'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die("ERROR: No se pudo establecer conexión con la base de datos.");

//buscamos el codigo del tema pasandole la variable POST de tema
$tema_codigo_query = mysqli_query($server,"SELECT tema_codigo FROM temas WHERE tema_nombre = '$tema'");
$tema_codigo = mysqli_fetch_array($tema_codigo_query);

//insertamos en la tabla pregunta una pregunta con los datos obtenidos
$sql = "INSERT INTO preguntas (pregunta_texto, pregunta_tema, pregunta_tipo, pregunta_penalizacion) 
        VALUES ('$texto','{$tema_codigo['tema_codigo']}','$opcion_tipo','$opcion_pen')";
mysqli_query($server,$sql);

//buscamos esa pregunta
$query = mysqli_query($server,"SELECT pregunta_codigo FROM preguntas WHERE pregunta_texto = '$texto'");
$pregunta_codigo = mysqli_fetch_array($query);

//Insertamos en la tabla que relaciona pregunta con respuestas los valores indicados
$sql = "INSERT INTO profesor_pregunta VALUES ('{$pregunta_codigo['pregunta_codigo']}','$profesor_codigo')";
mysqli_query($server,$sql);

//si la pregunta creada es de tipo abcd, entonces creamos un formulario al profesor para poder introducir los datos
if ($opcion_tipo== 'abcd')
{
    $_SESSION['pregunta_codigo']=$pregunta_codigo['pregunta_codigo'];
    $_SESSION['texto_pregunta']=$texto;
    print("<FORM method='post' action='preguntas_abcd.php'>");
    print("<H1>Creación de respuestas</H1>");
    print("<LABEL for='res1'><B>respuesta 1</B></LABEL>");
    print("<INPUT type='text' name='res1' REQUIRED></BR>");
    print("<LABEL for='res2'><B>respuesta 2</B></LABEL>"); 
    print("<INPUT type='text' name='res2' REQUIRED></BR>");
    print("<LABEL for='res3'><B>respuesta 3</B></LABEL>");
    print("<INPUT type='text' name='res3' REQUIRED></BR>");
    print("<LABEL for='res3'><B>respuesta 3</B></LABEL>");
    print("<INPUT type='text' name='res4' REQUIRED></BR>");
    print("respuesta correcta:</br>");
    print("<INPUT name='correcta' type='radio' value='1' CHECKED/>1</BR>");
    print("<INPUT name='correcta' type='radio' value='2'/>2</BR>");
    print("<INPUT name='correcta' type='radio' value='3'/>3</BR>");
    print("<INPUT name='correcta' type='radio' value='4'/>4</BR>");
    print("<INPUT type='submit' value='Enviar'>");  
}
//si no, si es de tipo V/F, simplemente le pedimos cual de las respuestas será la correcta.
if ($opcion_tipo == 'v/f')
{
    $_SESSION['texto_pregunta']=$texto;
    
    print("<FORM method='post' action='preguntas_vf.php'></BR>");
    print("Respuesta de la pregunta:</BR>");
    print("<INPUT name='resp' type='radio' value='Verdadero' />Verdadero</BR>");
    print("<INPUT name='resp' type='radio' value='Falso' CHECKED/>Falso</BR>");
    print("<INPUT type='submit' value='Enviar'>");
    print("</FORM>");

}
?>
</body>
</html>