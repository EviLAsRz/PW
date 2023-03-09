<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido, Administrador del CASEM</h1>
    <br>
    <br>
    <h2>¿Que quieres hacer?</h2>
    <br>
    <form method="post" action="admin_accion.php">
    
        <input name="accion" type="radio" value="crear_estudiante" />Crear  estudiantes
        <br>
        <input name="accion" type="radio" value="crear_profesor" />Crear profesores
        <br>
        <input name="accion" type="radio" value="borrar_estudiante" />Borrar estudiantes
        <br>
        <input name="accion" type="radio" value="borrar_profesor" />Borrar profesores
        <br>
        <input name="accion" type="radio" value="modificar_estudiante" />Modificar estudiantes
        <br>
        <input name="accion" type="radio" value="modificar_profesor" />Modificar profesores
        <br>
        <input name="accion" type="radio" value="visualizar_estudiante" checked />Visualizar estudiantes
        <br>
        <input name="accion" type="radio" value="visualizar_profesor" />Visualizar profesores
        <br>
        <input type='submit' value='Enviar'>
    </form>
    <br>
    <a href='login.php'><button type='button'>VOLVER
    
</body>
</html>