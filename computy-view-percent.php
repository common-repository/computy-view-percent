<?php
/**
 * Plugin Name: Computy view percent
 * Text Domain:  computy-view-percent
 * Plugin URI: https://computy.ru/blog/plagin-validacii-formy-kommentariev-v-wordpress/
 * Description: The plugin does not require any settings and works automatically for standard WordPress posts.
When scrolling the page, the red bar at the top increases.
 * Version: 1.5.3
 * Author: computy.ru
 * Author URI: https://computy.ru
 * License: GPL
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'VP_COMPUTY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VP_COMPUTY_VERSION', '1.5.3' );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( VP_COMPUTY_PLUGIN_DIR . 'class.vp-computy-admin.php' );
    add_action( 'init', array( 'Vp_Computy_Admin', 'init' ) );
}
function vp_computy_script() {

    ?>
    <script type="text/javascript">
        window.document.body.insertAdjacentHTML( 'afterbegin','<div id="vp-computy"></div> ' );
        jQuery(document).ready(function($) {
            $(window).scroll(function(){
                let s = $(window).scrollTop();
                let f = $(document).height()-$(window).height();
                let d=s/f*100;
                let p=Math.round(d);
                $("#vp-computy").css( "width",p+'%' );
            });
        })
    </script>
    <?php
}

function vp_computy_style() {
    $r = get_option( 'vp_option_name' );
    ?><style>
    #vp-computy{
    position: fixed;
    height: <?=$r['vp-height'];?>px;
    background: <?=$r['vp-color'];?>;
    z-index: 1000;
    transition: all 1s ease-out;
   <?php if($r['shadow']== '1'){echo 'box-shadow: 0px -1px 6px '.$r['vp-color'].';';}else{echo '';}?>
    }</style>
    <?php
}

function vpc_scripts() {
    $r = get_option( 'vp_option_name' );
    if(empty($r['static-page'])){
        add_action('wp_footer', 'vp_computy_script');
        add_action('wp_footer', 'vp_computy_style');
    }
	if ( is_single() ) {
        add_action('wp_footer', 'vp_computy_script');
        add_action('wp_footer', 'vp_computy_style');
	}
}

add_action( 'template_redirect', 'vpc_scripts' );