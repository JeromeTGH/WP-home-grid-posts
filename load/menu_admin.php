<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "admin menu"
    add_action('admin_menu', 'JTGH_WPHGP_genere_menu_admin');

    // Fonction de génération du menu admin
    function JTGH_WPHGP_genere_menu_admin() {

        // Menu principal
        $menu_title = JTGH_WPHGP_PAGE_TITLE;
        $callback = JTGH_WPHGP_PREFIX.'page_accueil';
        $main_slug = JTGH_WPHGP_PREFIX.'page_accueil';
            $page_title = $menu_title.' - '.JTGH_WPHGP_PAGE_TITLE;
            $capability = 'manage_options';
            $icon_url = plugins_url('images/logo_wp_purple_20x20.png', JTGH_WPHGP_ROOT_FILE);
            add_menu_page($page_title, $menu_title, $capability, $main_slug, $callback, $icon_url);

        // Sous-menu #1
        $parent_slug = $main_slug;
        $submenu_title = 'Accueil';
        $submenu_slug = $main_slug;
        $callback = JTGH_WPHGP_PREFIX.'page_accueil';
            $page_title = $menu_title.' - '.JTGH_WPHGP_PAGE_TITLE;
            $capability = 'manage_options';
            add_submenu_page($parent_slug, $page_title, $submenu_title, $capability, $submenu_slug, $callback);

        // Sous-menu #2
        $parent_slug = $main_slug;
        $submenu_title = 'Categories';
        $submenu_slug = JTGH_WPHGP_PREFIX.'page_categories';
        $callback = JTGH_WPHGP_PREFIX.'page_categories';
            $page_title = $menu_title.' - '.JTGH_WPHGP_PAGE_TITLE;
            $capability = 'manage_options';
        add_submenu_page($parent_slug, $page_title, $submenu_title, $capability, $submenu_slug, $callback);

    }

?>