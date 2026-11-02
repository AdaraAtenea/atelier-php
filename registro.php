<?php
    require_once 'config.php';
    $pageTitle='Crear cuenta';
    $error='';
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        verify_csrf(); 
        $nombre=trim((string)($_POST['name']??'')); 
        $email=strtolower(trim((string)($_POST['email']??''))); 
        $password=(string)($_POST['password']??'');
        if(strlen($nombre)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8) $error='Ingresa un nombre, correo válido y contraseña de al menos 8 caracteres.';
        else {
            $stmt=db()->prepare("SELECT id FROM usuarios WHERE email=?");$stmt->execute([$email]);
            if($stmt->fetch())$error='Ese correo ya está registrado.';
            else{$stmt=db()->prepare("INSERT INTO usuarios(nombre,email,password_hash,rol) VALUES(?,?,?,'cliente')");
                $stmt->execute([$nombre,$email,password_hash($password,PASSWORD_DEFAULT)]);
                $_SESSION['usuario']=['id'=>(int)db()->lastInsertId(),'nombre'=>$nombre,'email'=>$email,'rol'=>'cliente'];
                header('Location: cuenta.php');exit;}
        }
    }
    require 'includes/header.php';
?>
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">BIENVENIDO A ATELIER</p>
            <h1>Crear cuenta</h1>
            <p>Guarda tus datos y consulta tus pedidos.</p>
            <?php if($error): ?>
                <p class="alert alert-error"><?= e($error) ?></p>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <label>Nombre completo
                        <input name="name" autocomplete="name" required value="<?= e($_POST['nombre']??'') ?>">
                    </label>
                    <label>Correo electrónico
                        <input type="email" name="email" autocomplete="email" required value="<?= e($_POST['email']??'') ?>">
                    </label>
                    <label>Contraseña (mínimo 8 caracteres)
                        <input type="password" name="password" autocomplete="new-password" minlength="8" required>
                    </label>
                    <button class="button button-dark button-wide">Crear cuenta</button>
                </form>
                <p class="auth-switch">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
            </div>
        </section>
<?php require 'includes/footer.php'; ?>
