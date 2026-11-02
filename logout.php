<?php
    require_once 'config.php';
    unset($_SESSION['usuario']);
    session_regenerate_id(true);
    header('Location: index.php');
exit;
