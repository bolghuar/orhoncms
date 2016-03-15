<?php
/**
 * WordPress Traditional Mongolian Localization Patches Collection by OrhonTeam
 */
 
/**
 * Legacy database options cleanup
 *
 * Cleanup of all options that were introduced pre-3.4.
 * To save time, this function is only to be called on admin pages.
 *
 * @since 3.4.0
 */
function mn_l10n_legacy_option_cleanup() {
	// 3.3 series
	delete_site_option( 'mn_l10n_preference_patches' );

	// 3.0.5, 3.1 series, 3.2 series
	delete_site_option( 'mn_language_pack_enable_chinese_fake_oembed' );

	// 3.0.1, 3.0.2, 3.0.3, 3.0.4
	delete_site_option( 'mn_language_pack_options_version' );
	delete_site_option( 'mn_language_pack_enable_backend_style_modifications' );

	// awkward ones...
	delete_site_option( 'mn_language_pack_enable_icpip_num_show' );
	delete_site_option( 'mn_language_pack_icpip_num' );
	delete_site_option( 'mn_language_pack_is_configured' );

}
add_action( 'admin_init', 'mn_l10n_legacy_option_cleanup' );

/**
 * Tudou wp_embed handler
 *
 * Embed code last updated:
 *  Tue, 05 Jun 2012 22:23:03 -0400
 *
 * Feel free to submit or correct URL formats here:
 *  http://cn.wordpress.org/contact/
 *
 * @since 3.4.0
 */
function wp_embed_handler_tudou( $matches, $attr, $url, $rawattr ) {
	$embed = sprintf(
		'<embed src="http://www.tudou.com/v/%1$s/&resourceId=0_05_05_99&bid=05/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="480" height="400"></embed>',
		esc_attr( $matches['video_id'] ) );

	return apply_filters( 'embed_tudou', $embed, $matches, $attr, $url, $rawattr );
}
wp_embed_register_handler( 'tudou',
	'#https?://(?:www\.)?tudou\.com/(?:programs/view|listplay/(?<list_id>[a-z0-9_=\-]+))/(?<video_id>[a-z0-9_=\-]+)#i',
	'wp_embed_handler_tudou' );


/**
 * 56.com wp_embed handler
 *
 * Embed code last updated:
 *  Tue, 05 Jun 2012 23:03:29 -0400
 *
 * Feel free to submit or correct URL formats here:
 *  http://cn.wordpress.org/contact/
 *
 * @since 3.4.0
 */
function wp_embed_handler_56com( $matches, $attr, $url, $rawattr ) {
	$matches['video_id'] = $matches['video_id1'] == '' ?
		$matches['video_id2'] : $matches['video_id1'];

	$embed = sprintf(
		"<embed src='http://player.56.com/v_%1\$s.swf'  type='application/x-shockwave-flash' width='480' height='400' allowFullScreen='true' allowNetworking='all' allowScriptAccess='always'></embed>",
		esc_attr( $matches['video_id'] ) );

	return apply_filters( 'embed_56com', $embed, $matches, $attr, $url, $rawattr );
}
wp_embed_register_handler( '56com',
	'#https?://(?:www\.)?56\.com/[a-z0-9]+/(?:play_album\-aid\-[0-9]+_vid\-(?<video_id1>[a-z0-9_=\-]+)|v_(?<video_id2>[a-z0-9_=\-]+))#i',
	'wp_embed_handler_56com' );


/**
 * Youku wp_embed handler
 *
 * Embed code last updated:
 *  Wed, 06 Jun 2012 00:36:11 -0400
 *
 * Feel free to submit or correct URL formats here:
 *  http://cn.wordpress.org/contact/
 *
 * @since 3.4.0
 */
function wp_embed_handler_youku( $matches, $attr, $url, $rawattr ) {
	$embed = sprintf(
		'<embed src="http://player.youku.com/player.php/sid/%1$s/v.swf" allowFullScreen="true" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>',
		esc_attr( $matches['video_id'] ) );

	return apply_filters( 'embed_youku', $embed, $matches, $attr, $url, $rawattr );
}
wp_embed_register_handler( 'youku',
	'#https?://v\.youku\.com/v_show/id_(?<video_id>[a-z0-9_=\-]+)#i',
	'wp_embed_handler_youku' );


add_filter( 'gettext_with_context', 'orhoncms_disable_open_sans', 888, 4 );
function orhoncms_disable_open_sans( $translations, $text, $context, $domain ) {
  if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
    $translations = 'off';
  }
  return $translations;
}


/*Dash*/
function orohn_dash_remove_submenu() {    
    remove_submenu_page( 'index.php', 'update-core.php' );
    remove_submenu_page( 'index.php', 'index.php' );
}
add_action('admin_init','orohn_dash_remove_submenu');

/**
  * 屏蔽后台页脚版本号
  */
 function change_footer_admin () {return '';}
 add_filter('admin_footer_text', 'change_footer_admin', 9999);
 function change_footer_version() {return 'orhon CMS 1.0.0';}
 add_filter( 'update_footer', 'change_footer_version', 9999);
 /*
  * 屏蔽后台导航栏LOGO
 */
 function annointed_admin_bar_remove() {
         global $wp_admin_bar;
         /* Remove their stuff */
         $wp_admin_bar->remove_menu('wp-logo');
 }
 add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);

 /*
 *禁止，插件更新，主题更新，wordpress本身更新提示的方法
 */
add_filter('pre_site_transient_update_core',    create_function('$a', "return null;")); // 关闭核心提示

add_filter('pre_site_transient_update_plugins', create_function('$a', "return null;")); // 关闭插件提示

add_filter('pre_site_transient_update_themes',  create_function('$a', "return null;")); // 关闭主题提示

remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新

remove_action('admin_init', '_maybe_update_plugins'); // 禁止 WordPress 更新插件

remove_action('admin_init', '_maybe_update_themes');  // 禁止 WordPress 更新主题
 
 //移除 WordPress 仪表盘欢迎面板
remove_action('welcome_panel', 'wp_welcome_panel');
 
 /**
  * 屏蔽后台仪表盘无用模块
  */
