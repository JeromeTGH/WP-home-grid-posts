<?php
	// Protection contre accès directs
	if (!defined('ABSPATH'))
		exit;
?>
<div class="JTGH_WPHGP_header_navigation">
	<span>Navigation : </span>
	<a href="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_accueil'; ?>">Accueil</a>
	<a href="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_categories'; ?>">Catégories</a>
	<a href="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_couleurs'; ?>">Couleurs</a>
	<a href="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_parametres'; ?>">Paramètres</a>
	<a href="admin.php?page=<?php echo JTGH_WPHGP_PREFIX.'page_import_export'; ?>">Import/Export</a>
</div>