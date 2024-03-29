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

        // Création d'une liste de couleurs "vide"
        $liste_de_couleurs = array();
        $all_categories_color = new stdClass();
        $all_categories_color->cat_id = 0;
        $all_categories_color->name = 'Tout';
        $all_categories_color->couleur = JTGH_WPHGP_DEFAULT_CATEGORY_COLOR;
        $all_categories_color->affichage = true;
        $all_categories_color->index = 0;
        $liste_de_couleurs[] = $all_categories_color;
    
        // Création/enregistrement des options
        JTGH_create_option('activation_date', time());
        JTGH_create_option('shortcode_name', JTGH_WPHGP_SHORTCODE_NAME);
        JTGH_create_option('categories_a_afficher', '[]');
        JTGH_create_option('couleurs_des_categories', json_encode($liste_de_couleurs));
        JTGH_create_option('nbre_d_articles_par_categorie', 12);
        JTGH_create_option('afficher_metadonnees', true);
        JTGH_create_option('nb_mots_maxi_extract', 35);

    }

?>