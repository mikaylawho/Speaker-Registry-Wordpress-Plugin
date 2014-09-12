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
define('DB_NAME', 'phpstorm');

/** MySQL database username */
define('DB_USER', 'phpstorm');

/** MySQL database password */
define('DB_PASSWORD', 'phpstorm');

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
define('AUTH_KEY',         'z+[0RKMN{s%BO{yB~MONwN+/`l-[Py7gTm WQXPo/zDeU#KAh=4O<N8-Ux,MO]ST');
define('SECURE_AUTH_KEY',  '*,N-WEd||lY<[2Y:/?q<VDuY2k,x+={,O5]BA9+:&wVvPw4g)n+k.f,N7Ej&+mPe');
define('LOGGED_IN_KEY',    '8fA8m}?~J:d%bP<*|:h.v7jOrh>Af]A6!x,`wK9?{eUafqxVFOhe-d}A+[i:J3D ');
define('NONCE_KEY',        'M4 8nY||]avZRBxR,8.5*H^D JLQz-Ve[YbRYaFeKZCJ;$Q6-tY&I5vgt#;}|[0G');
define('AUTH_SALT',        'zvk<]auyF#+ jRQ(X(eBCP^p[6g6~&ys%PEu-W+v5b&R+nnl*y?6oFtC1(~oe!~^');
define('SECURE_AUTH_SALT', 'n!Gmxc+wxjlO;`okKUUODeZ#[cF0+4;x&)Ei)X,v%yQev6y1=mzhFi|O-RCx}t]q');
define('LOGGED_IN_SALT',   '@!e1d0/eP_y]/+BxWY]sx!Xg>y$2Jkc8 +!!i jdZJ&I-Q1Fk,qfup:Dds?W2#|u');
define('NONCE_SALT',       'P=S.%Z8H-cV*|8n;A!d*~]^_WSefB{/#|$I|=|^h|wdF[z+|IKze|}=9{;jtUk=q');

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
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
