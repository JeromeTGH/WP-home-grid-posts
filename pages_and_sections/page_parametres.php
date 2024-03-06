<h1>PARAMÈTRES (plugin WPHGP)</h1>
<hr />
<br>
<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Drapeaux des messages à afficher, accessoirement
    $maj_effectuee = false;
    $echec_de_maj = false;
    $modifs_annulees = false;

    // Paramètres à afficher
    $nbre_d_articles_par_categorie = 0;
    $afficher_metadonnees = false;
    $nb_mots_maxi_extract = 0;

    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnCancelParamsModifications'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateParameters')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_parametres');
            exit;
        }

        // Levée du drapeau correspondant
        $modifs_annulees = true;
        
    }

    // Traitement des données postées, le cas échéant
    if(isset($_POST) && isset($_POST['btnUpdateParams'])) {

        // Vérification NONCE
        if(!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], JTGH_WPHGP_PREFIX.'updateParameters')) {
            wp_nonce_ays(JTGH_WPHGP_PREFIX.'page_parametres');
            exit;
        }

        // Vérification de la présence des données attendues (input text, hors checkbox)
        if(!isset($_POST['JTGH_WPHGP_param_nb_articles_par_categorie'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur "JTGH_WPHGP_param_nb_articles_par_categorie" manquante...</div><?php
            $echec_de_maj = true;
        }
        if(!isset($_POST['JTGH_WPHGP_param_nb_cars_max_extract'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur "JTGH_WPHGP_param_nb_cars_max_extract" manquante...</div><?php
            $echec_de_maj = true;
        }

        // Vérification du format des données
        if (!is_numeric($_POST['JTGH_WPHGP_param_nb_articles_par_categorie'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur "JTGH_WPHGP_param_nb_articles_par_categorie" non numérique...</div><?php
            $echec_de_maj = true;
        }
        if (!is_numeric($_POST['JTGH_WPHGP_param_nb_cars_max_extract'])) {
            ?><div class="JTGH_WPHGP_notice_alert">Valeur "JTGH_WPHGP_param_nb_cars_max_extract" non numérique...</div><?php
            $echec_de_maj = true;
        }

        // Traitement des données
        if(isset($_POST['JTGH_WPHGP_param_afficher_metadonnees'])) {
            $afficher_metadonnees = true;
        } else {
            $afficher_metadonnees = false;
        }
        $nbre_d_articles_par_categorie = $_POST['JTGH_WPHGP_param_nb_articles_par_categorie'];
        $nb_mots_maxi_extract = $_POST['JTGH_WPHGP_param_nb_cars_max_extract'];

        // Enregistrement des nouvelles données
        JTGH_write_option('nbre_d_articles_par_categorie', $nbre_d_articles_par_categorie);
        JTGH_write_option('afficher_metadonnees', $afficher_metadonnees);
        JTGH_write_option('nb_mots_maxi_extract', $nb_mots_maxi_extract);
        $maj_effectuee = true;

    }
    
?>
<?php
    // Chargement des options
    $nbre_d_articles_par_categorie = JTGH_read_option('nbre_d_articles_par_categorie');
    $afficher_metadonnees = JTGH_read_option('afficher_metadonnees');
    $nb_mots_maxi_extract = JTGH_read_option('nb_mots_maxi_extract');
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
<form method="post" action="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_parametres'; ?>">
    <div class="JTGH_WPHGP_params_layout">
        <?php
            wp_nonce_field(JTGH_WPHGP_PREFIX.'updateParameters');
        ?>
        <table class="JTGH_WPHGP_params_table">
            <tbody>
                <tr>
                    <td>Nombre d'articles par catégorie = </td>
                    <td><input
                        type="number"
                        id="JTGH_WPHGP_param_nb_articles_par_categorie"
                        name="JTGH_WPHGP_param_nb_articles_par_categorie"
                        value="<?php echo $nbre_d_articles_par_categorie; ?>"
                    /></td>
                </tr>
                <tr>
                    <td>Afficher les métadonnées (coché=oui) ? </td>
                    <td><input
                        type="checkbox"
                        id="JTGH_WPHGP_param_afficher_metadonnees"
                        name="JTGH_WPHGP_param_afficher_metadonnees"
                        <?php echo($afficher_metadonnees ? 'checked' : ''); ?>
                    /></td>
                </tr>
                <tr>
                    <td>Nombre de mots maxi à afficher (extrait d'article) = </td>
                    <td><input
                        type="number"
                        id="JTGH_WPHGP_param_nb_cars_max_extract"
                        name="JTGH_WPHGP_param_nb_cars_max_extract"
                        value="<?php echo $nb_mots_maxi_extract; ?>"
                    /></td>
                </tr>
            </tbody>
        </table>
        <div class="JTGH_WPHGP_params_bottom_btns">
            <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnCancelParamsModifications">Annuler les modifications non enregistrées</button>
            <button class="JTGH_WPHGP_cat_btn_bascul" type="submit" name="btnUpdateParams">Enregistrer toutes les modifications</button>
        </div>
    </div>
</form>