function orhon_remove_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

	//custom dashboard widget
	// wp_add_dashboard_widget('feed_orhon_dashboard_widget', __('Mongolian WordPress News'), 'dashboard_custom_feed_orhon_output');

}
 add_action('wp_dashboard_setup', 'orhon_remove_custom_dashboard_widgets' );
 
//订阅蒙古文Wordpress
function dashboard_custom_feed_orhon_output() {
     echo '<div class="_orhon-rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'http://www.orhoncms.org/wordpress/', //rss地址
          'title' => __('Mongolian WordPress News'),
          'items' => 6,         //显示篇数
          'show_summary' => 0,  //是否显示摘要，1为显示
          'show_author' => 0,   //是否显示作者，1为显示
          'show_date' => 1  )); //是否显示日期
     echo '</div>';
}

//自定义 WordPress 后台底部的版权信息
function orhon_remove_footer_admin () {
	echo _e( 'Powerd by <a target="_blank" href="http://www.orhoncms.org/"><font color=blue>Orhon Mongolian Informatization Academy</font></a>. ' );
}
add_filter('admin_footer_text', 'orhon_remove_footer_admin');

function orhon_login_headerurl () {
	return __( 'http://www.orhoncms.org/' );
}
add_filter( 'login_headerurl', 'orhon_login_headerurl' );

function orhon_login_headertitle () {
	return __( 'Powered by OrhonCMS' );
}
add_filter( 'login_headertitle', 'orhon_login_headertitle' );

function orhon_widget_meta_poweredby () {
	 $orhon_powerdby=sprintf( '<li><a href="%s" title="%s">%s</a></li>',esc_url( __( 'http://www.orhoncms.org/' ) ),esc_attr__( 'Powered by WordPress, state-of-the-art semantic personal publishing platform.' ),_x( 'OrhonCMS.org', 'meta widget link text' ) );
	 return $orhon_powerdby;
}
add_filter( 'widget_meta_poweredby',  'orhon_widget_meta_poweredby');

/**login page**/
//自定义登录页面的LOGO图片
function orhon_custom_login() {  
    echo '<link rel="stylesheet" type="text/css" href="' . WP_CONTENT_URL . '/languages/css/login_style.css" />';
    echo '<style type="text/css">
        h1 a { background-image:url('.WP_CONTENT_URL.'/languages/images/login_orhon.png) !important; }
        
    </style>';
    // wp_enqueue_script('orhon-U2M');
}
add_action('login_head', 'orhon_custom_login');

//自定义底部信息
function custom_login_html() {
    echo '<div class="orhon_login_footer">'.'<p style="text-align:left">Copyright © ' .'<a href="'. get_bloginfo('url').'"> '. get_bloginfo('name').' </a>'.'All Rights Reserved.'.'</p>'.'<br/>'.'<p>'.'Powered by '.'<a href="http://www.orhoncms.org">'.'OrhonTeam'.'</a>'.'</p>'.'</div>';
}
add_action('login_footer', 'custom_login_html');

//禁用用户登录验证
remove_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );

/*custom editor*/
add_action('init', 'orhon_custom_editor');   
function orhon_custom_editor() {   
    //判断用户是否有编辑文章和页面的权限   
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {   
        return;   
    }   
    //判断用户是否使用可视化编辑器   
    if ( get_user_option('rich_editing') == 'true' ) {   
        add_filter( 'mce_external_plugins', 'orhon_custom_editor_plugins' );   
        //add_filter( 'mce_buttons', 'register_custom_button' );   
    }   
}  
function register_custom_button( $buttons ) {   
    array_push( $buttons, "|"); //添加 一个chinese按钮(, "chinese" )
    return $buttons;   
} 
/* Add the TinyMCE VisualBlocks Plugin */
add_filter('mce_external_plugins', 'orhon_custom_editor_plugins');
function orhon_custom_editor_plugins () {
     $plugins = array('orhon_editor_expand'); //Add any more plugins you want to load here (,'chinese')
     $plugins_array = array();
     //Build the response - the key is the plugin name, value is the URL to the plugin JS
     foreach ($plugins as $plugin ) {
          $plugins_array[ $plugin ] = content_url('/languages/js/editor-plugins/'.$plugin.'.js');
     }
     return $plugins_array;
}


/*coscumiz sanitize_title_with_dashes function*/
function mgl2enword($str) 
	{ 
	  $a = array("᠀" , "᠁" , "᠂" , "᠃" , "᠄" , "᠅" , "᠆" , "᠇" , "᠊" , "᠋" , "᠌" , "᠍" , "᠎" , "ᠠ" , "ᠡ" , "ᠢ" , "ᠣ" , "ᠤ" , "ᠥ" , "ᠦ" , "ᠧ", "ᠨ" , "ᠩ" , "ᠪ" , "ᠫ" , "ᠬ" , "ᠭ" , "ᠮ" , "ᠯ" , "ᠰ" , "ᠱ" , "ᠲ" , "ᠳ" , "ᠴ" , "ᠵ" , "ᠶ" , "ᠷ" , "ᠸ" , "ᠹ" , "ᠺ" , "ᠻ" , "ᠼ" , "ᠽ" , "ᠾ" , "ᠿ" , "ᡀ" , "ᡁ" , "ᡂ" , "‌" , "‍" , " " , "⁈" , "⁉" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "" , "︔" , "︕" , "︖" , "︱" , "︵" , "︶" , "︹" , "︺" , "︽" , "︾" , "︿" , "﹀" , "﹁" , "﹂" , "﹈"); 
	  $b = array("", "", "", "", "", "", "", "", "", "᠋", "᠌", "᠍", "_", "a", "e", "i", "w", "v", "o", "w", "eE", "n", "N", "b", "p", "h", "g", "m", "l", "s", "x", "t", "d", "q", "j", "y", "r", "E", "f", "k", "K", "z", "Z", "H", "R", "L", "d", "d", "‌", "‍", "_", "", "", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "i", "i", "i", "i", "w", "w", "w", "w", "w", "w", "w", "w", "w", "v", "v", "v", "v", "v", "v", "v", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "eE", "eE", "E", "E", "n", "n", "n", "n", "n", "n", "n", "n", "n", "n", "N", "N", "N", "N", "n", "n", "b", "b", "b", "b", "b", "b", "b", "p", "p", "p", "p", "p", "p", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "h", "g", "g", "g", "g", "g", "g", "g", "g", "g", "g", "g", "g", "g", "m", "m", "m", "m", "m", "m", "l", "l", "l", "l", "l", "l", "s", "s", "s", "s", "s", "s", "x", "x", "x", "x", "x", "t", "t", "t", "t", "t", "t", "t", "t", "d", "d", "d", "d", "d", "q", "q", "q", "j", "j", "j", "j", "j", "j", "y", "y", "y", "y", "r", "r", "r", "r", "r", "r", "r", "E", "E", "b", "E", "f", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "k", "c", "c", "c", "z", "z", "z", "eZ", "Z", "Z", "R", "R", "R", "L", "L", "L", "L", "oo", "j", "u", "u", "", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_"); 
	  return str_replace($a, $b, $str);   
} 
function orhon_sanitize_title( $title, $fallback_title = '', $context = 'save' ) {
	$raw_title = $title;

	if ( 'save' == $context )
		$title = remove_accents($title);

	/**
	 * Filter a sanitized title string.
	 *
	 * @since 1.2.0
	 *
	 * @param string $title     Sanitized title.
	 * @param string $raw_title The title prior to sanitization.
	 * @param string $context   The context for which the title is being sanitized.
	 */
	$title = apply_filters( 'orhon_sanitize_title', $title, $raw_title, $context );

	if ( '' === $title || false === $title )
		$title = $fallback_title;

	return $title;
}
function orhon_sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display' ) {
	$title = strip_tags($title);
	$title = mgl2enword($title);/*check mgl word*/
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');
	
	return $title;
}
add_filter( 'orhon_sanitize_title',           'orhon_sanitize_title_with_dashes',   10, 3 );

