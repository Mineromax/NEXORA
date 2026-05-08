<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mi_juego");

$error = "";
$success = "";

// ==========================
// REGISTRO
// ==========================
if (isset($_POST['registro'])) {

    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM usuarios WHERE usuario='$usuario'");

    if ($check->num_rows > 0) {

        $error = "El usuario ya existe";

    } else {

        $conn->query("INSERT INTO usuarios 
        (usuario,password,last_update,mina_oro,mina_cristal)
        VALUES
        ('$usuario','$password',0,1,1)");

        $success = "Cuenta creada correctamente 🔥";
    }
}

// ==========================
// LOGIN
// ==========================
if (isset($_POST['login'])) {

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM usuarios WHERE usuario='$usuario'");

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['usuario'] = $user['usuario'];

            header("Location: panel.php");
            exit();

        } else {

            $error = "Contraseña incorrecta";

        }

    } else {

        $error = "Usuario no encontrado";

    }
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
    font-family:Arial;
    background:
    linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)),
    url('https://images.unsplash.com/photo-1462331940025-496dfbfc7564?q=80&w=1974&auto=format&fit=crop');
    background-size:cover;
    background-position:center;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
}

.box{
    width:350px;
    background:rgba(15,23,42,0.95);
    padding:30px;
    border-radius:15px;
    border:2px solid #1e293b;
    box-shadow:0 0 20px rgba(56,189,248,0.3);
}

h1{
    text-align:center;
    color:#38bdf8;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:none;
    border-radius:8px;
    background:#1e293b;
    color:white;
    font-size:16px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#22c55e;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#16a34a;
}

.sec{
    margin-top:15px;
}

.msg{
    text-align:center;
    margin-bottom:15px;
    color:#f87171;
}

.ok{
    color:#4ade80;
}

</style>

</head>
<body>

<div class="box">

<h1>🚀 Juego Espacial</h1>

<?php if($error != ""){ ?>
<div class="msg"><?php echo $error; ?></div>
<?php } ?>

<?php if($success != ""){ ?>
<div class="msg ok"><?php echo $success; ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="usuario" placeholder="Usuario" required>

<input type="password" name="password" placeholder="Contraseña" required>

<button type="submit" name="login">
Iniciar Sesión
</button>

</form>

<div class="sec">

<form method="POST">

<input type="text" name="usuario" placeholder="Nuevo usuario" required>

<input type="password" name="password" placeholder="Nueva contraseña" required>

<button type="submit" name="registro">
Crear Cuenta
</button>

</form>

</div>

</div>

</body>
</html>