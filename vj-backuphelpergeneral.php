<?php
/*
Plugin Name: VJMedia: Backup Helper (General Site)
Description: for General site use, without Monitor
Version: 1.0
Author: <a href="http://www.vjmedia.com.hk/">技術組</a>
GitHub Plugin URI: https://github.com/VJMedia/vj-backuphelpergeneral
*/

defined('WPINC') || (header("location: /") && die());

/* register_activation_hook(__FILE__, 'vjbh_activation');

function vjbh_activation() {
	if (! wp_next_scheduled ( 'vjbh_event' )) {
		wp_schedule_event(time(), 'hourly', 'vjbh_event');
	}
	vjbh_do();
} add_action('vjbh_event', 'vjbh_do');

function vjbh_do() {
	$upload_dir=wp_upload_dir()["basedir"];
	file_put_contents($upload_dir."/vj-backuphelper.dat",current_time("YmdHis"));
} */

function vjbhg_dummy(){}

function vjbhg_muselfdeactive(){
	global $vjbhg_needdeactivate;
	if($vjbhg_needdeactivate){
		deactivate_plugins( VJBHG_PATH );
	}
} add_action('wp_loaded','vjbhg_muselfdeactive');

function vjbh_everyminutes( $schedules ) {
	$schedules['vjbh_everyminutes'] = ['interval'  => 60, 'display'   => 'Every Minutes'];
	return $schedules;
} add_filter( 'cron_schedules', 'vjbh_everyminutes' );

if ( ! wp_next_scheduled( 'vjbh_everyminutes' ) ) {
	wp_schedule_event( time(), 'vjbh_everyminutes', 'vjbh_everyminutes' );
}

function vjbh_everyminutes_func() {
	$upload_dir=wp_upload_dir()["basedir"];
	file_put_contents($upload_dir."/vj-backuphelper.dat",current_time("YmdHis"));
} add_action( 'vjbh_everyminutes', 'vjbh_everyminutes_func' );

function vjbh_helperfilestatus( $wp_admin_bar ) {
	
	$upload_dir=wp_upload_dir()["basedir"];
	$content=file_get_contents($upload_dir."/vj-backuphelper.dat");
	if(! preg_match('/\d{14}/',$content)){
		$color="red";
	}else{
		if ((current_time("timestamp")-strtotime($content))/60 > 1440){
			$color="red";
		}else{
			$color="#fff";
		}
	}
	
	$args = array(
		'id'    => 'vjbh',
		'title' => "Backup Helper File Status: <span style='color: {$color}'>{$content}</span>",
		'href'  => admin_url( 'admin-ajax.php' ).'?action=vjbh_rebuild&return='.urlencode((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"),
		'meta'  => array( 'class' => 'my-toolbar-page' )
	);
	$wp_admin_bar->add_node( $args );
}add_action( 'admin_bar_menu', 'vjbh_helperfilestatus', 999 );

function vjbh_rebuild_callback() {
	
	vjbh_everyminutes_func();
	
	wp_schedule_event( time(), 'vjbh_everyminutes', 'vjbh_everyminutes' );
	
	if(! parse_url($_GET["return"])["query"]){
		$symbol="?";
	}else{
		$symbol="&";
	}
		
	header("location:". $_GET["return"].$symbol."vjbh_rebuild=1");
	wp_die(); 
} add_action( 'wp_ajax_vjbh_rebuild', 'vjbh_rebuild_callback' );

function vjbh_adminnotice() {
	if($_GET["vjbh_rebuild"]){
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Rebuild backuphelper.dat: done</p>
    </div>
    <?php
}} add_action( 'admin_notices', 'vjbh_adminnotice' );

?>
