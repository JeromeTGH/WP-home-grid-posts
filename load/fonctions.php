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


    // =========================================
    // Fonction : JTGH_WPHGP_modifier_luminosite
    // =========================================
    function JTGH_WPHGP_modifier_luminosite($color_hex_code, $decimal_value) {

        $rouge_dec = hexdec(substr($color_hex_code, 0, 2));
        $vert_dec = hexdec(substr($color_hex_code, 2, 2));
        $bleu_dec = hexdec(substr($color_hex_code, 4, 2));

        $rouge_dec += $decimal_value;
        $vert_dec += $decimal_value;
        $bleu_dec += $decimal_value;

        if($rouge_dec < 0) $rouge_dec = 0;
        if($vert_dec < 0) $vert_dec = 0;
        if($bleu_dec < 0) $bleu_dec = 0;

        if($rouge_dec > 255) $rouge_dec = 255;
        if($vert_dec > 255) $vert_dec = 255;
        if($bleu_dec > 255) $bleu_dec = 255;

        $rouge_hex = sprintf('%02x', $rouge_dec);
        $vert_hex = sprintf('%02x', $vert_dec);
        $bleu_hex = sprintf('%02x', $bleu_dec);
        
        return $rouge_hex.$vert_hex.$bleu_hex;
    }
?>