<h1>CATÉGORIES (plugin WPHGP)</h1>
<hr />
<br>
<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Tableau qui contiendra les éventuelles catégories sélectionnées
    $tblSelectedCategories = array();

    // Drapeaux des messages à afficher, accessoirement
    $maj_effectuee = false;
    $modifs_annulees = false;

    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnCancelCatModifications'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateCat')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_categories');
            exit;
        }

        // Levée du drapeau correspondant
        $modifs_annulees = true;
        
    }


    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnUpdateCategories'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateCat')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_categories');
            exit;
        }

        // On sort si jamais certains éléments sont manquants
        if(!isset($_POST['JTGH_WPHGP_categories_choisies'])) {
            ?>
                <div class="JTGH_WPHGP_notice_alert">Données manquante (form) ?!</div>
            <?php	
            exit;
        }

        // Récupération de la liste des catégories éventuellement choisies
        $tblSelectedCategories = json_decode($_POST['JTGH_WPHGP_categories_choisies']);

        // Si ce n'est pas un tableau, on quitte
        if(!is_array($tblSelectedCategories)) {
            ?>
                <div class="JTGH_WPHGP_notice_alert">Données non conformes (not an array), dans l'input de transfert ?!</div>
            <?php
            exit;
        }

        // On vérifie que tous les éléments de cette liste sont bien des nombres
        foreach ($tblSelectedCategories as $selectedCategory) {
            if (!is_numeric($selectedCategory)) {
                ?>
                    <div class="JTGH_WPHGP_notice_alert">Données non numériques trouvées, dans l'input de transfert ?!</div>
                <?php
                exit;
            }
        }

        // Enregistrement des nouvelles options
        JTGH_write_option('categories_a_afficher', json_encode($tblSelectedCategories));
        $maj_effectuee = true;

    }

    // (re)chargement de l'option contenant la liste des catégories choisies
    $tblSelectedCategories = json_decode(JTGH_read_option('categories_a_afficher'));

    // Requête pour lister les catégories
    $rqt_listing_categories = "SELECT T.term_id, T.name
    FROM `".$wpdb->prefix."terms` AS T
    LEFT JOIN `".$wpdb->prefix."term_taxonomy` AS X ON T.term_id = X.term_id
    WHERE X.taxonomy = 'category'
    ORDER BY T.name ASC";
    $resultat_listing_categories = $wpdb->get_results($rqt_listing_categories);

    // Création de tableaux d'entrée pour les selects
    $src_tbl = array();
    $dest_tbl = array();

    foreach($tblSelectedCategories as $cat_id) {
        foreach($resultat_listing_categories as $row) {
            $object = new stdClass();
            $object->term_id = $row->term_id;
            $object->name = $row->name;
            if ($row->term_id == $cat_id) {
                $dest_tbl[] = $object;
            }
        }
    }

    foreach($resultat_listing_categories as $row) {
        $object = new stdClass();
        $object->term_id = $row->term_id;
        $object->name = $row->name;
        if (!in_array($row->term_id, $tblSelectedCategories)) {
            $src_tbl[] = $object;
        }
    }
    
?>
<?php
    if($modifs_annulees) {?>
        <div class="JTGH_WPHGP_notice_warning">Modifications non enregistrées annulées !</div> <?php
    }
?>
<?php
    if($maj_effectuee) {?>
        <div class="JTGH_WPHGP_notice_success">Mise à jour effectuée avec succès !</div> <?php
    }
?>
<form method="post" action="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_categories'; ?>">
    <?php
        wp_nonce_field(JTGH_WPHGP_PREFIX.'updateCat');
    ?>
    <div class="JTGH_WPHGP_categories_layout">
        <h2 style="grid-area: cat_title_gauche;" class="JTGH_WPHGP_texte_align_center">Ensemble des catégories</h2>
        <select style="grid-area: cat_select_gauche;" class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_source" id="JTGH_WPHGP_categories_source" multiple>
            <?php
                foreach($src_tbl as $row) {
                    echo '<option value="'.$row->term_id.'">'.$row->name.'</option>';
                }
            ?>
        </select>
        <div style="grid-area: cat_btn_bascul;" class="JTGH_WPHGP_center_div_column">
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>>')">&gt;&gt;</button>
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>')">&gt;</button>
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<')">&lt;</button>
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<<')">&lt;&lt;</button>
        </div>
        <h2 style="grid-area: cat_title_droite;" class="JTGH_WPHGP_texte_align_center">Ensemble à prendre en compte</h2>
        <select style="grid-area: cat_select_droite;" class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_dest" id="JTGH_WPHGP_categories_dest" multiple>
            <?php
                foreach($dest_tbl as $row) {
                    echo '<option value="'.$row->term_id.'">'.$row->name.'</option>';
                }
            ?>    
        </select>
        <div style="grid-area: cat_btn_up_down;" class="JTGH_WPHGP_center_div_column">
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↑')">↑</button>
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↓')">↓</button>
        </div>
    </div>
    <br>
    <br>
    <input type="hidden" value="<?php echo json_encode($tblSelectedCategories); ?>" name="JTGH_WPHGP_categories_choisies" id="JTGH_WPHGP_categories_choisies" />
    <div class="JTGH_WPHGP_categories_bottom_btns">
        <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnCancelCatModifications">Annuler les modifications non enregistrées</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" type="button" onclick="JTGH_WPHGP_unselect_all_cat()">Tout désélectionner dans les listes</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnUpdateCategories">Enregistrer toutes les modifications</button>
    </div>
</form>