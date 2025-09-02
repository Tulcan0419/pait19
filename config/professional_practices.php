<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración del Sistema de Prácticas Preprofesionales
    |--------------------------------------------------------------------------
    |
    | Este archivo contiene la configuración para el sistema de revisión
    | de documentos de prácticas preprofesionales para profesores.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Configuración General
    |--------------------------------------------------------------------------
    */
    'system_name' => 'Sistema de Revisión de Prácticas Preprofesionales',
    'version' => '1.0.0',
    'maintenance_mode' => env('PRACTICES_MAINTENANCE_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Carreras
    |--------------------------------------------------------------------------
    */
    'careers' => [
        'mechanical' => [
            'name' => 'Mecánica Automotriz',
            'short_name' => 'Mecánica',
            'color' => '#dc3545',
            'icon' => 'fas fa-cog',
            'enabled' => true,
        ],
        'software' => [
            'name' => 'Desarrollo de Software',
            'short_name' => 'Software',
            'color' => '#007bff',
            'icon' => 'fas fa-laptop-code',
            'enabled' => true,
        ],
        'education' => [
            'name' => 'Educación Básica',
            'short_name' => 'Educación',
            'color' => '#28a745',
            'icon' => 'fas fa-chalkboard-teacher',
            'enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Estados de Documentos
    |--------------------------------------------------------------------------
    */
    'document_statuses' => [
        'pending' => [
            'name' => 'Pendiente de Revisión',
            'color' => '#ffc107',
            'icon' => 'fas fa-clock',
            'description' => 'Documento enviado, pendiente de revisión por el profesor',
        ],
        'approved' => [
            'name' => 'Aprobado',
            'color' => '#28a745',
            'icon' => 'fas fa-check-circle',
            'description' => 'Documento aprobado por el profesor',
        ],
        'rejected' => [
            'name' => 'Rechazado',
            'color' => '#dc3545',
            'icon' => 'fas fa-times-circle',
            'description' => 'Documento rechazado por el profesor',
        ],
        'under_review' => [
            'name' => 'En Revisión',
            'color' => '#17a2b8',
            'icon' => 'fas fa-search',
            'description' => 'Documento siendo revisado actualmente',
            'enabled' => false, // Estado opcional
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipos de Documentos
    |--------------------------------------------------------------------------
    */
    'document_types' => [
        'practice_report' => [
            'name' => 'Reporte de Práctica',
            'description' => 'Informe detallado de las actividades realizadas',
            'required' => true,
            'max_size' => 10240, // KB
            'allowed_extensions' => ['pdf', 'doc', 'docx'],
        ],
        'progress_report' => [
            'name' => 'Reporte de Progreso',
            'description' => 'Actualización del progreso en las prácticas',
            'required' => true,
            'max_size' => 5120, // KB
            'allowed_extensions' => ['pdf', 'doc', 'docx'],
        ],
        'final_report' => [
            'name' => 'Reporte Final',
            'description' => 'Reporte final de las prácticas preprofesionales',
            'required' => true,
            'max_size' => 15360, // KB
            'allowed_extensions' => ['pdf', 'doc', 'docx'],
        ],
        'supporting_document' => [
            'name' => 'Documento de Apoyo',
            'description' => 'Documento adicional que respalda la práctica',
            'required' => false,
            'max_size' => 5120, // KB
            'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Revisión
    |--------------------------------------------------------------------------
    */
    'review' => [
        'require_comments_on_rejection' => true,
        'max_comment_length' => 1000,
        'auto_notify_student' => true,
        'allow_status_change' => true,
        'require_approval_for_approval' => false,
        'max_review_time_hours' => 72, // Tiempo máximo recomendado para revisar
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Estadísticas
    |--------------------------------------------------------------------------
    */
    'statistics' => [
        'default_period' => 'month',
        'available_periods' => [
            'month' => 'Último mes',
            'quarter' => 'Último trimestre',
            'semester' => 'Último semestre',
            'year' => 'Último año',
            'all' => 'Todo el tiempo',
        ],
        'chart_colors' => [
            'primary' => '#007bff',
            'success' => '#28a745',
            'warning' => '#ffc107',
            'danger' => '#dc3545',
            'info' => '#17a2b8',
        ],
        'refresh_interval' => 300, // segundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Comentarios
    |--------------------------------------------------------------------------
    */
    'comments' => [
        'enable_templates' => true,
        'max_templates' => 10,
        'default_templates' => [
            'approved' => 'Excelente trabajo. El documento cumple con todos los requisitos establecidos y demuestra un buen nivel de comprensión del tema.',
            'minor_improvements' => 'Buen trabajo en general. Se sugiere revisar la ortografía y gramática, así como mejorar la presentación del contenido.',
            'rejected_content' => 'El documento no cumple con los requisitos mínimos. Se requiere ampliar el contenido y profundizar en el análisis del tema.',
            'rejected_format' => 'El contenido es adecuado pero el formato no cumple con las especificaciones requeridas. Por favor, ajustar según las normas establecidas.',
        ],
        'enable_rich_text' => false,
        'allow_attachments' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Exportación
    |--------------------------------------------------------------------------
    */
    'export' => [
        'formats' => ['pdf', 'excel'],
        'default_format' => 'pdf',
        'max_records_per_export' => 1000,
        'include_charts' => true,
        'include_metadata' => true,
        'filename_prefix' => 'practices_report',
        'temporary_file_retention_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Notificaciones
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email' => [
            'enabled' => true,
            'template' => 'emails.document_status',
            'subject_prefix' => '[Prácticas Preprofesionales]',
        ],
        'database' => [
            'enabled' => true,
            'retention_days' => 90,
        ],
        'in_app' => [
            'enabled' => true,
            'auto_mark_read' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Archivos
    |--------------------------------------------------------------------------
    */
    'files' => [
        'storage_disk' => 'local',
        'public_disk' => 'public',
        'max_file_size' => 15360, // KB
        'allowed_mime_types' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ],
        'virus_scan' => false,
        'auto_rename' => true,
        'backup_enabled' => true,
        'backup_retention_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Seguridad
    |--------------------------------------------------------------------------
    */
    'security' => [
        'require_authentication' => true,
        'session_timeout_minutes' => 120,
        'max_login_attempts' => 5,
        'lockout_duration_minutes' => 15,
        'password_history_count' => 5,
        'enable_audit_log' => true,
        'log_sensitive_actions' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Rendimiento
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'enable_caching' => true,
        'cache_ttl_minutes' => 60,
        'pagination_default' => 15,
        'pagination_options' => [10, 15, 25, 50, 100],
        'max_concurrent_requests' => 10,
        'query_timeout_seconds' => 30,
        'enable_lazy_loading' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Interfaz de Usuario
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'theme' => 'default',
        'language' => 'es',
        'timezone' => 'America/Guayaquil',
        'date_format' => 'd/m/Y',
        'time_format' => 'H:i',
        'datetime_format' => 'd/m/Y H:i',
        'enable_animations' => true,
        'enable_tooltips' => true,
        'enable_confirmations' => true,
        'auto_save_interval_seconds' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Reportes
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'enable_scheduled_reports' => false,
        'default_report_time' => '09:00',
        'report_recipients' => [],
        'include_charts_in_reports' => true,
        'max_report_size_mb' => 10,
        'compression_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Integración
    |--------------------------------------------------------------------------
    */
    'integration' => [
        'moodle' => [
            'enabled' => false,
            'api_url' => env('MOODLE_API_URL'),
            'api_token' => env('MOODLE_API_TOKEN'),
        ],
        'google_drive' => [
            'enabled' => false,
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        ],
        'microsoft_office' => [
            'enabled' => false,
            'client_id' => env('MS_OFFICE_CLIENT_ID'),
            'client_secret' => env('MS_OFFICE_CLIENT_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Desarrollo
    |--------------------------------------------------------------------------
    */
    'development' => [
        'debug_mode' => env('PRACTICES_DEBUG_MODE', false),
        'log_queries' => env('PRACTICES_LOG_QUERIES', false),
        'enable_test_data' => env('PRACTICES_ENABLE_TEST_DATA', false),
        'mock_notifications' => env('PRACTICES_MOCK_NOTIFICATIONS', false),
        'performance_monitoring' => env('PRACTICES_PERFORMANCE_MONITORING', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Mantenimiento
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'auto_cleanup_old_files' => true,
        'cleanup_interval_days' => 7,
        'max_file_age_days' => 365,
        'enable_backup' => true,
        'backup_schedule' => 'daily',
        'max_backup_size_gb' => 10,
        'enable_health_checks' => true,
        'health_check_interval_hours' => 24,
    ],
];
