var url = window.location.hostname;

function beginEgg() {
	$.ajax({
		url : 'http://54.84.108.88/resources/web/isegg.php',
		type: 'POST',
    	async: false,
		data: { website: url },
		success: runEgg
	});
}

function runEgg(response) {
	console.log(response);
	if (response == 1) {
		$(document).ready(function() {
		    var elems = $('img');
			var select = Math.floor(Math.random() * elems.length);

			elems[select].setAttribute("src", 'http://54.84.108.88/resources/img/EggHackGreen.png');

			console.log("here");

			elems[select].onmouseover = function() {
				$.ajax({
					url : 'http://54.84.108.88/resources/web/find.php',
					type: 'POST',
			    	async: false,
					data: { website: url },
					success : found
				});
	   		};
	   	});
	}
}

function found(res) {
	console.log("found");
	if (res == "success")
		window.location = "http://54.84.108.88/riddle.php?website=" + url, '_blank';
}

beginEgg();

