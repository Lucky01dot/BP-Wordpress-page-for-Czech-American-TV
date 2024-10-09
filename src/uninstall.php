<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) { die; }

$container = GT_Container::instance();

// drop database tables
$db = $container->get_database();

if ( ! $db->drop_tables() ) {
	die( "Can't drop plugin tables." );
}