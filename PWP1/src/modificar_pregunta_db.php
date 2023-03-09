<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modificar preguntas db</title>
</head>
<body>
<?php
//quitar el reporte de errores como variables no inicializadas
error_reporting(0);
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user = $_SESSION['user_session'];
$profesor_codigo = $_SESSION['profesor_codigo'];
$pregunta_codigo = $_POST['pregunta'];
$pregunta_a_modificar = $_SESSION['pregunta_codigo'];
$campo = $_POST['campo'];

//request de modificar
$modificar = $_REQUEST['modificar'];
    //si modificar tiene valor (por defecto, al entrar a la pagina, modificar no tendrá valor, y avisará de que no 
    //está inicializada, por eso colocamos el error_reporting)
    if (isset($modificar))
    {
        $server=mysqli_connect("127.0.0.1","casem_user","","casem")
        or die("ERROR: No se pudo establecer conexión con la base de datos.");
        $cambio = $_POST['cambio'];

        //buscamos los codigos de respuesta de esa pregunta 
        $sql1 = "SELECT respuesta_codigo FROM pregunta_respuesta
                 WHERE pregunta_codigo = '$pregunta_a_modificar'";

        $sql2 = "SELECT resultado FROM pregunta_respuesta
                 WHERE pregunta_codigo = '$pregunta_a_modificar'";

        $sql3 = "SELECT respuesta_codigo FROM respuestas
                 WHERE respuesta_texto = '$cambio'";

        $query1 = mysqli_query($server,$sql1);
        $query2 = mysqli_query($server,$sql2);
        $query3 = mysqli_query($server,$sql3);
        $codigo_respuesta = mysqli_fetch_array($query3);
        $filas = mysqli_num_rows($query1);

        if ($filas > 0){
            for($j=0;$j<$filas;$j++){
                $res1 = mysqli_fetch_array($query1);
                $res2 = mysqli_fetch_array($query2);
                //si el resultado es TRUE (1), lo cambiamos a FALSE (0)
                if ($res2['resultado'] == '1')
                {
                    $sql = "UPDATE pregunta_respuesta
                            SET resultado = '0'
                            WHERE respuesta_codigo = '{$res1['respuesta_codigo']}'
                            AND pregunta_codigo = '$pregunta_a_modificar'";
                    $query = mysqli_query($server,$sql);
                }
                //si los codigos coinciden significa que el la respuesta a la que se quiere colocar como respuesta correcta
                if ($res1['respuesta_codigo'] == $codigo_respuesta['respuesta_codigo'])
                {
                    $sql = "UPDATE pregunta_respuesta
                            SET resultado = '1'
                            WHERE respuesta_codigo = '{$res1['respuesta_codigo']}'
                            AND pregunta_codigo = '$pregunta_a_modificar'";
                    $query = mysqli_query($server,$sql);

                    $sql = "UPDATE preguntas
                            SET pregunta_respuesta = '{$res1['respuesta_codigo']}'
                            WHERE pregunta_codigo = '$pregunta_a_modificar'";
                    $query = mysqli_query($server,$sql);
                }
            }
        }
            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
                
            print ("<H2>La respuesta de la pregunta se ha modificado exitosamente</H2>");
            print ("<A HREF=profesor.php>volver al inicio</A>");
        
    }else
    {
        $server=mysqli_connect("127.0.0.1","casem_user","","casem")
   or die("ERROR: No se pudo establecer conexión con la base de datos.");

   //busqueda de la pregunta con ese codigo
        $sql = "SELECT * FROM preguntas
                WHERE pregunta_codigo = '$pregunta_codigo'";
        
        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);
        if ($filas > 0)
        {
            
            print ("<FORM ACTION='modificar_pregunta_db.php' METHOD='post'>\n");
            print ("<H2>Pregunta a modificar</H2>");
            print ("<TABLE>\n");
            print ("<TR>\n");
            print ("<TH>Texto</TH>\n");
            print ("<TH>Tema</TH>\n");
            print ("<TH>Tipo</TH>\n");
            print ("<TH>Penalizacion</TH>\n");
            print ("</TR>\n");
            

            for ($i=0; $i<$filas; $i++)
            {
                $resultado = mysqli_fetch_array($query);
                print ("<TR>\n");
                print ("<TD>" . $resultado['pregunta_texto'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tema'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tipo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_penalizacion'] . "</TD>\n");
                print ("</TR>\n");

            }
        print ("</TABLE>\n");
        print ("<BR>\n");
        //si lo que se quiere cambiar es la respuesta correcta
        if ($campo == 'respuesta correcta')
        {
            //y si ademas, es de tipo V/F, alternamos los valores de las respuestas
            if ($resultado['pregunta_tipo'] == 'v/f')
            {
                $sql1 = "SELECT respuesta_codigo FROM pregunta_respuesta
                         WHERE pregunta_codigo = '$pregunta_codigo'";

                $sql2 = "SELECT resultado FROM pregunta_respuesta
                         WHERE pregunta_codigo = '$pregunta_codigo'";
                $query1 = mysqli_query($server,$sql1);
                $query2 = mysqli_query($server,$sql2);
                $filas = mysqli_num_rows($query1);
                if ($filas > 0){
                    for($j=0;$j<$filas;$j++){
                        $res1 = mysqli_fetch_array($query1);
                        $res2 = mysqli_fetch_array($query2);
                        if ($res2['resultado'] == '1')
                        {
                            $sql = "UPDATE pregunta_respuesta
                                    SET resultado = '0'
                                    WHERE respuesta_codigo = '{$res1['respuesta_codigo']}'
                                    AND pregunta_codigo = '$pregunta_codigo'";
                            $query = mysqli_query($server,$sql);
                        }else
                        {
                            $sql = "UPDATE pregunta_respuesta
                                    SET resultado = '1'
                                    WHERE respuesta_codigo = '{$res1['respuesta_codigo']}'
                                    AND pregunta_codigo = '$pregunta_codigo'";
                            $query = mysqli_query($server,$sql);

                            $sql = "UPDATE preguntas
                                    SET pregunta_respuesta = '{$res1['respuesta_codigo']}'
                                    WHERE pregunta_codigo = '$pregunta_codigo'";
                            $query = mysqli_query($server,$sql);
                        }
                    }   
                }

                $_SESSION['user_id'] = $id;
                $_SESSION['user_session'] = $user;
                
                print ("<H2>La respuesta de la pregunta se ha modificado exitosamente</H2>");
                print ("<A HREF=profesor.php>volver al inicio</A>");

            }else
            //si no, habrá que buscar las respuestas de la pregunta y preguntarle al usuario por la nueva
            //respuesta correcta
            {
                print ("Cambiar respuesta correcta:\n");
                print ("<SELECT NAME='cambio'>");
            
                $sql = "SELECT * FROM respuestas WHERE respuesta_codigo IN (
                    SELECT respuesta_codigo FROM pregunta_respuesta WHERE pregunta_codigo = '$pregunta_codigo' AND resultado != '1')";
                $query = mysqli_query($server,$sql);
                    $filas = mysqli_num_rows($query);
                if ($filas > 0)
                {
                    for($j=0;$j<$filas;$j++)
                    {
                    $fila = mysqli_fetch_array($query);
                    ?>
                    <OPTION NAME='cambio'> <?php echo $fila['respuesta_texto']; ?></OPTION>
                    <?php
                    }
                }
                $_SESSION['pregunta_codigo'] = $pregunta_codigo;
                print ("<BR>\n");
                print ("</SELECT>");
                print ("<BR>\n");
                print ("<INPUT TYPE='SUBMIT' NAME='modificar' VALUE='modificar'>\n");
                print ("</FORM>\n");
            }
        }else
        //si lo que queremos cambiar es la penalizacion de la pregunta, se la cambiamos directamente en la base de datos
        {
            $sql = "SELECT pregunta_penalizacion FROM preguntas
                    WHERE pregunta_codigo = '$pregunta_codigo'";
            
            $query = mysqli_query($server,$sql);
            $penalizacion = mysqli_fetch_array($query);
            if ($penalizacion['pregunta_penalizacion'] == 'medio')
            {
                $sql = "UPDATE preguntas
                SET pregunta_penalizacion = 'cuarto'
                WHERE pregunta_codigo = '$pregunta_codigo'";
                mysqli_query($server,$sql);
            }else
            {
                $sql = "UPDATE preguntas
                SET pregunta_penalizacion = 'medio'
                WHERE pregunta_codigo = '$pregunta_codigo'";
                mysqli_query($server,$sql);
            }

            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
            
            print ("<H2>La penalización de la pregunta se ha modificado exitosamente</H2>");
            print ("<A HREF=profesor.php>volver al inicio</A>");
        }
        
    }
}
?>
</body>
</html>