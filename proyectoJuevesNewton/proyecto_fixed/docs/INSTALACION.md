# 🛠️ Guía de Instalación — Sistema de gestión SaaS

Sigue estos pasos para tener el proyecto funcionando en tu PC con WAMP.

---

## ✅ Requisitos

| Herramienta | Versión mínima |
|-------------|----------------|
| WampServer  | 3.x            |
| PHP         | 8.1+           |
| MySQL       | 8.0+           |
| Navegador   | Chrome / Edge / Firefox |

---

## 📂 Paso 1 — Clonar / Descargar el Repositorio

Coloca la carpeta del proyecto **dentro de `wamp64\www`**.  
Puedes elegir cualquier ruta, por ejemplo:

```
C:\wamp64\www\proyectos\proyectoJuevesNewton\proyecto_fixed\
```

> **Nota:** El nombre de la carpeta puede ser cualquiera, el sistema se adapta automáticamente.

---

## 🗄️ Paso 2 — Importar la Base de Datos

1. Asegúrate de tener **WAMP encendido** (icono verde en la bandeja del sistema).
2. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
3. En la pantalla de inicio de phpMyAdmin (sin seleccionar ninguna BD), haz clic en la pestaña **"Importar"**.
4. Haz clic en **"Seleccionar archivo"** y elige:
   ```
   [tu-carpeta-del-proyecto]\database\final_schema.sql
   ```
5. Desplázate al final y haz clic en **"Continuar"** o **"Importar"**.

> ✅ El script creará automáticamente la base de datos `gestor_pro` con todas las tablas y datos de prueba.

---

## ⚙️ Paso 3 — Configurar el Archivo `.env`

1. En la raíz del proyecto encontrarás un archivo llamado **`.env.example`**.
2. **Cópialo** y renombra la copia como **`.env`** (sin la extensión `.example`).
3. Abre el `.env` con un editor de texto (VS Code, Notepad++, etc.) y verifica:

```env
DB_HOST=localhost
DB_NAME=gestor_pro
DB_USER=root
DB_PASS=           ← Dejar vacío si no tienes contraseña en MySQL (WAMP por defecto)
DB_PORT=3306

APP_URL=http://localhost/proyectos/proyectoJuevesNewton/proyecto_fixed
```

> **Importante:** Ajusta `APP_URL` con la ruta exacta donde colocaste el proyecto.  
> Si la pusiste en `C:\wamp64\www\miproyecto\`, la URL sería `http://localhost/miproyecto`.

---

## 🚀 Paso 4 — Ejecutar el Proyecto

1. Abre tu navegador y escribe la URL del proyecto, por ejemplo:
   ```
   http://localhost/proyectos/proyectoJuevesNewton/proyecto_fixed/
   ```
2. Si todo está bien configurado, verás la **pantalla de Login**.

### 🔑 Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador Global | `admin@gestorpro.com` | `password` |
| Director Demo | `director@demo.com` | `password` |

---

## 🔧 Activar `mod_rewrite` en WAMP (si ves Error 404)

El proyecto usa URLs limpias (sin `index.php` en la URL). Para que funcionen:

1. Haz clic izquierdo en el ícono de WAMP en la barra de tareas.
2. Ve a **Apache → Apache Modules**.
3. Busca **`rewrite_module`** y haz clic para activarlo (debe aparecer con tilde ✓).
4. WAMP se reiniciará automáticamente.

---

## 💡 Solución de Problemas Comunes

| Problema | Causa probable | Solución |
|----------|---------------|----------|
| **Error 404** en todas las páginas | `mod_rewrite` desactivado | Ver sección anterior |
| **"Error de conectividad con la BD"** | Datos incorrectos en `.env` | Verifica `DB_NAME`, `DB_USER`, `DB_PASS` |
| **Página en blanco** | Error PHP silenciado | Activa errores en `php.ini` o revisa el log de Apache en WAMP |
| **Login no funciona** | BD no importada | Repite el Paso 2 |
| **Imágenes/CSS no cargan** | `APP_URL` incorrecta en `.env` | Ajusta la URL al paso 3 |
