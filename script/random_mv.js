function grabFile() {
	var request = getHTTPObject();
	var file = "get_mv.php";
	if(request) {
		document.getElementById("random_mv_link").className = "displayLoading";	
		document.getElementById("random_mv_link").innerHTML = "...";
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
			var random_mv = document.getElementById("random_mv");
			document.getElementById("random_mv_link").className = "";
			random_mv.innerHTML = request.responseText;
			return true;
		}
	}
}