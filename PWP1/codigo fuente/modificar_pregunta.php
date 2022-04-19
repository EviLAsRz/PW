<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modificar_pregunta</title>

</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user = $_SESSION['user_session'];
$profesor_codigo = $_SESSION['profesor_codigo'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die("ERROR: No se pudo establecer conexión con la base de datos.");

//consulta para obtener todas las preguntas que ha creado ese profesor
$sql = "SELECT * FROM preguntas WHERE pregunta_codigo IN (
        SELECT pregunta_codigo FROM profesor_pregunta WHERE profesor_codigo = '$profesor_codigo')";

$query = mysqli_query($server,$sql);
$filas = mysqli_num_rows($query);

if ($filas > 0)
        {   
            //fomulario de tipo post a modificar_pregunta_db
            print ("<FORM ACTION='modificar_pregunta_db.php' METHOD='post'>\n");
            print ("<TABLE>\n");
            print ("<TR>\n");
            print ("<TH>Id</TH>\n");
            print ("<TH>Texto</TH>\n");
            print ("<TH>Tema</TH>\n");
            print ("<TH>Tipo</TH>\n");
            print ("<TH>Penalizacion</TH>\n");
            print ("</TR>\n");
            

            for ($i=0; $i<$filas; $i++)
            {
                $resultado = mysqli_fetch_array($query);
                print ("<TR>\n");
                print ("<TD>" . $resultado['pregunta_codigo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_texto'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tema'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tipo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_penalizacion'] . "</TD>\n");
                print ("</TR>\n");

            }
        print ("</TABLE>\n");
        print ("pregunta a modificar:\n");
        print ("<SELECT NAME='pregunta'>");

        $sql = "SELECT * FROM preguntas WHERE pregunta_codigo IN (
                SELECT pregunta_codigo FROM profesor_pregunta WHERE profesor_codigo = '$profesor_codigo')";
   
        $query = mysqli_query($server,$sql);

        if ($filas > 0)
        {
            for($j=0;$j<$filas;$j++)
            {
            $fila = mysqli_fetch_array($query);
            ?>
            <OPTION NAME='pregunta'> <?php echo $fila['pregunta_codigo']; ?></OPTION>
            <?php
            }
        }
        print ("<BR>\n");
        print ("</SELECT>");
        print ("<BR>\n");
        print ("Campo a modificar:\n");
        print ("<SELECT NAME='campo'>");
        print ("<OPTION NAME='campo'>penalización</OPTION>");
        print ("<OPTION NAME='campo'>respuesta correcta</OPTION>");
        print ("<BR>\n");
        print ("</SELECT>");
        print ("<BR>\n");
        print ("<INPUT TYPE='submit' VALUE='modificar'>");
        }
?> 
</body>
</html>