<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Si cliqué sur "export json"
    if (isset($_POST['JTGH_WPHGP_export_json'])) {
        ob_clean();
        $output_filename = 'JTGH_WPHGP_export.json';
        $donnees_a_enregistrer = ['param1' => 42, 'param2' => 'test'];      // Pour test de création objet json = {"param1":42,"param2":"test"})
        $date_du_jour = date("Y-m-d");
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$date_du_jour.' '.$output_filename);
        echo json_encode($donnees_a_enregistrer);
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