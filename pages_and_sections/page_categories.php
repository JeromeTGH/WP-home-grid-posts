<h2>Liste des catégories (brut)</h2>
<?php

    // Récupération du lien vers la BDD WP
    global $wpdb;

    // Requête pour lister les catégories
    $rqt_listing_categories = "SELECT T.term_id, T.name
    FROM `wp_terms` AS T
    LEFT JOIN `wp_term_taxonomy` AS X ON T.term_id = X.term_id
    WHERE X.taxonomy = 'category'";

    $resultat_listing_categories = $wpdb->get_results($rqt_listing_categories);

    foreach($resultat_listing_categories as $row)
    {
        echo 'ID=<strong>&nbsp;'.esc_attr($row->term_id).'&nbsp;</strong>';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        echo 'NAME=<strong>&nbsp;'.esc_attr($row->name).'&nbsp;</strong>';
        echo '<br>';
    }
    echo '<br>';
    echo '<br>';

?>
<h2>Catégories à prendre en compte</h2>