/*pagefix*/
function resizeContent(){
    var wht = jQuery(window).height();
    var wwd = jQuery(window).width();
    jQuery("body").css("height",wht);
    jQuery("#wpwrap").css('width',wht-5);
    jQuery("#wpwrap").css('min-height',wwd-36);
}
function temp_plugin_editor_resize() {
	var wwd = jQuery("#template").width();
	jQuery("#newcontent").css('height',wwd-15);
	jQuery("#newcontent").css('width',"1000px");
	jQuery('#newcontent').parent().css('width', wwd-15);
	jQuery('#newcontent').parent().css('height', "1000px");
	jQuery('#template').css('height', "1000px");
}
function customiz_view_resize() {
	var wht = jQuery(window).height();
	var wwd = jQuery(window).width();
	jQuery(".wp-full-overlay.expanded #customize-preview").css('height',wht-300);
	jQuery("#customize-preview").css('width',wwd);
	jQuery(".wp-customizer .wp-full-overlay").css('height',wwd);
}
jQuery(window).resize(function(){
	resizeContent();
	temp_plugin_editor_resize();
	if (jQuery("#customize-preview").length>0) {
		 customiz_view_resize()
	}
});
jQuery(document).ready(function(){
    resizeContent();
    temp_plugin_editor_resize();
    if (jQuery("#customize-preview").length>0) {
    	 customiz_view_resize();
    	 jQuery('.collapse-sidebar').bind('click',function() {
    	 	var wht = jQuery(window).height();
    	 	jQuery('.wp-full-overlay').hasClass('collapsed')?jQuery("#customize-preview").css('height',wht-300):jQuery("#customize-preview").css('height',wht);
    	 });
    }
    
});
/*selet2div*/
jQuery(document).ready(function(){
	jQuery('select').select2();
});