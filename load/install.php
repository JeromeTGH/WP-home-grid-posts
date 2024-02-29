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

    }

?>