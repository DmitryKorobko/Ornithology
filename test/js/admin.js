$(document).ready(function(){
	$(".iconlist").click(function(){
		var fname = $(this).data("filename");
		$("#image").val(fname);
	});
});

