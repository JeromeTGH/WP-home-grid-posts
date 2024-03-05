<?php 

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
	
    // Ajout du shortcode principal du plugin (les options/paramètres de ce shortcode permettra la sélection du "bon" bloc html à retourner)
    add_shortcode(JTGH_WPHGP_SHORTCODE_NAME, 'JTGH_WPHGP_display_content');     // Pour activer les shortcodes du plugin
    add_filter('widget_text', 'do_shortcode');                                  // Pour activer les shortcodes dans les widgets de type texte


    // Fonction qui retourne le code HTML correspondant au shortcode appelé
    function JTGH_WPHGP_display_content($shortcode_parameters) {

        // Tampon de retour
        $code_html_a_retourner = '';

        // Récupération du lien vers la BDD, pour pouvoir faire des requêtes ensuite
        global $wpdb;

        // Chargement de la liste des catégories choisies, et des couleurs associées
        $tblCategoriesChoisies = json_decode(JTGH_read_option('categories_a_afficher'));
        $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));

        // Tri du tableau des couleurs de catégories, par index
        function JTGH_WPHGP_tri_tableau_couleurs_selon_index($lg_tbl_A, $lg_tbl_B) {
            if ($lg_tbl_A->index < $lg_tbl_B->index) {
                return -1;
            }
            if ($lg_tbl_A->index == $lg_tbl_B->index) {
                return 0;
            }
            if ($lg_tbl_A->index > $lg_tbl_B->index) {
                return 1;
            }
        }
        usort($tblCouleursDesCategories, "JTGH_WPHGP_tri_tableau_couleurs_selon_index");

        // Génération des catégories en entête
        $code_html_a_retourner .= '<ul class="JTGH_WPHGP_cat_container">';
        foreach($tblCouleursDesCategories as $infos_categorie) {
            if($infos_categorie->affichage) {
                $couleur = $infos_categorie->couleur;
                $code_html_a_retourner .= '<li style="border-left: 1rem solid #'.$couleur.';" onclick="JTGH_WPHGP_handleCategoryChange('.$infos_categorie->cat_id.')">'.$infos_categorie->name.'</li>';
            }
        }
        $code_html_a_retourner .= '</ul>';






        






        // Génération des tabs
        $code_html_a_retourner .= '<div>';
        $code_html_a_retourner .= '</div>';








 
        // print_r($tblCouleursDesCategories);
        // echo '<br><br>';
        
        // Retour HTML du shortcode
        return $code_html_a_retourner;

    }

?>