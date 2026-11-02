<?php
    require_once 'config.php';
    $pageTitle='Iniciar sesión';
    $error=''; $next=(string)($_GET['next']??'');
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        verify_csrf(); 
        $email=strtolower(trim((string)($_POST['email']??'')));
        $password=(string)($_POST['password']??'');
        $stmt=db()->prepare("SELECT id,nombre,email,password_hash,rol FROM usuarios WHERE email=?");
        $stmt->execute([$email]);
        $user=$stmt->fetch();
        if($user && password_verify($password,$user['password_hash'])){
            session_regenerate_id(true);
            $_SESSION['usuario']=[
                'id'=>(int)$user['id'],
                'nombre'=>$user['nombre'],
                'email'=>$user['email'],
                'rol'=>$user['rol']
            ];
            $dest=($_POST['next']??'')==='admin'&&$user['rol']==='admin'?'admin/index.php':((($_POST['next']??'')==='checkout')?'checkout.php':'cuenta.php');
            header('Location: '.$dest);
            exit;
        }
        $error='Correo o contraseña incorrectos.';
    }
    require 'includes/header.php';
?>
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">ATELIER</p>
            <h1>Iniciar sesión</h1>
            <p>Qué gusto tenerte de vuelta.</p>
            <?php if($error): ?>
                <p class="alert alert-error"><?= e($error) ?></p>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="next" value="<?= e($next) ?>">
                    <label>Correo electrónico
                        <input type="email" name="email" autocomplete="email" required>
                    </label>
                    <label>Contraseña
                        <input type="password" name="password" autocomplete="current-password" required>
                    </label>
                    <button class="button button-dark button-wide">Ingresar</button>
                </form>
            <p class="auth-switch">¿Aún no tienes cuenta? <a href="registro.php">Crear cuenta</a></p>
        </div>
    </section>
<?php require 'includes/footer.php'; ?>