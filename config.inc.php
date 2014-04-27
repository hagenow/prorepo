<?php
/**  MySQL settings */
/** Name of the database */
define('DB_NAME', 'prorepo');

/** database user */
define('DB_USER', 'prorepo');

/** database password*/
define('DB_PASSWORD', 'wAnUvpCSWv44DF4r');

/** location of mysql-service */
define('DB_HOST', 'localhost');

/** prefix of tables */
define ('TBL_PREFIX', 'repo_');

/** tmp folder */
define ('TMP', 'tmp/');

/** path ( folder ) */
define ('PATHINFO', '/prorepo/');

/** own debug check */
define ('DEBUG', FALSE);

/** own debug check */
define ('ADMINMAIL', 'hagenowh@tf.uni-freiburg.de');

/** define max. size of an uploaded file 
 * here 2GiB
 * The value have to be in bytes */
define ('FILESIZE', '2048000000');

/** Schemas for XML checking */
define ('XESSchema' , 'schemas/xes.xsd');
define ('MXMLSchema' , 'schemas/WorkflowLog.xsd');
define ('PNMLSchema' , 'schemas/validatePNMLDocument.sh');
define ('PNMLReport' , 'schemas/pnmlValidationReport.html');

/** Storage for placing the repository files
 * possible options
 * local or remote
 * no trailing slash because of errors!
 * */
define ('STRG_DEST', 'local');
define ('STRG_PATH', 'repository');

/** PHP Settings */
/** suppress errors with 0, else -1 will display all errors in the apache log 
 * file*/
ini_set('display_errors', 0);

/** Timezone */
date_default_timezone_set('Europe/Berlin');
?>
