function checkUserInfo(obj){
	if (cu(obj) && ce(obj) && cp(obj) && ct(obj) && cn(obj) && ca(obj) && cb(obj)){
		return true;
	}
	return false;
}
function checkLogin(obj){
	if (obj.username.value == "" || obj.password.value == "" || obj.captcha.value == ""){
		a("用户名、密码以及验证码不能为空");
		return false;
	}
	if (obj.captcha.value.length != 4){
		a("验证码长度错误")
		return false;
	}
	return true;
}
function checkRePwd(obj){
	if (cu(obj) && ce(obj) && cp(obj) && ct(obj)){
		return true;
	}
	return false;
}
function checkUpdateInfo(obj){
	if (ce(obj) &&  ct(obj) && cn(obj) && ca(obj) && cb(obj)){
		return true;
	}
	return false;
}
function checkUpdatePwd(obj){
	var pwd = obj.oldpwd.value;
	var alc = 0;
	var numc = 0;
	if (pwd == "" || pwd.length < 8 || pwd.length > 20){
		a("密码不能为空且密码长度不能低于8位高于20位");
		return false;
	}
	for (var i = 0;i<pwd.length;i++){
		if (pwd[i] <= '9' && pwd[i] >= '0') numc++;
		if ((pwd[i] <= 'z' && pwd[i] >= 'a') || (pwd[i] <= 'Z' && pwd[i] >= 'A')) alc++;
	}
	if (numc == 0 || alc == 0){
		a("密码必须包括数字和字母");
		return false;
	}
	if (!cp(obj)){
		return false;
	}
	return true;
}
function checkUpdatePoto(obj){
	var poto = obj.file.value;
	if (poto == ""){
		a("请选择文件。。");
		return false;
	}
	return true;
}
function cu(obj){
	var username = obj.username.value;
	if (username == ""){
		a("用户名不能为空");
		return false;
	}
	return true;
}
function cp(obj){
	var pwd = obj.pwd.value;
	var alc = 0;
	var numc = 0;
	if (pwd == "" || pwd.length < 8 || pwd.length > 20){
		a("密码不能为空且密码长度不能低于8位高于20位");
		return false;
	}
	for (var i = 0;i<pwd.length;i++){
		if (pwd[i] <= '9' && pwd[i] >= '0') numc++;
		if ((pwd[i] <= 'z' && pwd[i] >= 'a') || (pwd[i] <= 'Z' && pwd[i] >= 'A')) alc++;
	}
	if (numc == 0 || alc == 0){
		a("密码必须包括数字和字母");
		return false;
	}
	return true;
}
function cn(obj){
	var nickname = obj.nickname.value;
	if (nickname == ""){
		a("昵称不能为空");
		return false;
	}
	return true;
}
function ca(obj){
	var city = obj.city.value;
	if (city == "请选择..."){
		a("地址不能为空");
		return false;
	}
	return true;
}
function ce(obj){
	var email = obj.email.value;
	if (email == ""){
		a("邮箱不能为空");
		return false;
	}
	return true;
}
function cb(obj){
	var brithday = obj.brithday.value;
	if (brithday == "" || new Date(brithday) > new Date()){
		a("生日不能为空且生日不能在未来");
		return false;
	}
	return true;
}
function ct(obj){
	var tell = obj.tell.value;
	if (tell == ""){
		a("电话不能为空");
		return false;
	}
	return true;
}
function d(vari){
	console.log(vari);
}
function a(vari){
	alert(vari);
	/*var lable = document.getElementById('showmsg');
	lable.innerHTML = vari;*/
}
