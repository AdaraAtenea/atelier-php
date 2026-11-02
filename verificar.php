
<?php
    echo "Versión de PHP: " . PHP_VERSION . "<br>";
    if (extension_loaded('pdo_mysql')) {
        echo "Correcto: PDO MySQL está habilitado.";
    } else {
        echo "Error: PDO MySQL sigue deshabilitado.";
    }
?>
