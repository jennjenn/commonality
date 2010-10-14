<?php
require_once('connect.php');

$apiKey = "48792291a85b6bb9c45c7ba86372e720";
$apiSecret ="e519a126d6de492a";


// Create new phpFlickr object
$f = new phpFlickr($apiKey,$apiSecret);


//*** GET EXISTING TAGS FOR THAT PHOTO ***//
function displayExistingTags($photoID){
	$query = 'SELECT * FROM photos NATURAL LEFT JOIN connect WHERE flickrID = "'. $photoID.'"';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$tagID = $row['tagID'];
		$query = 'SELECT * FROM tags WHERE tagID = "'. $tagID.'"';
		$result2 = mysql_query($query) or die('Query failed: ' . mysql_error()); 
		while ($row2 = mysql_fetch_assoc($result2)) {
			//echo print_r($row2);
			$isMachine = $row2['tagType'];
			if($isMachine != "machine"){
				$rawTag = $row2['rawTag'];
				echo $rawTag . ', ';
			}
		}
	}
}

//*** GET RECOMMENDED TAGS FOR THAT PHOTO ***//
function displayRecommended($photoID){
	$query = 'SELECT * FROM relevance NATURAL LEFT JOIN tags NATURAL LEFT JOIN photos WHERE flickrID = "'. $photoID .'" AND tagType NOT LIKE "nonEng" AND tagType NOT LIKE "machine" ORDER BY `relevance`.`score` DESC LIMIT 15';
	//echo $query;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$rawTag = $row['rawTag'];
		$tagID = $row['tagID'];
		echo '<li><input type="checkbox" name="recoTag[]" value="'.$tagID.'" /><label for="'.$tagID.'">'. $rawTag . '</label></li>';
	}

}

function displayExistingChoices($photoID){
	$query = 'SELECT * FROM photos NATURAL LEFT JOIN connect WHERE flickrID = "'. $photoID.'"';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$tagID = $row['tagID'];
		$query = 'SELECT * FROM tags WHERE tagID = "'. $tagID.'"';
		$result2 = mysql_query($query) or die('Query failed: ' . mysql_error()); 
		$i = 1;
		while ($row2 = mysql_fetch_assoc($result2)) {
			//echo print_r($row2);
			$isMachine = $row2['tagType'];
			if($isMachine != "machine"){
				$rawTag = $row2['rawTag'];
				//echo $rawTag . ', ';
				echo '<li><input type="checkbox" name="recoTag[]" value="'.$tagID.'" /><label for="'.$tagID.'">'. $rawTag . '</label></li>';
			}
		}
	}
}
//echo $query;


//*** ADD NICKNAME TO DB ***//
function addNickname($nickname, $showExisting, $tagSet){
	$query = 'INSERT INTO users(nickname,showExisting,tagSet) VALUES ("'.$nickname.'", "'.$showExisting.'", "'.$tagSet.'");';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
}


//*** GET PARTICIPANT INFO FROM DB ***//
function getParticID($nickname){
	$query = 'SELECT particID FROM users WHERE nickname = "'.$nickname.'" LIMIT 1;';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$particID = $row['particID'];
		//echo $particID;
	}
}

//*** CHECK FOR NICKNAME DATA ***//
function checkNick($nickname){
	$query = 'SELECT * FROM users WHERE nickname = "'.$nickname.'";';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$resultCount = mysql_num_rows($result);
	if ($resultCount > 0){
		//echo '<p>username taken. please <a href="/commonality">go back</a> and select a different name.</p>';
		while ($row = mysql_fetch_assoc($result)) {
			//echo print_r($row);
			$showExisting = $row['showExisting'];
			$tagSet = $row['tagSet'];
			$particID = $row['particID'];
		}
	}else{
		$showExisting = rand(0,1);
		//$showExisting = 0;
		if ($showExisting == 0){$tagSet = rand(0,1); }
		addNickname($nickname, $showExisting, $tagSet);
	}

}


?>