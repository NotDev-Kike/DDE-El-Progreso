# DDE El Progreso

Sistema de gestión educativa para la Dirección Distrital de Educación de El Progreso.

## Tecnologías

- React 19.3
- Vite 8
- Node.js 24 o superior
- PHP 8.5+
- MySQL/MariaDB local
- API PHP con `mysqli`

## Requisitos

Instala o habilita:

- Node.js y npm
- XAMPP, WAMP o Laragon
- Apache
- MySQL o MariaDB
- Extensión PHP `mysqli`

## Instalación de la base de datos

1. Copia el proyecto dentro de la carpeta pública de Apache, por ejemplo:

   ```text
   C:\xampp\htdocs\DDE El Progreso
   ```

2. Inicia Apache y MySQL desde XAMPP.
3. Abre phpMyAdmin en `http://localhost/phpmyadmin`.
4. Importa [if0_40123270_dde_progreso.sql](./if0_40123270_dde_progreso.sql).

La conexión local predeterminada es:

```text
Host:       127.0.0.1
Puerto:     3306
Base datos: dde_progreso
Usuario:    root
Contraseña: vacía
```

Si ya habías importado la base de datos, ejecuta [admin_reset.sql](./admin_reset.sql) para crear o restablecer el usuario inicial.

## Usuario inicial

```text
Usuario:    admin
Contraseña: 1234
```

Cambia esta contraseña después del primer inicio de sesión.

## Ejecutar React

Desde la carpeta raíz del proyecto:

```powershell
npm install --prefix frontend
npm start
```

Después abre:

```text
http://localhost:5173
```

También puedes ejecutar los comandos desde `frontend`:

```powershell
cd frontend
npm install
npm run dev
```

## Compilar para producción

```powershell
npm run build --prefix frontend
```

El resultado se genera en la carpeta `dist`.

## API

La API está en [api/index.php](./api/index.php) y utiliza la sesión PHP para autenticación.

Rutas principales:

- `POST /api/index.php?action=login`
- `POST /api/index.php?action=logout`
- `GET /api/index.php?resource=dashboard`
- `GET /api/index.php?resource=docentes`
- `GET /api/index.php?resource=estructuras`
- `GET /api/index.php?resource=usuarios`
- `GET /api/index.php?resource=nombramiento-basica`
- `GET /api/index.php?resource=nombramiento-media`
- `GET /api/index.php?resource=traslado-basica`
- `GET /api/index.php?resource=traslado-media`
- `GET /api/index.php?resource=cancelaciones`

## Error `Failed to fetch`

Este error aparece cuando Vite no puede conectarse con Apache/PHP. Comprueba que:

1. Apache esté iniciado y responda en `http://localhost:80`.
2. MySQL esté iniciado.
3. El proyecto esté dentro de `htdocs` o la carpeta pública equivalente.
4. La API exista en `http://localhost/DDE%20El%20Progreso/api/index.php`.

La documentación adicional de instalación local está en [README_LOCAL.md](./README_LOCAL.md).
