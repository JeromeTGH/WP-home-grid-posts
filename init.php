<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Définition des constantes du plugin
	define('JTGH_WPHGP_PREFIX', 'JTGH_WPHGP_');
	define('JTGH_WPHGP_PAGE_TITLE', 'WP Home Grid Posts');
	// define('JTGH_WPHGP_NONCE_BASE', 'JTGH_WPHGP_');
	define('JTGH_WPHGP_SHORTCODE_NAME', 'JTGH_WPHGP');

	// Mise en mémoire tampon des données qui suivront (hormis headers)
	ob_start();

	// Scripts à lancer
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/fonctions.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/install.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/uninstall.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/pages_manager.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/menu_admin.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/shortcode-handler.php');
	require(JTGH_WPHGP_ROOT_DIRECTORY.'/load/add-css-and-js.php');

?>