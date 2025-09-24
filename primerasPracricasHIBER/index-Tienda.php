<!DOCTYPE html>


<?php
//establecemos conexion a la BD
$conexion= new mysqli("localhost","root","","tienda");

if ($conexion->connect_error){
    die("error de conexion: ".$conexion->connect_error);
}
$consulta = $conexion->query("select * from productos");
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <title>Tienda</title>
</head>
<body>

<br>
<div class="card">
    <div class="card col-md-8 offset-1">
        <div class="card-body">

            <h4> formulario de registro de productos
                <br><br>
                <form action="guardar_producto.php" method="post">

                    <div class="mb-3">
                        <label>Nombre del producto</label>
                        <INPUT TYPE="TEXT" NAME="TXTnombre" class="form-control" placeholder="nombre del producto" required>
                     </div>

                    <div class="mb-3">
                        <label>Descripcion</label>
                       <textarea name="txtDescripcion" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>
                            Precio del producto
                        </label>
                        <INPUT TYPE="text" NAME="TXTprecio" class="form-control" placeholder="precio" required>
                    </div>


                    <div class="mb-3">
                        <button type="submit" class="btn-primary">GUARDAR</button>
                    </div>

                </form>





        </div>






    </div>
<!-- table-->
    <div class="card col-md-8 offset-1">
        <div class="card-body">

            <h4> tabla de productos
                <br><br>
                <table class="table table-hover">
                   <thead>
                   <tr>
                   <th scope="col">ID</th>
                   <th scope="col">Nombre</th>
                   <th scope="col">Descripcion</th>
                   <th scope="col">Precio</th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   while ($fila = $consulta->fetch_assoc()):
                   ?>
                   <tr>
                   <th scope="row"><?=$fila['id']?></th>
                       <td ><?=$fila['nombre']?></td>
                       <td ><?=$fila['descripcion']?></td>
                       <td ><?=$fila['precio']?></td>
                   </tr>
                   <?php endwhile;?>

                   </tbody>

                </table>
                </div>
                </div>
</body>
</html>