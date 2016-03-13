var url = window.location.hostname;

function beginEgg() {
	$.ajax({
		url : 'http://localhost/resources/web/isegg.php',
		type: 'POST',
    	async: false,
		data: { website: url },
		success: runEgg
	});
}

function runEgg(response) {
	if (response == 0) {
		// nothing
	} else {
		$(document).ready(function() {
		    var elems = $('img');
			var select = Math.floor(Math.random() * elems.length);

			switch (response) {
				case 1:
					elems[select].setAttribute("src", 'http://localhost/resources/img/EggHackGreen.png');
					break;
				case 2:
					elems[select].setAttribute("src", 'http://localhost/resources/img/EggHackBlue.png');
					break;
				case 3:
					elems[select].setAttribute("src", 'http://localhost/resources/img/EggHackRed.png');
					break;
				case 4:
					elems[select].setAttribute("src", 'http://localhost/resources/img/EggHackGold.png');
					break;
			}

			elems[select].onmouseover = function() {
				$.ajax({
					url : 'http://localhost/resources/web/find.php',
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
	if (res == "success")
		window.location = "http://localhost/riddle.php?website=" + url, '_blank';
}

beginEgg();

