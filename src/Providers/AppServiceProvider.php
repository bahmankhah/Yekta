<?php

namespace App\Providers;


class AppServiceProvider
{

    public function register()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'yekta_audit';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            origin_id BIGINT(20) UNSIGNED NULL,
            type VARCHAR(50) NOT NULL,
            params LONGTEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY origin_id (origin_id)
        ) $charset_collate;";
        dbDelta($sql);

        $origins_table = $wpdb->prefix . 'yekta_allowed_origins';
        $sql2 = "CREATE TABLE IF NOT EXISTS $origins_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            ip VARCHAR(45) NOT NULL,
            client_id VARCHAR(100) NOT NULL UNIQUE,
            redirect_url TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql2);

    }


    public function boot()
    {
        
    }
}
