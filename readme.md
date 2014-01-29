image import
=====================
A image import popup that allows user to import from various sources. Currently allows for import from user desktop, any url, and Facebook.
Additional sources are forthcoming.

Look & Feel made similar to https://www.inkfilepicker.com/.

setup
=====
Edit image_im_config.php with source selection and appropriate API keys.

Edit both UploadHandler.php and UploadUrl.php with target directory of imported images. Integration with Amazon S3 and other cloud-based storage is forthcoming.

This tool requires both jQuery and Bootstrap libraries to function properly.

View included index.html to see usage and setup. Mainly one requires the appropriate divs in which the pop-up will manifest.
<div id="toPopup"> 
        <div class="closePopup"></div>
		<div id="popup_content"> <!--your content start-->
        </div> <!--your content end-->
     </div> <!--toPopup end-->
	<div class="loader"></div>
   	<div id="backgroundPopup"></div>

The link to call the pop-up is:
	<a href="#" class="topopup">Click Here Trigger</a>

sample
======
http://www.weflect.com/demo/image_import/

apis
====
Facebook integration requires Facebook Php SDK which is included.
