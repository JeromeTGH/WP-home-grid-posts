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
    <div class="JTGH_WPHGP_center_div">
        <h2>Ensemble des catégories</h2>
        <select class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_source" id="JTGH_WPHGP_categories_source" multiple>
            <?php
                foreach($resultat_listing_categories as $row) {
                    echo '<option value="'.$row->term_id.'">'.$row->name.'</option>';
                }
            ?>
        </select>
    </div>
    <div class="JTGH_WPHGP_center_div">
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>>')">&gt;&gt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('>')">&gt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<')">&lt;</button>
        <button class="JTGH_WPHGP_cat_btn_bascul" onclick="JTGH_WPHGP_handleCategoriesBasc('<<')">&lt;&lt;</button>
    </div>
    <div class="JTGH_WPHGP_center_div">
        <h2>Ensemble à prendre en compte</h2>
        <select class="JTGH_WPHGP_select_cat_layout" name="JTGH_WPHGP_categories_dest" id="JTGH_WPHGP_categories_dest" multiple>
        </select>
    </div>
</div>