function orhon_filter_the_content_img($content) {
    if (is_feed()) return $content;
    return preg_replace_callback('/(<\s*img)([^>]+)(width\s*=\s*"[^"]+")([^>]+)(height\s*=\s*"[^"]+")([^>]+)([^>]+>)/i', 'orhon_preg_replace_callback', $content);
}

function orhon_preg_replace_callback($matches) {
    $imgfind = array("\"", "=", "width", "height");
    $imgboxstart = '<span class="orhonimgbox" style="width:'.str_replace($imgfind, "", $matches[5]).
    'px; height:'.str_replace($imgfind, "", $matches[3]).
    'px;">';
    $imgboxend = "</span>";
    $replacement = $imgboxstart.$matches[1].$matches[2].$matches[3].$matches[4].$matches[5].$matches[6].$matches[7].$imgboxend;
    return $replacement;
}
if (is_single()||is_page()||is_singular()) {
	add_filter('the_content', 'orhon_filter_the_content_img' ,   10, 3 );
}

function orhon_get_ssl_avatar($avatar) {
   $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
   return $avatar;
}
add_filter('get_avatar', 'orhon_get_ssl_avatar');

function orhoncms_settings_api_init() {
	// Add the section to reading settings so we can add our
	// fields to it
	add_settings_section(
		'orhoncms_orhonjsime_section',
		__( 'OrhonIME Admin Settings Section' ),
		'orhoncms_orhonjsime_section_callback_function',
		'general'
	);
	add_settings_section(
		'orhoncms_orhonjsime_front_section',
		__( 'OrhonIME Frontend Settings Section' ),
		'orhoncms_orhonjsime_section_callback_function',
		'general'
	);
	// admin page orhonime settings
	add_settings_field(
		'orhoncms_orhonjsime_status_admin',
		__( 'disactive in admin' ),
		'orhoncms_orhonjsime_status_admin_callback_function',
		'general',
		'orhoncms_orhonjsime_section'
	);
	add_settings_field(
		'orhoncms_orhonjsime_init_script_target',
		__( 'Target element' ),
		'orhoncms_orhonjsime_init_script_target_callback_function',
		'general',
		'orhoncms_orhonjsime_section'
	);
	add_settings_field(
		'orhoncms_orhonjsime_init_script_except',
		__( 'except element' ),
		'orhoncms_orhonjsime_init_script_except_callback_function',
		'general',
		'orhoncms_orhonjsime_section'
	);

	//front page orhonime settings
	add_settings_field(
		'orhoncms_orhonjsime_status_front',
		__( 'disactive in frontend' ),
		'orhoncms_orhonjsime_status_front_callback_function',
		'general',
		'orhoncms_orhonjsime_front_section'
	);
	add_settings_field(
		'orhoncms_orhonjsime_init_front_script_target',
		__( 'Target element' ),
		'orhoncms_orhonjsime_init_front_script_target_callback_function',
		'general',
		'orhoncms_orhonjsime_front_section'
	);
	add_settings_field(
		'orhoncms_orhonjsime_init_front_script_except',
		__( 'except element' ),
		'orhoncms_orhonjsime_init_front_script_except_callback_function',
		'general',
		'orhoncms_orhonjsime_front_section'
	);
	// for code transform
	add_settings_section(
		'orhoncms_orhonjsime_output_unicode',
		__( 'Orhonjsime Output by mcode in every client' ),
		'orhoncms_orhonjsime_output_unicode_callback_function',
		'general'
	);
	add_settings_section(
		'orhoncms_codetrans_all_to_mcode',
		__( 'Convert all page to mcode' ),
		'orhoncms_codetrans_all_to_mcode_callback_function',
		'general'
	);
	// // Register our setting so that $_POST handling is done for us and
	// our callback function just has to echo the <input>
	register_setting( 'general', 'orhoncms_orhonjsime_status_admin' );
	register_setting( 'general', 'orhoncms_orhonjsime_init_script_target' );
	register_setting( 'general', 'orhoncms_orhonjsime_init_script_except' );

	register_setting( 'general', 'orhoncms_orhonjsime_status_front' );
	register_setting( 'general', 'orhoncms_orhonjsime_init_front_script_target' );
	register_setting( 'general', 'orhoncms_orhonjsime_init_front_script_except' );

	register_setting( 'general', 'orhoncms_orhonjsime_output_unicode' );
	register_setting( 'general', 'orhoncms_codetrans_all_to_mcode' );
	// register_setting( 'general', 'orhoncms_script_tooltip' );
}  
add_action( 'admin_init', 'orhoncms_settings_api_init' );

