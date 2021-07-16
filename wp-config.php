<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'chss_abc' );

/** MySQL database username */
define( 'DB_USER', 'chss_abc' );

/** MySQL database password */
define( 'DB_PASSWORD', 'jQGJG184fcdWkkAC' );

/** MySQL hostname */
define( 'DB_HOST', 'manolegeorge.ga' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '.P?<%K^QbNst~{|+`W__06:mwKNB>c(eNGQjhTre9qu0;*qn!Yh9i%E0,{yf,c*3' );
define( 'SECURE_AUTH_KEY',  'ZyDsR^4fuRHilo+E-vjJ]LvLM7D`GGLM-n#i0snjw!O12@NIx9hCJ]5qw=NS*70!' );
define( 'LOGGED_IN_KEY',    'k^%@lc;ugys59Z)t:%,{y&G)= NK.lSTVSJz(^~b5SSqPgpzat<[JC9xG)?VeSLc' );
define( 'NONCE_KEY',        'H_If?z<TZfs7=5}I{G3j4QeAhki+:u;njsWagn]e2<sS/DkzRzOxBVIz+s$}7D.;' );
define( 'AUTH_SALT',        '[UEX ICrr:6BRs}|D-O8jfAV7uujri*wFT6)k[O@6kUdn*nK%{mM^`b5E3l|zM2{' );
define( 'SECURE_AUTH_SALT', 'J:?Uq;`k*C_F&c5@0O/] (bx}swdMN pggbS(EjeN OmBNEM* $&VD+%A.Cr1P;$' );
define( 'LOGGED_IN_SALT',   'O@jT2.n>_1rAl98TE9gT!)&}?i*GmD`Um3k}?:Q602gT%R[o50]q{5sP|7~38gL5' );
define( 'NONCE_SALT',       '=Jjy6YQrWot1E).uHYZ` (:@<?s_9|ek*y$ yG%,9DKmxc*X+JBzbs./@h_rT`/l' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
