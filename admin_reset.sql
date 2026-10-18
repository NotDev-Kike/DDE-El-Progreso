-- Restablece el acceso administrativo del sistema local.
-- Usuario: admin | Contraseña: 1234
USE `dde_progreso`;

INSERT INTO `usuarios` (`usuario`, `contrasena`, `nombre_completo`, `rol`)
VALUES ('admin', '$2b$12$oPFO5pg4aiNbLXJ5gFyeT.moeB3RtGATKJRGb8MSRJW5tqErDNaHS', 'Administrador', 'admin')
ON DUPLICATE KEY UPDATE
  `contrasena` = VALUES(`contrasena`),
  `nombre_completo` = VALUES(`nombre_completo`),
  `rol` = 'admin';
