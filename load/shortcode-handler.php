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

        // Chargement des options qui nous intéresse
        $tblCategoriesChoisies = json_decode(JTGH_read_option('categories_a_afficher'));
        $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));
        $bAfficherMetadonnees = json_decode(JTGH_read_option('afficher_metadonnees'));

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
            $code_html_a_retourner .= JTGH_WPHGP_get_category_bloc_content(0, $nombre_d_articles_a_afficher, $bAfficherMetadonnees);

            // Autres catégories (scan $tblCategoriesChoisies)
            foreach($tblCategoriesChoisies as $categoriesChoisie) {
                $code_html_a_retourner .= JTGH_WPHGP_get_category_bloc_content($categoriesChoisie, $nombre_d_articles_a_afficher, $bAfficherMetadonnees);
            }

        $code_html_a_retourner .= '</div>';

        // Retour HTML du shortcode
        return $code_html_a_retourner;

    }


    // Fonction permettant la génération du bloc de contenu, pour une catégorie donnée (ou pas de catégorie, si cat_id=0)
    function JTGH_WPHGP_get_category_bloc_content($cat_id, $nombre_d_articles_a_afficher, $bAfficherMetadonnees) {

        global $wpdb;

        // Initialise la variable de retour
        $code_html_a_retourner = '';

        // Requête pour lister tous les articles d'une catégorie donnée (ou de tout l'ensemble, le cas échéant)
        if($cat_id == 0) {
            $rqt_recup_articles_par_categorie = "SELECT P.ID, P.post_title, P.post_content, P.post_date_gmt, PM.meta_value AS PM_meta_value, UM.meta_value AS UM_meta_value
            FROM `wp_posts` AS P
            LEFT JOIN `wp_term_relationships` AS RS ON RS.object_id = P.ID
            LEFT JOIN `wp_term_taxonomy` AS TT ON TT.term_taxonomy_id = RS.term_taxonomy_id
            LEFT JOIN `wp_terms` AS T ON T.term_id = TT.term_id
            LEFT JOIN `wp_postmeta` AS PM ON PM.post_id = P.ID
            LEFT JOIN `wp_usermeta` AS UM ON UM.user_id = P.post_author
            WHERE P.post_type = 'post'
            AND P.post_status = 'publish'
            AND PM.meta_key = '_thumbnail_id'
            AND UM.meta_key = 'nickname'
            ORDER BY P.post_date_gmt DESC
            LIMIT $nombre_d_articles_a_afficher";
        } else {
            $rqt_recup_articles_par_categorie = "SELECT P.ID, P.post_title, P.post_content, P.post_date_gmt, PM.meta_value AS PM_meta_value, UM.meta_value AS UM_meta_value
            FROM `wp_posts` AS P
            LEFT JOIN `wp_term_relationships` AS RS ON RS.object_id = P.ID
            LEFT JOIN `wp_term_taxonomy` AS TT ON TT.term_taxonomy_id = RS.term_taxonomy_id
            LEFT JOIN `wp_terms` AS T ON T.term_id = TT.term_id
            LEFT JOIN `wp_postmeta` AS PM ON PM.post_id = P.ID
            LEFT JOIN `wp_usermeta` AS UM ON UM.user_id = P.post_author
            WHERE P.post_type = 'post'
            AND P.post_status = 'publish'
            AND T.term_id = $cat_id
            AND PM.meta_key = '_thumbnail_id'
            AND UM.meta_key = 'nickname'
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
                WHERE P.ID = ".$resultat_recup_articles_par_categorie[$i]->PM_meta_value;
                $resultat_recupere_lien_image_article = $wpdb->get_results($rqt_recupere_lien_image_article);

                // Génère les données
                $resultat_recupere_lien_image_article = $wpdb->get_results($rqt_recupere_lien_image_article);
                $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_container">';
                    if(count($resultat_recupere_lien_image_article) == 0) {
                        $code_html_a_retourner .= '<img src="" alt="Aucune image" />';
                    } else {
                        $code_html_a_retourner .= '<img src="'.$resultat_recupere_lien_image_article[0]->guid.'" alt="'.$resultat_recupere_lien_image_article[0]->post_content.'" />';
                    }
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_title">'.$resultat_recup_articles_par_categorie[$i]->post_title.'</div>';
                    if($bAfficherMetadonnees) {
                        $datetime_de_creation = strtotime($resultat_recup_articles_par_categorie[$i]->post_date_gmt);
                        $date_de_creation = date('d/m/Y', $datetime_de_creation);
                        $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_meta">';
                        $code_html_a_retourner .= '<svg width="0.7rem" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="m148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48h-352c-26.5 0-48-21.5-48-48v-352c0-26.5 21.5-48 48-48h48v-52c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128v-52c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346v-298h-352v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"/></svg>';
                        $code_html_a_retourner .= 'Publié le&nbsp;';
                        $code_html_a_retourner .= '<strong>'.$date_de_creation.'</strong>';
                        $code_html_a_retourner .= '&nbsp;&nbsp;';
                        $code_html_a_retourner .= '<svg width="0.7rem" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="m224 256c70.7 0 128-57.3 128-128s-57.3-128-128-128-128 57.3-128 128 57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7c-74.2 0-134.4 60.2-134.4 134.4v41.6c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>';
                        // $code_html_a_retourner .= 'Écrit par&nbsp;';
                        $code_html_a_retourner .= '<span>'.$resultat_recup_articles_par_categorie[$i]->UM_meta_value.'</span>';
                        $code_html_a_retourner .= '</div>';
                    }
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_extract">'.JTGH_WPHGP_post_extract($resultat_recup_articles_par_categorie[$i]->post_content, 35).'</div>';
                    $code_html_a_retourner .= '<div class="JTGH_WPHGP_category_post_link"><a href="'.get_permalink($resultat_recup_articles_par_categorie[$i]->ID).'?pseSrc=home">Lire plus...</a></div>';
                $code_html_a_retourner .= '</div>';
            }
            if($cat_id == 0) {
                $code_html_a_retourner .= '<div class="JTGH_WPHGP_grid_next_row">→&nbsp;<a href="/articles/">Voir tous les articles</a></div>';
            } else {
                $code_html_a_retourner .= '<div class="JTGH_WPHGP_grid_next_row">→&nbsp;<a href="'.get_term_link($cat_id).'">Voir tous les articles de cette catégorie</a></div>';
            }
        $code_html_a_retourner .= '</div>';

        // Retourne les données
        return $code_html_a_retourner;
    }

?>