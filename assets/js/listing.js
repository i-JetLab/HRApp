/*
 ** Simple script to have the tabbed views on the listing page
 ** move when called to move by user click.
 */
var job_tmp="",moving=!1;$(document).on("click",".results_item.title_card",function(){$(this).hasClass("active")?!1===moving&&(moving=!0,$(this).removeClass("active"),job_tmp=$(this).attr("job"),$(".results_item_content").find(`[job='${job_tmp}']`).parent().slideUp("fast",function(){moving=!1})):!1===moving&&(moving=!0,$(this).addClass("active"),job_tmp=$(this).attr("job"),$(".results_item_content").find(`[job='${job_tmp}']`).parent().slideDown("fast",function(){moving=!1}))});
