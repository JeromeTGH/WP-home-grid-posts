<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // ============================
    // Fonction : JTGH_read_option
    // ============================
    function JTGH_read_option($nom_de_l_option) {
        return get_option(JTGH_WPHGP_OPTION_PREFIX.$nom_de_l_option);
    }   

    // ============================
    // Fonction : JTGH_write_option
    // ============================
    function JTGH_write_option($nom_de_l_option, $nouvelle_valeur) {
        // Lecture préalable, pour voir si cette n'option n'existe pas déjà en base
        $retour_lecture_option = get_option(JTGH_WPHGP_OPTION_PREFIX.$nom_de_l_option);

        // On créé cette nouvelle option si elle n'existe pas en base, où la met à jour si elle existe déjà
        if($retour_lecture_option != false)
            update_option(JTGH_WPHGP_OPTION_PREFIX.$nom_de_l_option, $nouvelle_valeur);
        else
            add_option(JTGH_WPHGP_OPTION_PREFIX.$nom_de_l_option, $nouvelle_valeur);
    }

    // =============================
    // Fonction : JTGH_delete_option
    // =============================
    function JTGH_delete_option($nom_de_l_option) {
        delete_option(JTGH_WPHGP_OPTION_PREFIX.$nom_de_l_option);
    } 

?>