// This function is needed if we added a new section. This function 
// will be run at the start of our section
//
 
 function orhoncms_orhonjsime_section_callback_function() {
 	echo __( '<p>check under the option manage if active orhonjsime or not and init item</p>');
 }
 // frontend tools callback
 function orhoncms_script_tool_section_callback_function() {
 	echo __( '<p>check if active these in front</p>');
 }
 // ------------------------------------------------------------------
 // Callback function for our orhonjsims status 
 // ------------------------------------------------------------------
 //
 // if is checked will active orhonjsime
 //
 function orhoncms_orhonjsime_status_admin_callback_function() {
 	echo '<input name="orhoncms_orhonjsime_status_admin" id="orhoncms_orhonjsime_status_admin" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'orhoncms_orhonjsime_status_admin' ), false ) . ' /> ';
 }
 function orhoncms_orhonjsime_init_script_target_callback_function() {
	echo '<input name="orhoncms_orhonjsime_init_script_target" type="text" id="orhoncms_orhonjsime_init_script_target" value="' . esc_attr( get_option( 'orhoncms_orhonjsime_init_script_target' ) ) . '" class="notime regular-text" />';
	echo '<p class="description">' . __( 'plaes insert the target element by our rules like : element\'s id(#id), element\'s class(.class), element\'s name(@name). and separated by commas<,>.' ) . '</p>';
 }
 function orhoncms_orhonjsime_init_script_except_callback_function() {
	echo '<input name="orhoncms_orhonjsime_init_script_except" type="text" id="orhoncms_orhonjsime_init_script_except" value="' . esc_attr( get_option( 'orhoncms_orhonjsime_init_script_except' ) ) . '" class="notime regular-text" />';
	echo '<p class="description">' . __( 'plaes insert the except element by our rules like : element\'s id(#id), element\'s class(.class), element\'s name(@name). and separated by commas<,>.' ) . '</p>';
 }
// frontend orhonjsime settings callback 
function orhoncms_orhonjsime_status_front_callback_function() {
 	echo '<input name="orhoncms_orhonjsime_status_front" id="orhoncms_orhonjsime_status_front" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'orhoncms_orhonjsime_status_front' ), false ) . ' /> ';
}
 function orhoncms_orhonjsime_init_front_script_target_callback_function() {
	echo '<input name="orhoncms_orhonjsime_init_front_script_target" type="text" id="orhoncms_orhonjsime_init_front_script_target" value="' . esc_attr( get_option( 'orhoncms_orhonjsime_init_front_script_target' ) ) . '" class="notime regular-text" />';
	echo '<p class="description">' . __( 'plaes insert the target element by our rules like : element\'s id(#id), element\'s class(.class), element\'s name(@name). and separated by commas<,>.' ) . '</p>';
 }
 function orhoncms_orhonjsime_init_front_script_except_callback_function() {
	echo '<input name="orhoncms_orhonjsime_init_front_script_except" type="text" id="orhoncms_orhonjsime_init_front_script_except" value="' . esc_attr( get_option( 'orhoncms_orhonjsime_init_front_script_except' ) ) . '" class="notime regular-text" />';
	echo '<p class="description">' . __( 'plaes insert the except element by our rules like : element\'s id(#id), element\'s class(.class), element\'s name(@name). and separated by commas<,>.' ) . '</p>';
 }
 // tooltip callback
 function orhoncms_script_tooltip_front_callback_function() {
 	echo '<input name="orhoncms_script_tooltip" id="orhoncms_script_tooltip" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'orhoncms_script_tooltip' ), false ) . ' /> ';
 }
