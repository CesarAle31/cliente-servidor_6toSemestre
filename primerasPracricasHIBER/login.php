<?php
//establecemos conexion a la BD
$conexion= new mysqli("localhost","root","","tienda");

if ($conexion->connect_error){
die("error de conexion: ".$conexion->connect_error);
}
$consulta = $conexion->query("select * from usuarios");
?>





<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>




<div class="container col-md-6 offset-3">
    <br><br><br>
    <h1><center>Login de Acceso</center></h1>
    <br><br>
    <form action="validar.php" method="post">
        <div class="form-floating mb-3">
            <input type="text" name="txtUsuario" class="form-control"
                   id="floatingInput" placeholder="Usuario">
            <label for="floatingInput">usuario</label>
        </div>
        <div class="form-floating">
            <input type="password" name="txtPassword" class="form-control"
                   id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
    <br>
        <button type="submit" class="btn btn-primary">Login
        </button>
    </form>

</div>





</body>
</html>