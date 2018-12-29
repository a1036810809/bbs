var oSelect = document.getElementById("select"); 
var input = document.getElementById("file_input"); 
var result;
if(typeof FileReader==='undefined'){    
    alert("抱歉，你的浏览器不支持 FileReader");    
    input.setAttribute('disabled','disabled');    
}else{    
    input.addEventListener('change',readFile,false);    
}　　　　　//handler    

    
function readFile(){
	var d = document.getElementById('choosepoto')
	if (d != null){
		d.remove();
	}
    var iLen = this.files.length;  
    var index = 0;  
    for(var i=0;i<iLen;i++){  
        if (!input['value'].match(/.jpg|.gif|.png|.jpeg|.bmp/i)){　　//判断上传文件格式    
            return alert("上传的图片格式不正确，请重新选择");    
        }
        var reader = new FileReader();  
        reader.index = i; 
        reader.readAsDataURL(this.files[i]);  //转成base64    
        reader.fileName = this.files[i].name;  


        reader.onload = function(e){    
            result = '<div class="user_poto" id="choosepoto"><img src="'+this.result+'" alt=""/></div>';
            var div = document.createElement('div');  
            div.innerHTML = result;    
            document.getElementById('set_info').appendChild(div);  　　//插入dom树  
        }    
    }    
}

oSelect.onclick = function(){
	input.click();
};
  