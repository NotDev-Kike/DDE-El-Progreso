# DDE El Progreso — instalación local

## Requisitos

- XAMPP, WAMP o Laragon con Apache y MySQL/MariaDB.
- PHP 8.5.x. PHP 8.6 aún es una versión de prueba y no debe usarse en producción.

## Instalación

1. Copie esta carpeta dentro de la carpeta pública del servidor web, por ejemplo `C:\xampp\htdocs\DDE El Progreso`.
2. Inicie Apache y MySQL desde el panel del servidor.
3. Abra phpMyAdmin y ejecute el archivo `if0_40123270_dde_progreso.sql`. El script crea y selecciona automáticamente la base de datos local `dde_progreso`.
4. Abra `http://localhost/DDE%20El%20Progreso/`.

El proyecto declara sus requisitos en [composer.json](composer.json). No necesita paquetes externos para funcionar; Composer se usa para comprobar la versión de PHP y extensiones.

## Nueva interfaz React

La interfaz moderna está en la carpeta `frontend` y usa React 19.3 con Vite 8. Para desarrollo:

```powershell
cd frontend
npm install
npm run dev
```

Abra `http://localhost:5173`. El servidor de Vite redirige `/api` al backend PHP local. Para generar la versión de producción use `npm run build`; el resultado queda en la carpeta `dist`.

Antes de iniciar sesión, en XAMPP inicie primero **Apache** y después **MySQL**. Apache debe responder en `http://localhost:80`; si está usando otro puerto, actualice el destino del proxy en `frontend/vite.config.js`. Si la base ya fue importada, ejecute `admin_reset.sql` desde phpMyAdmin. El acceso inicial es `admin` / `1234`; cambie esta contraseña después del primer acceso.

La conexión predeterminada es:

- Host: `127.0.0.1`
- Usuario: `root`
- Contraseña: vacía
- Base de datos: `dde_progreso`
- Puerto: `3306`

Si su instalación local usa otros datos, defina las variables `DDE_DB_HOST`, `DDE_DB_USER`, `DDE_DB_PASS`, `DDE_DB_NAME` y `DDE_DB_PORT` en Apache/PHP. No es necesario modificar el código.

## Usuarios incluidos en el respaldo

El respaldo incluye los usuarios `admin2` y `admin`. Por seguridad, cambie sus contraseñas después del primer inicio de sesión.
