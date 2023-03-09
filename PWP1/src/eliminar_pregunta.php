<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eliminar_pregunta</title>
</head>
<body>
<h1>eliminar pregunta</h1>
<?php
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$profesor_codigo = $_SESSION['profesor_codigo'];

error_reporting(0);

//de manera similar a la matriculacion de estudiantes, recibimos un post desde la misma pagina con los datos de la
//pregunta a eliminar
$eliminar = $_REQUEST['eliminar'];
   if (isset($eliminar))
   {

   $server=mysqli_connect("127.0.0.1","casem_user","","casem")
   or die("ERROR: No se pudo establecer conexión con la base de datos.");

      $borrar = $_REQUEST['borrar'];
      $nfilas = count ($borrar);

      for ($i=0; $i<$nfilas; $i++)
      {
        //buscamos las preguntas con esos codigos
         $sql = "SELECT * FROM preguntas WHERE pregunta_codigo = $borrar[$i]";
         $query = mysqli_query ($server, $sql)
            or die ("Fallo en la consulta");
         $resultado = mysqli_fetch_array ($query);

         print ("Pregunta eliminada:\n");
         print ("<UL>\n");
         print ("   <LI>Texto: " . $resultado['pregunta_texto']);
         print ("   <LI>Tema: " . $resultado['pregunta_tema']);
         print ("   <LI>Tipo: " . $resultado['pregunta_tipo']);
         print ("   <LI>Penalizacion: " . $resultado['pregunta_penalizacion']);
         print ("</UL>\n");

        //si es V/F, no le borramos las respuestas por detalles de implementacion
        if ($resultado['pregunta_tipo'] == "v/f")
        {
            $sql = "DELETE FROM profesor_pregunta WHERE pregunta_codigo = $borrar[$i]";
            $query = mysqli_query ($server,$sql);

            $sql = "DELETE FROM preguntas WHERE pregunta_codigo = $borrar[$i]";
            $query = mysqli_query ($server,$sql);
        }else
        {
            //si no, buscamos las respuestas de esa pregunta y las borramos tambien
            $query = mysqli_query($server,"SELECT respuesta_codigo FROM pregunta_respuesta WHERE pregunta_codigo = $borrar[$i]");
            $filas = mysqli_num_rows($query);
            $respuestas = array();
            if ($filas > 0){
                for($j=0;$j<$filas;$j++){
                $res = mysqli_fetch_array($query);
                $respuestas[] = $res['respuesta_codigo'];
                }
            }
            $respuestas = join(', ', $respuestas);

            $sql = "DELETE FROM pregunta_respuesta WHERE pregunta_codigo = {$resultado['pregunta_codigo']}";
            $query = mysqli_query ($server,$sql);

            $sql = "DELETE FROM profesor_pregunta WHERE pregunta_codigo = $borrar[$i]";
            $query = mysqli_query ($server,$sql);

            $sql = "DELETE FROM preguntas WHERE pregunta_codigo = $borrar[$i]";
            $query = mysqli_query ($server,$sql);

            $sql = "DELETE FROM respuestas WHERE respuesta_codigo IN ($respuestas)";
            $query = mysqli_query ($server,$sql);
        }
        print ("<P>Numero total de preguntas eliminadas: " . $nfilas . "</P>\n");
      }
      
      mysqli_close ($server);
      print ("<A HREF='eliminar_pregunta.php'>eliminar mas preguntas</A>\n");
   }
   else
   {
    $server=mysqli_connect("127.0.0.1","casem_user","","casem")
    or die("ERROR: No se pudo establecer conexión con la base de datos.");
    
    //se buscan las preguntas de ese profesor
    $sql = "SELECT * FROM preguntas 
            WHERE pregunta_codigo IN (
                SELECT pregunta_codigo FROM profesor_pregunta
                    WHERE profesor_codigo = '$profesor_codigo')";

        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);

        if ($filas > 0)
        {
            
            print ("<FORM ACTION='eliminar_pregunta.php' METHOD='post'>\n");
            print ("<TABLE>\n");
            print ("<TR>\n");
            print ("<TH>Texto</TH>\n");
            print ("<TH>Tema</TH>\n");
            print ("<TH>Tipo</TH>\n");
            print ("<TH>Penalizacion</TH>\n");
            print ("<TH>borrar</TH>\n");
            print ("</TR>\n");
            

            for ($i=0; $i<$filas; $i++)
            {
                $resultado = mysqli_fetch_array($query);
                print ("<TR>\n");
                print ("<TD>" . $resultado['pregunta_texto'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tema'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_tipo'] . "</TD>\n");
                print ("<TD>" . $resultado['pregunta_penalizacion'] . "</TD>\n");
                print ("<TD><INPUT TYPE='CHECKBOX' NAME='borrar[]' VALUE='" .
               $resultado['pregunta_codigo'] . "'></TD>\n");

                print ("</TR>\n");

            }
        print ("</TABLE>\n");
        print ("<BR>\n");
        print ("<INPUT TYPE='SUBMIT' NAME='eliminar' VALUE='eliminar preguntas marcadas'>\n");
        print ("</FORM>\n");
        $_SESSION['user_id'] = $id;
        $_SESSION['user_session'] = $user;
        print ("<A HREF=profesor.php>volver al inicio</A>");

        }else
        {
            echo "No hay preguntas disponibles";
            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
            print ("<A HREF=profesor.php>volver al inicio</A>");
            mysqli_close($server);
        }
    
    }
?>
</body>
</html>