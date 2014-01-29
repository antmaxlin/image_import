<?php 
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
        $albums = $facebook->api('/me/albums', 'GET');
		if ((isset($_POST['album_id'])) && ($_POST['album_id']!='')) {
			$album_id = $_POST['album_id'];
			$url = '/'.$album_id.'/photos';
			$photos = $facebook->api($url, 'GET');
			echo '<ul class="list filelist thumbnails">';
			foreach($photos['data'] as $key=>$value ){
				$name = $value['name']; $id = $value['id']; $picture = $value['picture'];
				echo '<li unselectable="on"><a id="photo_'.$id.'" class="thumbnail selectable" onclick="photo('.$id.');"><img src="'.$picture.'"></a><h5>'.$name.'</h5></li>';
			}
			echo '</ul>';
		} elseif ((isset($_POST['photo_id'])) && ($_POST['photo_id']!='')) {
			$photo_id = $_POST['photo_id'];
			$url = '/'.$photo_id;
			$photo = $facebook->api($url, 'GET');
			$name = $photo['name']; $picture = $photo['picture']; $source = $photo['source'];
			echo '<h4>'.$name.'</h4><div class="thumbnail"><img src="'.$picture.'"></div>';
			echo '<div id="action_group">
            		<a id="fb_upload" class="btn btn-large btn-primary btn-upload">Upload</a>
					<input type="hidden" id="url_input" name="url_input" value="'.$source.'"> 
            		<div id="upload_status"></div>
        		</div>';
		} else {
			echo '<div id="bread"><ol class="breadcrumb"><li>Image Import</li><li><a href="#facebook" onclick="fbapi();">Facebook</a></li></ol></div>
			<div id="picker">
				<div class="picker_files_header">
					<span>
						Filename
					</span>
					<span class="floatright glyphicon glyphicon-resize-vertical"></span>
				</div>
				<ul id="picker_files">';
			 foreach($albums['data'] as $key=>$value ){
				$name = $value['name']; $id = $value['id'];
				echo '<li onclick="album('.$id.','."'$name'".');"><i class="glyphicon glyphicon-folder-open"></i> '.$name.'<i class="glyphicon glyphicon-chevron-right icon-white floatright"></i></li>';
			}
			echo '</ul>
			</div>';
			echo '<div id="picker_preview"></div>';
		}
      } catch(FacebookApiException $e) {
        echo 
		'<div style="padding-top:15%">
			<div class="center">
				<h1>Upload a file from Facebook</h1>
				<p><a class="btn btn-large btn-primary button-block button-main" onclick="fb_login();">Connect to Facebook</a></p>
				<p style="margin-top: 10px">We will open a new page to help you connect your Facebook account</p>
			</div>
		</div>';
      }   
    } else {
      echo 
		'<div style="padding-top:15%">
			<div class="center">
				<h1>Upload a file from Facebook</h1>
				<p><a class="btn btn-large btn-primary button-block button-main" onclick="fb_login();">Connect to Facebook</a></p>
				<p style="margin-top: 10px">We will open a new page to help you connect your Facebook account</p>
			</div>
		</div>';
    }
?>

<script>
	function album (album_id, album_name) {
		var append_string = "<li>"+album_name+"</li>";
		$( ".breadcrumb" ).append( append_string );
		 $( "#picker" ).load( "api/facebook.php", {"album_id" : album_id},function( response, status, xhr ) {
			if ( status == "error" ) {
				var msg = "Sorry but there was an error: ";
				$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			}
		});	
	}
	function photo (photo_id) {
		var photo_element = "#photo_"+photo_id;
		if ($(photo_element).hasClass('selectedItem')) {
			$('.thumbnails li a').removeClass('selectedItem');
			$("#picker_preview").html("");
		} else {
			$('.thumbnails li a').removeClass('selectedItem');
	  		$(photo_element).addClass('selectedItem');
		
			$( "#picker_preview" ).load( "api/facebook.php", {"photo_id" : photo_id},function( response, status, xhr ) {
				if ( status == "error" ) {
					var msg = "Sorry but there was an error: ";
					$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
				}
			});
		}
	}
	function fbapi() {
		$( "#img_content" ).load( "api/facebook.php",function( response, status, xhr ) {
			if ( status == "error" ) {
				var msg = "Sorry but there was an error: ";
				$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			}
		});	
	}
	function fb_login() {
		var win = window.open("api/source_login.php?source=facebook");
		var pollTimer = window.setInterval(function() {
			if (win.closed !== false) { // !== is required for compatibility with Opera
				window.clearInterval(pollTimer);
				fbapi();
			}
		}, 200);	
	}
	$('#fb_upload').on('click', function (e) {
		var val = document.getElementById('url_input').value;
		$('#fb_upload').addClass('disabled');
		$( "#upload_status" ).load( "UploadUrl.php", {"url" : val},function( response, status, xhr ) {
			if ( status == "error" ) {
				var msg = "Sorry but there was an error: ";
				$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			}
		});

	})

</script>

