<?php
//establecemos conexion a la BD
$conexion= new mysqli("localhost","root","","agenda");

if ($conexion->connect_error){
die("error de conexion: ".$conexion->connect_error);
}
$consulta = $conexion->query("select * from contactos");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <title>Registro</title>
</head>
<body>



<div class="container col-md-6 offset-3">
    <br><br><br>
    <h1><center>Registro</center></h1>
    <br><br>

<form action="registro.php" method="post">

<div class="mb-3 row">
    <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="inputNombre" name="inputNombre">
    </div>
</div>
<div class="mb-3 row">
    <label for="inputTelefonoFijo" class="col-sm-2 col-form-label">Teléfono Fijo</label>
    <div class="col-sm-10">
        <input type="tel" class="form-control" id="inputTelefonoFijo" name="inputTelefonoFijo">
    </div>
</div>
<div class="mb-3 row">
    <label for="inputTelefonoCel" class="col-sm-2 col-form-label">Teléfono Celular</label>
    <div class="col-sm-10">
        <input type="tel" class="form-control" id="inputTelefonoCel" name="inputTelefonoCel">
    </div>
</div>
<div class="mb-3 row">
    <label for="inputCorreo" class="col-sm-2 col-form-label">Correo Electrónico</label>
    <div class="col-sm-10">
        <input type="email" class="form-control" id="inputCorreo" name="inputCorreo">
    </div>
</div>



<div class="mb-4 row">
    <div class="col-sm-10 offset-sm-2">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>




</form>


</div>


    <div class="col-sm-14 offset-sm-4">
        <form action="agentaPagina.html" >
            <button type="submit" class="btn btn-primary">Regresar
            </button>
        </form>
    </div>


</body>
</html>