// for code trans settings call back 
function orhoncms_codetrans_all_to_mcode_callback_function(){
	echo '<input name="orhoncms_codetrans_all_to_mcode" id="orhoncms_codetrans_all_to_mcode" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'orhoncms_codetrans_all_to_mcode' ), false ) . ' /> ';
}
function orhoncms_orhonjsime_output_unicode_callback_function(){
	echo '<input name="orhoncms_orhonjsime_output_unicode" id="orhoncms_orhonjsime_output_unicode" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'orhoncms_orhonjsime_output_unicode' ), false ) . ' /> ';
}
/*注册全局样式和脚本*/
function orhon_matrix_screens_style_script() {
	wp_deregister_script( 'editor-expand' );
	/*add editoer style*/
	add_editor_style(content_url('/languages/css/orhon_mn_editor_style.css' ));
	/*add orhonclib code translate script*/
	wp_register_script('orhonmclib',content_url(( WP_DEBUG ? '/languages/js/orhonmclib.js':'/languages/js/orhonmclib.min.js') ) , $deps = array(), '201401109');
	// wp_register_script('orhon-U2M',content_url(( WP_DEBUG ?'/languages/js/orhon-U2M.js' :'/languages/js/orhon-U2M.js')) , $deps = array('orhonmclib'), '201401109');
	/*orhon font load style*/
	wp_register_style('orhonfont',content_url('/languages/css/orhonfont.css' ) , $deps = array(), '201401109');
	/*orhon chage html select to jq div*/
	wp_register_script('orhon-Select2Div',content_url(( WP_DEBUG ? '/languages/js/orhonwSelect.mn.js' :'/languages/js/orhonwSelect.mn.min.js')) ,array('jquery'), '201401109');
	wp_register_style( 'orhon-Select2Div',content_url( ( WP_DEBUG ? '/languages/css/orhonwselect.css' : '/languages/css/orhonwselect.min.css' ) ),	array(),'201401109');
	/*form submit code trans*/
	wp_register_script('orhon-codetrans2form',content_url(( WP_DEBUG ?'/languages/js/codetrans2form.js': '/languages/js/codetrans2form.min.js')) , $deps = array('jquery','orhonmclib','jquery-form'), '201401109');
	/*orhon fix chinese*/
	wp_register_script('orhonfixchinese',content_url('/languages/js/chinesetransform.js' ) , $deps = array('jquery'), '201401109');
	wp_register_style('orhonfixchinese',content_url('/languages/css/chinesetransform.css' ) , $deps = array(), '201401109');
	wp_register_script('orhonmediafix',content_url(( WP_DEBUG ?'/languages/js/orhonmediafix.js' :'/languages/js/orhonmediafix.min.js')) , $deps = array('jquery'), '201401109');
	/*orhon ime*/
	wp_register_style('orhonkbuicss',content_url(( WP_DEBUG ? '/languages/js/orhonjsime/css/jquery-ui.css':'/languages/js/orhonjsime/css/jquery-ui.min.css')) , array(), '201401109');
	wp_register_style('orhonkbcss',content_url(( WP_DEBUG ? '/languages/js/orhonjsime/css/orhonkb.css': '/languages/js/orhonjsime/css/orhonkb.min.css')) , array('orhonkbuicss'), '201401109');
	wp_register_script('orhonjseasydrag',content_url(( WP_DEBUG ? '/languages/js/orhonjsime/js/jquery.easydrag.js':'/languages/js/orhonjsime/js/jquery.easydrag.min.js')) , array('jquery-ui-core', 'jquery-ui-mouse'), '201401109');
	/*ime min*/
	wp_register_script('OrhonIME',"http://ime.orhonit.com/orhonjsime/js/OrhonIME.js" , array('jquery','orhonjseasydrag'), '201401109');
	wp_register_script('orhoncustomlib',content_url(( WP_DEBUG ?'/languages/js/orhoncustomlib.js':'/languages/js/orhoncustomlib.js')) , array('jquery','orhonmclib'), '201401109');
	wp_register_style('orhoncustomstyle',content_url(( WP_DEBUG ? '/languages/css/orhoncustomstyle.css': '/languages/css/orhoncustomstyle.css')) , array(), '201401109');

	//nprogress 
	wp_register_script('nprogress',content_url(( WP_DEBUG ?'/languages/js/nprogress/nprogress.js':'/languages/js/nprogress/nprogress.js')) , array('jquery'), '20151103');
	wp_register_style('nprogress',content_url(( WP_DEBUG ? '/languages/js/nprogress/nprogress.css': '/languages/js/nprogress/nprogress.css')) , array('orhonkbuicss'), '201401109');
	
	//translate select element with jquery lib
	wp_register_script('orhoncms-select2', content_url( ( WP_DEBUG ? '/languages/js/select/js/select2.js' : '/languages/js/select/js/select2.min.js' ) ), array('jquery'), '4.0.2', false );
	wp_register_style('orhoncms-select2', content_url( ( WP_DEBUG ? '/languages/js/select/css/select2.min.css' : '/languages/js/select/css/select2.min.css' ) ), '4.0.2', false);

	wp_register_style( 'mn-l10n-administration-screens',content_url( ( WP_DEBUG ? '/languages/mn-administration.css' : '/languages/mn-administration.min.css' ) ),array( 'orhonfont','wp-admin','orhon-Select2Div', 'nprogress', 'orhoncms-select2' ),'20111120');

}
add_action( 'init','orhon_matrix_screens_style_script' );

function orhoncms_is_need2trans_u2m(){
	global $is_safari, $is_chrome, $is_opera;
	if (wp_is_mobile()||$is_safari||$is_opera||get_option("orhoncms_codetrans_all_to_mcode")) {
		return true;
	}else{
		return false;
	}
	
}
//trans code
function orhoncms_script_page_load_trans_u2m() {
	if (orhoncms_is_need2trans_u2m()) {
		if (!wp_script_is( 'orhonmclib', 'enqueued' )) {
			wp_enqueue_script('orhonmclib');
		}
		$trasscript ='<style>';
		$trasscript .='
			body{
				visibility: hidden;
			}
		';
		$trasscript .='</style>';
		$trasscript .='<script type="text/javascript">';
		$trasscript .='
				if(document.readyState=="4") {
					shapeNode(document.body);
				}else{
					addEvent(window,\'load\',function(){
						shapeNode(document.body);
					});	
				}
				function addEvent(el,ev,f){
					if (window.addEventListener) {
						el.addEventListener(ev , f, false);
					}else {
						el.attachEvent("on" + ev , f );
					}
				}

				function shapeNode(rt){
					var bgfix = document.createElement("div");
						bgfix.id = "bgfix";
						document.body.appendChild(bgfix);
						bgfix.style.position="fixed";
						bgfix.style.top="0";
						bgfix.style.left="0";
						bgfix.style.right="0";
						bgfix.style.bottom="0";
						bgfix.style.background="#444";

					document.body.appendChild(bgfix);
					
					if (typeof(NProgress)!=="undefined") {
						NProgress.start();
					}

					if (typeof(Unicode2M) == "function") {
						if(document.createTreeWalker){
							var tw = document.createTreeWalker(rt,NodeFilter.SHOW_TEXT,null,false);
							var a = 0;
							while(tw.nextNode()){
							    
								tw.currentNode.data=Unicode2M(tw.currentNode.data);
							}
						}else{
							var n = rt.childNodes[0];
							while(n != null) {
								if(n.nodeType == 3) {
									n.nodeValue=Unicode2M(n.nodeValue);
								}
								if(n.hasChildNodes()) {
									n = n.firstChild;
								}else{
									while(n!=null && n.nextSibling == null && n != rt) {
										n=n.parentNode;
									}
									if(n!=null) n=n.nextSibling;
								}
							}	
						};
						var t = document.querySelectorAll("input[type=button], input[type=submit], input[type=reset],input[type=text],input[type=search]");
						for (var i = 0; i < t.length; i++) {
							t[i].value=t[i].value==""?"":Unicode2M(t[i].value);
						}
					}
					// bgfix.remove();
					document.body.removeChild(bgfix);
					document.body.style.visibility="visible";
					if (typeof(NProgress)!=="undefined") {
						NProgress.done(); 
					}
				};
		';
		$trasscript .="</script>";
	}else{
		$trasscript ='';
	}
	
	echo $trasscript;
}
/**
* orhonime init 
*/
function orhoncms_script_orhonjsime_admin_init() {
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		orhonIMEinit = {
				element:"'.esc_attr( get_option( 'orhoncms_orhonjsime_admin_init_target' ) ).'", 
				ele_except:"'.esc_attr( get_option( 'orhoncms_orhonjsime_init_script_except' ) ).'#orhoncms_orhonjsime_init_front_script_target,#orhoncms_orhonjsime_init_front_script_except,#user_login,#orhoncms_orhonjsime_init_script_except,@login,@email,#post_name,@hh,@mn,@aa,@jj,@menu_order,@trackback_url,@time_format_custom,@date_format_custom,@mailserver_url,@mailserver_login,@mailserver_pass,@permalink_structure,@category_base,@tag_base,@slug,@user_login,@email,@url,@post_name,@post_password,@menu-name",
				editor:{
					IME_Mount:true,
					tinymce:"",
					ueditor:"",
				}, 
				IME_cssurl:"/wp-content/languages/js/orhonjsime/" ,
				Output_Unicode:'.(get_option("orhoncms_orhonjsime_output_unicode")?"false":"true").',
		};
		Iframe = {
				iframes:"",
				ifr_except:"",
				element:"",
				ele_except:"",
		};
		// jQuery(window).load(function(){
		// 	jQuery("iframe").each(function(){
		// 		this.onload=function(){IME_iframe_init(Iframe)};
		// 	});
		// });
	';

	$trasscript .="</script>";
	echo $trasscript;
}
function orhoncms_script_orhonjsime_frontend_init() {
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		orhonIMEinit = {
				element:"'.esc_attr( get_option( 'orhoncms_orhonjsime_frontend_init_target' ) ).'", 
				ele_except:"'.esc_attr( get_option( 'orhoncms_orhonjsime_frontend_init_script_except' ) ).'",
				editor:{
					IME_Mount:true,
					tinymce:"",
					ueditor:"",
				}, 
				IME_cssurl:"/wp-content/languages/js/orhonjsime/" ,
				Output_Unicode:'.(get_option("orhoncms_orhonjsime_output_unicode")?"false":"true").',
		};
		Iframe = {
				iframes:"",
				ifr_except:"",
				element:"",
				ele_except:"",
		};
		// jQuery(window).load(function(){
		// 	jQuery("iframe").each(function(){
		// 		this.onload=function(){IME_iframe_init(Iframe)};
		// 	});
		// });
	';

	$trasscript .="</script>";
	echo $trasscript;
}

