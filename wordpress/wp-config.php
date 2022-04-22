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
define( 'DB_NAME', 'wordpress_fedex' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'rocfZYeh/k<y:JBGh9r=y]P%(@Qc|sJ!IF-h]RP(?.1s| lhHRl{cE|Rf+ZaCOV_' );
define( 'SECURE_AUTH_KEY',  '<fWFBRGAi=8&V)}Q=/0hC~`{Giuz,T,WfSGjq=!rysssma,$zR-ep9=)s(w]7i99' );
define( 'LOGGED_IN_KEY',    '1Jon`v|oi00Bcqq6NuND%UONLaIY%]qxur@0.f9S!CA=8t@qu^T),?*Qc#7tGM,`' );
define( 'NONCE_KEY',        'yRrpUPXu~v|TCFl2%5m[mzhFXNgv*X3z !:Z(X?|%e@R<(1U]`,,&x}OqtmvChMF' );
define( 'AUTH_SALT',        '=8ga-XX*dwW7sDJx.slW$<6b;F6aG80sd# ~D81c$&T,z|*WP9@:7x~WTs-G#2VL' );
define( 'SECURE_AUTH_SALT', 'RkpnldMJ/Uf#<:i6oZ37~YJ=9H hAA@u..6Ge.UKPg3dacSFTj/K-k}p&?M&tOK,' );
define( 'LOGGED_IN_SALT',   '_/@=PBXCmUKwQLF8yh@0zR5 zs}U=(i]W;56a[8GU]|A~bbv?&ftG>SMs3lvbkNe' );
define( 'NONCE_SALT',       '#0B-VPpF{)OrKWsr/5dRGbje%ya%+,.S-VN;<48E.=^1&X{+l>RGfHA7r]xYGq$x' );

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
