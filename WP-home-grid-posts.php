<?php

	/*
		Plugin Name: WP-home-grid-posts
		Plugin URI: https://github.com/JeromeTGH/WP-home-grid-posts
		Description: Permet de générer une grille d'articles, triables par catégorie, à faire afficher sur la homepage
		Author: Jérôme TOMSKI
		Version: 1.0.0
		Author URI: https://github.com/JeromeTGH
	*/

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

	// Mémorisation de l'adresse du script et de son répertoire courant de lancement
	define('JTGH_WPHGP_ROOT_FILE', __FILE__);
	define('JTGH_WPHGP_ROOT_DIRECTORY', dirname(__FILE__));

	// Script d'initialisation plugin
	require_once(JTGH_WPHGP_ROOT_DIRECTORY.'/init.php');

?>