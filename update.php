<?php
/*
* update.php
* @package [MOD] Temp de Vol
* @author Snipe <santory@websantory.net>
* @version 0.2
*	created		: 07/01/2007
*/


if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
}

global $db,$table_prefix;
$mod_folder = "tempsvols";
$mod_name = "tempsvols";
update_mod($mod_folder, $mod_name);