    <div id="local_dragdrop" class="center"><h1 style="margin-top: 130px">Drag files here</h1></div>
    <p id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input type="file"></label></p>
    <p id="filereader">File API & FileReader API not supported</p>
    <p id="formdata">XHR2's FormData is not supported</p>
    <p id="progress">XHR2's upload progress isn't supported</p>
    <input id="upload_num" type="hidden" value="0" /></input>
	<form id="fileUploadForm">
        <label class="control-label" for="fileUpload" style="font-size:1.4em">
            <em>OR</em>
             Select a file to upload:
        </label>
        <div id="fileInputContainer">
            <input id="fileUploadInput" type="file" accept="image/*" name="fileUpload" onChange="uploadFile();"></input>
        </div>
    </form>

<script>
// Drag Drop
						   
	var holder = document.getElementById('local_dragdrop'),
		tests = {
		  filereader: typeof FileReader != 'undefined',
		  dnd: 'draggable' in document.createElement('span'),
		  formdata: !!window.FormData,
		  progress: "upload" in new XMLHttpRequest
		}, 
		support = {
		  filereader: document.getElementById('filereader'),
		  formdata: document.getElementById('formdata'),
		  progress: document.getElementById('progress')
		},
		acceptedTypes = {
		  'image/png': true,
		  'image/jpeg': true,
		  'image/gif': true
		},
		fileupload = document.getElementById('upload');
	
	"filereader formdata progress".split(' ').forEach(function (api) {
	  if (tests[api] === false) {
		support[api].className = 'fail';
	  } else {
		// FFS. I could have done el.hidden = true, but IE doesn't support
		// hidden, so I tried to create a polyfill that would extend the
		// Element.prototype, but then IE10 doesn't even give me access
		// to the Element object. Brilliant.
		support[api].className = 'hidden';
	  }
	});
	
	function previewfile(file) {
	  if (tests.filereader === true && acceptedTypes[file.type] === true) {
		var reader = new FileReader();
		reader.onload = function (event) {
		  var image = new Image();
		  image.src = event.target.result;
		  image.width = 100; // a fake resize
		  var upload_num = document.getElementById("upload_num").value;
		  var upload_id = 'uploadprogress'+upload_num;
		  if (upload_num == 1) {holder.innerHTML = '';};
		  $(holder).append("<div class='img_upload'>");
		  holder.appendChild(image);
		  var size = (file.size ? (file.size/1024|0) + 'K' : '');
		   $(holder).append('<div class="file_info"><p>name: '+file.name+'</p><p>type: '+file.type+'</p><p>size: '+size+'</p></div><progress id="'+upload_id+'" min="0" max="100" value="0">0</progress></div>');
		};
	
		reader.readAsDataURL(file);
	  }  else {
		holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
		console.log(file);
	  }
	}
	
	function readfiles(files) {
		debugger;
		var formData = tests.formdata ? new FormData() : null;
		for (var i = 0; i < files.length; i++) {
		  var upload_num = Number(document.getElementById("upload_num").value) + 1;
		  document.getElementById("upload_num").value = upload_num;
		  if (tests.formdata) formData.append('file', files[i]);
		  previewfile(files[i]);
		}
		// now post a new XHR request
		if (tests.formdata) {
		  var xhr = new XMLHttpRequest();
		  xhr.open('POST', 'UploadHandler.php');
		  xhr.upload.addEventListener("progress", uploadProgress, false);
		  xhr.addEventListener("load", uploadComplete, false);
          xhr.addEventListener("error", uploadFailed, false);
          xhr.addEventListener("abort", uploadCanceled, false);
	
		  xhr.send(formData);
		}
	}
	
	if (tests.dnd) { 
	  holder.ondragover = function () { this.className = 'hover'; return false; };
	  holder.ondragend = function () { this.className = ''; return false; };
	  holder.ondrop = function (e) {
		this.className = '';
		e.preventDefault();
		readfiles(e.dataTransfer.files);
	  }
	} else {
	  fileupload.className = 'hidden';
	  fileupload.querySelector('input').onchange = function () {
		readfiles(this.files);
	  };
	}
	function uploadFile() {
		var fd = new FormData();
		fd.append("file", document.getElementById('fileUploadInput').files[0]);
		var upload_num = Number(document.getElementById("upload_num").value) + 1;
		document.getElementById("upload_num").value = upload_num;
		previewfile(document.getElementById('fileUploadInput').files[0]);
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", uploadProgress, false);
		xhr.addEventListener("load", uploadComplete, false);
		xhr.addEventListener("error", uploadFailed, false);
		xhr.addEventListener("abort", uploadCanceled, false);
		xhr.open("POST", "UploadHandler.php");
		xhr.send(fd);
	}
		
	function uploadProgress(evt) {
		if (evt.lengthComputable) {
			var upload_num = document.getElementById("upload_num").value;
			var upload_id = 'uploadprogress'+upload_num;
			var percentComplete = Math.round(evt.loaded * 100 / evt.total);
			document.getElementById(upload_id).value = percentComplete;
		}
		else {
		}
	}

	function uploadComplete(evt) {
		/* This event is raised when the server send back a response */
		/*alert(evt.target.responseText);*/
		var upload_num = document.getElementById("upload_num").value;
			var upload_id = 'uploadprogress'+upload_num;
		document.getElementById(upload_id).value = 100;
	}

	function uploadFailed(evt) {
		alert("There was an error attempting to upload the file.");
	}

	function uploadCanceled(evt) {
		alert("The upload has been canceled by the user or the browser dropped the connection.");
	}

</script>