<?php
namespace app\Core;

class I18n {
    private static $translations = [
        'es' => [
            'dashboard' => 'Tablero',
            'projects' => 'Proyectos',
            'tickets' => 'Soporte',
            'chat' => 'Mensajería',
            'logout' => 'Cerrar Sesión',
            'welcome' => 'Bienvenido',
            'system_active' => 'SISTEMA ACTIVO',
            'new_project' => 'Nuevo Proyecto',
            'new_ticket' => 'Nuevo Ticket',
            'users' => 'Usuarios',
            'settings' => 'Ajustes',
            'search' => 'Buscar...',
            'bot_title' => 'GestorBot',
            'bot_welcome' => '¡Hola! ¿En qué puedo ayudarte hoy?',
            'bot_placeholder' => 'Escribe tu duda...',
            'loading_chat' => 'Cargando conversación...',
            'select_contact' => 'Selecciona un contacto',
            'send' => 'Enviar',
            'active_tasks' => 'Tareas Activas',
            'support_box' => 'Caja de Soporte',
            'check_pending' => 'Ver peticiones pendientes',
            'role' => 'Rol',
            'recent_activity' => 'Actividad Reciente',
            'new_project_assigned' => 'Nuevo Proyecto Asignado',
            'ticket_responded' => 'Ticket Respondido',
            'click_to_manage' => 'Gestionar proyectos',
            'ago' => 'hace',
            'yesterday' => 'ayer',
            'no_recent_activity' => 'No hay actividad reciente para mostrar',
            'login_title' => 'Sistema de Gestión',
            'login_subtitle' => 'Inicia sesión en tu cuenta para continuar',
            'email_address' => 'Correo Electrónico',
            'password' => 'Contraseña',
            'forgot' => '¿Olvidaste?',
            'sign_in' => 'Ingresar',
            'no_account' => '¿No tienes cuenta?',
            'contact_admin' => 'Contactar Admin',
            'forgot_title' => 'Recuperar Contraseña',
            'forgot_desc' => 'Ingresa tu correo electrónico y enviaremos un enlace para restablecer tu contraseña.',
            'send_link' => 'Enviar enlace',
            'cancel' => 'Cancelar',
            'request_title' => 'Solicitar Cuenta',
            'request_desc' => 'Ingresa tus datos y el administrador revisará tu solicitud para crear la cuenta.',
            'your_name' => 'Tu Nombre',
            'send_request' => 'Enviar solicitud',
            'request_sent' => 'Solicitud enviada al administrador.',
            'forgot_sent' => 'Si el correo existe, recibirás un enlace de recuperación.'
        ],
        'en' => [
            'dashboard' => 'Dashboard',
            'projects' => 'Projects',
            'tickets' => 'Support',
            'chat' => 'Messaging',
            'logout' => 'Log Out',
            'welcome' => 'Welcome',
            'system_active' => 'SYSTEM ACTIVE',
            'new_project' => 'New Project',
            'new_ticket' => 'New Ticket',
            'users' => 'Users',
            'settings' => 'Settings',
            'search' => 'Search...',
            'bot_title' => 'GestorBot',
            'bot_welcome' => 'Hello! How can I help you today?',
            'bot_placeholder' => 'Type your question...',
            'loading_chat' => 'Loading conversation...',
            'select_contact' => 'Select a contact',
            'send' => 'Send',
            'active_tasks' => 'Active Tasks',
            'support_box' => 'Support Box',
            'check_pending' => 'Check pending requests',
            'role' => 'Role',
            'recent_activity' => 'Recent Activity',
            'new_project_assigned' => 'New Project Assigned',
            'ticket_responded' => 'Ticket Responded',
            'click_to_manage' => 'Manage all projects',
            'ago' => 'ago',
            'yesterday' => 'yesterday',
            'no_recent_activity' => 'No recent activity to show',
            'login_title' => 'Management System',
            'login_subtitle' => 'Log in to your account to continue',
            'email_address' => 'Email Address',
            'password' => 'Password',
            'forgot' => 'Forgot?',
            'sign_in' => 'Sign In',
            'no_account' => "Don't have an account?",
            'contact_admin' => 'Contact Admin',
            'forgot_title' => 'Recover Password',
            'forgot_desc' => 'Enter your email address and we will send you a link to reset your password.',
            'send_link' => 'Send link',
            'cancel' => 'Cancel',
            'request_title' => 'Request Account',
            'request_desc' => 'Enter your details and the administrator will review your request to create an account.',
            'your_name' => 'Your Name',
            'send_request' => 'Send request',
            'request_sent' => 'Request sent to the administrator.',
            'forgot_sent' => 'If the email exists, you will receive a recovery link.'
        ]
    ];

    public static function t($key) {
        $lang = Session::get('lang') ?: 'es';
        return self::$translations[$lang][$key] ?? $key;
    }

    public static function setLang($lang) {
        if (in_array($lang, ['es', 'en'])) {
            Session::set('lang', $lang);
        }
    }

    public static function getLang() {
        return Session::get('lang') ?: 'es';
    }
}
