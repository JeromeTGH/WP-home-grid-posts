<?php

    // Récupération du lien vers la BDD WP
    global $wpdb;

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
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>>')">&gt;&gt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>')">&gt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<')">&lt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<<')">&lt;&lt;</button>
    </div>
    <h2 style="grid-area: cat_title_droite;" class="JTGH_WPHGP_texte_align_center">Ensemble à prendre en compte</h2>
    <select style="grid-area: cat_select_droite;" class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_dest" id="JTGH_WPHGP_categories_dest" multiple>
    </select>
    <div style="grid-area: cat_btn_up_down;" class="JTGH_WPHGP_center_div_column">
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↑')">↑</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesUpDown('↓')">↓</button>
    </div>
</div>
<br>
<br>
<div class="JTGH_WPHGP_categories_bottom_btns">
    <button class="JTGH_WPHGP_cat_btn_bascul" onClick="window.location.reload();">Annuler les modifications non enregistrées</button>
    <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_unselect_all_cat()">Tout désélectionner dans les listes</button>
    <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_update_cats()">Enregistrer toutes les modifications</button>
</div>
