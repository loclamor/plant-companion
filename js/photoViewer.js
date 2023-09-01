class PhotoViewer {
	dropArea;
	uploadings = 0;
	
	carousel = null;
	photos;
	nbPhotos = 0;
	$elt;
	constructor() {
		this.photos = {};
		this.$elt = $(
			`<div id="viewer" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false" data-bs-wrap="false">
				<div class="carousel-indicators">
					<button type="button" id="add-indicator" data-bs-target="#viewer" class="active btn btn-outline-primary" data-bs-slide-to="0" itemOrder="0">+</button>
				</div>
				<div class="carousel-inner">
					<div class="carousel-item active" id="add-item" >
						<div id="drop-area" class="d-block w-90 position-relative">
							<div class="my-form">
								<p>Ajouter des photos<p>
								<p>
									<input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)">
									<label class="btn btn-primary" for="fileElem">Photos...</label>
								</p>
								<p>
									<label class="btn btn-primary" for="pictureFromCamera"><i class="bi bi-camera"></i></label>
									<input type="file" id="pictureFromCamera" multiple accept="image/*" capture="environment" onchange="handleFiles(this.files)">
								</p>
								<p>
									<button class="btn btn-primary" id="send"><i class="bi bi-send"></i></button>
								</p>
							</div>
							
							
						</div>
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#viewer" data-bs-slide="prev">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#viewer" data-bs-slide="next">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Next</span>
				</button>
			</div>`
		);
		// this.$elt.find('#fileElem')[0].onchange = (files) => this.handleFiles(files);
		this.dropArea = this.$elt.find('#drop-area')[0];

		this.uploadings = 0;
		
		['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
			this.dropArea.addEventListener(eventName, (e) => {
				e.preventDefault();
				e.stopPropagation();
			}, false);
		});
		
		
		['dragenter', 'dragover'].forEach(eventName => {
			this.dropArea.addEventListener(eventName, (e) => this.highlight(e), false);
		});
		
		['dragleave', 'drop'].forEach(eventName => {
			this.dropArea.addEventListener(eventName, (e) => this.unhighlight(e), false);
		});
		
		this.dropArea.addEventListener('drop', (e) => this.handleDrop(e), false);
		
		this.$elt.find('#send').on('click', () => {
			for (const hash in this.photos) {
				let uploader = new Uploader(this.photos[hash]);
				uploader.upload();
			}
		});
	}
	
	highlight(e) {
		this.dropArea.classList.add('highlight');
	}
	
	unhighlight(e) {
		this.dropArea.classList.remove('highlight');
	}

	handleDrop(e) {
		let dt = e.dataTransfer;
		let files = dt.files;
		
		this.handleFiles(files);
	}
	
	addFile(file) {
		let photoView = new PhotoView(file, this);
		
		this.$elt.find('.carousel-inner #add-item').before(
			$(`<div class="carousel-item" hash="${photoView.hash}"></div>`).html(photoView.render())
		);
		this.$elt.find('.carousel-indicators #add-indicator').before(
			$(`<button type="button" data-bs-target="#viewer" data-bs-slide-to="${this.nbPhotos}" itemOrder="${this.nbPhotos}" class="" hash="${photoView.hash}"></button>`)
			.html(photoView.renderMiniature())
			.prepend(`<span class="upload-progress" style="width: 0%"></span>`)
		).attr('data-bs-slide-to', this.nbPhotos + 1).attr('itemOrder', this.nbPhotos + 1).data('bs-slide-to', this.nbPhotos + 1);
		this.photos[photoView.hash] = photoView;
		this.nbPhotos ++;
		
	}
	
	render() {
		return this.$elt;
	}
	
	slideToHash(hash) {
		let itemOrder = parseInt(this.$elt.find(`[hash="${hash}"]`).attr('itemOrder'));
		this.slideTo(itemOrder);
	}
	
	slideTo(item) {
		if (this.carousel === null) {
			var myCarousel = document.querySelector('#viewer');
			this.carousel = bootstrap.Carousel.getOrCreateInstance(myCarousel);
		}
		this.carousel.to(item);
	}
	
	handleFiles(files) {
		let beforeSize = this.photos.length;
		[...files].forEach((file) => this.addFile(file));
		this.slideTo(beforeSize);
	}
	
	remove(hash) {
		let itemOrder = parseInt(this.$elt.find(`[hash="${hash}"]`).attr('itemOrder'));
		this.slideTo(itemOrder + 1);
		delete this.photos[hash];
		this.$elt.find(`[hash="${hash}"]`).remove();
		for(let i = itemOrder + 1; i <= this.nbPhotos; i++) {
			console.log(hash, itemOrder, i)
			this.$elt.find(`[itemOrder="${i}"]`).attr('data-bs-slide-to', i - 1).attr('itemOrder', i - 1).data('bs-slide-to', i - 1)
		}
		this.nbPhotos --;
		
	}
}