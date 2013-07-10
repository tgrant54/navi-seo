var defaults = { //defaults object 
    hash: "#!/",
    content: $("#content"),
    animationType: "slideUp",
    animationSpeed: 400,
    ajaxFolder: "./pages/"
}

function changeAnimation(ani) { //change the animationType in defaults obj
    $("nav").removeData("navi");
    defaults['animationType'] = ani;
    $("nav").navi(defaults);
    console.log($(defaults.content));
}
function changeAnimationSpeed(aniSpeed) { // change the animationSpeed in defaults obj
    $("nav").removeData("navi");
    defaults['animationSpeed'] = aniSpeed;
    $("nav").navi(defaults);
}

$("[name='animation']").change(function() { // listen for the the animation select boxes change event
    $("#content").removeAttr("class").css("height","");
    var anim = $(this).val();
    changeAnimation(anim);
})
$("[name='animationSpeed']").change(function() { // list for the animation speed change event --> Have to click outside of box to fire.
    $("#content").removeAttr("class").css("height","");
    var speed = $(this).val();
    changeAnimationSpeed(speed);
})
