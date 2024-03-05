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

        // Génération des catégories en entête
        $code_html_a_retourner .= '<div class="JTGH_WPHGP_entete_categories">';
        $code_html_a_retourner .= '<ul class="JTGH_WPHGP_cat_container">';
            foreach($tblCouleursDesCategories as $infos_categorie) {
                if($infos_categorie->affichage) {
                    $couleur_bordure = JTGH_WPHGP_modifier_luminosite($infos_categorie->couleur, -20);
                    $couleur_fond = JTGH_WPHGP_modifier_luminosite($infos_categorie->couleur, 0);
                    $code_html_a_retourner .= '<li style="background: #'.$couleur_fond.'; border-color: #'.$couleur_bordure.';">'.$infos_categorie->name.'</li>';
                }
            }







        $code_html_a_retourner .= '</ul>';
        $code_html_a_retourner .= '</div>';

 
        print_r($tblCouleursDesCategories);
        echo '<br><br>';
        
        // À développer par la suite
        return $code_html_a_retourner;

    }

?>