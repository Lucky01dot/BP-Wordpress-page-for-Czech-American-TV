<?php

// Nastav cestu ke stávající instalaci WordPressu
$wp_root = 'C:/xampp/xampp/htdocs/wordpress'; // Uprav podle své cesty!

// Načti WordPress
require_once $wp_root . '/wp-load.php';

// Načti plugin
require_once dirname(__DIR__) . '/GT_Plugin_EN_CZ_Translation.php';
require_once dirname(__DIR__) . '/GT_Plugin_Public.php';
