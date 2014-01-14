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

/** prefix of tables */
define ('TBL_PREFIX', 'repo_');

/** own debug check */
define ('DEBUG', TRUE);

/** define max. size of an uploaded file 
 * here 1024*500 are 500 kilobytes. 
 * The value have to be in bytes */
define ('FILESIZE', '512000');

/** Storage for placing the repository files
 * possible options
 * local or remote
 * no trailing slash because of errors!
 * */
define ('STRG_DEST', 'local');
define ('STRG_PATH', 'repository');

/** Example for remote storage:
 *
 * PRECONDITION: a function/class must exists, that can handle this
 * look at http://us2.php.net/manual/de/function.ssh2-sftp.php for sftp/ssh2 
 * connection or http://php.net/manual/de/ref.ftp.php for ftp functions.
 *
 * define ('STRG_DEST', 'remote');
 * define ('STRG_PROT', 'sftp');
 * define ('STRG_HOST', 'IP_ADDRESS');
 * define ('STRG_PORT', 'PORT as Number, eg. 22');
 * define ('STRG_USER', 'SSH-USER');
 * define ('STRG_PASS', 'SSH-PASS');
 * define ('STRG_PATH', 'repository');
 * */

/** PHP Settings */
/** suppress errors with 0, else -1 will display all errors in the apache log 
    * file*/
error_reporting(-1);

/** Timezone */
date_default_timezone_set('Europe/Berlin');
?>
