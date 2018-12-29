var imgs = document.getElementsByClassName('poto');
for (var i = 0; i < imgs.length; i++) {
	if (imgs[i].width > imgs[i].height){
		imgs[i].style.height = "100%";
	}else{
		imgs[i].style.width = "100%";
	}
}