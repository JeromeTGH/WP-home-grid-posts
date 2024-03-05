<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Enregistrement "admin enqueue scripts"
    add_action('admin_enqueue_scripts', 'JTGH_WPHGP_add_backend_css_and_js_scripts');
    add_action('wp_enqueue_scripts', 'JTGH_WPHGP_add_frontend_css_and_js_scripts');
     

    // Partie back-end
    function JTGH_WPHGP_add_backend_css_and_js_scripts() {
        
        // Ajout du CSS
        wp_register_style('JTGH_WPHGP_backend_style', plugins_url('css/JTGH_WPHGP_backend_style.css', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_style('JTGH_WPHGP_backend_style');
                        
        // Ajout du JS 
        wp_register_script('JTGH_WPHGP_backend_js_script', plugins_url('js/JTGH_WPHGP_backend_script.js', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_script('JTGH_WPHGP_backend_js_script');

    }

    // Partie front-end
    function JTGH_WPHGP_add_frontend_css_and_js_scripts() {
        
        // Ajout du CSS
        wp_register_style('JTGH_WPHGP_frontend_style', plugins_url('css/JTGH_WPHGP_frontend_style.css', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_style('JTGH_WPHGP_frontend_style');
                        
        // Ajout du JS 
        wp_register_script('JTGH_WPHGP_frontend_js_script', plugins_url('js/JTGH_WPHGP_frontend_script.js', JTGH_WPHGP_ROOT_FILE));
        wp_enqueue_script('JTGH_WPHGP_frontend_js_script');

    }


?>