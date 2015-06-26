<?php
/*
* install.php
* @package [MOD] Temp de Vol
* @author Snipe <santory@websantory.net>
* @version 0.2
*	created		: 07/01/2007
*/

if (!defined('IN_SPYOGAME')) {
    die("Hacking attempt");
}

$is_ok = false;
$mod_folder = "tempsvols";
$is_ok = install_mod($mod_folder);
if ($is_ok == true)
	{
		// Si besoin de creer des tables, à faire ici
	}
else
	{
		echo  "<script>alert('Désolé, un problème a eu lieu pendant l'installation, corrigez les problèmes survenue et réessayez.');</script>";
	}
?>