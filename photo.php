<?php
require("header.php");
$nickname = $_REQUEST["nickname"];
if ($nickname == ""){
	echo '<p>oops! looks like you forgot to choose a username! please <a href="/commonality">go back</a> and try again.</p>'; 
}else{
	checkNick($nickname);
	$query = 'SELECT particID FROM users WHERE nickname = "'.$nickname.'";';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$particID = $row['particID'];
	}

	$submitted = $_REQUEST["tag"];

	if ($submitted == 1){	
		$oldphotoID = $_REQUEST["photoID"];
		$recoList = $_REQUEST['recoTag'];
		$particID = $_REQUEST['particID'];
		//*** SUBMIT CHOSEN TAGS TO THE DB ***//
		$query = 'DELETE FROM chosenTags WHERE particID = "'.$particID.'" AND flickrID = "'.$oldphotoID.'" AND tagged = 0;';
		//echo $query;
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		for($i=0; $i < count($recoList); $i++){
			$query = 'INSERT INTO chosenTags(particID, flickrID, tagID, tagged) VALUES("'.$particID.'", "'.$oldphotoID.'", "'.$recoList[$i].'", "1")';
			$result = mysql_query($query) or die('<p>tagging failed. please <a href="photo.php?nickname='.$nickname.'">refresh the page</a></p>Query failed: ' . mysql_error());
		}
		$query = 'SELECT * FROM `chosenTags` NATURAL LEFT JOIN users WHERE particID = '.$particID.' AND tagged = 0;';
		$result = mysql_query($query) or die('<p>tagging failed. please <a href="photo.php?nickname='.$nickname.'">refresh the page</a></p>Query failed: ' . mysql_error());
		$resultCount = mysql_num_rows($result);
		echo '<p>tags added. '.$resultCount.' photos to go! please continue:</p>';
	}	

	//*** FIRST ACCESS: PICK A RANDOM PHOTO ***//
	$query = 'SELECT * FROM chosenTags WHERE particID = "'.$particID.'" GROUP BY flickrID;';
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$resultCount = mysql_num_rows($result);
	if($resultCount < 10){
		$query = 'SELECT * FROM photos NATURAL LEFT JOIN connect WHERE exper = 1 GROUP BY flickrID ORDER BY rand() LIMIT 10;';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_assoc($result)) {
			$photoID = $row['flickrID'];
			$query = 'INSERT INTO chosenTags(particID, flickrID, tagged) VALUES("'.$particID.'", "'.$photoID.'", "0")';
			//echo $query;
			$result2 = mysql_query($query) or die('Query failed: ' . mysql_error());
		}
	}
	//*** GET A RANDOM PHOTO FROM THE USER'S EXPERIMENT LIST ***//
	$query =  'SELECT * FROM chosenTags WHERE (particID = "'.$particID.'" AND tagged = 0) GROUP BY flickrID ORDER BY rand() LIMIT 1';
	//echo $query;
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$resultCount = mysql_num_rows($result);
	if($resultCount == 0){
		echo '<p>you\'ve tagged all 10 photos! please continue on to the <a href="survey.php">survey</a> &raquo;</p>';
	}else{
		while ($row = mysql_fetch_assoc($result)) {
			//echo print_r($row);
			$photoID = $row['flickrID'];
			if (isset($_REQUEST['testphoto'])){$photoID = $_REQUEST['testphoto'];}
		}

		//*** GET MEDIUM PHOTO URL ***//
		$photoURL = $f->photos_getSizes($photoID);
		//echo print_r($photoURL);
		foreach ($photoURL as $photoSize){
			$sizes = $photoSize['label'];
			if ($sizes == "Medium"){
				$singleURL = $photoSize['source'];
			}
		}
		$query = 'SELECT showExisting, tagSet FROM users WHERE particID = "'.$particID.'";';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_assoc($result)) {
			$showExisting = $row['showExisting'];
			$tagSet = $row['tagSet'];
		}
		?>

		<div id="tags">
			<h4>potential tags</h4>
			<ul>
				<form method="GET" action="photo.php">
					<?php if($tagSet == 1){ 
						displayExistingChoices($photoID); 
					}else{ 
						displayRecommended($photoID); 
					}
					?>
					<li><input type="checkbox" name="recoTag[]" value="none" /><label for="none">(none of these)</label></li>
				</ul>
				<input type="hidden" name="nickname" value="<?php echo $nickname; ?>" />
				<input type="hidden" name="photoID" value="<?php echo $photoID; ?>" />
				<input type="hidden" name="particID" value="<?php echo $particID; ?>" />
				<input type="hidden" name="tag" value="1" />
				<button type="submit" class="tagbutton"><img src="tick.png" />tag</button>
			</form>
		</div>

		<div id="photo">

			<img src="<?php echo $singleURL; ?>" />
		</div>

		<?php

	if($showExisting == 1){ ?>
		<div id="existingTags">
			<h4>existing tags:</h4>
			<p id="existingTags"><?php displayExistingTags($photoID); ?></p>
		</div>
		<?php }else{ ?>
			<div id="existingTags">
				<p>please choose tags from the list --><br /><br /></p>
			</div>
			<?php
	} 
} 
}
?>
	<?php require("footer.php"); ?>