function howGridAnimations(){

	var self = this;

	this.smileAnimations = function(background,color){
		var previousElement = document.querySelector('#smileBlock').previousElementSibling;
		var nextElement = document.querySelector('#smileBlock').nextElementSibling;
		if(background && color){
			previousElement.style.cssText = 'background-color:'+background+';color:'+color+';';
			nextElement.style.cssText = 'background-color:'+background+';color:'+color+';';
		} else {
			previousElement.style.cssText = '';
			nextElement.style.cssText = '';
		}
	}

	this.descriptionBoxAnimations = function(e,p){
		var origin = e.srcElement || e.target;
		if (!origin.className.match(/(description_box)/)){
			var descriptionBox = e.target.lastElementChild;

			if(p==0){
				descriptionBox.removeEventListener('mouseenter',function(event){self.descriptionBoxAnimations(event,0)},false);
			} else {
				descriptionBox.addEventListener('mouseenter',function(event){self.descriptionBoxAnimations(event,0)},false);
			}
			self.moveBlocksElements(e.target,p);
			descriptionBox.style.cssText = 'left:-'+p+'%';
		} else {return true;}
	}

	this.moveBlocksElements = function(b,d){
		var direction = Math.abs(parseInt(d-100));
		var blockDigit = b.children[0];
		var blockHeader = b.children[1];
		var blockIcon = b.children[2];


		switch(d){
			case 0:
				blockHeader.style.marginTop = '20%';
				break;
			case 100:
				blockHeader.style.marginTop = '26%';
				break;
		}

		blockDigit.style.top = '-'+direction+'%';
		blockIcon.style.top = direction+'%';

	}
}

function detectmob() { 
	 if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	 ){
	    return true;
	  }
	 else {
	    return false;
	  }
}