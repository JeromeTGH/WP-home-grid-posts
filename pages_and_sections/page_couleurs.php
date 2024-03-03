<h1>COULEURS (plugin WPHGP)</h1>
<hr />
<br>
<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Récupération de la liste des couleurs de catégories, enregistrée dans les options de la bdd
    $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));

    // Récupération de la liste des catégories choisies, enregistrée dans les options de la bdd
    $tblCategoriesChoisies = json_decode(JTGH_read_option('categories_a_afficher'));

    // Parcours des catégories choisies, pour voir si elles ont toutes une couleur assignée (sinon, on leur met celle de base, par défaut)
    $modif_sur_liste_de_couleurs = false;
    foreach($tblCategoriesChoisies as $cat_choisie_id) {
        $couleur_trouvee = false;
        foreach($tblCouleursDesCategories as $couleur_avec_categorie) {
            if($couleur_avec_categorie->cat_id == $cat_choisie_id) {
                $couleur_trouvee = true;
            }
        }
        if(!$couleur_trouvee) {
            $modif_sur_liste_de_couleurs = true;
            $new_categories_color = new stdClass();
            $new_categories_color->cat_id = $cat_choisie_id;
            $new_categories_color->name = '';
            $new_categories_color->couleur = JTGH_WPHGP_DEFAULT_CATEGORY_COLOR;
            $new_categories_color->affichage = true;
            $tblCouleursDesCategories[] = $new_categories_color;
        }
    }

    // S'il y a des catégories déselectionnées, alors on les "masque"
    $nom_de_categorie_manquant = false;
    for($i=0 ; $i < count($tblCouleursDesCategories); $i++) {
        if(!in_array($tblCouleursDesCategories[$i]->cat_id, $tblCategoriesChoisies) && $tblCouleursDesCategories[$i]->cat_id != 0) {
            $modif_sur_liste_de_couleurs = true;
            $tblCouleursDesCategories[$i]->affichage = false;
        }
        if($tblCouleursDesCategories[$i]->name == '') {
            $nom_de_categorie_manquant = true;
        }
    }
    
    // S'il y a des catégories dont on n'a pas déjà enregistré le nom, alors on les récupère
    if($nom_de_categorie_manquant) {

        // Requête pour lister les catégories en BDD
        $rqt_listing_categories = "SELECT T.term_id, T.name
        FROM `wp_terms` AS T
        LEFT JOIN `wp_term_taxonomy` AS X ON T.term_id = X.term_id
        WHERE X.taxonomy = 'category'
        ORDER BY T.name ASC";
        $resultat_listing_categories = $wpdb->get_results($rqt_listing_categories);
    
        // Renseignement des noms
        for($i=0 ; $i < count($tblCouleursDesCategories); $i++) {
            if($tblCouleursDesCategories[$i]->name == '') {
                $modif_sur_liste_de_couleurs = true;

                foreach($resultat_listing_categories as $row) {
                    if($row->term_id == $tblCouleursDesCategories[$i]->cat_id) {
                        $tblCouleursDesCategories[$i]->name = $row->name;
                    }
                }
            }
        }
    }
    
    // Si la liste des couleurs de catégories a été modifiée, alors on enregistre les changements, et on la recharge
    if($modif_sur_liste_de_couleurs) {
        JTGH_write_option('couleurs_des_categories', json_encode($tblCouleursDesCategories));
        $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));
    }


    // Création d'un tableau d'affichage
    $tableau_des_couleurs_de_categories_a_afficher = array();
    foreach($tblCouleursDesCategories as $couleur_avec_categorie) {
        if($couleur_avec_categorie->cat_id == 0) {
            $categorie_coloree_a_afficher = new stdClass();
            $categorie_coloree_a_afficher->cat_id = 0;
            $categorie_coloree_a_afficher->name = $couleur_avec_categorie->name;
            $categorie_coloree_a_afficher->couleur = $couleur_avec_categorie->couleur;
            $tableau_des_couleurs_de_categories_a_afficher[] = $categorie_coloree_a_afficher;
        }
    }
    foreach($tblCategoriesChoisies as $cat_choisie_id) {
        foreach($tblCouleursDesCategories as $couleur_avec_categorie) {
            if($couleur_avec_categorie->cat_id == $cat_choisie_id) {
                $categorie_coloree_a_afficher = new stdClass();
                $categorie_coloree_a_afficher->cat_id = $cat_choisie_id;
                $categorie_coloree_a_afficher->name = $couleur_avec_categorie->name;
                $categorie_coloree_a_afficher->couleur = $couleur_avec_categorie->couleur;
                $tableau_des_couleurs_de_categories_a_afficher[] = $categorie_coloree_a_afficher;
            }
        }
    }






    echo '<br>';
    echo '<strong>Tableau des couleurs</strong> = '.json_encode($tblCouleursDesCategories).'<br>';
    echo '<strong>Catégories sélectionnées</strong> = '.json_encode($tblCategoriesChoisies).'<br>';
    echo '<strong>Tableau à afficher</strong> = '.json_encode($tableau_des_couleurs_de_categories_a_afficher).'<br>';






?>