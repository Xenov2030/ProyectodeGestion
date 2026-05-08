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
            'bot_title' => 'Asistente IA',
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
            'no_recent_activity' => 'No hay actividad reciente para mostrar'
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
            'bot_title' => 'AI Assistant',
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
            'no_recent_activity' => 'No recent activity to show'
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
