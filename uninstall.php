<?php
/*
* uninstall.php
* @package [MOD] Temp de Vol
* @author Snipe <santory@websantory.net>
* @version 0.1b
*	created		: 07/01/2007
*/

if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
}

global $db, $table_prefix;
$mod_uninstall_name = "tempsvols";
uninstall_mod ($mod_uninstall_name, $mod_uninstall_table);
?>