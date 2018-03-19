<?php
/*
Plugin Name: 輔仁網: Backup Helper (General Site)
Description: for General site use, without Monitor
Version: 1.0
Author: <a href="http://www.vjmedia.com.hk/">技術組</a>
*/

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

?>
