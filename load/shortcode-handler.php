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



        

        // Requête pour lister les catégories
        $rqt_recup_articles_par_categorie = "SELECT P.ID, P.post_title, PM.meta_value
        FROM `wp_posts` AS P
        LEFT JOIN `wp_term_relationships` AS RS ON RS.object_id = P.ID
        LEFT JOIN `wp_term_taxonomy` AS TT ON TT.term_taxonomy_id = RS.term_taxonomy_id
        LEFT JOIN `wp_terms` AS T ON T.term_id = TT.term_id
        LEFT JOIN `wp_postmeta` AS PM ON PM.post_id = P.ID
        WHERE P.post_type = 'post'
        AND P.post_status = 'publish'
        AND T.term_id = 5
        AND PM.meta_key = '_thumbnail_id'
        ORDER BY P.post_date_gmt DESC";
        $resultat_recup_articles_par_categorie = $wpdb->get_results($rqt_recup_articles_par_categorie);

        $rqt_recupere_lien_image_article = "SELECT P.guid, P.post_content
        FROM `wp_posts` AS P
        WHERE P.ID = ".$resultat_recup_articles_par_categorie[0]->meta_value;
        $resultat_recupere_lien_image_article = $wpdb->get_results($rqt_recupere_lien_image_article);


        // print_r($resultat_recup_articles_par_categorie);
        // echo '<br><br>';
        // print_r($resultat_recupere_lien_image_article);
        // echo '<br><br>';





        // Génération des tabs
        $code_html_a_retourner .= '<div class="JTGH_WPHGP_all_posts_container">';
        $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_posts_container">';
        $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_container">';
        $code_html_a_retourner .= '<img src="'.$resultat_recupere_lien_image_article[0]->guid.'" alt="'.$resultat_recupere_lien_image_article[0]->post_content.'" />';
        $code_html_a_retourner .= '<div>Titre</div>';
        $code_html_a_retourner .= '<div>Texte</div>';
        $code_html_a_retourner .= '<div>Lire plus...</div>';
        $code_html_a_retourner .= '</div>';
        $code_html_a_retourner .= '</div>';
        $code_html_a_retourner .= '</div>';






        // Retour HTML du shortcode
        return $code_html_a_retourner;

    }

?>