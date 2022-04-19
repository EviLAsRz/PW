<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>maticulacion estudiante</title>
</head>
<body>
<?php
error_reporting(0);
//inicio de sesion
session_start();
$id = $_SESSION['user_id'];
$user= $_SESSION['user_session'];
$estudiante_codigo = $_SESSION['estudiante_codigo'];
$crear = $_REQUEST['crear'];

//si crear tiene valor (similar a la eliminacion de preguntas)
if (isset($crear))
{
    
    $server=mysqli_connect("127.0.0.1","casem_user","","casem")
   or die("ERROR: No se pudo establecer conexión con la base de datos.");

   //obtencion de los codigos de asignaturas a matricularse
    $matricula = $_REQUEST['matricula'];
    $nfilas = count ($matricula);
    
    
    for ($i=0; $i<$nfilas; $i++)
      {
        //consulta para buscar esas asignaturas
        $sql = "SELECT * FROM asignaturas WHERE asignatura_codigo = $matricula[$i]";
        $query = mysqli_query ($server, $sql)
           or die ("Fallo en la consulta");
        $resultado = mysqli_fetch_array ($query);
        
        print ("<H2>Matricula actualizada</h2>\n");
        print ("<UL>\n");
        print ("   <LI>Codigo: " . $resultado['asignatura_codigo']);
        print ("   <LI>Nombre: " . $resultado['asignatura_nombre']);
        print ("</UL>\n");

        //relacionamos al estudiante con las asignaturas
        $sql = "INSERT INTO asignatura_estudiante (estudiante_id, asignatura_id)
                VALUES ('$estudiante_codigo','{$resultado['asignatura_codigo']}')";

        mysqli_query($server,$sql);
        
      }
      $_SESSION['user_id'] = $id;
    $_SESSION['user_session'] = $user;
    print ("<A HREF=estudiante.php>volver al inicio</A>");
    mysqli_close($server);

}else
{
    $server=mysqli_connect("127.0.0.1","casem_user","","casem") or die(mysqli_connect_error());

    //consulta para obtener el grado del estudiante
    $sql = "SELECT estudiante_grado FROM estudiantes
            WHERE estudiante_codigo = '$estudiante_codigo'";

    $query = mysqli_query($server,$sql);
    $grado_estudiante = mysqli_fetch_array($query);
    //consulta para obtener el id del estudiante
    $sql = "SELECT asignatura_id FROM asignatura_estudiante WHERE
            estudiante_id = '$estudiante_codigo'";
    
    $query = mysqli_query($server,$sql);
    $filas = mysqli_num_rows($query);
    $asignaturas = array();
    if ($filas > 0)
    {
        for($i = 0; $i < $filas; $i++)
        {
            $resultado = mysqli_fetch_array($query);
            $asignaturas[] = $resultado['asignatura_id'];
        }
        $asignaturas = join(', ', $asignaturas);
    
    //consulta para obtener las asignaturas del estudiante
    $sql = "SELECT asignatura_nombre FROM asignaturas
            WHERE asignatura_codigo IN ($asignaturas)";
    
    $query = mysqli_query($server,$sql);
    $filas = mysqli_num_rows($query);
    
    if($filas > 0)
    {
        print ("<H2>Asignaturas en curso</H2>");
        print ("<TABLE>\n");
                print ("<TR>\n");
                print ("<TH>Nombre</TH>\n");
                print ("</TR>\n");
        for ($i=0; $i<$filas; $i++)
        {
        $fila = mysqli_fetch_array($query);
        //visualizar los datos
        print ("<TR>");
        print ("<TD>".$fila['asignatura_nombre']."</TD>");
        print ("</TR>");
        }
        print ("</TABLE>");
    }
        //matricula
        print ("<H2>Matricula de asignaturas</H2>\n");
        //obtenemos las asignaturas del grado del estudiante
        $sql = "SELECT asignatura_codigo FROM grado_asignatura WHERE
            grado_nombre = '{$grado_estudiante['estudiante_grado']}'";
        
        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);
        $asignaturas = array();

        if ($filas > 0)
        {
            for($i = 0; $i < $filas; $i++)
            {
            $resultado = mysqli_fetch_array($query);
            $asignaturas[] = $resultado['asignatura_codigo'];
            }
            $asignaturas = join(', ', $asignaturas);
            
            //consulta para buscar las asignaturas del grado pero que no esten asociadas con ese estudiante
            $sql = "SELECT * FROM asignaturas
                    WHERE asignatura_codigo IN ($asignaturas)
                    AND asignatura_codigo NOT IN (
                    SELECT asignatura_id FROM asignatura_estudiante
                    WHERE estudiante_id = '$estudiante_codigo')";
            
            $query = mysqli_query($server,$sql);
            $filas = mysqli_num_rows($query);

            if($filas>0)
            {
                print ("<FORM ACTION='matriculacion_estudiante.php' METHOD='post'>\n");
                print ("<TABLE>\n");
                print ("<TR>\n");
                print ("<TH>Codigo</TH>\n");
                print ("<TH>Nombre</TH>\n");
                print ("<TH>matricula</TH>\n");
                print ("</TR>\n");
                for ($i=0; $i<$filas; $i++)
                {
                    $resultado = mysqli_fetch_array($query);
                
                    print ("<TD>" . $resultado['asignatura_codigo'] . "</TD>\n");
                    print ("<TD>" . $resultado['asignatura_nombre'] . "</TD>\n");
                    //checkbox para saber que a que asignaturas se va a matricularse
                    print ("<TD><INPUT TYPE='CHECKBOX' NAME='matricula[]' VALUE='" .
                    //guardamos el codigo
                    $resultado['asignatura_codigo'] . "'></TD>\n");
                    print ("</TR>\n");
                }
                print ("</TABLE>\n");
                print ("<INPUT TYPE='SUBMIT' NAME='crear' VALUE='matricularse de las asignaturas seleccionadas'>\n");
                print ("</FORM>\n");
                $_SESSION['user_id'] = $id;
                $_SESSION['user_session'] = $user;
                print ("<A HREF=estudiante.php>volver al inicio</A>");
                mysqli_close($server);

            }else
            {
                print ("<H4>No hay asignaturas disponibles</H4>");
                $_SESSION['user_id'] = $id;
                $_SESSION['user_session'] = $user;
                print ("<A HREF=estudiante.php>volver al inicio</A>");
                mysqli_close($server);
            }
            
        }
}else
    {
        //si el alumno no tiene asignaturas, se le despliega el menu de matriculacion directamente
        print ("<H2>No hay asignaturas disponibles</H2>\n");
        print ("<H2>Matricula de asignaturas</H2>\n");
        $sql = "SELECT asignatura_codigo FROM grado_asignatura WHERE
            grado_nombre = '{$grado_estudiante['estudiante_grado']}'";

        $query = mysqli_query($server,$sql);
        $filas = mysqli_num_rows($query);
        $asignaturas = array();

        if ($filas > 0)
        {
            for($i = 0; $i < $filas; $i++)
            {
            $resultado = mysqli_fetch_array($query);
            $asignaturas[] = $resultado['asignatura_codigo'];
            }
            $asignaturas = join(', ', $asignaturas);
            
            $sql = "SELECT * FROM asignaturas
                    WHERE asignatura_codigo IN ($asignaturas)";
            
            $query = mysqli_query($server,$sql);
            $filas = mysqli_num_rows($query);

            if($filas>0)
            {
                print ("<FORM ACTION='matriculacion_estudiante.php' METHOD='post'>\n");
                print ("<TABLE>\n");
                print ("<TR>\n");
                print ("<TH>Codigo</TH>\n");
                print ("<TH>Nombre</TH>\n");
                print ("<TH>matricula</TH>\n");
                print ("</TR>\n");
                for ($i=0; $i<$filas; $i++)
                {
                    $resultado = mysqli_fetch_array($query);
                
                    print ("<TD>" . $resultado['asignatura_codigo'] . "</TD>\n");
                    print ("<TD>" . $resultado['asignatura_nombre'] . "</TD>\n");
                    print ("<TD><INPUT TYPE='CHECKBOX' NAME='matricula[]' VALUE='" .
                    $resultado['asignatura_codigo'] . "'></TD>\n");
                    print ("</TR>\n");
                }
                print ("</TABLE>\n");
                print ("<INPUT TYPE='SUBMIT' NAME='crear' VALUE='matricularse de las asignaturas seleccionadas'>\n");
                print ("</FORM>\n");
                $_SESSION['user_id'] = $id;
                $_SESSION['user_session'] = $user;
                print ("<A HREF=estudiante.php>volver al inicio</A>");
                mysqli_close($server);
            }
            
        }
    }
}
?>  
</body>
</html>