# 🚀 GESTOR PRO - SaaS ERP/CRM

¡Bienvenido al proyecto final de **Gestor Pro**! Este sistema es una plataforma SaaS (Software as a Service) diseñada para la gestión integral de empresas, proyectos, tickets de soporte y mensajería interna.

Este documento servirá como guía principal para el equipo. Aquí encontrarás todo lo necesario para entender, ejecutar y expandir el proyecto.

---

## 📁 Estructura del Proyecto

El proyecto sigue una arquitectura **MVC (Modelo-Vista-Controlador)** personalizada para mantener el código organizado y fácil de escalar.

```text
proyectoJuevesNewton/
├── app/                # 🧠 EL CEREBRO (Lógica del Sistema)
│   ├── Controllers/    # Manejan las peticiones del usuario (Rutas)
│   ├── Models/         # Se comunican con la Base de Datos
│   ├── Core/           # El motor del framework (Sesiones, Rutas, DB, Idiomas)
│   └── Config/         # Configuraciones globales
├── botchat/            # 🤖 CHATBOT IA
│   └── js/             # Lógica y conocimiento del bot
├── database/           # 🗄️ BASE DE DATOS
│   └── final_schema.sql # El archivo para crear las tablas
├── public/             # 🌐 ARCHIVOS PÚBLICOS
│   ├── css/            # Estilos visuales (diseño moderno)
│   └── index.php       # Punto de entrada principal
├── routes/             # 📍 RUTAS
│   └── web.php         # Define qué URL lleva a qué Controlador
├── views/              # 🎨 VISTAS (Lo que ve el usuario)
│   ├── layouts/        # Plantillas generales (App, Login)
│   ├── dashboard/      # Vista del panel principal
│   ├── chat/           # Interfaz de mensajería
│   └── ...             # Otras carpetas (proyectos, tickets, auth)
├── .env                # 🔑 CONFIGURACIÓN SECRETA (Conexión DB)
└── README.md           # Esta guía
```

---

## 🛠️ Cómo Empezar (Instalación Rápida)

Si eres nuevo en el proyecto, sigue estos pasos para tenerlo funcionando en tu PC:

1.  **Instalar WAMP/XAMPP**: Necesitas un servidor local con PHP 8.0+ y MySQL.
2.  **Configurar la Base de Datos**: Importa el archivo `database/final_schema.sql` en phpMyAdmin.
3.  **Configurar el archivo `.env`**: Asegúrate de que los datos de conexión a tu base de datos sean correctos.
4.  **Acceso al Sistema**:
    *   **URL**: `http://localhost/proyectos/proyectoJuevesNewton/`
    *   **Usuario Admin**: `admin@gestorpro.com`
    *   **Contraseña**: `password` (o `admin123` según la versión)

> [!IMPORTANT]
> Lee la guía detallada de [Instalación y Configuración](docs/INSTALACION.md) para más detalles.

---

## 🌟 Funcionalidades Principales

1.  **Panel de Control (Dashboard)**: Resumen visual de tareas, proyectos y tickets.
2.  **Sistema Multi-idioma**: Selector ES/EN integrado en la barra superior.
3.  **Chatbot Inteligente**: Asistente disponible 24/7 con opciones interactivas.
4.  **Mensajería Interna**: Chat en tiempo real entre empleados y clientes.
5.  **Gestión de Proyectos**: Creación y seguimiento de proyectos por empresa.
6.  **Soporte (Tickets)**: Sistema de atención al cliente organizado por estados.

---

## 👨‍💻 Guía para Desarrolladores (Tu equipo)

Si quieres agregar algo nuevo (ej: una nueva página de "Clientes"):
1. **Crea el Modelo**: En `app/Models/Cliente.php` para manejar los datos.
2. **Crea el Controlador**: En `app/Controllers/ClienteController.php` para la lógica.
3. **Crea la Vista**: En `views/clientes/index.php` para el diseño.
4. **Registra la Ruta**: Agrega la URL en `routes/web.php`.

Para más detalles sobre cómo programar en este sistema, consulta la [Guía de Arquitectura](docs/ARQUITECTURA.md).

---

## 🤝 Contribuir al Proyecto

Para que todos trabajemos de forma organizada:
*   Usa el archivo `public/css/style.css` para cualquier cambio de diseño (no escribas estilos dentro del HTML).
*   Usa el sistema de idiomas `I18n::t('clave')` para que el sistema siga siendo bilingüe.
*   Documenta tus cambios en la sección de "Actividad Reciente".

---
*Desarrollado con pasión para el Proyecto Final de Programación 2026.*
