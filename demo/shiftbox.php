<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Shiftbox;

/**
 *	@link		https://github.com/nostalgiaz/bootstrap-switch bootstrap-switch@GitHub
 */

require __DIR__.'/lib/init.php';

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'Shiftbox Demo', './shiftbox.php' );
$app->setBootstrapVersion( 3 );
$app->setFontAwesomeVersion( 4 );
$app->addStyle( 'assets/examples.css' );

$bsVersion	= BsVersion::from( $app->getBsVersion() );
$usePlugin	= match( $bsVersion ){
	BsVersion::V2, BsVersion::V3	=> TRUE,
	BsVersion::V4, BsVersion::V5	=> FALSE,
};

$content	= [];
if( $usePlugin ){
	//  jQuery plugin bootstrap-switch (composer package nostalgiaz/bootstrap-switch) supports Bootstrap 2 and 3 only
	$pathPlugin	= '../vendor/nostalgiaz/bootstrap-switch/dist/';
	$app->addScript( $pathPlugin.'js/bootstrap-switch.min.js' );
	$app->addStyle( $pathPlugin.'css/bootstrap'.$bsVersion->value.'/bootstrap-switch.min.css' );
	$content[]	= ( new Alert( 'For Bootstrap '.$bsVersion->value.', switches are made by jQuery plugin <a href="https://github.com/nostalgiaz/bootstrap-switch">bootstrap-switch</a>: <code>composer require nostalgiaz/bootstrap-switch</code>, load its script and style and turn checkboxes into switches (see bottom of this page). Options are set as data attributes.', Alert::CLASS_INFO ) )->render();
}
else
	$content[]	= ( new Alert( 'Bootstrap '.$bsVersion->value.' has switches itself, which are used here. No plugin is needed. Options of the plugin (size, texts, colors) are not used, but the label is shown.', Alert::CLASS_INFO ) )->render();

$content[]	= DemoExample::section( 'Examples' );

$content[]	= DemoExample::render( 'Default', static function(): string {
	return ( new Shiftbox( 'check1', 'on', TRUE ) )->render();
} );

$content[]	= DemoExample::render( 'With label', static function(): string {
	return ( new Shiftbox( 'check2', 'on' ) )->setLabel( 'Clicking the label changes the switch as well' )->render();
} );

$content[]	= DemoExample::render( 'Large with yes/no texts', static function(): string {
	return ( new Shiftbox( 'check3', 'on', TRUE, [
		'size'		=> Shiftbox::SIZE_LARGE,
		'on-text'	=> 'YES',
		'off-text'	=> 'NO',
	] ) )->render();
} );

$content[]	= DemoExample::render( 'Small with icons', static function(): string {
	return ( new Shiftbox( 'check4', 'on', TRUE, [
		'size'			=> Shiftbox::SIZE_SMALL,
		'label-text'	=> (string) new Icon( 'power-off' ),
		'on-text'		=> (string) new Icon( 'check' ),
		'off-text'		=> (string) new Icon( 'times' ),
	] ) )->render();
} );

$content[]	= DemoExample::render( 'Mini with colors and label', static function(): string {
	return ( new Shiftbox( 'check5', 'on', TRUE, [
		'size'			=> Shiftbox::SIZE_MINI,
		'on-color'		=> 'success',
		'off-color'		=> 'danger',
		'on-text'		=> '&nbsp;I&nbsp;',
		'off-text'		=> '&nbsp;O&nbsp;',
	] ) )->setLabel( 'Power' )->render();
} );

if( $usePlugin ){
	$script	= '$(document).ready(function(){
	$(":input[type=checkbox].shiftbox").bootstrapSwitch();
	$(":input[type=checkbox].shiftbox").on("switchChange.bootstrapSwitch", function(event, state){
		console.log($(this).attr("id") + ": " + state);
	});
});';
	$content[]	= DemoExample::section( 'Initialization' );
	$content[]	= '<p>Turn all shiftboxes into switches, after loading jQuery and the plugin:</p>';
	$content[]	= ( new CeusMedia\Bootstrap\Code( $script ) )->render();
	$content[]	= '<script>'.$script.'</script>';
}

$app->setContent( join( "\n", $content ) );
$app->run();
