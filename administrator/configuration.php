<?php
/**  MySQL settings */
/** Name of the database */
define('DB_NAME', 'prorepo');

/** database user */
define('DB_USER', 'prorepo');

/** database password*/
define('DB_PASSWORD', 'jeB6KVM6Vm8LseMS');

/** location of mysql-service */
define('DB_HOST', 'localhost');

/** some database settings when creating tables */
define('DB_CHARSET', 'utf8');

define('DB_COLLATE', '');

/** PHP Settings */
// Fehlermeldungen unterdrücken
error_reporting( -1 );
//  
// Erzwingen das Session-Cookies benutzt werden und die SID nicht per URL 
// transportiert wird
ini_set( 'session.use_only_cookies', '1' );
ini_set( 'session.use_trans_sid', '0' );
?>
