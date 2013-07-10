
$("nav").navi({
    hash: "#!/",
    content: $("#content"),
    animationType: "slideUp",
    animationSpeed: 400,
    ajaxFolder: "./pages/"
})


$(document).ajaxComplete(function(e,r,s) {
	if (s.url == "./pages/docs.html") {
		$.ajax({
			url: "javascripts/responsive-tables.js",
			dataType: "script"
		})
	}
})