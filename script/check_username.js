window.onload = initPage;

function initPage() {
	document.getElementById("user_field").focus();
	document.getElementById("password").onfocus = grabFile;
}

function grabFile() {
	var request = getHTTPObject();
	var username = document.getElementById("user_field").value;
	var file = "check_username.php?username=" + username;
	if(request) {
		request.onreadystatechange = function() {
			displayMsg(request);
		}
		request.open("GET",file,true);
		request.send(null);
	}
}

function displayMsg(request) {
	if(request.readyState == 4) {
		if(request.status == 200 || request.status == 304) {
			var msg = document.getElementById("check_msg");
			var show_check = document.getElementById("user_field");
			if(request.responseText == "okay") {
				show_check.setAttribute("class","good");
				msg.innerHTML = "恭喜, 此帳號可以使用.";
			} 
			else if(request.responseText == "used") {
				show_check.setAttribute("class","bad");
				msg.innerHTML = "此帳號已被人使用, 請再試一次.";
				document.getElementById("user_field").focus();
			}
			else if(request.responseText == "invalid") {
				show_check.setAttribute("class","bad");
				msg.innerHTML = "輸入的帳號無效, 請再試一次.";
				document.getElementById("user_field").focus();
			}			
		}
	}
}