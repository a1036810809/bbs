var autor = document.getElementsByClassName('autor');
var lastuser = document.getElementsByClassName('lastuser');

for (var i = 0; i < autor.length; i++) {
	autor[i].onmouseover = showIdPage;
	lastuser[i].onmouseover = showIdPage;
	autor[i].onmouseout = outIdPage;
	lastuser[i].onmouseout = outIdPage;
}

function showIdPage(e){
	var id = e.target.attributes.value.value;
	var page = document.getElementById(id);
	page.onmouseover = function(){
		page.style.display = "block";
	};
	page.onmouseout = function(){
		page.style.display = "none";
	};
	page.style.display = "block";
	page.style.position = "fixed";
	page.style.top = e.clientY + "px";
	page.style.left = (e.clientX - 250) + "px";
}
function outIdPage(e){
	var id = e.target.attributes.value.value;
	var page = document.getElementById(id);
	page.style.display = "none";
}