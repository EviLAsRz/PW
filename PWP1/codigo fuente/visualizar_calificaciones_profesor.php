<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar calificaciones profesor</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user = $_SESSION['user_session'];
$profesor_codigo = $_SESSION['profesor_codigo'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem")
    or die("ERROR: No se pudo establecer conexión con la base de datos.");

//Buscamos las asignaturas de ese profesor
$sql_asignaturas = "SELECT * FROM profesor_asignatura WHERE profesor_codigo = '$profesor_codigo'";
$query_asignaturas = mysqli_query($server,$sql_asignaturas);
$filas_asignaturas = mysqli_num_rows($query_asignaturas);
//array para almacenar los codigos de las asignaturas
$asignaturas = array();

if ($filas_asignaturas > 0)
{
    for ($i = 0; $i < $filas_asignaturas; $i++)
    {
        $asignaturas_profesor = mysqli_fetch_array($query_asignaturas);
        //mientras encuentre asignaturas, vamos guardando su codigo en un array
        $asignaturas[] = $asignaturas_profesor['asignatura_codigo'];
    }
}
//formateamos el array para poder pasarlo a una consulta sql
$asignaturas = join(', ', $asignaturas);

//consulta para obtener todas las temas de las asignaturas con esos codigos de asignatura (asignaturas del profesor)
$sql_temas = "SELECT * FROM asignatura_tema WHERE asignatura_codigo IN ($asignaturas)";
$query_temas = mysqli_query($server,$sql_temas);
$filas_temas = mysqli_num_rows($query_temas); 
//hacemos lo mismo para los temas
$temas = array();
if ($filas_temas > 0)
{
    for ($i = 0; $i < $filas_temas; $i++)
    {
        $temas_profesor = mysqli_fetch_array($query_temas);
        //cada codigo de los temas lo guardamos en un array
        $temas[] = $temas_profesor['tema_codigo'];
    }
}
//formateamos el array para poder pasarlo a una consulta sql
$temas = join(', ', $temas);

//consulta para obtener los examenes con ese codigo (por implementación de la base de datos,
//se ha tenido en cuenta que el codigo del examen es igual al del tema)
$sql_examenes = "SELECT * FROM examenes WHERE examen_codigo IN ($temas)";
$query_examenes = mysqli_query($server,$sql_examenes);
$filas_examenes = mysqli_num_rows($query_examenes);
//creacion de dos arrays, uno para los examenes disponibles y otro para los examenes caducados
$examenes_disponibles = array();
$examenes_caducado = array();
if ($filas_examenes > 0)
{  
    for ($i=0; $i<$filas_examenes; $i++)
    {
        $examenes = mysqli_fetch_array($query_examenes);
        //consulta que nos devuelve la cantidad de estudiantes que han realizado el examen
        $sql_tema = "SELECT * FROM estudiante_examen
                        WHERE examen_codigo = '{$examenes['examen_tema']}'";
        $query_examenes_disp = mysqli_query($server,$sql_tema);
        $coincidencias = mysqli_num_rows($query_examenes_disp);
        //consulta que nos devuelve lla fecha del examen
        $sql_fecha_examen = "SELECT examen_fecha FROM examenes
                        WHERE examen_tema = '{$examenes['examen_codigo']}'";
        $query_fecha_examen = mysqli_query($server,$sql_fecha_examen);
        $fecha_valida = mysqli_fetch_array($query_fecha_examen);

        //si existe mas de 0 estudiantes que han realizado el examen, guardamos el codigo de ese examen en un array
        if ($coincidencias > 0)
        {
            $examenes_disponibles[] = $examenes['examen_codigo'];
        }
        //si la fecha del examen es distinta a la fecha actual (fecha del sistema), guardamos el codigo en otro array
        if ($fecha_valida['examen_fecha'] != date("Y-m-d"))
        {
        $examenes_caducado[] = $examenes['examen_codigo'];
        }
    }
}
//Si no ha encontrado examenes disponibles, manda al usuario al inicio
if (empty($examenes_disponibles))
{
    print ("<H4>No hay exámenes disponibles</H4>");
    $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
    print ("<A HREF=profesor.php>volver al inicio</A>");
}else
//si no, concatena los arrays de los examenes
{
    
    $array_resultado = array_merge($examenes_disponibles,$examenes_caducado);
    $array_resultado = join(', ', $array_resultado);
    //buscar los examenes con esos codigos 
    $sql_examenes_disponibles = "SELECT examen_tema FROM examenes WHERE examen_tema IN ($array_resultado)";
    $query_examenes_disponibles = mysqli_query($server,$sql_examenes_disponibles);
    $filas_examenes_disponibles = mysqli_num_rows($query_examenes_disponibles);
    if ($filas_examenes_disponibles > 0)
        {
            for ($i=0; $i<$filas_examenes_disponibles; $i++)
            {
               
                $resultado = mysqli_fetch_array($query_examenes_disponibles);
                //consulta para buscar los nombre de los examenes (mismo que el del tema)
                $sql_examen_nombre = "SELECT tema_nombre FROM temas WHERE tema_codigo = '{$resultado['examen_tema']}'";
                $query_examen_nombre = mysqli_query($server,$sql_examen_nombre);
                $examen_nombre = mysqli_fetch_array($query_examen_nombre); 

                //consulta para buscar los datos del examen
                $sql_datos_examen = "SELECT * FROM estudiante_examen WHERE examen_codigo = '{$resultado['examen_tema']}'";
                $query_datos_examen = mysqli_query($server,$sql_datos_examen);
                $filas_datos_examen = mysqli_num_rows($query_datos_examen);
                print("<H4>{$examen_nombre['tema_nombre']}</H4>");

                //variables para calcular los datos generales de los examenes
                $calificacion = 0;
                $suspensos = 0;
                $aprobados = 0;
                $notables = 0;
                $sobresalientes = 0;
                $j = 0;
                    if ($filas_datos_examen > 0)
                    {
                        print ("<TABLE>\n");
                        print ("<TR>\n");
                        print ("<TH>Nombre</TH>\n");
                        //print ("<TH>Examen tema</TH>\n");
                        print ("<TH>Calificación</TH>\n");
                        print ("</TR>\n");

                        for ($j=0; $j<$filas_datos_examen; $j++)
                        {
                            $datos_examen = mysqli_fetch_array($query_datos_examen);

                            //se obtiene el nombre del examen
                            $sql_tema = "SELECT tema_nombre FROM temas 
                                    WHERE tema_codigo = '{$datos_examen['examen_codigo']}'";
                            $query_tema = mysqli_query($server,$sql_tema);
                            $nombre_tema = mysqli_fetch_array($query_tema);
                            //si nota del alumno < 5
                            if ($datos_examen['calificacion'] < 5)
                            {
                                $suspensos ++;
                            }
                            //si nota del alumno >= 5
                            if ($datos_examen['calificacion'] >= 5)
                            {
                                $aprobados ++;
                            }
                            //si nota del alumno > 5 y <9
                            if ($datos_examen['calificacion'] > 5 && $datos_examen['calificacion'] < 9)
                            {
                                $notables ++;
                            }
                            //si nota del alumno > 8
                            if ($datos_examen['calificacion'] > 8)
                            {
                                $sobresalientes ++;
                            }   

                            
                            $calificacion += $datos_examen['calificacion'];   
                            print ("<TR>\n");
                            print ("<TD>" . $datos_examen['estudiante_nombre'] . "</TD>\n");
                            //print ("<TD>" . $nombre_tema['tema_nombre'] . "</TD>\n");
                            print ("<TD>" . $datos_examen['calificacion'] . "</TD>\n");
                            print ("</TR>\n");
                        }
                        
                        
                    }
                    //se imprimen los valores generales del examen
                    print ("</TABLE>\n");
                    print ("</BR>");
                    print "Suspensos: $suspensos";
                    print ("</BR>");
                    print "Aprobados: $aprobados";
                    print ("</BR>");
                    print "Notables: $notables";
                    print ("</BR>");
                    print "Sobresalientes: $sobresalientes";
                    print ("</BR>");

                    //si no hay ningun estudiante que ha realizado el examen, la media total es 0
                    if ($j == 0)
                    {
                        print "Nota Media Total: $j";
                        print ("</BR>");print ("</BR>");print ("</BR>");
                    }else
                    //si no, nota media = suma calificacion/numero de alumnos, formateado a dos decimales
                    {
                        print "Nota Media Total: " .round($calificacion/$j,2)."";
                        print ("</BR>");print ("</BR>");print ("</BR>");
                    }

                   
                    
            }
            $_SESSION['user_id'] = $id;
            $_SESSION['user_session'] = $user;
            print ("<A HREF=profesor.php>volver al inicio</A>");
        
    }
}
?>
</body>
</html>