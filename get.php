<?php
	if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
		require_once ('config/config_dev.php'); //dev
	} else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
		require_once ('config/config_OVH.php');
	} else {
		require_once ('config/config.php'); //prod
	} 

	$table = get_exist('table');
	$sexe = get_exist('sexe');
	if(!($table && $sexe)) {
		echo "variables manquantes";
		exit();
	}

	$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
	$select_req = $bdd->prepare("SELECT annee, salaire FROM " . $table . " WHERE age = 0 AND sexe = '" . $sexe . "'");
	//$select_req = $bdd->prepare("SELECT salaire FROM " . $table . " WHERE age = 0 AND sexe = '" . $sexe . "'");
	$select_req->execute();

	$result = $select_req->fetchall(PDO::FETCH_NUM);
	echo json_encode($result);
	exit();


	function get_exist($value) {
		if(isset($_GET[$value]) && ($_GET[$value] != '')) {
			return $_GET[$value];
		} else {
			return false;
		}
	}
	function viz($array) {
		echo "<pre>";
		var_export($array);
		echo "</pre>";
	}
?>