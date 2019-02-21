<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$includes = array(
	'/ph-dom.php',
	'/ph-main.php',
	'/ph-aromatherapie.php',
	'/ph-phytotherapie.php',
	'/ph-produit.php'
);

foreach ( $includes as $file ) {
	$filepath = locate_template( 'functions/class' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating class%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
