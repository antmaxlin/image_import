<?php
	if ((isset($_POST['url'])) && ($_POST['url'] != '')) {
		$url = $_POST['url'];
		$filename = basename($url);
		$img = 'upload/'.$filename;
		if (file_put_contents($img, file_get_contents($url))) {
			print "Upload Successful";	
		}
	 } else {
		 print "Upload Failed";
	 }
?>