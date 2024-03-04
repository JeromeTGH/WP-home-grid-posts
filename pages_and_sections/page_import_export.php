<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Si cliqué sur "export json"
    if (isset($_POST['JTGH_WPHGP_export_json'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'importOrExport')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'import_or_export');
            exit;
        }

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
<?php

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Drapeaux des messages à afficher, accessoirement
    $import_demande = false;
    $echec_de_l_import = false;

    // Si cliqué sur "import json"
    $donnees_a_importer = '';
    if (isset($_POST['JTGH_WPHGP_import_json'])) {

        $import_demande = true;

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'importOrExport')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'import_or_export');
            exit;
        }

        // Vérification de la présence des données attendues (input text, hors checkbox)
        if(!isset($_POST['JTGH_WPHGP_data_json'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur "JTGH_WPHGP_data_json" manquante...</div><?php
            $echec_de_l_import = true;
        }

        // Vérification si la zone de données n'est pas vide
        if(!$echec_de_l_import && $_POST['JTGH_WPHGP_data_json'] == '') {
            ?><div class="JTGH_WPHGP_notice_alert">Merci de bien vouloir remplir la zone de données, avant importation</div><?php
            $echec_de_l_import = true;
        }

        // Mise en forme (sous tableau associatif), des données reçues
        if(!$echec_de_l_import) {

            // Renvoi des données vers la textarea
            $donnees_a_importer = $_POST['JTGH_WPHGP_data_json'];

            // Chargement des données JSON dans un tableau associatif PHP
            $tableau_de_donnees_a_importer = json_decode($_POST['JTGH_WPHGP_data_json'], true);

            // Vérification si le tableau contient des lignes ou pas
            if(!is_array($tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Aucun champ présent, dans les données passées pour l'import ?!</div><?php
                $echec_de_l_import = true;
            }
        }

        if(!$echec_de_l_import) {

            // Vérification de la présence de tous les champs de données attendus
            if (!array_key_exists('activation_date', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "activation_date" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('shortcode_name', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "shortcode_name" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('categories_a_afficher', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "categories_a_afficher" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('couleurs_des_categories', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "couleurs_des_categories" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('nbre_d_articles_par_page', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "nbre_d_articles_par_page" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('nbre_de_colonnes_d_affichage', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "nbre_de_colonnes_d_affichage" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('afficher_metadonnees', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "afficher_metadonnees" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }
            if (!array_key_exists('longueur_maxi_extract', $tableau_de_donnees_a_importer)) {
                ?><div class="JTGH_WPHGP_notice_alert">Champ "longueur_maxi_extract" manquant dans les données à importer</div><?php
                $echec_de_l_import = true;
            }

        }

        if(!$echec_de_l_import) {

            // Extraction des données
            $activation_date = $tableau_de_donnees_a_importer['activation_date'];
            $shortcode_name = $tableau_de_donnees_a_importer['shortcode_name'];
            $categories_a_afficher = $tableau_de_donnees_a_importer['categories_a_afficher'];
            $couleurs_des_categories = $tableau_de_donnees_a_importer['couleurs_des_categories'];
            $nbre_d_articles_par_page = $tableau_de_donnees_a_importer['nbre_d_articles_par_page'];
            $nbre_de_colonnes_d_affichage = $tableau_de_donnees_a_importer['nbre_de_colonnes_d_affichage'];
            $afficher_metadonnees = $tableau_de_donnees_a_importer['afficher_metadonnees'];
            $longueur_maxi_extract = $tableau_de_donnees_a_importer['longueur_maxi_extract'];

            // Suppression des données présentes en base
            JTGH_delete_option('activation_date');
            JTGH_delete_option('shortcode_name');
            JTGH_delete_option('categories_a_afficher');
            JTGH_delete_option('couleurs_des_categories');
            JTGH_delete_option('nbre_d_articles_par_page');
            JTGH_delete_option('nbre_de_colonnes_d_affichage');
            JTGH_delete_option('afficher_metadonnees');
            JTGH_delete_option('longueur_maxi_extract');

            // Enregistrement des nouvelles données
            JTGH_create_option('activation_date', $activation_date);
            JTGH_create_option('shortcode_name', $shortcode_name);
            JTGH_create_option('categories_a_afficher', $categories_a_afficher);
            JTGH_create_option('couleurs_des_categories', $couleurs_des_categories);
            JTGH_create_option('nbre_d_articles_par_page', $nbre_d_articles_par_page);
            JTGH_create_option('nbre_de_colonnes_d_affichage', $nbre_de_colonnes_d_affichage);
            JTGH_create_option('afficher_metadonnees', $afficher_metadonnees);
            JTGH_create_option('longueur_maxi_extract', $longueur_maxi_extract);

        }

    }

?>
<?php
    if($import_demande && !$echec_de_l_import) {?>
        <div class="JTGH_WPHGP_notice_success">Import effectué avec succès !</div> <?php
    }
?>
<?php
    if($import_demande && $echec_de_l_import) {?>
        <div class="JTGH_WPHGP_notice_alert">Echec de l'importation...</div> <?php
    }
?>
<form method="post" action="">
    <?php
        wp_nonce_field(JTGH_WPHGP_PREFIX.'importOrExport');
    ?>
    <div class="JTGH_WPHGP_import_export_layout">
        <input type="submit" name="JTGH_WPHGP_export_json" class="JTGH_WPHGP_cat_btn_bascul" value="Exporter les paramètres (au format JSON)" />
        <textarea name="JTGH_WPHGP_data_json" class="JTGH_WPHGP_import_textarea" rows="15"><?php echo $donnees_a_importer; ?></textarea>
        <input type="submit" name="JTGH_WPHGP_import_json" class="JTGH_WPHGP_cat_btn_bascul" value="Importer ces paramètres (au format JSON)" onclick="return JTGH_WPHGP_confirm_btn()" />
    </div>
</form>