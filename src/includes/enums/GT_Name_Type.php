<?php

/**
 * Class GT_Request_Type is the enum of available name variant in the plugin.
 * -
 * This enum must match with the JS enum defined in `common/js/request_types.js` !!!
 */
abstract class GT_Name_Type {
	const FIRST_NAME = "fname";
	const LAST_NAME = "lname";
	const LAST_NAME_FEMALE = "femvar";
}