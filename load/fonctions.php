<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // ============================
    // Fonction : JTGH_read_option
    // ============================
    function JTGH_read_option($nom_de_l_option) {
        return get_option(JTGH_WPHGP_PREFIX.$nom_de_l_option);
    }   

    // ============================
    // Fonction : JTGH_write_option
    // ============================
    function JTGH_write_option($nom_de_l_option, $nouvelle_valeur) {
        update_option(JTGH_WPHGP_PREFIX.$nom_de_l_option, $nouvelle_valeur);
    }

    // =============================
    // Fonction : JTGH_delete_option
    // =============================
    function JTGH_delete_option($nom_de_l_option) {
        delete_option(JTGH_WPHGP_PREFIX.$nom_de_l_option);
    } 

    // =============================
    // Fonction : JTGH_create_option
    // =============================
    function JTGH_create_option($nom_de_l_option, $nouvelle_valeur) {
        delete_option(JTGH_WPHGP_PREFIX.$nom_de_l_option);
        add_option(JTGH_WPHGP_PREFIX.$nom_de_l_option, $nouvelle_valeur);
    }


    // ===============================
    // Fonction : JTGH_test_hexa_color
    // ===============================
    function JTGH_test_hexa_color($hexa_val) {
        $hex_color_pattern = "/^[0-9a-fA-F]{0,8}$/i";
        return preg_match($hex_color_pattern, $hexa_val);
    }
?>