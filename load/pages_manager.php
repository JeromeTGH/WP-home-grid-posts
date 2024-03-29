<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
    
    // ==============
    // Page : ACCUEIL
    // ==============
    function JTGH_WPHGP_page_accueil() {
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/header.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/page_accueil.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/footer.php');
    }

    // =================
    // Page : CATEGORIES
    // =================
    function JTGH_WPHGP_page_categories() {
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/header.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/page_categories.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/footer.php');
    }

    // ===============
    // Page : COULEURS
    // ===============
    function JTGH_WPHGP_page_couleurs() {
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/header.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/page_couleurs.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/footer.php');
    }

    // =================
    // Page : PARAMÈTRES
    // =================
    function JTGH_WPHGP_page_parametres() {
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/header.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/page_parametres.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/footer.php');
    }

    // ====================
    // Page : IMPORT/EXPORT
    // ====================
    function JTGH_WPHGP_page_import_export() {
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/header.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/page_import_export.php');
        require(JTGH_WPHGP_ROOT_DIRECTORY.'/pages_and_sections/footer.php');
    }

?>