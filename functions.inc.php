<?php
/** Allgemeine FUnktionen definieren */
require_once 'functions/genfunc.inc.php';

/* Including the password_compat
 * https://github.com/ircmaxell/password_compat
 * */
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
?>
