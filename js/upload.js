let dropArea = document.getElementById('drop-area');

let uploadings = 0;

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, preventDefaults, false);
})

function preventDefaults (e) {
  e.preventDefault();
  e.stopPropagation();
}


['dragenter', 'dragover'].forEach(eventName => {
  dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
  dropArea.classList.add('highlight');
}

function unhighlight(e) {
  dropArea.classList.remove('highlight');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
  let dt = e.dataTransfer;
  let files = dt.files;

  handleFiles(files);
}

function handleFiles(files) {
  [...files].forEach(uploadFile);
}


function uploadFile(file) {
  let url = '?controller=photo&action=handleSingleUpload';
  let formData = new FormData();

  formData.append('file', file);
  
  previewFile(file);
  
  uploadings++;

  fetch(url, {
    method: 'POST',
    body: formData
  })
  .then((response) => {
  	response.text().then((id) => {
  		document.getElementById('submitBtn').classList.remove('d-none');
  		let fileHash = getFileHash(file);
  		document.getElementById('vegetable_' + fileHash).setAttribute('name', 'vegetable[' + id + ']');
  		document.getElementById('date_' + fileHash).setAttribute('name', 'date[' + id + ']');
  		document.getElementById('type_action_' + fileHash).setAttribute('name', 'type_action[' + id + ']');
  		document.getElementById('title_observation_' + fileHash).setAttribute('name', 'observation[' + id + ']');
  		document.getElementById('title_' + fileHash).setAttribute('name', 'title[' + id + ']');
  		document.getElementById('comment_' + fileHash).setAttribute('name', 'comment[' + id + ']');
  	})
  	document.getElementById('progress-bar_' + getFileHash(file)).remove();
  	uploadings--;
  })
  .catch(() => {
  	document.getElementById('progress-bar_' + getFileHash(file)).remove()
  	document.getElementById('photo_' + getFileHash(file)).classList.add('border-danger');
  	uploadings--;
  });
}

function previewFile(file) {
	
	console.log(file)
	
	let fileHash = getFileHash(file);
	
	let preview = document.createElement('div');
	preview.innerHTML = '<div class="card mb-3" style="max-width: 540px;" id="photo_' + fileHash + '">'
	+ '<div class="row g-0">'
	+ '  <div class="col-md-4">'
	+ '    <img src="./plante.png" class="img-fluid rounded-start" alt="" id="img_' + fileHash + '">'
	+ '  </div>'
	+ '  <div class="col-md-8">'
	+ '    <div class="card-body">'
	+ '	     <div class="progress" id="progress-bar_' + fileHash + '">'
	+ '	       <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>'
	+ '      </div>'
	+        getVegetableSelectString(fileHash)
	+ '      <div class="form-floating mb-3">'
	+ '        <input type="date" class="form-control" id="date_' + fileHash + '" name="date_' + fileHash + '"  value="" required>'
	+ '        <label for="date_' + fileHash + '">Date</label>'
	+ '      </div>'
	+        getTypeActionSelectString(fileHash)
	+ '      <div id="action_group_' + fileHash + '" class="d-none">'
	+ '        <div class="form-floating mb-3 d-none" id="title_form_' + fileHash + '" >'
	+ '          <input type="text" class="form-control" id="title_' + fileHash + '" name="title_' + fileHash + '" placeholder="Titre"  value="">'
	+ '          <label for="title_' + fileHash + '">Titre</label>'
	+ '        </div>'
	+          getObservationSelectString(fileHash)
	+ '        <div class="form-floating mb-3">'
	+ '          <textarea class="form-control" placeholder="Commentaire" id="comment_' + fileHash + '" name="comment_' + fileHash + '" style="height: 50px"></textarea>'
	+ '          <label for="comment_' + fileHash + '">Commentaire</label>'
	+ '        </div>'
	+ '      </div>'
	+ '    </div>'
	+ '  </div>'
	+ '</div>'
	+ '</div>';
	preview.classList.add('col');
	document.getElementById('gallery').appendChild(preview);
	
	var typeSelect = document.getElementById("type_action_" + fileHash);
	
	typeSelect.onchange = onTypeActionChange;

	let reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onloadend = function() {
		document.getElementById('img_' + fileHash).src = reader.result;
	};
	
	var tags = ExifReader.load(file).then(function (tags) {
		// console.log(tags);
		var dateStr = tags.DateTimeOriginal != null ? tags.DateTimeOriginal.description : '';
		var date = new Date(dateStr.split(' ')[0].replaceAll(':','/') + ' ' + dateStr.split(' ')[1]);
		console.log(date);
		document.getElementById('date_' + fileHash).value = date.toISOString().split('T')[0];
	});
}

function getFileHash(file) {
	return file.lastModified + '_' + file.size;
}

function onTypeActionChange() {
    var fileHash = this.getAttribute('hash');
	var typeSelect = document.getElementById("type_action_" + fileHash);
	var obervation = document.getElementById("observation_form_" + fileHash);
	var title = document.getElementById("title_form_" + fileHash);
	var action_group = document.getElementById("action_group_" + fileHash);
	if (typeSelect.value === 'SANS_ACTION') {
		action_group.classList.add('d-none');
	} else {
		action_group.classList.remove('d-none');
	}
	if (typeSelect.value === 'observation') {
		obervation.classList.remove("d-none");
		title.classList.add('d-none');
	} else {
		obervation.classList.add("d-none");
		title.classList.remove('d-none');
	}
}



function submitActions() {
	console.log('click');
	let form = document.getElementById('gallery');
	if (form.checkValidity()) {
		form.submit();
	}
	else {
		alert("Un champs requis n'est pas rensigné !");
	}
}

document.getElementById('submitBtn').onclick = submitActions;