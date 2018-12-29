var index = 0;
var menu = document.getElementById('set_menu').children;
var info = document.getElementById('set_info').children;
var choose = function(e){
	var i;
	if (e.target.innerHTML == "更改信息"){
		i = 0;
	}else if (e.target.innerHTML == "更改密码"){
		i = 1;
	}else{ i = 2;}
	menu[index].className = "";
	info[index].className = "noshow";
	index = i;
	menu[index].className = "hover";
	info[index].className = "show";
}
for (var i = 0; i < menu.length;i++){
	menu[i].onclick = choose;
}