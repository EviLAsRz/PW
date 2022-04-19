<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        session_start();
        
        $accion = $_SESSION['accion'];

        $servidor=mysqli_connect("127.0.0.1","casem_user","","casem")
        or die("ERROR: No se pudo establecer conexión con la base de datos.");

        switch($accion)
        {
            case "modificar_estudiante":

                $modificar = $_SESSION['modificar_estudiante']; //Eleccion si ha elegido cambiar grado o desmatricular asignatura
                $codigo = $_SESSION['codigo_estudiante']; //Codigo del estudiante
                
                if($modificar == "grado")
                {
                    $grado = $_POST['grado_modificar_estudiante'];
                    //Procedo a borrar los datos necesarios
                    //PRIMERO BORRAR TUPLAS DE ESTUDIANTE_PREGUNTA DONDE APAREZCA EL
                    $borrar_estudiante_pregunta= "DELETE FROM estudiante_pregunta WHERE estudiante_codigo = '$codigo'"; 
                    $query_borrar_estudiante_pregunta= mysqli_query($servidor,$borrar_estudiante_pregunta);
                    //BORRAR SU MATRICULACION EN LAS ASIGNATURAS
                    $borrar_asignatura_estudiante= "DELETE FROM asignatura_estudiante WHERE estudiante_id = '$codigo'"; 
                    $query_borrar_asignatura_estudiante= mysqli_query($servidor,$borrar_asignatura_estudiante);
                    //CAMBIAR EL GRADO DEL ESTUDIANTE
                    $modificar_grado= "UPDATE estudiantes SET estudiante_grado = '$grado' WHERE estudiante_codigo= '$codigo'"; 
                    $query_modificar_grado= mysqli_query($servidor,$modificar_grado);

                    echo"El alumno ha cambiado su grado a $grado.<br>";

                }
                if($modificar == "asignatura")
                {
                    $asignatura = $_POST['asignatura_modificar_estudiante'];

                    //BORRAR SU MATRICULACION EN LA ASIGNATURA SELECCIONADA
                    //Busco el id, ya q tengo el nombre de la aignatura
                    $query_asignaturas_codigo = mysqli_query($servidor,
                                "SELECT asignatura_codigo FROM asignaturas WHERE asignatura_nombre = '$asignatura'");
                    $resultado_asignaturas_codigo = mysqli_fetch_array($query_asignaturas_codigo);

                    $borrar_asignatura_estudiante= 
                    "DELETE FROM asignatura_estudiante 
                    WHERE estudiante_id = '$codigo' AND asignatura_id ='{$resultado_asignaturas_codigo['asignatura_codigo']}' "; 
                    $query_borrar_asignatura_estudiante= mysqli_query($servidor,$borrar_asignatura_estudiante);

                    echo"La asignatura $asignatura ha sido desmatriculada del expediente del estudiente<br>";

                }
                //Boton de volver para ambos casos
                ?><br><a href='admin_accion.php'><button type='button'>VOLVER<?php //Vuelve al menu de modificar estudiante

        }
        mysqli_close($servidor);
    ?>
</body>
</html>