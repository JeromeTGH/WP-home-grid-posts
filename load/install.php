<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "activation hook"
    register_activation_hook(JTGH_WPHGP_ROOT_FILE, 'JTGH_WPHGP_install');

    // Fonction d'installation
    function JTGH_WPHGP_install() {

        // Vérifie si le plugin est bien actif, au moment de l'installation
        $pluginName = basename(JTGH_WPHGP_ROOT_FILE, ".php").'/'.basename(JTGH_WPHGP_ROOT_FILE);    // = xxx/xxx.php       
        if (is_plugin_active($pluginName)) {
            wp_die("is_plugin_active");
        }
    
        // Création/enregistrement des options
        JTGH_write_option('activation_date', time());






        // Enregistrement du "nbre d'éléments à afficher par page dans tableau" (dans la table des options de la BDD de WP)
        $WPHGP_show_limit = get_option(JTGH_WPHGP_OPTION_PREFIX.'show_limit');
        if($WPHGP_show_limit != false)
            update_option(JTGH_WPHGP_OPTION_PREFIX.'show_limit', 100);
        else
            add_option(JTGH_WPHGP_OPTION_PREFIX.'show_limit', 100);

        // Enregistrement de "ce sur quoi sera fait le tri, lors d'un affichage tableau" (dans la table des options de la BDD de WP)
        $WPHGP_sort_by = get_option(JTGH_WPHGP_OPTION_PREFIX.'sort_by');
        if($WPHGP_sort_by != false)
            update_option(JTGH_WPHGP_OPTION_PREFIX.'sort_by', 'id');
        else
            add_option(JTGH_WPHGP_OPTION_PREFIX.'sort_by', 'id');

        // Enregistrement du "sens de tri, lors d'un affichage tableau" (dans la table des options de la BDD de WP)
        $WPHGP_sort_direction = get_option(JTGH_WPHGP_OPTION_PREFIX.'sort_direction');
        if($WPHGP_sort_direction != false)
            update_option(JTGH_WPHGP_OPTION_PREFIX.'sort_direction', 'desc');
        else
            add_option(JTGH_WPHGP_OPTION_PREFIX.'sort_direction', 'desc');


        // Création de la table, si inexsitante
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $create_table_rqt = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.JTGH_WPHGP_BDD_TBL_NAME." (
            `id` INT NOT NULL AUTO_INCREMENT,
            `shortcode` VARCHAR(128) NOT NULL,
            `htmlCode` LONGTEXT NOT NULL,
            `bActif` TINYINT NOT NULL,
            PRIMARY KEY (`id`)
        ) ".$charset_collate;
        $wpdb->query($create_table_rqt);

    }

?>