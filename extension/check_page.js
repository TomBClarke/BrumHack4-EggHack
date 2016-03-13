var location = window.location.href;

function beginEgg() {
	$.ajax({
		url : 'http://54.84.108.88/rescources/web/isegg.php',
		type: 'POST',
    	async: false,
		data: { website: location },
		success : runEgg(response)
	});
}

function runEgg(response) {
	console.log(response)
	if (response == 0) {
		// nothing
	} else {
		$(document).ready(function() {
		    var elems = $('img');
			var select = Math.floor(Math.random() * elems.length);

			// need a switch here:
			elems[select].setAttribute("src", 'http://54.84.108.88/resources/img/EggHackGreen.png');


			elems[select].onmouseover = function() {
				$.ajax({
					url : 'http://54.84.108.88/rescources/web/find.php',
					type: 'POST',
			    	async: false,
					data: { website: location },
					success : found(response)
				});
	   		};
	   	}
	}
}

function found(res) {
	if (res == "success")
		window.open("http://54.84.108.88/riddle.php?website=" + location, '_blank');
}