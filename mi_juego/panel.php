<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mi_juego");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$result = $conn->query("SELECT * FROM usuarios WHERE usuario='$usuario'");
$user = $result->fetch_assoc();

$now = time();

if ($user['last_update'] == 0) {
    $user['last_update'] = $now;
}

$tiempo = $now - $user['last_update'];

$metal_seg = $user['mina_oro'] * 1;
$cristal_seg = $user['mina_cristal'] * 0.5;

$nuevo_oro = $user['oro'] + ($tiempo * $metal_seg);
$nuevo_cristal = $user['cristal'] + ($tiempo * $cristal_seg);

$conn->query("UPDATE usuarios SET 
oro='$nuevo_oro',
cristal='$nuevo_cristal',
last_update='$now'
WHERE usuario='$usuario'");

$user['oro'] = $nuevo_oro;
$user['cristal'] = $nuevo_cristal;

// MEJORAS
if (isset($_GET['mejorar_oro'])) {

    $costo = $user['mina_oro'] * 100;

    if ($user['oro'] >= $costo) {
        $conn->query("UPDATE usuarios SET 
        oro=oro-$costo,
        mina_oro=mina_oro+1
        WHERE usuario='$usuario'");
    }

    header("Location: panel.php");
}

if (isset($_GET['mejorar_cristal'])) {

    $costo = $user['mina_cristal'] * 100;

    if ($user['oro'] >= $costo) {
        $conn->query("UPDATE usuarios SET 
        oro=oro-$costo,
        mina_cristal=mina_cristal+1
        WHERE usuario='$usuario'");
    }

    header("Location: panel.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Juego Espacial</title>

<style>

body{
    margin:0;
    background:#020617;
    font-family:Arial;
    color:white;
}

/* TOP */

.topbar{
    background:#0f172a;
    padding:15px;
    display:flex;
    justify-content:space-around;
    position:sticky;
    top:0;
    z-index:999;
    border-bottom:2px solid #1e293b;
}

.recurso{
    font-size:18px;
    font-weight:bold;
}

/* GRID */

.container{
    padding:20px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

/* CARD */

.card{
    background:#0f172a;
    border:2px solid #1e293b;
    border-radius:12px;
    overflow:hidden;
    transition:0.3s;
    text-align:center;
}

.card:hover{
    transform:scale(1.03);
    border-color:#38bdf8;
}

/* IMG */

.card img{
    width:100%;
    height:220px;
    object-fit:cover;
    animation:float 3s infinite;
}

@keyframes float{
    0%,100%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-8px);
    }
}

/* CONTENT */

.content{
    padding:15px;
}

h2{
    margin:10px 0;
    color:#38bdf8;
}

.btn{
    display:inline-block;
    margin-top:10px;
    background:#22c55e;
    color:white;
    padding:10px 15px;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
}

.btn:hover{
    background:#16a34a;
}

/* MOBILE */

@media(max-width:600px){

    .topbar{
        flex-direction:column;
        gap:10px;
        text-align:center;
    }

}

</style>

</head>

<body>

<div class="topbar">

<div class="recurso">⛓️ Metal: <?php echo floor($user['oro']); ?></div>

<div class="recurso">💎 Cristal: <?php echo floor($user['cristal']); ?></div>

</div>

<div class="container">

<div class="grid">

<!-- MINA METAL -->

<div class="card">

<img src="img/mina_metal.png">

<div class="content">

<h2>Mina de Metal</h2>

<p>Nivel <?php echo $user['mina_oro']; ?></p>

<p>Producción: <?php echo $user['mina_oro']; ?>/s</p>

<p>Costo: <?php echo $user['mina_oro'] * 100; ?></p>

<a class="btn" href="?mejorar_oro=1">Mejorar</a>

</div>

</div>

<!-- MINA CRISTAL -->

<div class="card">

<img src="img/mina_cristal.png">

<div class="content">

<h2>Mina de Cristal</h2>

<p>Nivel <?php echo $user['mina_cristal']; ?></p>

<p>Producción: <?php echo $user['mina_cristal'] * 0.5; ?>/s</p>

<p>Costo: <?php echo $user['mina_cristal'] * 100; ?></p>

<a class="btn" href="?mejorar_cristal=1">Mejorar</a>

</div>

</div>

<!-- DEUTERIO -->

<div class="card">
<img src="img/mina_deuterio.png">
<div class="content">
<h2>Mina de Deuterio</h2>
<p>Próximamente</p>
</div>
</div>

<!-- ENERGIA -->

<div class="card">
<img src="img/planta_energia.png">
<div class="content">
<h2>Planta de Energía</h2>
<p>Próximamente</p>
</div>
</div>

<!-- HANGAR -->

<div class="card">
<img src="img/hangar.png">
<div class="content">
<h2>Hangar</h2>
<p>Próximamente</p>
</div>
</div>

<!-- BASE -->

<div class="card">
<img src="img/base_cientifica.png">
<div class="content">
<h2>Base Científica</h2>
<p>Próximamente</p>
</div>
</div>

<!-- ALMACENES -->

<div class="card">
<img src="img/almacen_metal.png">
<div class="content">
<h2>Almacén Metal</h2>
</div>
</div>

<div class="card">
<img src="img/almacen_cristal.png">
<div class="content">
<h2>Almacén Cristal</h2>
</div>
</div>

<div class="card">
<img src="img/almacen_deuterio.png">
<div class="content">
<h2>Almacén Deuterio</h2>
</div>
</div>

</div>

<br>

<center>
<a class="btn" href="logout.php">Cerrar sesión</a>
</center>

</div>

</body>
</html>