function orhoncms_script_codetrans2form() {
	if (!wp_script_is( 'orhonmclib', 'enqueued' )) {
		wp_enqueue_script('orhonmclib');
	}
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		jQuery(document).ready(function() {
			jQuery(\'form\').submit(function(e) {
				formshapeNode(this);
				if ( (typeof tinymce !== \'undefined\' ) && tinymce.activeEditor&&tinymce.activeEditor.getContent() && (typeof(Unicode2M) == "function")) {
	              	tinymce.activeEditor.setContent(M2Unicode(tinymce.activeEditor.getContent()));
	            }
				function formshapeNode(rt){
					var bgfix = document.createElement("div");
						bgfix.id = "bgfix";
						document.body.appendChild(bgfix);
						bgfix.style.position="fixed";
						bgfix.style.top="0";
						bgfix.style.left="0";
						bgfix.style.right="0";
						bgfix.style.bottom="0";
						bgfix.style.background="#444";

					document.body.appendChild(bgfix);
					
					if (typeof(NProgress)!=="undefined") {
						NProgress.start();
					}
					if (typeof(Unicode2M) == "function") {
						if(document.createTreeWalker){
							var tw = document.createTreeWalker(rt,NodeFilter.SHOW_TEXT,null,false);
							var a = 0;
							while(tw.nextNode()){
							    
								tw.currentNode.data=M2Unicode(tw.currentNode.data);
							}
						}else{
							var n = rt.childNodes[0];
							while(n != null) {
								if(n.nodeType == 3) {
									n.nodeValue=M2Unicode(n.nodeValue);
								}
								if(n.hasChildNodes()) {
									n = n.firstChild;
								}else{
									while(n!=null && n.nextSibling == null && n != rt) {
										n=n.parentNode;
									}
									if(n!=null) n=n.nextSibling;
								}
							}	
						};
						var t = rt.querySelectorAll(\'input[type=button], input[type=submit], input[type=reset],input[type=text],input[type=search]\');
						for (var i = 0; i < t.length; i++) {
							t[i].value=t[i].value==""?"":M2Unicode(t[i].value);
						}
					}
					// bgfix.remove();
					document.body.removeChild(bgfix);
					if (typeof(NProgress)!=="undefined") {
						NProgress.done(); 
					}
				};
			});
		});
	';
	$trasscript .="</script>";
	echo $trasscript;
}

// tooltip
function orhoncms_script_tooltip() {
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		jQuery(function() {
		    jQuery("a,span,td,tr,b").each(function(b) {
		        if (this.title) {
		            var title = this.title;
		            var pian = 30;
		            if (typeof(Unicode2M) == "function") {
		            	title = Unicode2M(title);
		            };
		            jQuery(this).mouseover(function(d) {
		                this.title = "";
		                jQuery("body").append(\'<div id="tooltip">\' + title + "</div>");
		                jQuery("#tooltip").css({
		                    left: (d.pageX + pian) + "px",
		                    top: d.pageY + "px",
		                    opacity: "0.8"
		                }).show(250)
		            }).mouseout(function() {
		                this.title = title;
		                jQuery("#tooltip").remove()
		            }).mousemove(function(d) {
		                jQuery("#tooltip").css({
		                    left: (d.pageX + pian) + "px",
		                    top: d.pageY + "px"
		                })
		            })
		        }
		    })
		});
	';
	$trasscript .="</script>";
	echo $trasscript;
}


// media fix admin page
function orhoncms_script_admin_media_fix() {

	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		function orhonmatriximgfix(targetimg) {
	        // var winheight = parseInt(jQuery(window).height());
	        jQuery(targetimg).each(function(i) {
	        	var winheight = parseInt(jQuery(window).height());
	            var img = jQuery(this);
	            var realWidth;
	            var realHeight;
	            var ImgWidth = img.width();
	            var ImgHeight = img.height();
	            var imghtml = jQuery(this).parent();
	            var imgclass = img.attr("class");
	            var imgbox = jQuery(\'<span />\').addClass("orhonimgbox").addClass(imgclass);
	            var imgfloat = jQuery(img)[0].style.float;
	            img.appendTo(imgbox);
	            imghtml.append(imgbox);
	            var contentWidth = jQuery("#wpbody").width();
	            jQuery("<img/>").attr("src", jQuery(img).attr("src")).load(function() {
	                realWidth = this.naturalWidth;
	                realHeight = this.naturalHeight;
	                imgfloat = this.style.float;
	                scaling = realWidth / realHeight;
	                if (ImgHeight >= contentWidth) {
	                    jQuery(img).css("height", contentWidth + "px").css("width", (contentWidth) * scaling + "px").css("max-width", (contentWidth) * scaling + "px").css("max-height", contentWidth).css("box-sizing", "border-box").css("float", imgfloat);
	                    var orhonimgboxheight = jQuery(img).css("width");
	                    var orhonimgboxwidth = jQuery(img).css("height");
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", orhonimgboxwidth).css("height", orhonimgboxheight).css("display", "inline-block").css("box-sizing", "border-box");
	                } else if (ImgHeight <= contentWidth && realHeight >= contentWidth && ImgHeight !== 0) {
	                    jQuery(img).css("width", ImgHeight * scaling + "px").css("height", ImgHeight);
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", ImgHeight).css("height", ImgHeight * scaling + "px").css("display", "inline-block").css("box-sizing", "border-box").css("float", imgfloat);
	                } else {
	                    jQuery(img).css("width", realWidth).css("height", realHeight).css("max-width", realWidth);
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", realHeight).css("height", realWidth).css("display", "inline-block").css("box-sizing", "border-box").css("float", imgfloat);
	                }
	            });
	        });
	    }
	    jQuery(".wp_attachment_image img").each(function(i) {
	        if (!jQuery(this).hasClass("orhonico")) {
	            orhonmatriximgfix(this);
	        }
	    });
	';
	$trasscript .="</script>";
	echo $trasscript;
}

//mediafix frontend
function orhoncms_script_front_media_fix($elements="",$contentbox="") {
	$contentbox = $contentbox?$contentbox:"#orhonlayout";
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		function orhonmatriximgfix(targetimg) {
	        // var winheight = parseInt(jQuery(window).height());
	        jQuery(targetimg).each(function(i) {
	        	var winheight = parseInt(jQuery(window).height());
	            var img = jQuery(this);
	            var realWidth;
	            var realHeight;
	            var ImgWidth = img.width();
	            var ImgHeight = img.height();
	            var imghtml = jQuery(this).parent();
	            var imgclass = img.attr("class");
	            var imgbox = jQuery(\'<span />\').addClass("orhonimgbox").addClass(imgclass);
	            var imgfloat = jQuery(img)[0].style.float;
	            img.appendTo(imgbox);
	            imghtml.append(imgbox);
	            var contentWidth = jQuery("'.$contentbox.'").width();
	            jQuery("<img/>").attr("src", jQuery(img).attr("src")).load(function() {
	                realWidth = this.naturalWidth;
	                realHeight = this.naturalHeight;
	                imgfloat = this.style.float;
	                scaling = realWidth / realHeight;
	                if (ImgHeight >= contentWidth) {
	                    jQuery(img).css("height", contentWidth + "px").css("width", (contentWidth) * scaling + "px").css("max-width", (contentWidth) * scaling + "px").css("max-height", contentWidth).css("box-sizing", "border-box").css("float", imgfloat);
	                    var orhonimgboxheight = jQuery(img).css("width");
	                    var orhonimgboxwidth = jQuery(img).css("height");
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", orhonimgboxwidth).css("height", orhonimgboxheight).css("display", "inline-block").css("box-sizing", "border-box");
	                } else if (ImgHeight <= contentWidth && realHeight >= contentWidth && ImgHeight !== 0) {
	                    jQuery(img).css("width", ImgHeight * scaling + "px").css("height", ImgHeight).css("max-width", ImgHeight * scaling + "px");
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", ImgHeight).css("height", ImgHeight * scaling + "px").css("display", "inline-block").css("box-sizing", "border-box").css("float", imgfloat);
	                } else {
	                    jQuery(img).css("width", realWidth).css("height", realHeight).css("max-width", realWidth);
	                    jQuery(img).closest(\'.orhonimgbox\').css("width", realHeight).css("height", realWidth).css("display", "inline-block").css("box-sizing", "border-box").css("float", imgfloat);
	                }
	            });
	        });
	    }';
	    if (!$elements) {
	    	$trasscript .='
	    	jQuery(document).ready(function(){
		    	jQuery("#orhon_content_body img").each(function(i) {
			        if (!jQuery(this).hasClass("orhonico")) {
			            orhonmatriximgfix(this);
			        }
			    });
			});
		    ';
	    }else{
	    	$trasscript .='
	    	jQuery(document).ready(function(){
		    	jQuery("'.$elements.'").each(function(i) {
			        if (!jQuery(this).hasClass("orhonico")) {
			            orhonmatriximgfix(this);
			        }
			    });
			});
		    ';
	    }
	    
	$trasscript .="</script>";
	echo $trasscript;
}
/**
 * Mongolian administration screens style - enqueue stylesheet
 *
 * This patch serves as a work-around to fix the font-size and font-style.
 *
 * Submit better CSS rules here:
 *  http://www.orhoncms.org
 *
 * @since 3.9.0
 */
function orhon_l10_script_whole() {
	wp_enqueue_script('orhoncustomlib');
	wp_enqueue_style('orhoncustomstyle');
}
add_action( 'init','orhon_l10_script_whole' );

/**fix for ie**/
function mn_l10n_patch_admin_style_fix_ie() {
	global $is_IE;
	$u = $_SERVER['HTTP_USER_AGENT'];
	$isIE7  = (bool)preg_match('/msie 7./i', $u );
	$isIE8  = (bool)preg_match('/msie 8./i', $u );
	$isIE9  = (bool)preg_match('/msie 9./i', $u );
	if ($isIE9) {
		wp_enqueue_style( 'orhon-fix4ie9',content_url( ( WP_DEBUG ? '/languages/css/ie9.css' : '/languages/css/ie9.min.css' ) ),
			array( 'wp-admin' ),
			'20141111');
		wp_enqueue_style( 'orhon-fix4ie9' );
	}if ($isIE8) {
		wp_enqueue_style( 'orhon-fix4ie8',content_url( ( WP_DEBUG ? '/languages/css/ie8.css' : '/languages/css/ie8.css' ) ),
			array( 'wp-admin' ),
			'20141111');
		wp_enqueue_style( 'orhon-fix4ie8' );
	}
	
}
add_action( 'admin_init','mn_l10n_patch_admin_style_fix_ie' );

/**
 * Mongolian fix script
 *
 * This patch serves as a page fix to mogolian Writing habits .
 * @since 3.9.0
 */

function mn_l10n_patch_admin_screens_style_enqueue_stylesheet() {
	wp_enqueue_style( 'mn-l10n-administration-screens' );
	wp_enqueue_script('orhonl10n',content_url('/languages/js/orhonl10n.js' ) , array('jquery','orhonmclib','jquery-form','orhon-Select2Div','nprogress','orhoncms-select2'), '201401109');

}
add_action( 'admin_init','mn_l10n_patch_admin_screens_style_enqueue_stylesheet' );

function mn_l10n_patch_admin_screens_style_script() {
	//orhonime settings
	if (!get_option( 'orhoncms_orhonjsime_status_admin' )&&is_admin()) {
		orhoncms_script_orhonjsime_admin_init(); // print ime init
		wp_enqueue_style('orhonkbcss');  // active ime style
		wp_enqueue_script('OrhonIME');   // active ime script
	}
}
add_action( 'admin_print_scripts','mn_l10n_patch_admin_screens_style_script' );

function orhoncms_orhonjsime_status_front() {
	//orhonime settings
	if (!get_option( 'orhoncms_orhonjsime_status_front' )&&!is_admin()) {
		orhoncms_script_orhonjsime_admin_init(); // print ime init
		wp_enqueue_style('orhonkbcss');  // active ime style
		wp_enqueue_script('OrhonIME');   // active ime script
	}
	orhoncms_script_page_load_trans_u2m();
}
add_action( 'wp_print_scripts','orhoncms_orhonjsime_status_front' );



/**
 * Mongolian admin code trans footer
 *
 * This patch serves as a page fix to mogolian Writing habits .
 * @since 3.9.0
 */
function admin_footer_code() {
	orhoncms_script_page_load_trans_u2m();
	orhoncms_script_codetrans2form();
	orhoncms_script_tooltip();
	orhoncms_script_admin_media_fix();
}
add_action( 'admin_footer', 'admin_footer_code', 0 );

function orhoncms_script_page_fix() {
	wp_enqueue_script('jquery');
	$trasscript ='<script type="text/javascript">';
	$trasscript .='
		jQuery(window).resize(resizeContent);

		function resizeContent(){
		    var wht = jQuery(window).height();
		    var wwd = jQuery(window).width();
		    jQuery("body,#orhonlayoutbox").css("height",wht);
		    jQuery("body,#orhonlayoutbox").css("width",wwd);
		    jQuery("#orhonlayout").css("width",wht);
		    jQuery("#orhonlayout").css("height",wwd);
		}

		jQuery(document).ready(function(){
		    resizeContent();
		});
	';
	$trasscript .="</script>";
	echo $trasscript;
}

/**
 * Mongolian frontend code trans footer
 */
function frontend_footer_code() {
	if (get_option( 'orhoncms_script_tooltip' )) {
		orhoncms_script_tooltip();
	}
	orhoncms_script_codetrans2form();
}
add_action( 'wp_footer', 'frontend_footer_code', 0 );

function orhon_image_class_filter($classes) {
	return $classes . ' orhon-image';
}
add_filter('get_image_tag_class', 'orhon_image_class_filter');

// function orhon_image_tag_filter($html) {
// 	$rehtml='<span>'.$html."</span>";
// 	return $rehtml;
// }
// add_filter('get_image_tag', 'orhon_image_tag_filter');

function orhon_get_comment_link_filter($link) {
	return $link;
}
add_filter('get_comment_link', 'orhon_get_comment_link_filter');

function orhon_wp_default_scripts( &$scripts ) {
	/**
	 * for locallize the google javascript lib file
	 */
	$scripts->registered['prototype']->src="/wp-content/languages/js/googlelib/prototype.js";
	$scripts->registered['scriptaculous-root']->src="/wp-content/languages/js/googlelib/scriptaculous.js";
	$scripts->registered['scriptaculous-builder']->src="/wp-content/languages/js/googlelib/builder.js";
	$scripts->registered['scriptaculous-dragdrop']->src="/wp-content/languages/js/googlelib/dragdrop.js";
	$scripts->registered['scriptaculous-effects']->src="/wp-content/languages/js/googlelib/effects.js";
	$scripts->registered['scriptaculous-slider']->src="/wp-content/languages/js/googlelib/slider.js";
	$scripts->registered['scriptaculous-sound']->src="/wp-content/languages/js/googlelib/sound.js";
	$scripts->registered['scriptaculous-controls']->src="/wp-content/languages/js/googlelib/controls.js";

	/**
	 * for mongolian admin jquery ui libery
	 */
	$scripts->registered['jquery-ui-draggable']->src="/wp-content/languages/js/ui/jquery.ui.draggable.mn.js";
	$scripts->registered['jquery-ui-droppable']->src="/wp-content/languages/js/ui/jquery.ui.droppable.mn.js";
	$scripts->registered['jquery-ui-sortable']->src="/wp-content/languages/js/ui/jquery.ui.sortable.mn.js";

}

add_action( 'wp_default_scripts', 'orhon_wp_default_scripts' );

function orhon_wp_default_styles( &$styles ) {
	$open_sans_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		// Hotlink Open Sans, for now
		$open_sans_font_url = "//fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600&subset=$subsets";
	}
	$styles->registered['open-sans']->src=$open_sans_font_url;
}
add_action( 'wp_default_styles', 'orhon_wp_default_styles' );



?>