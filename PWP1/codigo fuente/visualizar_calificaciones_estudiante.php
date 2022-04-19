<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar calificaciones estudiante</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$estudiante_codigo = $_SESSION['estudiante_codigo'];
//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem")
    or die("ERROR: No se pudo establecer conexión con la base de datos.");
    
    //buscamos los datos de examen de ese estudiante
    $sql = "SELECT * FROM estudiante_examen 
            WHERE estudiante_codigo = '$estudiante_codigo'";

        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);

        if ($filas > 0)
        {   
            for ($i=0; $i<$filas; $i++)
            {
                $resultado = mysqli_fetch_array($query);
                
                //consulta para buscar el nombre del examen
                $sql_respuesta = "SELECT tema_nombre FROM temas WHERE tema_codigo = '{$resultado['examen_codigo']}'";
                $query_respuesta = mysqli_query($server,$sql_respuesta);
                $respuesta = mysqli_fetch_array($query_respuesta);
                
                //consulta para buscar las preguntas que ha fallado el estudiante
                $sql_preguntas_erroreas = "SELECT * FROM estudiante_pregunta
                                           WHERE estudiante_codigo = '$estudiante_codigo'
                                           AND examen_codigo = '{$resultado['examen_codigo']}'";
                $query_preguntas_erroneas = mysqli_query($server,$sql_preguntas_erroreas);
                $filas_preguntas_erroneas = mysqli_num_rows($query_preguntas_erroneas);
                print ("<H4>Examen: {$respuesta['tema_nombre']}</H4>");
                //dependiendo de la nota del examen, se muestra suspenso, aprobado, etc mas la nota real del examen
                if ($resultado['calificacion'] < 5)
                {
                    print ("<H4>Nota: {$resultado['calificacion']} (Suspenso)</H4>");
                } else if ($resultado['calificacion'] >= 5 && $resultado['calificacion'] < 7)
                {
                    print ("<H4>Nota: {$resultado['calificacion']} (Aprobado)</H4>"); 
                }else if ($resultado['calificacion'] > 6 && $resultado['calificacion'] < 9)
                {
                    print ("<H4>Nota: {$resultado['calificacion']} (Notable)</H4>");
                }else
                {
                    print ("<H4>Nota: {$resultado['calificacion']} (Sobresaliente)</H4>");
                }
                
                if ($filas_preguntas_erroneas > 0)
                {
                    print ("<TABLE>\n");
                    print ("<TR>\n");
                    print ("<TH>Examen tema</TH>\n");
                    print ("<TH>Pregunta erréonea</TH>\n");
                    print ("</TR>\n");

                    for ($j=0; $j<$filas_preguntas_erroneas; $j++)
                    {

                        $datos_examen = mysqli_fetch_array($query_preguntas_erroneas);
                        //consulta para buscar el nombre de la pregunta fallada
                        $sql_pregunta_nombre = "SELECT pregunta_texto FROM preguntas
                                                WHERE pregunta_codigo = '{$datos_examen['pregunta_codigo']}'";
                        $query_pregunta_nombre = mysqli_query($server,$sql_pregunta_nombre);
                        $pregunta_nombre = mysqli_fetch_array($query_pregunta_nombre);

                        print ("<TR>\n");
                        print ("<TD>" . $respuesta['tema_nombre'] . "</TD>\n");
                        print ("<TD>" . $pregunta_nombre['pregunta_texto'] . "</TD>\n");
                        print ("</TR>\n");
                    }
                }
                print ("</TABLE>\n");
                print ("<BR>\n");
            }
        
        $_SESSION['user_id'] = $id;
        $_SESSION['user_session'] = $user;
        print ("<A HREF=estudiante.php>volver al inicio</A>");
        }else
        {
            echo "No hay calificaciones disponibles";
            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
            print ("<A HREF=estudiante.php>volver al inicio</A>");
            mysqli_close($server);
        }
?>   
</body>
</html>