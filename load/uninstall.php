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

    }

?>