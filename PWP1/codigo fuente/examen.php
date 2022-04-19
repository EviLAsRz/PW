<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>examen</title>
</head>
<body>
<?php
//inicio de sesion
session_start();
$user = $_SESSION['user_session'];
$id = $_SESSION['user_id'];
$estudiante_codigo = $_SESSION['estudiante_codigo'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die(mysqli_connect_error());

//Consulta para comprobar si el estudiante está matriculado en alguna asignatura
$sql = "SELECT asignatura_id FROM asignatura_estudiante
        WHERE estudiante_id = '$estudiante_codigo'";
$query = mysqli_query($server,$sql);
$filas = mysqli_num_rows($query);
$asignaturas = array();
//Si lo esta, se comprueba si esas asignaturas tienen temas
if ($filas > 0)
{
    for($i=0;$i<$filas;$i++)
    {
        $resultado = mysqli_fetch_array($query);
        $asignaturas[] = $resultado['asignatura_id'];
    }
    $asignaturas = join(', ', $asignaturas);

    //se obtienen los valores de las diferentes asignaturas
    $sql = "SELECT tema_codigo, asignatura_codigo FROM asignatura_tema 
            WHERE asignatura_codigo IN ($asignaturas)";
    $query_examen = mysqli_query($server,$sql);
    $filas_tema = mysqli_num_rows($query_examen);
    $examenes_realizados = array();
    $examenes_invalidos = array();
    $examenes_caducado = array();
    //De cada tema, vamos a comprobar que el estudiante no haya realizado si examen y que
    //ademas, aunque no lo haya realizado, que el tema del examen tenga al menos 10 preguntas.
    if ($filas_tema > 0)
    {
        for ($i = 0; $i < $filas_tema; $i++)
        {
            
            $tema_codigo = mysqli_fetch_array($query_examen);
            //sacamos los temas los cuales el estudiante ya ha realizado el examen
            $sql_examen = "SELECT * FROM estudiante_examen
                           WHERE estudiante_codigo = '$estudiante_codigo'
                           AND examen_codigo = '{$tema_codigo['tema_codigo']}'";
            $query_examen_auxiliar = mysqli_query($server,$sql_examen);
            $coincidencias = mysqli_num_rows($query_examen_auxiliar);

            //sacamos los temas que tienen al menos 10 preguntas mediante un COUNT(*)
            $sql_numero_preguntas = "SELECT COUNT(*) FROM preguntas 
                                     WHERE pregunta_tema = '{$tema_codigo['tema_codigo']}'";
            $query_tema_valido = mysqli_query($server,$sql_numero_preguntas);
            $tema_valido = mysqli_fetch_array($query_tema_valido);

            //hacer consulta de fecha
            $sql_fecha_examen = "SELECT examen_fecha FROM examenes
                                 WHERE examen_tema = '{$tema_codigo['tema_codigo']}'";
            $query_fecha_examen = mysqli_query($server,$sql_fecha_examen);
            $fecha_valida = mysqli_fetch_array($query_fecha_examen);

            if ($coincidencias > 0)
            {
                $examenes_realizados[] = $tema_codigo['tema_codigo'];
            }
            if ($tema_valido['COUNT(*)'] < 10)
            {
                $examenes_invalidos[] = $tema_codigo['tema_codigo'];
            }
            if ($fecha_valida['examen_fecha'] != date("Y-m-d"))
            {
                $examenes_caducado[] = $tema_codigo['tema_codigo'];
            }
        }
    }
//Si el estudiante no ha realizado ningun examen, entonces se buscan los temas que tengan examen disponible
if (empty($examenes_realizados))
{   
    $array_resultado = array_merge($examenes_caducado,$examenes_invalidos);
    $array_resultado = join(', ', $array_resultado);
    $sql = "SELECT tema_codigo, asignatura_codigo FROM asignatura_tema 
            WHERE asignatura_codigo IN ($asignaturas) AND tema_codigo NOT IN ($array_resultado)";
    $query = mysqli_query($server,$sql);
    $filas = mysqli_num_rows($query);

//si no, buscamos los temas de los cuales el estudiante no haya realizado el examen y que además, este esté disponible
}else
{
    $array_resultado = array_merge($examenes_realizados,$examenes_invalidos,$examenes_caducado);
    $array_resultado = join(', ', $array_resultado);
    $sql = "SELECT tema_codigo, asignatura_codigo FROM asignatura_tema 
            WHERE asignatura_codigo IN ($asignaturas) AND tema_codigo NOT IN ($array_resultado)";
    $query = mysqli_query($server,$sql);
    $filas = mysqli_num_rows($query);
}

if ($filas > 0)
{
    print ("<H2>Lista de examenes</H2>");
    print ("<FORM method='post' action='generador_examen.php'>");
    print ("<SELECT name='examen'>");
    for($i=0;$i<$filas;$i++)
    {   
        $resultado = mysqli_fetch_array($query);
        $sql = "SELECT tema_nombre FROM temas WHERE tema_codigo = '{$resultado['tema_codigo']}'";
        $query_nombre = mysqli_query($server,$sql);
        $sql = "SELECT asignatura_nombre FROM asignaturas WHERE asignatura_codigo = '{$resultado['asignatura_codigo']}'";
        $query_asignatura = mysqli_query($server,$sql);
        $asignatura_nombre = mysqli_fetch_array($query_asignatura);
        $tema_nombre = mysqli_fetch_array($query_nombre);
        print ("<OPTION name='examen' value ='{$resultado['tema_codigo']}'>".$tema_nombre['tema_nombre']." | ".$asignatura_nombre['asignatura_nombre']."</OPTION>");
    }
    print ("</SELECT></BR>");
    print ("<INPUT type='submit' name ='realizar' value='realizar examen'/>");
    print ("</FORM>");
}else
{
    print ("<H4>No hay exámenes disponibles</H4>");
    $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
    print ("<A HREF=estudiante.php>volver al inicio</A>");
}
}else
{
    print ("<H4>No hay exámenes disponibles</H4>");
    $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
    print ("<A HREF=estudiante.php>volver al inicio</A>");
}

?>  
</body>
</html>