<?php require('header.php'); ?>

<div id="indextext">
	<p><em>commonality is an experimental system using data from the <a href="http://flickr.com/commons">flickr commons</a>.</em></p>
	<p><em><u>it's pretty simple</u>: you'll be given a series of <strong>10 photos</strong>, each with a list of tags. select the tags you think should be associated with that photo and click the "tag" button. that's it!</em></p>
	<p><em>you can add as many or as few tags as you want. there are no right or wrong answers.</em></p>
	
	<form method="GET" action="photo.php"><p>all set? <br />first, give yourself a nickname: <input type="text" class="nickname" name="nickname" /></p>
	<p><button type="submit" class="nicknamebutton">take me to the photos &raquo;</button></p>
	<p class="moreinfo"><a href="info.php">(or click here to get some more info)</p>
</form>
</div>

<?php require('footer.php'); ?>