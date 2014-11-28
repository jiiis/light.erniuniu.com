jQuery(document).ready(function($){
	var message = $("div.navi_highlighted").text();
	
	// 白色底色
	var neonbasecolor="#fff";
	var neontextcolor="#e70012";
	var neontextcolor2="#f28d94";
	
	// 红色底色
	var neonbasecolor="#e70012";
	var neontextcolor="#fff";
	var neontextcolor2="#f28d94";
	
	// 桔黄（top title）
	var neonbasecolor="#d42604";
	var neontextcolor="#fff";
	var neontextcolor2="#d27967";
	
	// 黑色光泽
	/*
	var neonbasecolor="#e70012";
	var neontextcolor="#000";
	var neontextcolor2="#6f2e33";
	*/
	
	var flashspeed=200;
	var flashingletters=2;
	var flashingletters2=1;
	var flashpause=0;
	var n=0;
	var temp_msg = null;
	
	if (document.all||document.getElementById){
		temp_msg = '<font color="'+neonbasecolor+'">';
		for (m=0;m<message.length;m++){
			temp_msg += '<span id="neonlight'+m+'">'+message.charAt(m)+'</span>';
		}
		temp_msg += '</font>';
	}
	else{
		temp_msg = message;
	}
	
	document.getElementById("neonText").innerHTML = temp_msg;

	function crossref(number){
		var crossobj=document.all? eval("document.all.neonlight"+number) : document.getElementById("neonlight"+number);
		return crossobj;
	}

	function neon(){
		//Change all letters to base color
		if (n==0){
			for (m=0;m<message.length;m++){
				crossref(m).style.color=neonbasecolor;
			}
		}

		//cycle through and change individual letters to neon color
		crossref(n).style.color=neontextcolor;

		if (n>flashingletters-1){
			crossref(n-flashingletters).style.color=neontextcolor2;
		}
		
		if (n>(flashingletters+flashingletters2)-1){
			crossref(n-flashingletters-flashingletters2).style.color=neonbasecolor;
		}

		if (n<message.length-1){
			n++;
		}else{
			n=0;
			clearInterval(flashing);
			setTimeout(function(){
				beginneon();
			},flashpause);
			return;
		}
	}

	function beginneon(){
		if (document.all||document.getElementById){
			flashing=setInterval(function(){
				neon();
			},flashspeed);
		}
	}

	beginneon();
});