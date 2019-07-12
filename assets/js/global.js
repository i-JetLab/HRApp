/*
 ** Just for the purposes of
 ** creating a preloader and
 ** animation on pageload.
 */
 $("body").addClass("preloader-lock"),$(window).on("load",function(){$("body").removeClass("preloader-lock"),$(".preloader").fadeOut("slow"),$(".wrapper").fadeIn()});
