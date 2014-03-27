<?php
	ini_set("auto_detect_line_endings", true);
	
	if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
		require_once ('../config/config_dev.php'); //dev
	} else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
		require_once ('../config/config_OVH.php');
	} else {
		require_once ('../config/config.php'); //prod
	} 

	$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
	parse_sexe_age($bdd);

	function parse_sexe_age($bdd) {
		$insert_req = $bdd->prepare("INSERT INTO age_sexe (annee, sexe, age, salaire) VALUES (:an, :se, :ag, :sa)");

		$dir_name = "../data_insee/A) Salaires nets 1950-2010/";
		$file = fopen($dir_name."CSV_03 - sexe age.csv", 'r+');		
		$line = fgets($file);
		$count = 2;
		while($line = fgets($file)) {

			$age = [0, 1820, 2125, 2630, 3140, 4150, 5160, 6165, 6599];
			$explode_line = explode(';', $line);

			if(++$count == 3) {
				$count = 0;
				$annee = $explode_line[0];
			}

			for ($i=2; $i <= 10; $i++) { 
				$salaire = filter_var($explode_line[$i], FILTER_SANITIZE_NUMBER_INT);
				$insert_req->execute(array("an" => $annee, "se" => strtolower($explode_line[1][0]), "ag" => $age[$i-2], "sa" => $salaire));
				viz(array("an" => $annee, "se" => strtolower($explode_line[1][0]), "ag" => $age[$i-2], "sa" => $salaire));
			}
			echo "<p>End Tuple</p>";
		}


	}



	function viz($array) {
		echo "<pre>";
		var_export($array);
		echo "</pre>";
	}
?>