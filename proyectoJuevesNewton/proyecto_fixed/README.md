# 🚀 GESTOR PRO — SaaS ERP/CRM

Sistema de gestión integral para empresas: proyectos, tickets de soporte y mensajería interna. Desarrollado como proyecto final para el curso de Programación 2026.

---

## ⚡ Instalación Rápida (WAMP)

> Para la guía completa paso a paso, ver [`docs/INSTALACION.md`](docs/INSTALACION.md)

```
1. Clonar/descargar el repositorio en:  C:\wamp64\www\[nombre-carpeta]\
2. Importar `database/final_schema.sql` en phpMyAdmin (desde la pantalla de inicio → Importar)
3. Copiar `.env.example` → `.env` y ajustar APP_URL con tu ruta
4. Abrir: http://localhost/[nombre-carpeta]/
```

**Credenciales de prueba:**
- Admin: `admin@gestorpro.com` / `password`
- Director: `director@demo.com` / `password`

---

## 📁 Estructura del Proyecto

Arquitectura **MVC personalizada** sin frameworks externos.

```
proyecto_fixed/
├── app/                    # 🧠 Lógica del sistema
│   ├── Config/             #   → Configuración global
│   ├── Controllers/        #   → Manejan las peticiones HTTP
│   ├── Core/               #   → Motor: Router, DB, Sesiones, i18n
│   ├── Middlewares/        #   → Control de acceso (auth, roles)
│   └── Models/             #   → Comunicación con la base de datos
├── botchat/                # 🤖 Chatbot IA (JavaScript)
├── database/
│   └── final_schema.sql    # 🗄️ Esquema completo (crea la BD automáticamente)
├── docs/                   # 📖 Documentación técnica
│   ├── INSTALACION.md      #   → Guía de instalación detallada
│   ├── ARQUITECTURA.md     #   → Cómo está organizado el código
│   └── BASE_DE_DATOS.md    #   → Diagrama y descripción de tablas
├── public/                 # 🌐 Punto de entrada público
│   ├── css/                #   → Estilos del sistema
│   ├── uploads/            #   → Archivos subidos por usuarios
│   └── index.php           #   → Front Controller (entrada única)
├── routes/
│   └── web.php             # 📍 Definición de todas las rutas
├── views/                  # 🎨 Plantillas HTML (PHP)
│   ├── layouts/            #   → Plantillas generales (app, login)
│   ├── dashboard/          #   → Panel de control
│   ├── proyectos/          #   → Gestión de proyectos
│   ├── tickets/            #   → Sistema de soporte
│   ├── chat/               #   → Mensajería interna
│   └── auth/               #   → Login / Logout
├── .env.example            # ⚙️ Plantilla de configuración (copiar como .env)
├── .gitignore              # 🔒 Archivos excluidos del repositorio
└── README.md               # Esta guía
```

---

## 🌟 Funcionalidades

| Módulo | Descripción |
|--------|-------------|
| **Dashboard** | Panel con resumen de tareas, proyectos y tickets en tiempo real |
| **Multi-idioma** | Selector ES/EN integrado en la barra de navegación |
| **Chatbot IA** | Asistente disponible 24/7 con menú interactivo |
| **Mensajería** | Chat interno entre empleados y clientes |
| **Proyectos** | Creación y seguimiento de proyectos por empresa |
| **Tickets** | Sistema de soporte organizado por estado y prioridad |
| **Usuarios** | Sistema de gestión de usuarios |

---

## 👨‍💻 Guía para Contribuir

Para agregar un nuevo módulo (ej: "Clientes"):

```
1. Crear modelo:      app/Models/Cliente.php
2. Crear controlador: app/Controllers/ClienteController.php
3. Crear vista:       views/clientes/index.php
4. Registrar ruta:    routes/web.php  →  $router->get('/clientes', [...])
```

**Reglas del equipo:**
- Usa `public/css/style.css` para estilos (nunca CSS inline en el HTML)
- Usa `I18n::t('clave')` para textos bilingües
- No subas el archivo `.env` al repositorio

---

## 🔐 Roles del Sistema

| ID | Rol | Descripción |
|----|-----|-------------|
| 1 | `admin` | Superadministrador global |
| 2 | `directivo` | Dueño/directivo de empresa cliente |
| 3 | `administrativo` | Gestor operativo |
| 4 | `empleado` | Personal técnico |
| 5 | `cliente` | Usuario final / solicitante |

---

*Desarrollado con PHP 8.1 · MySQL 8 · Arquitectura MVC · Proyecto Final de Programación 2026*
