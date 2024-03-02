<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_GET = stripslashes_deep($_GET);
    $_POST = stripslashes_deep($_POST);


    // Récupération du "numéro" de message à afficher, si il y a
    $app_msg = '';
    if(isset($_GET['appmsg'])){
        $app_msg = intval($_GET['appmsg']);
    }
    if($app_msg == 1) { ?>
        <div class="JTGH_WPHGP_notice_success">Mise à jour des catégories réussie !</div> <?php
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
                <div class="JTGH_WPHGP_notice_alert">Données manquante (form), désolé...</div>
            <?php	
            exit;
        }

        echo 'Categories choisies = '.$_POST['JTGH_WPHGP_categories_choisies'].'<br><br><br>';

    }







    // echo json_encode($arr);      php array to json, par exemple




    // Requête pour lister les catégories
    $rqt_listing_categories = "SELECT T.term_id, T.name
    FROM `wp_terms` AS T
    LEFT JOIN `wp_term_taxonomy` AS X ON T.term_id = X.term_id
    WHERE X.taxonomy = 'category'
    ORDER BY T.name ASC";

    $resultat_listing_categories = $wpdb->get_results($rqt_listing_categories);

?>
<h1>CATÉGORIES (plugin WPHGP)</h1>
<hr />
<br>
<form method="post" action="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_categories'; ?>&action=updateCat">
    <?php
        wp_nonce_field(JTGH_WPHGP_PREFIX.'updateCat');
    ?>
    <div class="JTGH_WPHGP_categories_layout">
        <h2 style="grid-area: cat_title_gauche;" class="JTGH_WPHGP_texte_align_center">Ensemble des catégories</h2>
        <select style="grid-area: cat_select_gauche;" class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_source" id="JTGH_WPHGP_categories_source" multiple>
            <?php
                foreach($resultat_listing_categories as $row) {
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
        </select>
        <div style="grid-area: cat_btn_up_down;" class="JTGH_WPHGP_center_div_column">
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↑')">↑</button>
            <button type="button" class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↓')">↓</button>
        </div>
    </div>
    <br>
    <br>
    <input type="hidden" value="" name="JTGH_WPHGP_categories_choisies" id="JTGH_WPHGP_categories_choisies" />
    <div class="JTGH_WPHGP_categories_bottom_btns">
        <button class="JTGH_WPHGP_cat_btn_bascul" type="button" onClick="window.location.reload();">Annuler les modifications non enregistrées</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" type="button" onclick="JTGH_WPHGP_unselect_all_cat()">Tout désélectionner dans les listes</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnUpdateCategories">Enregistrer toutes les modifications</button>
    </div>
</form>