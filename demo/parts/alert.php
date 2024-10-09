<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Alert;

/** @var bool $isBs4 */

$list	= [];
if( $isBs4 ){
	$list[]	= new Alert( 'Primary', Alert::CLASS_PRIMARY );
	$list[]	= new Alert( 'Secondary', Alert::CLASS_SECONDARY );
}
$list[]	= new Alert( 'Success', Alert::CLASS_SUCCESS );
$list[]	= new Alert( 'Danger', Alert::CLASS_DANGER );
$list[]	= new Alert( 'Warning', Alert::CLASS_WARNING );
$list[]	= new Alert( 'Info', Alert::CLASS_INFO );
if( $isBs4 ){
	$list[]	= new Alert( 'Light', Alert::CLASS_LIGHT );
	$list[]	= new Alert( 'Dark', Alert::CLASS_DARK );
}

print '<h3>Alerts</h3>'.join( $list );
