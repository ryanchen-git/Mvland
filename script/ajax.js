function getHTTPObject() {
	var XMLHttpRequestObject = false;
	if(window.XMLHttpRequest) {
		XMLHttpRequestObject = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
			XMLHttpRequestObject = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				XMLHttpRequestObject = false;
			}
		}
	}
	return XMLHttpRequestObject;
}
