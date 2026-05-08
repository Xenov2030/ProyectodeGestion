# 🛠️ Guía de Instalación y Configuración

Sigue estos pasos detallados para configurar el entorno de desarrollo local en tu computadora.

## 1. Requisitos Previos
*   **WampServer** (Recomendado) o XAMPP.
*   **PHP 8.1** o superior.
*   **MySQL 8.0** o superior.
*   **Navegador Web** (Chrome, Edge o Firefox).

---

## 2. Configuración de la Carpeta
Asegúrate de colocar el proyecto en la carpeta correcta de tu servidor local:
*   En WAMP: `C:\wamp64\www\proyectos\proyectoJuevesNewton\`
*   En XAMPP: `C:\xampp\htdocs\proyectos\proyectoJuevesNewton\`

---

## 3. Configuración de la Base de Datos
1.  Abre tu navegador y ve a `http://localhost/phpmyadmin/`.
2.  Crea una nueva base de datos llamada `proyecto_final` (o el nombre que prefieras).
3.  Selecciona la base de datos creada.
4.  Haz clic en la pestaña **"Importar"**.
5.  Selecciona el archivo: `database/final_schema.sql` que está dentro de la carpeta del proyecto.
6.  Haz clic en **"Continuar"** o **"Importar"**.

---

## 4. Configuración del Entorno (.env)
En la raíz del proyecto verás un archivo llamado `.env`. Este archivo le dice al sistema cómo conectarse a la base de datos. Ábrelo con un editor de texto (Notepad++, VS Code) y verifica lo siguiente:

```env
APP_NAME="Gestor Pro SaaS"
APP_ENV=local
APP_URL=http://localhost/proyectos/proyectoJuevesNewton

DB_HOST=localhost
DB_NAME=proyecto_final   # <--- Debe coincidir con el nombre en phpMyAdmin
DB_USER=root            # Por defecto en WAMP/XAMPP es root
DB_PASS=                # Deja vacío si no tienes contraseña en MySQL
```

---

## 5. Ejecución
1.  Asegúrate de que WAMP/XAMPP esté encendido (icono en verde).
2.  Abre tu navegador y escribe: `http://localhost/proyectos/proyectoJuevesNewton/`
3.  Si todo es correcto, verás la pantalla de **Login**.

### Credenciales de Prueba:
*   **Email**: `admin@gestorpro.com`
*   **Contraseña**: `password`

---

## 💡 Solución de Problemas Comunes
*   **Error 404**: Verifica que el archivo `.htaccess` esté en la raíz y que el módulo `mod_rewrite` esté activado en Apache.
*   **Error de Conexión DB**: Revisa que los datos en el `.env` sean idénticos a los de tu phpMyAdmin.
*   **Página en Blanco**: Asegúrate de tener activada la visualización de errores en PHP o revisa el log de errores de Apache.
