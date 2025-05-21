<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '' );

/** Database username */
define( 'DB_USER', '' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '_b0<-k59]H@CH4I]LPS$|w21e5A3Q9:Hl;uCrZ5F<7%o5?OM<nXTT2Zg0$.epp,F' );
define( 'SECURE_AUTH_KEY',  '^7Q X{N$/<^C]QWm+&:BFh(y&ZxFngU#m0C^q1!~6A#)V2CBW =CPd}f1;r{#r(G' );
define( 'LOGGED_IN_KEY',    '/$V:DRWFVtAI|l59M//!Jxq]c HU?#W,*8w8)vk[TB*PolSipYVZDv=w;YV`D|qF' );
define( 'NONCE_KEY',        'WdwFBiJ:@UXr=Nx|_.QqenReh9k3f`ho~.A_<@/7ZZ@+OgG%rNc]L]S;h)$TBrk~' );
define( 'AUTH_SALT',        'g-E@*CLDrJFXx0jmx(<v0=vW;ksr+I|M.8Q)gw|R8ahyQ0lfu^DH:[$2|iI,d9l.' );
define( 'SECURE_AUTH_SALT', 'q](z=z@c~N?a7INe!bU=KRACv74BDX^0RpUTg5*5KS]>1Lv9n-nNTS*Qtu>q+WFn' );
define( 'LOGGED_IN_SALT',   '_ek)g^bld2/fY-.AU}:erg1pCg`b6qWX>nc`EQU<!VDG/-[4hg{H}h n4Uy.GGUj' );
define( 'NONCE_SALT',       'pAvgyu$ADeb,6AAxVr<xT21!Yg*iqcvZ~g6b>cR2D,K|Yv9BNY^M_ndv,F+Q={qk' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', 1);
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );




/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('ALLOW_UNFILTERED_UPLOADS', true);