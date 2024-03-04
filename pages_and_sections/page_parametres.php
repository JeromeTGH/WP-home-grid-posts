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

    // Paramètres à afficher
    $nbre_d_articles_par_page = '';
    $nbre_de_colonnes_d_affichage = '';
    $afficher_metadonnees = false;
    $longueur_maxi_extract = '';
    
?>
<?php
    // Drapeaux des messages à afficher, accessoirement
    $maj_effectuee = false;
    $echec_de_maj = false;
    $modifs_annulees = false;
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
                    <td>Nombre d'articles par page = </td>
                    <td><input
                        type="number"
                        id="JTGH_WPHGP_param_nb_articles_par_page"
                        name="JTGH_WPHGP_param_nb_articles_par_page"
                        value="<?php echo $nbre_d_articles_par_page; ?>"
                    /></td>
                </tr>
                <tr>
                    <td>Nombre de colonnes à afficher = </td>
                    <td><input
                        type="number"
                        id="JTGH_WPHGP_param_nb_colonnes_a_afficher"
                        name="JTGH_WPHGP_param_nb_colonnes_a_afficher"
                        value="<?php echo $nbre_de_colonnes_d_affichage; ?>"
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
                    <td>Nombre de caractères maxi à afficher (extract) = </td>
                    <td><input
                        type="number"
                        id="JTGH_WPHGP_param_nb_cars_max_extract"
                        name="JTGH_WPHGP_param_nb_cars_max_extract"
                        value="<?php echo $longueur_maxi_extract; ?>"
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