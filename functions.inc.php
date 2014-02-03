<?php
/** Allgemeine FUnktionen definieren */
require_once 'functions/genfunc.inc.php';

/* Including the password_compat
 * https://github.com/ircmaxell/password_compat */
require_once 'functions/pwdfunc.inc.php';

/* Alle Funktionen für das Handling mit Login und Sessions sind hier ausgelagert
 * */
require_once 'functions/userfunc.inc.php';

/* Alle Funktionen für das Handling mit Dateien und deren Zielverzeichnis
 *  */
require_once 'functions/filefunc.inc.php';

/* Alle Funktionen für das Handling mit Dateien und deren Zielverzeichnis
 *  */
require_once 'functions/catfunc.inc.php';

/* Alle Funktionen für das Handling mit Modellen */
require_once 'functions/modfunc.inc.php';

/* Alle Funktionen für das Handling mit Logs */
require_once 'functions/logfunc.inc.php';

/* Alle Funktionen für den Download einer Datei */
require_once 'functions/dwndfunc.inc.php';

/* Alle Funktionen für das Handling von Gruppen */
require_once 'functions/grpfunc.inc.php';

/* Funktionen für die Validierung von XML basierten Dateien */
require_once 'functions/vldfunc.inc.php';

/* Funktionen für das Zippen von Ordrnerstrukturen mit Pfaderhaltung*/
require_once 'functions/zipfunc.inc.php';

/* Funktionen für das Zippen von Ordrnerstrukturen mit Pfaderhaltung*/
require_once 'functions/mailfunc.inc.php';

/* Funktionen für die Administration der User */
require_once 'functions/adminfunc.inc.php';

/* Funktionen für die Suche */
require_once 'functions/searchfunc.inc.php';
?>
