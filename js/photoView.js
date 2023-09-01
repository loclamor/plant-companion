class PhotoView {
	file;
	viewer;
	hash;
	date;
	title;
	vegetable;
	type;
	observation;
	comment;
	
	$elt;
	$eltMin;
	
	constructor(file, viewer) {
		this.file = file;
		this.viewer = viewer;
		
		this.hash = this.getFileHash()
		
		this.$elt = jQuery(
		`<div class="d-block w-100 h-100 position-relative" style="">
			<div id="remove" class="btn btn-outline-primary position-absolute top-0 end-0">
				<i class="bi bi-trash"></i>
			</div>
			<img src="./plante.png" height="100%" class="position-absolute top-50 start-50 translate-middle">
			<div id="options" class="position-absolute top-0 start-50 translate-middle-x" style="z-index: 99;">
				<input type="text" id="autocompleteVegetable" placeholder="Plante"><br>
				<input type="date" id="date" value="" required style="width: 256px"><br/>
				` + getTypeActionSelectString() + `<br>
				<div id="action_group" class="d-none">
					<div id="title-wrapper" class="d-none">
						<input type="text" id="title" placeholder="Titre"  value=""><br/>
					</div>
					<div class="" id="observation-wrapper">
						` + getObservationSelectString() + `<br>
					</div>
					<textarea placeholder="Commentaire" id="comment" style="height: 50px; width: 256px"></textarea>'
				</div>
			</div>
		</div>`);
		this.$eltMin = jQuery(`<img src="./plante.png" height="50px">`);
		
		let reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onloadend = jQuery.proxy(function() {
			this.$elt.find('img').attr('src', reader.result);
			this.$eltMin.attr('src', reader.result);
		}, this);
		
		var tags = ExifReader.load(file).then(jQuery.proxy(function (tags) {
			if (tags.DateTimeOriginal != null ) {
				 let dateStr = tags.DateTimeOriginal.description;
				this.date = new Date(dateStr.split(' ')[0].replaceAll(':','/') + ' ' + dateStr.split(' ')[1]);
			} else {
				this.date = new Date();
			}
			console.log(this.date);
			this.$elt.find('#date').val(this.date.toISOString().split('T')[0]);
		}, this));
		
		new Autocomplete(this.$elt.find('#autocompleteVegetable')[0], {
		    items: getVegetableJSON(),
		    suggestionsThreshold: 0,
		    onRenderItem: (item, label) => {
		      return label + " (" + item.value + ")";
		    },
		    onSelectItem: (item) => {
		    	this.vegetable = item.value;
		    	this.$elt.find('#autocompleteVegetable').removeClass('bg-danger');
		    	console.log(this)
		    }
		  });
		this.$elt.find('#type_action').on('change', (e) => {
		    	this.type = e.target.value;
		    	
		    	if (this.type === 'SANS_ACTION') {
					this.$elt.find('#action_group').addClass('d-none');
				} else {
					this.$elt.find('#action_group').removeClass('d-none');
				}
				if (this.type === 'observation') {
					this.$elt.find('#observation-wrapper').removeClass("d-none");
					this.$elt.find('#title-wrapper').addClass('d-none');
				} else {
					this.$elt.find('#observation-wrapper').addClass("d-none");
					this.$elt.find('#title-wrapper').removeClass('d-none');
				}
		    	
		    	console.log(this)
		});
		this.$elt.find('#title_observation').on('change', (e) => {
		    	this.observation = e.target.value;
		    	console.log(this)
		});
		this.$elt.find('#date').on('change', (e) => {
			this.date = new Date(e.target.value);
		});
		this.$elt.find('#title').on('change', (e) => {
			this.title = e.target.value;
		});
		this.$elt.find('#comment').on('change', (e) => {
			this.comment = e.target.value;
		});
		this.$elt.find('#remove').on('click', (e) => {
			this.remove();
		});
	}
	
	getFileHash() {
		return this.file.lastModified + '_' + this.file.size;
	}
	
	render() {
		return this.$elt;
	}
	
	renderMiniature() {
		return this.$eltMin;
	}
	
	canUpload() {
		if ( this.vegetable != undefined && this.vegetable != null && this.vegetable != '' ) {
			this.$elt.find('#autocompleteVegetable').removeClass('bg-danger');
			return true;
		} else {
			this.viewer.slideToHash(this.hash);
			this.$elt.find('#autocompleteVegetable').addClass('bg-danger');
			return false;
		}
	}
	
	remove() {
		this.$elt.remove();
		this.$eltMin.remove();
		this.viewer.remove(this.hash);
	}
}