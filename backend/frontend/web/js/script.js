$(document).ready(function(){

  // var w = $(window).width();
  // alert(w);

  // Panel click events
  $('.card-link').click(function(){
      $(this).parent().toggleClass("active");
  })
  $('.panel-control').click(function(){
      $(this).parent().toggleClass("active");
  })

  $(".switch-field li").click(function(){
    $(this).addClass("active");
    $(".switch-field li").removeClass("active");
  })

  // Slider
  $('#lightSlider').lightSlider({
    gallery: true,
    item: 3,
    loop:true,
    slideMargin: 10,
    thumbItem: 9,
    controls: false,
    pager: false,
    auto: true,
    cssEasing: 'ease',
  });

  $("#toggle").click(function() {
    $(this).toggleClass("on");
    $("#menu").slideToggle();
  });

  $(window).scroll(function(){
    var scroll = $(window).scrollTop();
			if (scroll > 100) {
        $("header").addClass("fixed");
			} else {
        $("header").removeClass("fixed");
			}
  })
  var myVideo = document.getElementById("video");
  if(myVideo){
      function playPause() {
          if (myVideo.paused)
            myVideo.play();
          else
            myVideo.pause();
        }
  }

});
