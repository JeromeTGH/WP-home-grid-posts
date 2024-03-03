<h1>COULEURS (plugin WPHGP)</h1>
<hr />
<br>
<?php

	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;

    // Récupération des canaux qui nous seront utiles ici
    global $wpdb;
    $_POST = stripslashes_deep($_POST);

    // Récupération de la liste des couleurs de catégories, enregistrée dans les options de la bdd
    $tblCouleursDesCategories = json_decode(JTGH_read_option('couleurs_des_categories'));








    


    echo json_encode($tblCouleursDesCategories);






?>