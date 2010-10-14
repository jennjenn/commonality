<?php require_once("phpFlickr/phpFlickr.php");

$apiKey = "48792291a85b6bb9c45c7ba86372e720";
$apiSecret ="e519a126d6de492a";

// Create new phpFlickr object
$f = new phpFlickr($apiKey,$apiSecret);

$instList = array("8623220@N02", "24785917@N03", "83979593@N00", "25053835@N03", "26134435@N05", "7167652@N06", "26577438@N06", "26808453@N03", "11334970@N05", "29454428@N08", "30194653@N06", "25786829@N08", "29998366@N02", "30115723@N02", "32300107@N06", "32741315@N06", "32951986@N05", "30835311@N07", "32605636@N06", "31846825@N04", "34586311@N05", "34101160@N07", "34419668@N08", "36038586@N04", "35310696@N04", "37199428@N06");
echo print_r($instList);
	$total = 0;
	$instCount = 0;
foreach ($instList as $currentInst){
	$instCount++;
	echo "<p>$instCount</p>";
$instPhotos = $f->people_getPublicPhotos($currentInst);
//echo print_r($instPhotos);
$count = $instPhotos['photos']['total'];
//echo '<p>'.$count.'</p>';
$total = $total + $count;
}
echo "<p>TOTAL : $total</p>";
?>