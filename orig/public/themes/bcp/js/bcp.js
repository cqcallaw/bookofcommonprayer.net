$(document).ready( function(){
	/* Not sure what's conflicting, but $.cookie does not work without it */
	$.noConflict();
	/* Accordion */
	$("#accordion").accordion();
	
	/* FONT SIZE ON LOAD */
	if($.cookie('font-size')) {
	    console.log($.cookie('font-size'));
	    $('body').css({ 'font-size': $.cookie('font-size')});
	}
	/* FONT SIZE FUNCTION */
	setFont = function(size) {
		if(size != "s") {
			fontSize = (size == "m") ? "18px" : "22px";
		} else {
			fontSize = "14px";
		}
		$.cookie('font-size', fontSize);
		$("body").css({"font-size" : fontSize});
	}
});