<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor</title>
</head>
<body>
<?php
// Iniciamos la sesion y obtenemos el nombre del usuario
session_start();
$user = $_SESSION['user_session'];
$id = $_SESSION['user_id'];

//conexion con el servidor
$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die(mysqli_connect_error());

//consulta para obtener el código del profesor
$sql = "SELECT profesor_codigo FROM profesores WHERE profesor_usuario = '$id'";
$profesor = mysqli_query($server,$sql);
$profesor_codigo = mysqli_fetch_array($profesor);

//lo guardamos en una variable de sesión
$_SESSION['profesor_codigo'] = $profesor_codigo['profesor_codigo'];

//consulta para obtener las asignaturas del profesor
$sql = "SELECT asignatura_codigo, asignatura_nombre FROM asignaturas 
            WHERE asignatura_codigo IN (
            SELECT asignatura_codigo FROM profesor_asignatura
                WHERE profesor_codigo = (
                SELECT profesor_codigo FROM profesores WHERE profesor_usuario = '$id'))";

$query=mysqli_query($server,$sql);
$filas=mysqli_num_rows($query);

//tabla para la visualizacion de las asignaturas
if($filas>0)
{
    print ("<H2>Asignaturas impartidas</H2>");
    print ("<TABLE>\n");
    print ("<TR>\n");
    print ("<TH>Asignaturas</TH>\n");
    print ("<TH>Grado</TH>\n");
    print ("</TR>\n");

    for ($i=0; $i<$filas; $i++)
    {
        $fila=mysqli_fetch_array($query);
        //visualizar los datos de la asignatura
        $sql_grado_asignatura = "SELECT grado_nombre FROM grado_asignatura
                                 WHERE asignatura_codigo = '{$fila['asignatura_codigo']}'";
        $query_grado_asignatura = mysqli_query($server,$sql_grado_asignatura);
        $grado_asignatura = mysqli_fetch_array($query_grado_asignatura);
        print ("<TR>");
        print ("<TD>".$fila['asignatura_nombre']."</TD>");
        print ("<TD>".$grado_asignatura['grado_nombre']."</TD></BR>");
        print ("</TR>");
        }
        print ("</TABLE>");
        print ("</BR>");
}
    //hipervinculo para ver las calificaciones
    print ("<A HREF='visualizar_calificaciones_profesor.php'>Ver calificaciones</A></BR>");
    print ("<FORM method='post' action='preguntas.php'>");
    print ("<H1>Gestión de Preguntas: </H1>");
    //hipervinculo para ver las preguntas creadas por ese profesor
    print ("<A HREF='visualizar_pregunta.php'>Visualizar preguntas</A></BR>");
    print ("asignatura: ");
    print ("<SELECT name='asignatura'>");

        $query=mysqli_query($server,$sql);
        $filas=mysqli_num_rows($query);
        if($filas>0)
        {
            for ($i=0; $i<$filas; $i++)
            {
                $fila=mysqli_fetch_array($query);
            //usamos un loop para obtener los datos
                print ("<OPTION name='asignatura'>". $fila['asignatura_nombre']."</OPTION>");
            }
        }
?>
    </SELECT></BR>
    Opcion:</BR>
    <INPUT name="opcion" type="radio" value="crear" checked/>Crear</BR>
    <INPUT name="opcion" type="radio" value="eliminar" />Eliminar</BR>
    <INPUT name="opcion" type="radio" value="modificar" />Modificar</BR>
<INPUT type="submit" value="Enviar">
<INPUT type="reset" value="Borrar"></br></br></br>
<H4>volver al login</H4>
<a href='login.php'><button type='button'>Volver
</FORM>
</body>
</html>