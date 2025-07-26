<?php
/**
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENV}}.php file.
 *
 * A good default policy is to deviate from the production config as little as
 * possible. Try to define as much of your configuration in this file as you
 * can.
 */

use Roots\WPConfig\Config;
use function Env\env;

/**
 * Directory containing all of the site's files
 *
 * @var string
 */
$root_dir = dirname(__DIR__);

/**
 * Document Root
 *
 * @var string
 */
$webroot_dir = $root_dir . '/web';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * .env.local will override .env if it exists
 */
if (file_exists($root_dir . '/.env')) {
    $env_files = file_exists($root_dir . '/.env.local')
        ? ['.env', '.env.local']
        : ['.env'];

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable($root_dir, $env_files, false);

    $dotenv->load();

    $dotenv->required(['WP_HOME', 'WP_SITEURL']);
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', env('WP_ENV') ?: 'production');

/**
 * Infer WP_ENVIRONMENT_TYPE based on WP_ENV
 */
if (!env('WP_ENVIRONMENT_TYPE') && in_array(WP_ENV, ['production', 'staging', 'development', 'local'])) {
    Config::define('WP_ENVIRONMENT_TYPE', WP_ENV);
}

/**
 * URLs
 */
Config::define('WP_HOME', env('WP_HOME'));
Config::define('WP_SITEURL', env('WP_SITEURL'));

/**
 * Custom Content Directory
 */
Config::define('CONTENT_DIR', '/app');
Config::define('WP_CONTENT_DIR', $webroot_dir . Config::get('CONTENT_DIR'));
Config::define('WP_CONTENT_URL', Config::get('WP_HOME') . Config::get('CONTENT_DIR'));

/**
 * DB settings
 */
if (env('DB_SSL')) {
    Config::define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
}

Config::define('DB_NAME', env('DB_NAME'));
Config::define('DB_USER', env('DB_USER'));
Config::define('DB_PASSWORD', env('DB_PASSWORD'));
Config::define('DB_HOST', env('DB_HOST') ?: 'localhost');
Config::define('DB_CHARSET', 'utf8mb4');
Config::define('DB_COLLATE', '');
$table_prefix = env('DB_PREFIX') ?: 'wp_';

if (env('DATABASE_URL')) {
    $dsn = (object) parse_url(env('DATABASE_URL'));

    Config::define('DB_NAME', substr($dsn->path, 1));
    Config::define('DB_USER', $dsn->user);
    Config::define('DB_PASSWORD', isset($dsn->pass) ? $dsn->pass : null);
    Config::define('DB_HOST', isset($dsn->port) ? "{$dsn->host}:{$dsn->port}" : $dsn->host);
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define('AUTH_KEY', env('AUTH_KEY'));
Config::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
Config::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
Config::define('NONCE_KEY', env('NONCE_KEY'));
Config::define('AUTH_SALT', env('AUTH_SALT'));
Config::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
Config::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
Config::define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', env('DISABLE_WP_CRON') ?: false);

// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);

// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);

// Limit the number of post revisions
Config::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?? true);


/** Enable W3 Total Cache */
//define('WP_CACHE', true); // Added by W3 Total Cache
Config::define('WP_CACHE', true);

Config::define('W3TC_REDIS_PASSWORD', env('W3TC_REDIS_PASSWORD'));
/**
 * Debugging Settings
 */
Config::define('WP_DEBUG_DISPLAY', false);
Config::define('WP_DEBUG_LOG', false);
Config::define('SCRIPT_DEBUG', false);
ini_set('display_errors', '0');

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * WP Mail SMTP
 */
/* Defining General Settings Constants
 *
 * Original doc: https://wpmailsmtp.com/docs/how-to-secure-smtp-settings-by-using-constants/
*/

Config::define( 'WPMS_ON', env('WPMS_ON') ?: false );
Config::define( 'WPMS_LICENSE_KEY', env('WPMS_LICENSE_KEY') ?: '' );
Config::define( 'WPMS_MAIL_FROM', env('WPMS_MAIL_FROM') ?: '' );
Config::define( 'WPMS_MAIL_FROM_FORCE', env('WPMS_MAIL_FROM_FORCE') ?: true );
Config::define( 'WPMS_MAIL_FROM_NAME', env('WPMS_MAIL_FROM_NAME') ?: '' );
Config::define( 'WPMS_MAIL_FROM_NAME_FORCE', env('WPMS_MAIL_FROM_NAME_FORCE') ?: true );
Config::define( 'WPMS_MAILER', env('WPMS_MAILER') ?: 'smtp' );
Config::define( 'WPMS_SET_RETURN_PATH', env('WPMS_SET_RETURN_PATH') ?: true );
Config::define( 'WPMS_DO_NOT_SEND', env('WPMS_DO_NOT_SEND') );
Config::define( 'WPMS_SMTP_HOST', env('WPMS_SMTP_HOST') ?: '' );
Config::define( 'WPMS_SMTP_PORT', env('WPMS_SMTP_PORT') ?: 587 );
Config::define( 'WPMS_SSL', env('WPMS_SSL') ?: '' );
Config::define( 'WPMS_SMTP_AUTH', env('WPMS_SMTP_AUTH') ?: true );
Config::define( 'WPMS_SMTP_USER', env('WPMS_SMTP_USER') ?: '' );
Config::define( 'WPMS_SMTP_PASS', env('WPMS_SMTP_PASS') ?: '' );
Config::define( 'WPMS_SMTP_AUTOTLS', env('WPMS_SMTP_AUTOTLS') ?: true );

/**
 * Unisender API
 */
Config::define( 'UNISENDER_API_KEY', env('UNISENDER_API_KEY') ?: '' );
Config::define( 'WP_FORMS_EMAIL', env('WP_FORMS_EMAIL') ?: '' );

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}
