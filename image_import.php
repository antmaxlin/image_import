 <?php
 	require_once ("image_im_config.php");
 ?>
 
        	<div id="img_source">
            	<div id="img_logo"></div>
                <ul class="source_list">
                	<?php if ($src_computer==true) { ?>
                    <li class="active" data-client="computer">
                    	<a href="#computer">
                            <i class="sicon-home"></i>
                            My Computer
                            <span class="label auth-label disabled" title="Unlink this account">x</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($src_facebook==true) { ?>
                    <li class="" data-client="facebook">
                       <a href="#facebook">
                            <i class="sicon-facebook"></i>
                            Facebook
                            <span class="label auth-label disabled" title="Unlink this account">x</span>
                    	</a>
                    </li>
                    <?php } ?>
                    <li class="" data-client="dropbox"></li>
                    <li class="" data-client="instagram"></li>
                    <li class="" data-client="googledrive"></li>
                    <li class="" data-client="flickr"></li>
                    <?php if ($src_download==true) { ?>
                    <li class="" data-client="url">
                    	<a href="#download">
                            <i class="sicon-download"></i>
                            Link (URL)
                            <span class="label auth-label disabled" title="Unlink this account">x</span>
                    	</a>
                    </li>
                    <?php } ?>
                    <li class="" data-client="webcam"></li>
                    <li class="" data-client="skydrive"></li>
                    <li class="" data-client="evernote"></li>
                    <li class="" data-client="github"></li>
                    <li class="" data-client="picasa"></li>
                    <li class="" data-client="box"></li>
                    <li class="" data-client="alfresco"></li>
                    <li class="" data-client="gmail"></li>
                </ul>
            </div>
            <div id="img_content">
            	
            </div>
<script>
	var ajax_load = '<div class="loader" style="display: block;"></div>'; 
	$('#img_source ul li').click(function() { 
	  $('#img_source ul li').removeClass('active'); 
	  $(this).addClass('active');
	  var href = $(this).children()[0];
	  var img_source = href.getAttribute('href').substr(1);
	  var loadUrl = 'api/'+img_source+'.php'; 
	  $("#img_content").html(ajax_load).load(loadUrl);  
	});
	$(document).ready(function(){
		var loadUrl = 'api/computer.php';
		$("#img_content").html(ajax_load).load(loadUrl);
	});
</script>