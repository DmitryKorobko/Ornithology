function rotator1 (addr){
var ad = [{"src":"AnimatedBirdQuest.gif",
				"height":"400",
				"width":"125",
				"href":"http://www.birdquest-tours.com"},
			{"src":"ManmuExpAdAnim2013.gif",
				"height":"400",
				"width":"130",
				"href":"http://birding-in-peru.com"},
			{"src":"skyscraper_fatbirder_text.png",
				"height":"400",
				"width":"130",
				"href":"http://www.birdingecotours.com"}];
var num = Math.floor(Math.random()*3);
document.getElementById("rotate1link").href = ad[num].href;
var im = document.getElementById("rotate1image");
im.src = addr + ad[num].src;
im.height = ad[num].height;
im.width = ad[num].width;
}

function rotator3 (addr){
var ad = [{"src":"bo-advert-anim-03.gif",
				"height":"400",
				"width":"125",
				"href":"http://www.grumpyoldbirder.com/index.php?page=a-z-of-birding"},
			{"src":"ATXSTX130x400.gif",
				"height":"400",
				"width":"130",
				"href":"http://www.swarovskioptik.com/en/home"}];
var num = Math.floor(Math.random()*2);
document.getElementById("rotate3link").href = ad[num].href;
var im = document.getElementById("rotate3image");
im.src = addr + ad[num].src;
im.height = ad[num].height;
im.width = ad[num].width;
}

function rotate(){
	var addr = "/newsite/adimages/";
	rotator1(addr);
	rotator3(addr);
}
window.onload = rotate;