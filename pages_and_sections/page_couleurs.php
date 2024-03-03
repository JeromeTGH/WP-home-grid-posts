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

?>
<?php

    // Drapeaux des messages à afficher, accessoirement
    $maj_effectuee = false;
    $echec_de_maj = false;
    $modifs_annulees = false;

    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnCancelColorModifications'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateColors')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_couleurs');
            exit;
        }

        // Levée du drapeau correspondant
        $modifs_annulees = true;
        
    }


    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnUpdateCatColors'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateColors')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_couleurs');
            exit;
        }

        // Vérification de la présence des données attendues
        if(!isset($_POST['JTGH_WPHGP_hex_code_0'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur JTGH_WPHGP_hex_code_<?php echo '0'; ?> manquante...</div><?php
            $echec_de_maj = true;
        }
        foreach($tblCategoriesChoisies as $cat_choisie_id) {
            if(!isset($_POST['JTGH_WPHGP_hex_code_'.$cat_choisie_id])) {
                ?><div class="JTGH_WPHGP_notice_alert">Valeur JTGH_WPHGP_hex_code_<?php echo $cat_choisie_id; ?> manquante...</div><?php
                $echec_de_maj = true;
            }
        }
        
        // Test la validité des données transmises
        if(!JTGH_test_hexa_color($_POST['JTGH_WPHGP_hex_code_0'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur JTGH_WPHGP_hex_code_<?php echo '0'; ?>="<?php echo $_POST['JTGH_WPHGP_hex_code_0']; ?>" non conforme...</div><?php
            $echec_de_maj = true;
        }
        foreach($tblCategoriesChoisies as $cat_choisie_id) {
            if(!JTGH_test_hexa_color($_POST['JTGH_WPHGP_hex_code_'.$cat_choisie_id])) {
                ?><div class="JTGH_WPHGP_notice_alert">Valeur JTGH_WPHGP_hex_code_<?php echo $cat_choisie_id; ?>="<?php echo $_POST['JTGH_WPHGP_hex_code_'.$cat_choisie_id]; ?>" non conforme...</div><?php
                $echec_de_maj = true;
            }
        }

        if(!$echec_de_maj) {
            // Mise à jour de la liste actuelle des couleurs de catégories
            for($i=0 ; $i < count($tblCouleursDesCategories); $i++) {
                if(isset($_POST['JTGH_WPHGP_hex_code_'.$tblCouleursDesCategories[$i]->cat_id])) {
                    $tblCouleursDesCategories[$i]->couleur = $_POST['JTGH_WPHGP_hex_code_'.$tblCouleursDesCategories[$i]->cat_id];
                }
            }
    
            // Mise à jour en BDD, et retéléchargement des données
            JTGH_write_option('couleurs_des_categories', json_encode($tblCouleursDesCategories));
            $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));
        }

        $maj_effectuee = true;

    }

?>
<?php

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

?>
<?php
    /*

    // Pour débug, si besoin
    echo '<br>';
    echo '<strong>Tableau des couleurs</strong> = '.json_encode($tblCouleursDesCategories).'<br>';
    echo '<strong>Catégories sélectionnées</strong> = '.json_encode($tblCategoriesChoisies).'<br>';
    echo '<strong>Tableau à afficher</strong> = '.json_encode($tableau_des_couleurs_de_categories_a_afficher).'<br>';
    echo '<br>';
    echo '<br>';

    */
?>
<?php
    if($modifs_annulees) {?>
        <div class="JTGH_WPHGP_notice_warning">Modifications non enregistrées annulées !</div> <?php
    }
?>
<?php
    if($maj_effectuee && !$echec_de_maj) {?>
        <div class="JTGH_WPHGP_notice_success">Mise à jour effectuée avec succès !</div> <?php
    }
?>
<?php
    if($maj_effectuee && $echec_de_maj) {?>
        <div class="JTGH_WPHGP_notice_alert">Echec de la mise à jour...</div> <?php
    }
?>

<form method="post" action="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_couleurs'; ?>">
    <div class="JTGH_WPHGP_colors_layout">
        <?php
            wp_nonce_field(JTGH_WPHGP_PREFIX.'updateColors');
        ?>
        <table class="JTGH_WPHGP_colors_table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Catégorie</th>
                    <th>Code HEXA</th>
                    <th>Aperçu couleur</th>
                <tr>
            </thead>
            <tbody>
                <?php
                    for($idx_ligne=0 ; $idx_ligne < count($tableau_des_couleurs_de_categories_a_afficher); $idx_ligne++) {
                ?> 
                <tr>
                    <td><?php echo $idx_ligne+1; ?></td>
                    <td><?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->name; ?></td>
                    <td>#<input
                        type="text"
                        id="JTGH_WPHGP_hex_code_<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->cat_id; ?>"
                        name="JTGH_WPHGP_hex_code_<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->cat_id; ?>"
                        value="<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->couleur; ?>"
                        onKeyDown="JTGH_WPHGP_mem_bloc_couleur(event)"
                        onKeyUp="JTGH_WPHGP_maj_bloc_couleur(event)"
                        alt="<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->cat_id; ?>"
                    /></td>
                    <td><span id="JTGH_WPHGP_color_bloc_<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->cat_id; ?>" class="JTGH_WPHGP_bloc_apercu_couleur" style="background: #<?php echo $tableau_des_couleurs_de_categories_a_afficher[$idx_ligne]->couleur; ?>">&nbsp;</span></td>
                <tr>
                <?php
                    }
                ?> 
            </tbody>
        </table>
        <div class="JTGH_WPHGP_colors_bottom_btns">
            <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnCancelColorModifications">Annuler les modifications non enregistrées</button>
            <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnUpdateCatColors">Enregistrer toutes les modifications</button>
        </div>
    </div>
</form>