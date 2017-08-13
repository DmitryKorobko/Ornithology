function rotator1 (){
var ad = [{"src":"AnimatedBirdQuest.gif",
				"height":"400",
				"width":"125",
				"href":"http://www.birdquest-tours.com"},
			{"src":"ManmuExpAdAnim2013.gif",
				"height":"400",
				"width":"130",
				"href":"http://birding-in-peru.com"},
			{"src":"CaligoSkyscraper.gif",
				"height":"400",
				"width":"130",
				"href":"http://www.caligo.com"},
			{"src":"skyscraper_fatbirder_text.png",
				"height":"400",
				"width":"130",
				"href":"http://www.birdingecotours.com"}];
var leng = ad.length;
var num = Math.floor(Math.random()*leng);
document.getElementById("rotate1link").href = ad[num].href;
var im = document.getElementById("rotate1image");
im.src = "/adimages/" + ad[num].src;
im.height = ad[num].height;
im.width = ad[num].width;
}

function rotator2 (){
/*var ad = [{"src":"CANOPYVERTICAL.gif",
				"height":"400",
				"width":"120",
				"href":"http://www.canopytower.com"},
			{"src":"TrogonAnimation2.gif",
				"height":"400",
				"width":"120",
				"href":"http://www.trogontours.com"}];
var leng = ad.length;
var num = Math.floor(Math.random()*leng);
document.getElementById("rotate2link").href = ad[num].href;
var im = document.getElementById("rotate2image");
im.src = "/adimages/" + ad[num].src;
im.height = ad[num].height;
im.width = ad[num].width;*/
}

function rotator3 (){
var ad = [{"src":"ATXSTX130x400.gif",
				"height":"400",
				"width":"130",
				"href":"http://www.swarovskioptik.com/en/home"}];
var leng = ad.length;
var num = Math.floor(Math.random()*leng);
document.getElementById("rotate3link").href = ad[num].href;
var im = document.getElementById("rotate3image");
im.src = "/adimages/" + ad[num].src;
im.height = ad[num].height;
im.width = ad[num].width;
}

function rotate(){
	rotator1();
	rotator2();
	rotator3();
}
window.onload = rotate;