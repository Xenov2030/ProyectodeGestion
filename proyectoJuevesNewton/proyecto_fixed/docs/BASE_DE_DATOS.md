# 🗄️ Documentación de la Base de Datos

El sistema utiliza una base de datos relacional MySQL. Aquí se explica cómo están conectadas las tablas principales.

## 📊 Tablas Principales

### 1. `usuarios`
Almacena a todas las personas que pueden entrar al sistema.
*   **Relaciones**: 
    *   `empresa_id`: Conecta con la tabla `empresas` (null si es admin global).
    *   `rol_id`: Define qué permisos tiene el usuario (admin, empleado, cliente, etc.).

### 2. `empresas`
Empresas que utilizan el SaaS.
*   **Importante**: Casi todos los datos (proyectos, clientes) están filtrados por `empresa_id` para que una empresa no vea los datos de otra.

### 3. `proyectos`
Proyectos creados dentro de una empresa.
*   **Relaciones**: Pertenece a una `empresa_id` y tiene un `estado` (Pendiente, Activo, Terminado).

### 4. `tickets`
Reportes de soporte técnico.
*   **Relaciones**: Creado por un `usuario_id` y asignado a una `empresa_id`.

### 5. `mensajes_chat`
Historial de la mensajería interna.
*   **Relaciones**: `remitente_id` y `destinatario_id` (ambos apuntan a `usuarios`).

---

## 🔑 Integridad Referencial (Foreign Keys)
Hemos configurado llaves foráneas con **`ON DELETE CASCADE`**.
*   **¿Qué significa?**: Si borras una empresa, el sistema borrará automáticamente todos sus proyectos, tickets y usuarios asociados para no dejar basura en la base de datos.

---

## 🚀 Cómo exportar cambios
Si agregas una tabla o una columna nueva mientras trabajas:
1.  Haz el cambio en phpMyAdmin.
2.  Ve a la pestaña **"Exportar"**.
3.  Selecciona el formato **SQL**.
4.  Guarda el archivo en `database/` con un nuevo nombre o reemplaza el `final_schema.sql` para que tus compañeros tengan la última versión.
