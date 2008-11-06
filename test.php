<?php


@mysql_connect ('localhost','root','');
@mysql_select_db ('l2jdb');
	
function mapRegionTable() {
	global $regions;
	
	$sql = 'SELECT "plop", region, sec0, sec1, sec2, sec3, sec4, sec5, sec6, sec7, sec8, sec9, sec10 FROM mapregion;';
	
	$rslt = mysql_query($sql);
	
	while ($row = mysql_fetch_row($rslt)) {
		$region = $row[1];
		for ($j = 0; $j < 10; $j++)
			$regions[$j][$region] = $row[($j + 2)];
	}
}

function getMapRegion($posX, $posY) {
	global $regions;
	return $regions[getMapRegionX($posX)][getMapRegionY($posY)];
}

function getMapRegionX($posX) {
	return ($posX >> 15) + 4;
}

function getMapRegionY($posY) {
	return ($posY >> 15) + 10;
}


mapRegionTable();


$towns = array(
	0  => array(-84176, 243382, -3126),		// Talking Island
	1  => array(45525, 48376, -3059),		// Elven Village
	2  => array(12181, 16675, -4580),		// DE Village
	3  => array(-45232, -113603, -224),		// Orc Village
	4  => array(115074, -178115, -880),		// Dwarven Village
	5  => array(-14138, 122042, -2988),		// Gludio Castle Town
	6  => array(-82856, 150901, -3128),		// Gludin Village
	7  => array(18823, 145048, -3126),		// Dion Castle Town
	8  => array(81236, 148638, -3469),		// Giran Castle Town
	9 => array(80853, 54653, -1524),		// Town of Oren
	10 => array(147391, 25967, -2012),		// Town of Aden
	11 => array(117163, 76511, -2712),		// Hunter Village
	13 => array(111381, 219064, -3543),		// Heine
	14 => array(43894, -48330, -797),		// Rune Castle Town
	15 => array(148558, -56030, -2781),		// Goddard
	16 => array(87331, -142842, -1317),		// Schuttgart
	17 => array(18823, 145048, -3126),		// Floran Village
	18 => array(10468, -24569, -3645),		// Primeval Isle
	19 => array(-118092, 46955, 360),		// Kamael Village
	21 => array(-58752, -56898, -2032)		// Fantasy Isle
);

foreach ($towns as $id => $town) {
	echo $id.' '.' '.getMapRegion($town[0],$town[1]).'<br />';
}

?>
