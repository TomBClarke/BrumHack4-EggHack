var egg_page = true;

if (egg_page) {
	var currentpage = window.location.href ;
	$( document ).ready(function() {
    var elems = $('img');
	//alert(elems.length);
	var select = Math.floor(Math.random() * elems.length);
		//alert(select);
		//console.log(elems[select]);
		elems[select].setAttribute("src", 'http://54.84.108.88/resources/img/EggHackGreen.png');
		elems[select].removeAttribute("href");
		elems[select].parent.removeAttribute("href");
		elems[select].onclick = function() {
   			window.location.href = "http://stackoverflow.com";
   		};
});
	
}




	
	
