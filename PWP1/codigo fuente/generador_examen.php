<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>generador de examenes</title>
</head>
<body>
<?php
session_start();
$user = $_SESSION['user_session'];
$id = $_SESSION['user_id'];
$tema = $_POST['examen'];
$estudiante_codigo = $_SESSION['estudiante_codigo'];
$_SESSION['tema_examen'] = $tema;

$server=mysqli_connect("127.0.0.1","casem_user","","casem") or die(mysqli_connect_error());

$sql = "SELECT * FROM preguntas WHERE pregunta_tema = '$tema'";
$query = mysqli_query($server,$sql);
$filas = mysqli_num_rows($query);

//dos arrays, uno para guardar todas las preguntas y "randomizarlas" y otro para almacenar el numero de preguntas deseado
$examenes = array();
$examenes_random = array();
if ($filas > 0)
{
    for($i=0;$i<$filas;$i++)
    {
        $res = mysqli_fetch_array($query);
        $examenes[] = $res['pregunta_codigo'];
    }
}
//shuffle de examenes
shuffle($examenes);

//copiamos 10 preguntas en otro vector, el cual le pasaremos a la consulta para buscar esas preguntas
for($i = 0; $i < count($examenes) && $i < 10; $i++)
{
    $examenes_random[$i] = $examenes[$i];
}
$examenes_random = join(', ', $examenes_random);
//lo guardamos en una variable de session para facil acceso
$_SESSION['array_preguntas'] = $examenes_random;
//consulta para buscar las preguntas con esos codigos
$sql = "SELECT * FROM preguntas WHERE pregunta_codigo IN ($examenes_random)";
$query = mysqli_query($server,$sql);
$filas = mysqli_num_rows($query);
//generacion del examen y correcion de este en correcion_examen
print("<FORM method='post' action='correccion_examen.php'>");
$indice = array();
if ($filas > 0)
        {
        for ($i = 0; $i < $filas; $i++)
        {
            $indice[] = $i+1;
            $resultado = mysqli_fetch_array($query);
            $sql = "SELECT * FROM respuestas WHERE respuesta_codigo IN(
                    SELECT respuesta_codigo FROM pregunta_respuesta WHERE pregunta_codigo = '{$resultado['pregunta_codigo']}')";
            $respuestas_query = mysqli_query($server,$sql);
            $filas_respuestas = mysqli_num_rows($respuestas_query);
            print ("<H2>$indice[$i]. {$resultado['pregunta_texto']}</H2>");
            for($j=0;$j<$filas_respuestas;$j++)
            {
                $respuestas = mysqli_fetch_array($respuestas_query);
                
                print("<INPUT name='$indice[$i]' type='radio' value='{$respuestas['respuesta_codigo']}'/>{$respuestas['respuesta_texto']}");
                print ("<BR>\n");  
            }
            print ("<BR>\n");
        }
    print ("<BR>\n");
    print("<INPUT type='submit' value='Terminar'>");
    print("</FORM>");
    }
?>
</body>
</html>