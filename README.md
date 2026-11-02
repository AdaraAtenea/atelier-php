## Acceso de demostración
Para facilitar la evaluación del proyecto, se proporcionan las siguientes cuentas de prueba:

**Administrador**
- Usuario: `sheylarmz@gmail.com`
- Contraseña: `12345678S`

**Usuario estándar**
- Usuario: `esmeg@gmail.com`
- Contraseña: `87654321E`

Estas cuentas son exclusivamente para fines de demostración y evaluación. Los datos utilizados dentro del sistema son ficticios.

## Funcionalidades incluidas
- Inicio editorial, catálogo, búsqueda, filtro por categoría y ordenamiento.
- Ficha de producto, tallas, stock y bolsa basada en sesión.
- Registro/login con contraseñas hash php y sesiones regeneradas al iniciar sesión.
- Checkout que crea pedidos y descuenta inventario dentro de una transacción.
- Cuenta del cliente e historial de pedidos.
- Panel administrador con resumen, control de stock, visibilidad de productos y estado de pedidos.
- Diseño CSS3 adaptable a escritorio, tablet y móvil.

## Notas importantes
- El pago real NO está conectado: el pedido se registra con estado `pending`.
- Las imágenes se cargan desde URLs externas.
- La administración inicial permite editar stock y activar/desactivar productos. Para altas y edición completa de productos.

## Estructura
- `index.php`: portada
- `catalogo.php`: catálogo y filtros
- `producto.php`: detalle
- `carrito.php`: bolsa
- `checkout.php`: creación de pedido
- `registro.php`, `login.php`, `cuenta.php`, `logout.php`
- `admin/`: panel de administración
- `config.php`: conexión PDO, sesiones y helpers
- `database.sql`: estructura y datos de prueba
- `css/styles.css`: diseño CSS3
- `js/app.js`: menú móvil
