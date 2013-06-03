<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db_donation');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!u<,vt,:Qi<#M~^/2,u#>FEqpL9d;{+Z7KNFp0r9BQ<(Edq&sCx(zMgt O6)9eI<');
define('SECURE_AUTH_KEY',  '^: ay.m9r-E-+P#HStP5g!0>_jN=xO8/OFk:8O`i6PEs6E~W=ZiBIKpEDH?0(o&`');
define('LOGGED_IN_KEY',    'A;b9(+P8$:HM+$>o_pAs@sm_syXz$*GQx!wD@!;tf?g(P|mjCg7p{ Q9;+SaG0=R');
define('NONCE_KEY',        'OEU@(VTQn+XGwY5Pw|@EGypQ/0#^hhi)Ljcz|4_@@!^OPwmu!PMhO4vKH2|4$khE');
define('AUTH_SALT',        '*:+%_M#0UlVn~5k|NmiY:{KFkNJx4> oQ-.]z7y;EeZzJ1~GT <U&`F=CO+we(_a');
define('SECURE_AUTH_SALT', 'a<[#+bfd$3xl]a8dEDv{G7jDG!y+kK.DA/{PjD6%#jbTx*:*}|,9}n6g[:zhl>AH');
define('LOGGED_IN_SALT',   '8{(Y$=juS&|~>%|N;I~  kT)HWq%9?UCnG| Z]3229HVlCBPXEs>iQKD;3&klG]h');
define('NONCE_SALT',       'l.iU*osXK|?BD$fkHDXg(^`XYqufU[4La*6uA>@QuPFs?t=<%p%9gW4|sh7e1|.K');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
