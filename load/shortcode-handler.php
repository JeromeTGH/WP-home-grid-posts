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


        // Récupération de certains paramètres du plugin
        $nombre_de_mots_a_afficher_au_maximum = 35;
        $nombre_d_articles_a_afficher = 12;
        

        // Génération des tabs
        $code_html_a_retourner .= '<div class="JTGH_WPHGP_all_posts_container">';

            // Catégorie "0" (tout, en fait)
            $code_html_a_retourner .= JTGH_WPHGP_get_category_bloc_content(0, $nombre_d_articles_a_afficher);

            // Autres catégories (scan $tblCategoriesChoisies)
            foreach($tblCategoriesChoisies as $categoriesChoisie) {
                $code_html_a_retourner .= JTGH_WPHGP_get_category_bloc_content($categoriesChoisie, $nombre_d_articles_a_afficher);
            }

        $code_html_a_retourner .= '</div>';

        // Retour HTML du shortcode
        return $code_html_a_retourner;

    }


    // Fonction permettant la génération du bloc de contenu, pour une catégorie donnée (ou pas de catégorie, si cat_id=0)
    function JTGH_WPHGP_get_category_bloc_content($cat_id, $nombre_d_articles_a_afficher) {

        global $wpdb;

        // Initialise la variable de retour
        $code_html_a_retourner = '';

        // Requête pour lister tous les articles d'une catégorie donnée (ou de tout l'ensemble, le cas échéant)
        if($cat_id == 0) {
            $rqt_recup_articles_par_categorie = "SELECT P.ID, P.post_title, P.post_content, PM.meta_value
            FROM `wp_posts` AS P
            LEFT JOIN `wp_term_relationships` AS RS ON RS.object_id = P.ID
            LEFT JOIN `wp_term_taxonomy` AS TT ON TT.term_taxonomy_id = RS.term_taxonomy_id
            LEFT JOIN `wp_terms` AS T ON T.term_id = TT.term_id
            LEFT JOIN `wp_postmeta` AS PM ON PM.post_id = P.ID
            WHERE P.post_type = 'post'
            AND P.post_status = 'publish'
            AND PM.meta_key = '_thumbnail_id'
            ORDER BY P.post_date_gmt DESC
            LIMIT $nombre_d_articles_a_afficher";
        } else {
            $rqt_recup_articles_par_categorie = "SELECT P.ID, P.post_title, P.post_content, PM.meta_value
            FROM `wp_posts` AS P
            LEFT JOIN `wp_term_relationships` AS RS ON RS.object_id = P.ID
            LEFT JOIN `wp_term_taxonomy` AS TT ON TT.term_taxonomy_id = RS.term_taxonomy_id
            LEFT JOIN `wp_terms` AS T ON T.term_id = TT.term_id
            LEFT JOIN `wp_postmeta` AS PM ON PM.post_id = P.ID
            WHERE P.post_type = 'post'
            AND P.post_status = 'publish'
            AND T.term_id = $cat_id
            AND PM.meta_key = '_thumbnail_id'
            ORDER BY P.post_date_gmt DESC
            LIMIT $nombre_d_articles_a_afficher";
        }
        $resultat_recup_articles_par_categorie = $wpdb->get_results($rqt_recup_articles_par_categorie);

        // Génère les données appropriées, complètes
        if($cat_id == 0) {
            $code_html_a_retourner .= '<div id="JTGH_WPHGP_category_number_'.$cat_id.'" class="JTGH_WPHGP_category_posts_container">';
        } else {
            $code_html_a_retourner .= '<div style="display: none;" id="JTGH_WPHGP_category_number_'.$cat_id.'" class="JTGH_WPHGP_category_posts_container">';
        }
            for($i=0 ; $i < count($resultat_recup_articles_par_categorie); $i++) {
                // Requête pour récupérer l'url de l'image de l'article correspondant
                $rqt_recupere_lien_image_article = "SELECT P.guid, P.post_content
                FROM `wp_posts` AS P
                WHERE P.ID = ".$resultat_recup_articles_par_categorie[$i]->meta_value;
                $resultat_recupere_lien_image_article = $wpdb->get_results($rqt_recupere_lien_image_article);

                // Génère les données
                $resultat_recupere_lien_image_article = $wpdb->get_results($rqt_recupere_lien_image_article);
                $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_container">';
                    $code_html_a_retourner .= '<img src="'.$resultat_recupere_lien_image_article[0]->guid.'" alt="'.$resultat_recupere_lien_image_article[0]->post_content.'" />';
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_title">'.$resultat_recup_articles_par_categorie[$i]->post_title.'</div>';
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_extract">'.JTGH_WPHGP_post_extract($resultat_recup_articles_par_categorie[$i]->post_content, 35).'</div>';
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_link"><a href="'.get_permalink($resultat_recup_articles_par_categorie[$i]->ID).'?pseSrc=home">Lire plus...</a></div>';
                $code_html_a_retourner .= '</div>';
            }
        $code_html_a_retourner .= '</div>';

        // Retourne les données
        return $code_html_a_retourner;
    }

?>