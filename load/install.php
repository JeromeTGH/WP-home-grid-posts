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
        JTGH_write_option('shortcode_name', JTGH_WPHGP_SHORTCODE_NAME);
        JTGH_write_option('categories_a_afficher', '[]');
        JTGH_write_option('couleurs_des_categories', '{"0": "'.JTGH_WPHGP_DEFAULT_CATEGORY_COLOR.'"}');

    }

?>