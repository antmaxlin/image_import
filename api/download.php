    <h1>Files from the Web</h1>
    <p>Url of the file: <input id="urlInput" type="text" placeholder="e.g. http://example.com/someImage.jpg" style="width:80%" name="url" onchange="load_url_image();" onkeypress="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();"></input></p>
    <p class="help-block">Grab any file off the web. Just provide the link.</p>
    <div id="preview" class="row-fluid url">
    	<div id="img_upload"></div>
        <div id="error"></div>
        <div id="preview_load" class="loader"></div>
        <div id="action_group">
            <a id="download_upload" class="btn btn-large btn-primary btn-upload">Upload</a>
            <div id="upload_status"></div>
        </div>
    </div>

<script>
	function load_url_image(){
		$('#preview_load').show();
		var val = document.getElementById('urlInput').value;
		var img = new Image();
		img.src = val;
		img.width = 200;
		val = val.replace(/\\/g,'/').replace( /.*\//, '' );
		img.onload = function() {
			$('#preview_load').hide();
			$('#download_upload').removeClass('disabled');
			document.getElementById('upload_status').innerHTML='';
			document.getElementById('img_upload').innerHTML='<div><p>filename: '+val+'</p></div>';
			document.getElementById('img_upload').appendChild(img);
		};
	}
	
	$('#download_upload').on('click', function (e) {
		var val = document.getElementById('urlInput').value;
		$('#download_upload').addClass('disabled');
		$( "#upload_status" ).load( "UploadUrl.php", {"url" : val},function( response, status, xhr ) {
			if ( status == "error" ) {
				var msg = "Sorry but there was an error: ";
				$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			}
		});

	})

</script>