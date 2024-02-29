<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "admin enqueue scripts"
    add_action('admin_enqueue_scripts', 'JTGH_WPHGP_add_css_and_js_scripts');

    function JTGH_WPHGP_add_css_and_js_scripts() {
        
        // Ajout du CSS
        wp_register_style('JTGH_WPHGP_style', plugins_url('css/style.css', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_style('JTGH_WPHGP_style');
                        
        // Ajout du JS 
        wp_register_script('JTGH_WPHGP_js_script', plugins_url('js/script.js', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_script('JTGH_WPHGP_js_script');

    }

?>