$(document).ready(function () {
$(document).on('click','ul.tabs li',function (e) {
    e.preventDefault();
    var tab_id = $(this).children().attr('href');
    var disabled= $(this).children().attr("disabled");
    jQuery('ul.tabs li').removeClass('active');
    jQuery('.tab-content').removeClass('current');
    jQuery('.tab-content').hide();
    jQuery(this).addClass('active');
    jQuery(tab_id).addClass('current');
    jQuery(tab_id).show();
  
});
});