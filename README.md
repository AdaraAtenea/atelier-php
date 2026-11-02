## Crear un usuario administrador
Primero crea una cuenta desde `http://localhost/atelier-php/registro.php`.
Después, abre phpMyAdmin → base `atelier_db` → tabla `users` → SQL y ejecuta, reemplazando el correo por el que registraste:

```sql
UPDATE users SET role='admin' WHERE email='tu-correo@ejemplo.com';
```

Cierra sesión e inicia nuevamente. El panel estará en `http://localhost/atelier-php/admin/`.

## Funcionalidades incluidas
- Inicio editorial, catálogo, búsqueda, filtro por categoría y ordenamiento.
- Ficha de producto, tallas, stock y bolsa basada en sesión.
- Registro/login con contraseñas hash y sesiones regeneradas al iniciar sesión.
- Checkout que crea pedidos y descuenta inventario dentro de una transacción.
- Cuenta del cliente e historial de pedidos.
- Panel administrador con resumen, control de stock, visibilidad de productos y estado de pedidos.
- Diseño CSS3 adaptable a escritorio, tablet y móvil.

## Notas importantes
- El pago real NO está conectado: el pedido se registra con estado `pending`. Integra una pasarela real antes de vender.
- Los correos no se envían en esta versión.
- Las imágenes se cargan desde URLs externas; para producción descarga imágenes propias con licencia adecuada y guárdalas localmente.
- Antes de publicar en internet, usa HTTPS, configura una contraseña fuerte de base de datos, limita permisos, añade protección adicional para cambios de estado/inventario, registro de actividad, política de privacidad, términos, aviso de cookies cuando aplique y copias de seguridad.
- El script `database.sql` elimina las tablas del mismo nombre antes de recrearlas. No lo importes sobre una base de datos con información que quieras conservar.
- La administración inicial permite editar stock y activar/desactivar productos. Para altas y edición completa de productos, se puede añadir un formulario CRUD dedicado.

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
