<?php

// Insert the path where you unpacked log4php
include('lib/log4php/log4php/Logger.php');
date_default_timezone_set("Asia/Kolkata");
// Tell log4php to use our configuration file.
Logger::configure('log4jphp.xml');

// Fetch a logger, it will inherit settings from the root logger
$log = Logger::getLogger('ccb');

$g_log_levels = array(
	LOG_MIN => '',
	LOG_AJAX => 'AJAX',
	LOG_REST => 'REST',
	LOG_LDAP => 'LDAP',
	USER_AUTHENTICATION => 'USER_AUTHENTICATION',
	LOG_DATABASE => 'DB',
	LOG_ENCRYPTION=> 'ENCRYPTION',
	READ_ATTENDENCE=> 'READ ATTENDENCE',
	MANAGE_ATTENDENCE=> 'MANAGE ATTENDENCE',
	MANAGE_TEST=> 'MANAGE TEST',
	MANAGE_QUESTIONS=> 'MANAGE QUESTION',
	MANAGE_CATEGORY=> 'MANAGE CATEGORY',
	UPLOAD_QUESTION=> 'UPLOAD_QUESTION',
	UPLOAD_BATCH=> 'UPLOAD_BATCH',
	EXPORT_EXAM=> 'EXPORT_EXAM',
	DOWNLOAD_ENCRYPT_EXAM=> 'DOWNLOAD_ENCRYPT_EXAM',
	UPLOAD_ENCRYPTED_EXAM=> 'UPLOAD_ENCRYPTED_EXAM',	
	CREATE_EXAM_HISTORY=> 'CREATE_EXAM_HISTORY',

);

function log_event( $p_level, $p_msg ) {
	global $g_log_levels, $g_complete_date_format, $g_log_destination, $g_global_log_level,$log;

	if (( int ) $p_level > ( int ) $g_global_log_level) {
		return;
	}

	$t_level = $g_log_levels [$p_level];

	$t_php_event = date ( $g_complete_date_format ) . substr ( microtime (), 1, 9 ) . ' ' . 	$t_level . ' ' . $p_msg;

	// Add log in log4php file too.
	$log->info($t_level . ' ' . $p_msg);

	list ( $t_destination, $t_modifiers ) = explode ( ':', $g_log_destination, 2 );

}
