//funkcja analogiczna do tej z jQuery
function _(e){
	return document.getElementById(e);
}

//upload pliku z formularza i przesłanie do pliku php

function uploadFile(){
	var file = _("file1").files[0];
	var formdata = new FormData();
	formdata.append("file1", file);
	var ajax = new XMLHttpRequest();
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.open("POST", "file_upload.php");
	ajax.send(formdata);
}

	
function completeHandler(event){
	_("status").innerHTML = event.target.responseText;
}

function errorHandler(event){
	_("status").innerHTML = "Upload Failed";
}
//pobieranie danych o strefie czasowej
window.onload=function(){
  var tz = jstz.determine();
  var strefa = tz.name();
  _("strefa").innerHTML="Strefa czasowa: "+strefa;
};
