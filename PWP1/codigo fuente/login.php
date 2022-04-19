<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: url('images/background.png');
        }
        .bg_img {
            background: linear-gradient(rgba(255,255,255,.6), rgba(255,255,255,.6)),url("images/fondo.jpg");
            min-height: 380px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            border-radius: 6px;
        }
        input[type=text], input[type=password] {
            width: 14%;
            padding: 6px 14px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 12px;
        }
        label[for="uname"]{
            font-size: 15px;
            border-radius: 12px;
            background-color: #575353;
            color: white;
            padding: 6px 32px;
            margin: 8px 0;
            border: none;
        }
        label[for="pswd"]{
            font-size: 15px;
            border-radius: 12px;
            background-color: #575353;
            color: white;
            padding: 6px 14px;
            margin: 8px 0;
            border: none;
        }
        h1 {
            font-size: 40px;
            border-radius: 12px;
            color: white;
            padding: 6px 150px;
            margin: 8px 0;
            border: none;
        }
        button {
            background-color: #575353;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 5%;
            border-radius: 12px;
        }
        .accept {
            font-size: 14px;
            width: auto;
            padding: 7px 2px;
        }
        button:hover {
            opacity: 0.8;
        }
        .imgcontainer{
            text-align: center;
            margin: 24px 0 12px 0;
        }
        img.logo_uca {
            width: 58%;
            height: 180px;
        }
        .container {
            padding: 16px;
        }

        span.psw {
            font-size: 12px;
            padding-top: 16px;
        }
    </style>
<body>
    <h1>Universidad de Cádiz</h1>
<form action="login_db.php" method="post">
    <div class="bg_img">
        <h2> Identificación de Usuario </h2>
        <div class="container">
            <label for="uname"><b>USUARIO</b></label>
            <input type="text" name="uname" required>
            </br>
            <label for="pswd"><b>CONTRASEÑA</b></label>
            <input type="password" name="psw" required>
            </br>
            <button type="submit" class="accept">ACEPTAR</button>
        </div>  
</form>
</div>
</body>
</html>