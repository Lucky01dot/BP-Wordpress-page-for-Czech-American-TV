<?php

/**
 * Plugin Name: Genealogy Tools
 * Description: A plugin full of tools for Czech Genealogy.
 * Version: ioi-6.0
 * Author: Jakub Santora, Miroslav Krysl, Tomas Kveton, Zhanel Mukanova, Jan Pizur
 */


// Include configuration file.
// The file needs to be created if it does not exist !!!
require_once plugin_dir_path( __FILE__ ) . 'config.php';


//region ----------- Define global constants -----------

// WP-side thing that checks if the constant exists - necessary for WP
// TODO: is this really necessary? If so, it should be in every php file.
defined( 'ABSPATH' ) or die( "This file can`t be accessed directly!" );

// prefix for all plugin identifiers used for wordpress actions/hooks/... registration
const GT_PREFIX = "gt_";

// Database options names. Used for storing plugin db credentials into wordpress db.
const GT_OPTION_DB = GT_PREFIX . 'db';

//endregion


//region ------- Helper functions -------

/**
 * Load all files from directory.
 *
 * @param string $dir The __FILE__ variable should be passed.
 * @param array $files Array of file names to load.
 */
function gt_require_files( string $dir, array $files ) {
	foreach ( $files as $file ) {
		require_once $dir . $file;
	}
}

//endregion


//region ------- Require all plugin files -------

// Includes
gt_require_files( plugin_dir_path( __FILE__ ) . "includes/", [
	"GT_Container.php",
	"GT_Config.php",
	"GT_Plugin.php",
	"GT_Database.php",
] );

// Enums
gt_require_files( plugin_dir_path( __FILE__ ) . "includes/enums/", [
	"GT_Ajax_Error.php",
	"GT_Name_Type.php",
	"GT_Autocomplete_Type.php",
	"GT_Changing_Names_Type.php",
	"GT_Tables.php",
] );

// Tables
gt_require_files( plugin_dir_path( __FILE__ ) . "includes/tables/", [
	"GT_Table.php",
	"GT_City_Table.php",
	"GT_District_Table.php",
	"GT_FN_Diminutive_Table.php",
	"GT_FN_Translation_Table.php",
    "GT_FN_EN_CZ_Translation_Table.php",
	"GT_LN_Table.php",
	"GT_FN_Table.php",
	"GT_LN_Count_Table.php",
	"GT_LN_Explanation_Table.php",
	"GT_MEP_Table.php",
	"GT_Region_Table.php"
] );

// DAOs
gt_require_files( plugin_dir_path( __FILE__ ) . "includes/daos/", [
	"GT_DAO.php",
	"GT_I_Name_DAO.php",
	"GT_MEP_DAO.php",
	"GT_Region_DAO.php",
	"GT_LN_DAO.php",
	"GT_FN_DAO.php",
	"GT_LN_Count_DAO.php",
	"GT_FN_Translation_DAO.php",
    "GT_FN_EN_CZ_translation_DAO.php",
	"GT_FN_Diminutive_DAO.php",
	"GT_LN_Explanation_DAO.php",
	"GT_City_DAO.php",
	"GT_District_DAO.php"
] );

// Services
gt_require_files( plugin_dir_path( __FILE__ ) . "includes/services/", [
	"GT_Transcription_Service.php",
	"GT_Female_Variant_Service.php",
	"GT_Name_Distribution_Service.php",
] );

// Admin
gt_require_files( plugin_dir_path( __FILE__ ) . "admin/", [
	"GT_Plugin_Admin.php",
	"GT_Admin_Output_Manager.php"
] );

// Public
gt_require_files( plugin_dir_path( __FILE__ ) . "public/", [
	"GT_Plugin_Public.php",
	"GT_Plugin_Changing_Names.php",
	"GT_Plugin_Behind_The_Name.php",
	"GT_Plugin_German_Terminology.php",
	"GT_Plugin_Name_Distribution.php",
	"GT_Plugin_Tutorial.php",
    "GT_Plugin_EN_CZ_Translation.php"
] );

//endregion


//region ------- Register plugin -------

// instantiate and register plugin
$gt_plugin = new GT_Plugin( __FILE__ );
$gt_plugin->register();

//endregion