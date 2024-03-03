<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "uninstall hook"
    register_uninstall_hook(JTGH_WPHGP_ROOT_FILE, 'JTGH_WPHGP_uninstall');

    // Fonction de désinstallation
    function JTGH_WPHGP_uninstall() {

        // Effacement des options
        JTGH_delete_option('activation_date');
        JTGH_delete_option('shortcode_name');
        JTGH_delete_option('categories_a_afficher');
        JTGH_delete_option('couleurs_des_categories');
        JTGH_delete_option('nbre_d_articles_par_page');
        JTGH_delete_option('nbre_de_colonnes_d_affichage');
        JTGH_delete_option('afficher_metadonnees');
        JTGH_delete_option('longueur_maxi_extract');
    }

?>