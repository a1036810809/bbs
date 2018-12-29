var index = 0;
var lis = document.getElementById('meun').children;
var choses = document.getElementsByClassName('content');
var chose = function(e){
	var i = e.target.innerHTML == "发布帖子" ? 1 : 0;
	lis[index].className = "";
	choses[index].className = "content";
	index = i;
	lis[index].className = "hov";
	choses[index].className = "content focus";
}
for (var i = 0; i < lis.length ;i++){
	lis[i].onclick = chose;
}
