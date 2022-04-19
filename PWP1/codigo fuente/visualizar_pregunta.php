<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>visualizar preguntas</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$profesor_codigo = $_SESSION['profesor_codigo'];

// Conexion con la base de datos
$server=mysqli_connect("127.0.0.1","casem_user","","casem")
    or die("ERROR: No se pudo establecer conexión con la base de datos.");
    
    //consulta para obtener las preguntas de ese profesor
    $sql = "SELECT * FROM preguntas 
            WHERE pregunta_codigo IN (
                SELECT pregunta_codigo FROM profesor_pregunta
                    WHERE profesor_codigo = '$profesor_codigo')";

        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);

        //tabla para visualizar las preguntas del profesor
        if ($filas > 0)
        {
            print ("<TABLE>\n");
            print ("<TR>\n");
            print ("<TH>Codigo</TH>\n");
            print ("<TH>Texto</TH>\n");
            print ("<TH>Tema</TH>\n");
            print ("<TH>Tipo</TH>\n");
            print ("<TH>Penalizacion</TH>\n");
            print ("<TH>Respuesta</TH>\n");
            print ("</TR>\n");
            
            for ($i=0; $i<$filas; $i++)
            {
                $resultado = mysqli_fetch_array($query);
                
                $sql_respuesta = "SELECT respuesta_texto FROM respuestas WHERE respuesta_codigo IN (
                        SELECT respuesta_codigo FROM pregunta_respuesta
                        WHERE resultado = '1'
                        AND pregunta_codigo ='{$resultado['pregunta_codigo']}')";
                $query_respuesta = mysqli_query($server,$sql_respuesta);
                $respuesta = mysqli_fetch_array($query_respuesta);
                
                print ("<TR>\n");
                print ("<TD>" . $resultado['pregunta_codigo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_texto'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tema'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tipo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_penalizacion'] . "</TD>\n");
                print ("<TD>" . $respuesta['respuesta_texto'] . "</TD>\n");
                print ("</TR>\n");

            }
        print ("</TABLE>\n");
        print ("<BR>\n");

        $_SESSION['user_id'] = $id;
        $_SESSION['user_session'] = $user;
        //hipervinculo para volver al menu de profesor
        print ("<A HREF=profesor.php>volver al inicio</A>");
        }else
        {
            //si no tiene preguntas, le pedimos que vuelva al menu
            echo "No hay preguntas disponibles";
            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
            print ("<A HREF=profesor.php>volver al inicio</A>");
            mysqli_close($server);
        }
?>
</body>
</html>