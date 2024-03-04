<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Si cliqué sur "export json"
    if (isset($_POST['JTGH_WPHGP_export_json'])) {

        ob_clean();

        // Paramètres de base de l'export
        $output_filename = 'JTGH_WPHGP_export.json';
        $date_du_jour = date("Y-m-d");

        // Chargement des options
        $activation_date = JTGH_read_option('activation_date');
        $shortcode_name = JTGH_read_option('shortcode_name');
        $categories_a_afficher = JTGH_read_option('categories_a_afficher');
        $couleurs_des_categories = JTGH_read_option('couleurs_des_categories');
        $nbre_d_articles_par_page = JTGH_read_option('nbre_d_articles_par_page');
        $nbre_de_colonnes_d_affichage = JTGH_read_option('nbre_de_colonnes_d_affichage');
        $afficher_metadonnees = JTGH_read_option('afficher_metadonnees');
        $longueur_maxi_extract = JTGH_read_option('longueur_maxi_extract');

        // Construction du tableau associatif, qui sera ensuite transformé en objet json
        $donnees_a_enregistrer = [
            'activation_date' => $activation_date,
            'shortcode_name' => $shortcode_name,
            'categories_a_afficher' => $categories_a_afficher,
            'couleurs_des_categories' => $couleurs_des_categories,
            'nbre_d_articles_par_page' => $nbre_d_articles_par_page,
            'nbre_de_colonnes_d_affichage' => $nbre_de_colonnes_d_affichage,
            'afficher_metadonnees' => $afficher_metadonnees,
            'longueur_maxi_extract' => $longueur_maxi_extract
        ];

        // Envoi de l'entête et des données au format JSON
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$date_du_jour.' '.$output_filename);
        echo json_encode($donnees_a_enregistrer);

        // Clotûre de l'envoi
        ob_flush();
        exit;
    }

?>


<h1>IMPORT/EXPORT (plugin WPHGP)</h1>
<hr />
<br>
<form method="post" action="">
    <input type="submit" name="JTGH_WPHGP_export_json" class="JTGH_WPHGP_cat_btn_bascul" value="Exporter les paramètres (au format JSON)" />
</form>