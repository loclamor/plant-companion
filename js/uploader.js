class Uploader {
	view;
	percent = 0;
	constructor(view) {
		this.view = view;
	}
	
	upload() {
		if (!this.view.canUpload()) {
			this.abortHandler(false);
			return;
		}
		this.view.viewer.$elt.find(`[hash="${this.view.hash}"]>.upload-progress`).removeClass('success abort error').attr('style', 'width: 0%');
		let formdata = new FormData();
		formdata.append("photo", this.view.file);
		formdata.append("hash", this.view.hash);
		formdata.append("date", this.view.date.toISOString().split('T')[0]);
		formdata.append("title", this.view.title);
		formdata.append("vegetable", this.view.vegetable);
		formdata.append("type_action", this.view.type);
		formdata.append("observation", this.view.observation);
		formdata.append("comment", this.view.comment);
		let ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", (e) => { this.progressHandler(e) }, false);
		ajax.addEventListener("load", (e) => { this.completeHandler(e) }, false);
		ajax.addEventListener("error", (e) => { this.errorHandler(e) }, false);
		ajax.addEventListener("abort", (e) => { this.abortHandler(e) }, false);
		ajax.open("POST", "?controller=photo&action=uploadOne");
		ajax.send(formdata);
	}
	
	progressHandler(event) {
		var percent = Math.round((event.loaded / event.total) * 100);
		console.log(percent)
		
		this.view.viewer.$elt.find(`[hash="${this.view.hash}"]>.upload-progress`).attr('style', 'width: ' + percent + '%');
	}
	
	completeHandler(e) {
		try {
			let response = JSON.parse(e.target.response);
			if (response.success === true) {
				this.view.viewer.$elt.find(`[hash="${this.view.hash}"]>.upload-progress`).addClass('success').attr('style', 'width: 100%');
				setTimeout(() => {
					this.view.remove();
				}, 2000);
				return;
			} else {
				this.errorHandler(e);
			}
		} catch(ex) {
			this.errorHandler(ex);
		}
		
	}
	
	errorHandler(e) {
		console.log(e);
		this.view.viewer.$elt.find(`[hash="${this.view.hash}"]>.upload-progress`).addClass('error').attr('style', 'width: 100%');
	}
	
	abortHandler(e) {
		this.view.viewer.$elt.find(`[hash="${this.view.hash}"]>.upload-progress`).addClass('abort').attr('style', 'width: 100%');
	}
}