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
//CREAR ESTUDIANTE: Recibe los datos del nuevo estudiante, los comprueba y crea tuplas correspondientes
            case "crear_estudiante":

                $dni = $_POST['dni'];
                $password = $_POST['password'];
                $nombre = $_POST['nombre'];
                $grado = $_POST['grado'];
                //COMPROBACION DEL DNI YA EXISTE
                $query_comprobar_dni = mysqli_query($servidor,"SELECT usuario_nif FROM usuarios WHERE usuario_nif = '$dni'");
                $filas_dni=mysqli_num_rows($query_comprobar_dni);

                if($filas_dni>0)
                {
                    echo"Lo siento, los datos de inicio no son validos";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else{
                //COMPROBACION DE QUE LA CONTRASEÑA YA EXISTE
                $query_comprobar_password = mysqli_query($servidor,"SELECT * FROM usuarios WHERE usuario_password = '$password'");
                $filas_password=mysqli_num_rows($query_comprobar_password);

                if($filas_password>0)
                {
                    echo"<br>Lo siento, los datos de inicio no son validos";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else{
                //CREACION DE LA TUPLA USUARIO

                $nuevo_usuario= "INSERT INTO usuarios (usuario_nif,usuario_password,usuario_rol,usuario_nombre) VALUES ('$dni','$password','estudiante','$nombre')"; 
                $query_nuevo_usuario= mysqli_query($servidor,$nuevo_usuario);

                //CREACION DE LA TUPLA ESTUDIANTE
                //hay q ver cual es el codigo del ususario para ponerselo al nuevo estudiante
                $ver_codigo_usuario=mysqli_query($servidor, "SELECT usuario_codigo FROM usuarios WHERE usuario_nif = '$dni'");
                $comprobar_codigo_usuario = mysqli_fetch_array($ver_codigo_usuario);


                $nuevo_estudiante= "INSERT INTO estudiantes (estudiante_usuario,estudiante_grado) VALUES ('{$comprobar_codigo_usuario['usuario_codigo']}','$grado' )"; 
                $query_nuevo_estudiante= mysqli_query($servidor,$nuevo_estudiante);

                //Volver por si se quiere meter mas estudiantes
                echo "Muy bien, estudiante $nombre creado <br>";
                
                echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                }

                break;
//CREAR PROFESOR
            case "crear_profesor":

                $dni = $_POST['dni'];
                $password = $_POST['password'];
                $nombre = $_POST['nombre'];
                $asignatura = $_POST['asignatura'];
                //COMPROBACION DEL DNI YA EXISTE
                $query_comprobar_dni = mysqli_query($servidor,"SELECT usuario_nif FROM usuarios WHERE usuario_nif = '$dni'");
                $filas_dni=mysqli_num_rows($query_comprobar_dni);

                if($filas_dni>0)
                {
                    echo"Lo siento, los datos de inicio no son validos";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else{
                //COMPROBACION DE QUE LA CONTRASEÑA YA EXISTE
                $query_comprobar_password = mysqli_query($servidor,"SELECT * FROM usuarios WHERE usuario_password = '$password'");
                $filas_password=mysqli_num_rows($query_comprobar_password);

                if($filas_password>0)
                {
                    echo"<br>Lo siento, los datos de inicio no son validos";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else{
                //CREACION DE LA TUPLA USUARIO

                $nuevo_usuario= "INSERT INTO usuarios (usuario_nif,usuario_password,usuario_rol,usuario_nombre) VALUES ('$dni','$password','profesor','$nombre')"; 
                $query_nuevo_usuario= mysqli_query($servidor,$nuevo_usuario);

                //CREACION DE LA TUPLA PROFESOR
                //hay q ver cual es el codigo del ususario para ponerselo al nuevo profesor
                $ver_codigo_usuario=mysqli_query($servidor, "SELECT usuario_codigo FROM usuarios WHERE usuario_nif = '$dni'");
                $comprobar_codigo_usuario = mysqli_fetch_array($ver_codigo_usuario);


                $nuevo_profesor= "INSERT INTO profesores (profesor_usuario) VALUES ('{$comprobar_codigo_usuario['usuario_codigo']}')"; 
                $query_nuevo_profesor= mysqli_query($servidor,$nuevo_profesor);

                //CREACION TUPLA PROFESOR_ASIGNATURA
                //Busqueda del codigo del profesor recien creado
                $ver_codigo_profesor=mysqli_query($servidor, "SELECT profesor_codigo FROM profesores WHERE profesor_usuario = '{$comprobar_codigo_usuario['usuario_codigo']}'");
                $comprobar_codigo_profesor = mysqli_fetch_array($ver_codigo_profesor);
                //Busqueda del codigo de la asignatura seleccionada mediante su nombre
                $ver_codigo_asignatura=mysqli_query($servidor, "SELECT asignatura_codigo FROM asignaturas WHERE asignatura_nombre = '$asignatura'");
                $comprobar_codigo_asignatura = mysqli_fetch_array($ver_codigo_asignatura);
                //Creamos la tupla
                
                $nuevo_profesor_asignatura= "INSERT INTO profesor_asignatura (profesor_codigo, asignatura_codigo) 
                    VALUES ('{$comprobar_codigo_profesor['profesor_codigo']}', '{$comprobar_codigo_asignatura['asignatura_codigo']}')"; 
                $query_nuevo_profesor_asignatura= mysqli_query($servidor,$nuevo_profesor_asignatura);

                //Volver por si se quiere meter mas profesores
                echo "Muy bien, profesor $nombre creado <br>";
                
                echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                }   
                break;

//BORRAR ESTUDIANTE           
            case "borrar_estudiante":

                $codigo = $_POST['codigo'];

                //COMPROBACION DE QUE EXISTA EL CODIGO

                $query_comprobar_codigo_estudiante = mysqli_query($servidor,"SELECT estudiante_codigo FROM estudiantes WHERE estudiante_codigo = '$codigo'");
                $filas_codigo_estudiante=mysqli_num_rows($query_comprobar_codigo_estudiante);

                if($filas_codigo_estudiante==0)
                {
                    echo"Lo siento, el codigo no es valido";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else
                {   
                    //BORRAR TUPLAS ESTUDIANTE_PREGUNTA
                    $borrar_estudiante_pregunta= "DELETE FROM estudiante_pregunta WHERE estudiante_codigo = '$codigo'"; 
                    $query_borrar_estudiante_pregunta= mysqli_query($servidor,$borrar_estudiante_pregunta);

                    //BORRAR TUPLAS ASIGNATURA_ESTUDIANTE

                    $borrar_asignatura_estudiante= "DELETE FROM asignatura_estudiante WHERE estudiante_id = '$codigo'"; 
                    $query_borrar_asignatura_estudiante= mysqli_query($servidor,$borrar_asignatura_estudiante);

                    //BORRADO DEL ESTUDIANTE
                    //Hace falta guardar la clave foranea a usuarios para poder borrarlo luego del estudiante

                    $ver_codigo_usuario=mysqli_query($servidor, "SELECT estudiante_usuario FROM estudiantes WHERE estudiante_codigo = '$codigo'");
                    $comprobar_codigo_usuario = mysqli_fetch_array($ver_codigo_usuario);

                    //Ahora ya se puede borrar

                    $borrar_estudiante= "DELETE FROM estudiantes WHERE estudiante_codigo = '$codigo'"; 
                    $query_borrar_estudiante= mysqli_query($servidor,$borrar_estudiante);

                    //Y finalmente borro al usuario

                    $borrar_usuario= "DELETE FROM usuarios WHERE usuario_codigo = '{$comprobar_codigo_usuario['estudiante_usuario']}'"; 
                    $query_borrar_usuario= mysqli_query($servidor,$borrar_usuario);

                    echo"Muy bien, estudiante borrado<br>";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }

                break;
//BORRAR PROFESOR
            case "borrar_profesor":

                $codigo = $_POST['codigo'];

                //COMPROBACION DE QUE EXISTA EL CODIGO

                $query_comprobar_codigo_profesor = mysqli_query($servidor,"SELECT profesor_codigo FROM profesores WHERE profesor_codigo = '$codigo'");
                $filas_codigo_profesor=mysqli_num_rows($query_comprobar_codigo_profesor);

                if($filas_codigo_profesor==0)
                {
                    echo"Lo siento, el codigo no es valido";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else
                {
                    //BORRAR TUPLAS PROFESOR_PREGUNTA
                    $borrar_profesor_pregunta= "DELETE FROM profesor_pregunta WHERE profesor_codigo = '$codigo'"; 
                    $query_borrar_profesor_pregunta= mysqli_query($servidor,$borrar_profesor_pregunta);


                    //BORRAR TUPLAS PROFESOR_ASIGNATURA
                    $borrar_profesor_asignatura= "DELETE FROM profesor_asignatura WHERE profesor_codigo = '$codigo'"; 
                    $query_borrar_profesor_asignatura= mysqli_query($servidor,$borrar_profesor_asignatura);

                    //CAMBIAR EL CAMPO DE COORDINADOR DE LAS ASIGNATURAS QUE IMPARTA A 0
                    $borrar_coordinador= "UPDATE asignaturas SET asignatura_coordinador = NULL WHERE asignatura_coordinador= '$codigo'"; 
                    $query_borrar_coordinador= mysqli_query($servidor,$borrar_coordinador);


                    //BORRADO DEL PROFESOR
                    //Hace falta guardar la clave foranea a usuarios para poder borrarlo luego del profesor

                    $ver_codigo_usuario=mysqli_query($servidor, "SELECT profesor_usuario FROM profesores WHERE profesor_codigo = '$codigo'");
                    $comprobar_codigo_usuario = mysqli_fetch_array($ver_codigo_usuario);

                    //Se borra el profesor
                    $borrar_profesor= "DELETE FROM profesores WHERE profesor_codigo = '$codigo'"; 
                    $query_borrar_profesor= mysqli_query($servidor,$borrar_profesor);

                    //Y finalmente borro al usuario

                    $borrar_usuario= "DELETE FROM usuarios WHERE usuario_codigo = '{$comprobar_codigo_usuario['profesor_usuario']}'"; 
                    $query_borrar_usuario= mysqli_query($servidor,$borrar_usuario);

                    echo"Muy bien, profesor borrado<br>";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }

                break;

//MODIFICAR ESTUDIANTE
            case "modificar_estudiante":

                $modificar = $_POST['modificar'];
                $codigo = $_POST['codigo'];
                $_SESSION['modificar_estudiante'] = $modificar; //Nos va a hacer falta para admin_tercera_accion.php para diferenciar
                $_SESSION['codigo_estudiante'] = $codigo;

                //COMPROBACION DE QUE EXISTA EL CODIGO

                $query_comprobar_codigo_estudiante = mysqli_query($servidor,"SELECT estudiante_codigo FROM estudiantes WHERE estudiante_codigo = '$codigo'");
                $filas_codigo_estudiante=mysqli_num_rows($query_comprobar_codigo_estudiante);

                if($filas_codigo_estudiante==0)
                {
                    echo"Lo siento, el codigo no es valido";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }
                else{

                    if($modificar == "grado"){

                        echo"Elige el nuevo grado del estudiante:<br>";
                        //Necesito el nombre del grado del estudiante para hacer la consulta que lo descarte de los que van a salir para elegir
                        $query_comprobar_nombre_grado = mysqli_query($servidor,
                        "SELECT estudiante_grado FROM estudiantes WHERE estudiante_codigo = '$codigo'");
                        $resultado_nombre_grado=mysqli_fetch_array($query_comprobar_nombre_grado);
                        //Guardamos el nobre de los grados, menos la de la que estaba matriculado
                        $query_cambiar_grado = mysqli_query($servidor,
                            "SELECT grado_nombre FROM grados WHERE  grado_nombre NOT LIKE '{$resultado_nombre_grado['estudiante_grado']}'");
                        //Ahora mostramos la lista desplegable con los grados
                        ?>
                        <form method="post" action="admin_tercera_accion.php">
                            <select name="grado_modificar_estudiante">
                            <?php //Como solo hay 2 grados, xq 1 no lo mostramos(el q estaba matriculado), entonces no hago bucle

                            for($i=0; $i<2 ; $i++)
                            {
                            $resultado_cambiar_grado=mysqli_fetch_array($query_cambiar_grado);
                            ?>
                            <option> <?php echo $resultado_cambiar_grado['grado_nombre']; ?> </option>
                            <?php
                            } ?>
                            </select>
                            <input type='submit' value='Enviar'>

                        </form>
                        <?php
                    }   
                    else //HA ELEGIDO DESMATRICULAR ASIGNATURA
                    {
                        //COMPROBAMOS SI ESTA MATRICULADO DE ALGUNA ASIGNATURA
                        $query_comprobar_asignaturas_matriculadas = mysqli_query($servidor,
                        "SELECT asignatura_id FROM asignatura_estudiante WHERE estudiante_id = '$codigo'");
                        $filas_asignaturas_matriculadas=mysqli_num_rows($query_comprobar_asignaturas_matriculadas);
                        if($filas_asignaturas_matriculadas==0)
                        {
                            echo"El estudiante no esta matriculado en ninguna asignatura<br>";
                            echo"<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }
                        //SI ESTA MATRICULADO EN ALGUNA ASIGNATURA
                        else
                        {   
                            echo"Estas son las asignaturas en las que está matriculado el estudiante, elige la que quieras que se desmatricule:<br>";
                            echo"<br>";
                            //GUARDO LAS ASIGNATURAS EN LAS QUE ESTA MATRICULADO (igual que la anterior)
                            $query_asignaturas_matriculadas = mysqli_query($servidor,
                            "SELECT asignatura_id FROM asignatura_estudiante WHERE estudiante_id = '$codigo'");
                            $filas_asignaturas_matriculadas=mysqli_num_rows($query_asignaturas_matriculadas);
                            //MOSTRAMOS LISTA DESPLEGABLE
                            ?>
                            <form method="post" action="admin_tercera_accion.php">
                            <select name="asignatura_modificar_estudiante">
                            <?php 
                            while($filas_asignaturas_matriculadas>0)
                            {
                                $resultado_desmatricularse=mysqli_fetch_array($query_asignaturas_matriculadas);
                                //Voy a mostrar el nombre de la asignatura, accedo a ellas por el codigo
                                $query_asignaturas_nombre = mysqli_query($servidor,
                                "SELECT asignatura_nombre FROM asignaturas WHERE asignatura_codigo = '{$resultado_desmatricularse['asignatura_id']}'");
                                $resultado_asignaturas_nombre = mysqli_fetch_array($query_asignaturas_nombre);
                                ?>
                                <option> <?php echo $resultado_asignaturas_nombre['asignatura_nombre']; ?> </option>
                                <?php
                                $filas_asignaturas_matriculadas--;
                            }
                            ?>
                            <input type='submit' value='Enviar'>

                        </form>
                        <?php
                        }
                    }
                }
                break;

//MODIFICAR_PROFESOR            
            case "modificar_profesor":

                $modificar = $_POST['modificar'];
                $codigo_profesor = $_POST['codigo_profesor'];
                $codigo_asignatura = $_POST['codigo_asignatura'];

                //COMPROBACION DE QUE EXISTA EL CODIGO_PROFESOR

                $query_comprobar_codigo_profesor = mysqli_query($servidor,
                "SELECT profesor_codigo FROM profesores WHERE profesor_codigo = '$codigo_profesor'");
                $filas_codigo_profesor=mysqli_num_rows($query_comprobar_codigo_profesor);

                if($filas_codigo_profesor==0)
                {
                    echo"Lo siento, el codigo del profesor no es valido";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }

                //SACA EL CODIGO DE LA ASIGNATURA, YA QUE RECIBIMOS EL NOMBRE

                $query_comprobar_codigo_asignatura = mysqli_query($servidor,
                "SELECT asignatura_codigo FROM asignaturas WHERE asignatura_nombre = '$codigo_asignatura'");
                $filas_codigo_asignatura=mysqli_num_rows($query_comprobar_codigo_asignatura);
                $resultado_nombre_asignatura=mysqli_fetch_array($query_comprobar_codigo_asignatura);

                if($filas_codigo_asignatura==0)
                {
                    echo"Lo siento, el codigo de la asignatura no es valido";
                    echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                }

                switch($modificar)
                {
                //IMPARTIR MAS ASIGNATURAS    
                    case "impartir_mas":

                        //Comprobar si la asignatura seleccionada ya la imparte
                        $query_comprobar_asignatura_repetida = mysqli_query($servidor,
                        "SELECT asignatura_codigo FROM profesor_asignatura 
                        WHERE asignatura_codigo = '{$resultado_nombre_asignatura['asignatura_codigo']}'
                        AND profesor_codigo='$codigo_profesor'");
                        $filas_asignatura_repetida=mysqli_num_rows($query_comprobar_asignatura_repetida);
                        if($filas_asignatura_repetida>0)
                        {
                            echo"Vaya, esa asignatura ya la imparte, lo lamento.<br>";
                            echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";

                        }
                        else
                        {
                            //CREA TUPLA PROFESOR_ASIGNATURA
                            $nuevo_profesor_asignatura= "INSERT INTO profesor_asignatura (profesor_codigo, asignatura_codigo) 
                            VALUES ('$codigo_profesor', '{$resultado_nombre_asignatura['asignatura_codigo']}')"; 
                            $query_nuevo_profesor_asignatura= mysqli_query($servidor,$nuevo_profesor_asignatura);

                            echo"Perfecto! El profesor ahora imparte la asignatura $codigo_asignatura.<br>";
                            echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }
                        break;

                //IMPARTIR MENOS ASIGNATURAS    
                    case "impartir_menos":

                        //Comprobar si ya imparte la asignatura recibida

                        $query_comprobar_asignatura = mysqli_query($servidor,
                        "SELECT profesor_codigo FROM profesor_asignatura 
                        WHERE profesor_codigo = '$codigo_profesor' AND asignatura_codigo = '{$resultado_nombre_asignatura['asignatura_codigo']}'");
                        $filas_comprobar_asignatura=mysqli_num_rows($query_comprobar_asignatura);
                        if($filas_comprobar_asignatura==0)
                        {
                            echo"Vaya, esa asignatura no la imparte, lo lamento.<br>";
                            echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }
                        else
                        {   
                            //BORRAR TUPLAS PROFESOR_ASIGNATURA
                            $borrar_profesor_asignatura= 
                            "DELETE FROM profesor_asignatura 
                            WHERE profesor_codigo = '$codigo_profesor'
                            AND asignatura_codigo ='{$resultado_nombre_asignatura['asignatura_codigo']}'"; 
                            $query_borrar_profesor_asignatura= mysqli_query($servidor,$borrar_profesor_asignatura);

                            echo"Perfecto! El profesor ahora no imparte la asignatura $codigo_asignatura.<br>";
                            echo "<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }

                        break;

                //COORDINAR MAS ASIGNATURAS    
                    case "coordinar_mas":

                        //Comprobamos si esa asignatura ya la coordina el
                        $query_comprobar_coordinacion = mysqli_query($servidor,
                        "SELECT asignatura_coordinador FROM asignaturas
                        WHERE asignatura_codigo = '{$resultado_nombre_asignatura['asignatura_codigo']}'");
                        $resultado_comprobar_coordinacion= mysqli_fetch_array($query_comprobar_coordinacion);

                        if($resultado_comprobar_coordinacion['asignatura_coordinador'] == $codigo_profesor)
                        {
                            echo"Vaya, esa asignatura ya la coordina, lo lamento.<br>";
                            echo"<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }
                        //Si no la imparte
                        else
                        {
                            //CAMBIAR EL CAMPO DE COORDINADOR DE LAS ASIGNATURAS QUE IMPARTA A SU CODIGO
                            $modificar_coordinador= "UPDATE asignaturas SET asignatura_coordinador = $codigo_profesor
                            WHERE asignatura_codigo= '{$resultado_nombre_asignatura['asignatura_codigo']}'"; 
                            $query_modificar_coordinador= mysqli_query($servidor,$modificar_coordinador);

                            echo"Listo, el profesor $codigo_profesor ahora coordina $codigo_asignatura<br>";
                            echo"<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }

                        break;

                //COORDINAR MENOS ASIGNATURAS        
                    case "coordinar_menos":

                        //Comprobamos si esa no la coordina el
                        $query_comprobar_coordinacion = mysqli_query($servidor,
                        "SELECT asignatura_coordinador FROM asignaturas
                        WHERE asignatura_codigo = '{$resultado_nombre_asignatura['asignatura_codigo']}'");
                        $resultado_comprobar_coordinacion= mysqli_fetch_array($query_comprobar_coordinacion);

                        if($resultado_comprobar_coordinacion['asignatura_coordinador'] != $codigo_profesor)
                        {
                            echo"Vaya, esa asignatura no la coordina, lo lamento.<br>";
                            echo"<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }
                        //Si la imparte
                        else
                        {
                            //CAMBIAR EL CAMPO DE COORDINADOR DE LAS ASIGNATURAS QUE IMPARTA A NULL
                            $modificar_coordinador= "UPDATE asignaturas SET asignatura_coordinador = NULL
                            WHERE asignatura_codigo= '{$resultado_nombre_asignatura['asignatura_codigo']}'"; 
                            $query_modificar_coordinador= mysqli_query($servidor,$modificar_coordinador);

                            echo"Listo, el profesor $codigo_profesor ahora no coordina $codigo_asignatura<br>";
                            echo"<br><a href='admin_accion.php'><button type='button'>VOLVER";
                        }

                        break;
                }

                break;
        }
        mysqli_close($servidor);
    ?>
</body>
</html>