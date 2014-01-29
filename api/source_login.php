<?php
 	if ((isset($_GET['source'])) && ($_GET['source']!="")) {
		$source = $_GET['source'];
		$current_url = explode("?", $_SERVER['REQUEST_URI']);
		$current_url = 'http://'.$_SERVER['HTTP_HOST'].$current_url[0];
		if ($source == "facebook") {
			require_once("../facebook-sdk/facebook.php");
			require_once ("../image_im_config.php");
			  $config = array(
				  'appId' => $fb_appId,
				  'secret' => $fb_secret,
				  'cookie' => true,
				  'fileUpload' => false, // optional
				  'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
			  );
			
			  $facebook = new Facebook($config);
			  $user_id = $facebook->getUser();
			  if($user_id) {
				  try {
					  $user_profile = $facebook->api('/me');
					  $end = 1;
				  } catch(FacebookApiException $e) {
					  error_log($e);
					  $user_id = null;
				  }
			  }
	    }
		if ($end!=1) {
		  $current_url=$current_url."?source=facebook";
		  $login_url = $facebook->getLoginUrl( array( 'scope' => 'user_photos', 'redirect_uri' => $current_url) );
		  header("Location: ".$login_url);
		  die();
		} else {
			?>
		  <script>
          close();
          </script>
          <?php
		}
	} else {
?>
<script>
	close();
</script>
<?php
	}
?>