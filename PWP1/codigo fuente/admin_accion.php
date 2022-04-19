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

        error_reporting(0); //Pa q no salga warning malvado
        
        
        if($_POST['accion']!=NULL)
        {
            $_SESSION['accion'] = $_POST['accion'];
            $accion = $_SESSION['accion'];
        }
        else{

            $accion = $_SESSION['accion'];

        }

        

        $servidor=mysqli_connect("127.0.0.1","casem_user","","casem")
        or die("ERROR: No se pudo establecer conexión con la base de datos.");

        //POSIBLES ACCIONES
        switch($accion)
        {   
//VISUALIZAR_ESTUDIANTES            
            case "visualizar_estudiante":
                
                $query_visualizar_estudiantes = 
                    mysqli_query($servidor,"SELECT estudiante_codigo, usuario_nombre, estudiante_grado
                        FROM  usuarios JOIN estudiantes ON usuario_codigo = estudiante_usuario
                        ORDER BY estudiante_codigo ASC ");

                $filas=mysqli_num_rows($query_visualizar_estudiantes);
                if($filas==0)
                {
                    echo"No hay estudiantes en el CASEM en estos momentos, lo sentimos";
                }
                //SE IMPRIMEN LOS ESTUDIANTES
                while($filas>0)
                {
                    $resultado= mysqli_fetch_array($query_visualizar_estudiantes);
                    echo $resultado['estudiante_codigo']." - ". $resultado['usuario_nombre']." estudiante de ". $resultado['estudiante_grado']."<br>";
                    $filas--;
                }
                //BOTON PARA VOLVER AL MENU ADMIN
                echo "<br><a href='admin.php'><button type='button'>VOLVER";
                
                break;

//VISUALIZAR PROFESORES            
            case "visualizar_profesor":
                
                $query_visualizar_profesores =
                    mysqli_query($servidor,"SELECT profesor_codigo, usuario_nombre
                        FROM usuarios JOIN profesores ON usuario_codigo = profesor_usuario
                        ORDER BY profesor_codigo ASC");


                $filas_profesores=mysqli_num_rows($query_visualizar_profesores);
                if($filas_profesores==0)
                {
                    echo"No hay profesores en el CASEM en estos momentos, lo sentimos";
                }

                //SE IMPRIMEN LOS PROFESORES
                while($filas_profesores>0)
                {   
                    $resultado_profesores= mysqli_fetch_array($query_visualizar_profesores);

                    //SE VE LAS ASIGNATURAS QUE IMPARTE CADA PROFESOR
                    $query_visualizar_profesores_impartidas =mysqli_query($servidor,
                        "SELECT a.asignatura_nombre, a.asignatura_coordinador, pa.profesor_codigo
                        FROM asignaturas a JOIN profesor_asignatura pa ON a.asignatura_codigo = pa.asignatura_codigo
                        WHERE pa.profesor_codigo = $resultado_profesores[profesor_codigo]");
                    $filas_impartidas=mysqli_num_rows($query_visualizar_profesores_impartidas);

                    //IMPRIME CODIGO Y NOMBRE
                    echo $resultado_profesores['profesor_codigo']." - ". $resultado_profesores['usuario_nombre'].". Imparte las asignaturas: ";
                    //IMPRIME LAS ASIGNATURAS QUE IMPARTE
                    while($filas_impartidas > 0){

                        $resultado_impartidas = mysqli_fetch_array($query_visualizar_profesores_impartidas);
                        echo $resultado_impartidas['asignatura_nombre'].", ";
                        //AHORA COMPROBAMOS SI COORDINA ESA ASIGNATURA Y SI ES ASÍ LA IMPRIMIMOS
                        if($resultado_impartidas['asignatura_coordinador'] == $resultado_impartidas['profesor_codigo'])
                        {
                            echo "y la coordina, ";
                        }
                        
                        $filas_impartidas--;
                    }

                    echo"<br>";
                    
                    $filas_profesores--;
                }
                //BOTON PARA VOLVER AL MENU ADMIN
                echo "<br><a href='admin.php'><button type='button'>VOLVER";
                break;
//CREAR ESTUDIANTE            
            case "crear_estudiante":

                echo"Introduce los datos del nuevo estudiante:<br><br>";

                ?> <!-- CIERRO PHP PARA EL FORMULARIO -->

                <form method="post" action="admin_segunda_accion.php">

                    DNI:<input type="text" name="dni" size=8 maxlength=9>
                    <br>
                    Contraseña:<input type="text" name="password" size=8 maxlength=20>
                    <br>
                    Nombre:<input type="text" name="nombre" size=8 maxlength=50>
                    <br>
                    Grados:<br>
                    <select name="grado">
                    <?php

                        $query_grados = mysqli_query($servidor,"SELECT grado_nombre FROM grados");
                        $filas_grados=mysqli_num_rows($query_grados);
                        if($filas_grados>0)
                        {
                            while($filas_grados>0)
                            {
                                $resultado_grados=mysqli_fetch_array($query_grados);
                                ?>
                                <option> <?php echo $resultado_grados['grado_nombre']; ?> </option>
                                <?php
                                $filas_grados--;
                            }
                        }
                        ?>
                    </select>    
                    <input type='submit' value='Enviar'>
                </form>
                <br><a href='admin.php'><button type='button'>VOLVER

                </form>

                <?php
                break;
            
//CREAR PROFESOR                        
                case "crear_profesor":

                    echo"Introduce los datos del nuevo profesor:<br><br>";

                    ?> <!-- CIERRO PHP PARA EL FORMULARIO -->

                    <form method="post" action="admin_segunda_accion.php">

                        DNI:<input type="text" name="dni" size=8 maxlength=9>
                        <br>
                        Contraseña:<input type="text" name="password" size=8 maxlength=20>
                        <br>
                        Nombre:<input type="text" name="nombre" size=8 maxlength=50>
                        <br>
                        Asignatura:<br>
                        <select name="asignatura">
                        <?php

                            $query_asignatura = mysqli_query($servidor,"SELECT asignatura_nombre FROM asignaturas");
                            $filas_asignatura=mysqli_num_rows($query_asignatura);
                            if($filas_asignatura>0)
                            {
                                while($filas_asignatura>0)
                                {
                                    $resultado_asignatura=mysqli_fetch_array($query_asignatura);
                                    ?>
                                    <option> <?php echo $resultado_asignatura['asignatura_nombre']; ?> </option>
                                    <?php
                                    $filas_asignatura--;
                                }
                            }
                            ?>
                        </select>
                            
                        <input type='submit' value='Enviar'>
                    </form>
                    <br><a href='admin.php'><button type='button'>VOLVER
                    

                    <?php
                    break;

//BORRAR ESTUDIANTE        
                case "borrar_estudiante":

                    echo"Escribe el código del estudiante a borrar, puedes ver los códigos en la opcion visualizar estudiantes<br><br>";
                    ?>
                    <form method="post" action="admin_segunda_accion.php">

                        Código:<input type="text" name="codigo" size=8 maxlength=3>
                        <input type='submit' value='Enviar'>

                    </form>

                    <br><a href='admin.php'><button type='button'>VOLVER
                    <?php

                    break;
                
//BORRAR PROFESOR                
                case "borrar_profesor":

                    echo"Escribe el código del profesor a borrar, puedes ver los códigos en la opcion visualizar profesores<br><br>";
                    ?>
                    <form method="post" action="admin_segunda_accion.php">

                        Código:<input type="text" name="codigo" size=8 maxlength=3>
                        <input type='submit' value='Enviar'>

                    </form>

                    <br><a href='admin.php'><button type='button'>VOLVER
                    <?php

                    break;
                
//MODIFICAR ESTUDIANTE                
                case "modificar_estudiante":

                    echo"Escribe el código del estudiante a modificar, puedes ver los códigos en la opcion visualizar estudiantes.<br><br>";
                    ?>
                    <form method="post" action="admin_segunda_accion.php">

                        Código:<input type="text" name="codigo" size=8 maxlength=3>
                        <br>
                        <h4>Elige que quieres modificar, el grado del estudiante o desmatricularlo de alguna asignatura</h4>
                        Grado:<input name="modificar" type="radio" value="grado" cheked />
                        <br>
                        Desmatricular:<input name="modificar" type="radio" value="asignatura" />
                        <br>
                        <br>
                        <input type='submit' value='Enviar'>

                    </form>

                    <br><a href='admin.php'><button type='button'>VOLVER
                    <?php


                    break;

//MODIFICAR PROFESOR
                case "modificar_profesor":

                    echo"Escribe el código del profesor a modificar, puedes ver los códigos en la opcion visualizar profesores.<br><br>";

                    ?>
                    <form method="post" action="admin_segunda_accion.php">

                        Código:<input type="text" name="codigo_profesor" size=8 maxlength=3>
                        <br>
                        <h4>Elige que quieres hacer con el profesor:</h4>
                        Que imparta una nueva asignatura:<input name="modificar" type="radio" value="impartir_mas" cheked />
                        <br>
                        Que deje de impartir una asignatura:<input name="modificar" type="radio" value="impartir_menos" />
                        <br>
                        Que coordine una asignatura:<input name="modificar" type="radio" value="coordinar_mas" />
                        <br>
                        Que deje de coordinar una asignatura:<input name="modificar" type="radio" value="coordinar_menos" />
                        <br>
                        <h4>Elige la asignatura que quieres emplear:</h4>

                        <?php //SELECCIONO LAS ASIGNATURAS QUE HAY, EN EL SIGUIENTE .PHP COMPRUEBO SI YA LAS IMPARTE
                        $query_comprobar_asignaturas = mysqli_query($servidor,
                        "SELECT asignatura_nombre FROM asignaturas");
                        $filas_asignaturas=mysqli_num_rows($query_comprobar_asignaturas);
                        ?>

                        Asignatura:<select name="codigo_asignatura">

                        <?php
                        while($filas_asignaturas>0)
                        {
                            $resultado_asignaturas=mysqli_fetch_array($query_comprobar_asignaturas);
                            ?>
                            <option> <?php echo $resultado_asignaturas['asignatura_nombre']; ?> </option>
                            <?php
                            $filas_asignaturas--;
                        }
                        ?>
                        </select>
                        <br>
                        <br>
                        <input type='submit' value='Enviar'>

                    </form>

                    <br><a href='admin.php'><button type='button'>VOLVER
                    <?php

                    break;
        }       
                

        mysqli_close($servidor);
    ?>
</body>
</html>