<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Checkbox;
use CeusMedia\Bootstrap\Icon;

require __DIR__.'/lib/init.php';

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'Checkbox Demo', './checkbox.php' );
$app->setBootstrapVersion( 5 );
$app->setFontAwesomeVersion( 6 );
$app->addStyle( 'assets/examples.css' );
$app->addStyle( '../assets/checkbox.css' );

$hint	= 'Bootstrap has no checkboxes with icons, so this component brings its own style: load <code>assets/checkbox.css</code>. '.
	'It works with all Bootstrap versions and with font icons (FontAwesome 4) as well as SVG icons (FontAwesome 5 and 6 loaded by JavaScript).';

$content	= [];
$content[]	= ( new Alert( $hint, Alert::CLASS_INFO ) )->render();

$content[]	= DemoExample::section( 'Examples' );

$content[]	= DemoExample::render( 'Checked and unchecked', static function(): string {
	return join( [
		new Checkbox( 'check1', 1, TRUE, 'This one is checked on load' ),
		new Checkbox( 'check2', 1, FALSE, 'This one is not checked on load' ),
	] );
} );

$content[]	= DemoExample::render( 'Several values for one name', static function(): string {
	$list	= [];
	foreach( ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'] as $value => $label ){
		$checkbox	= new Checkbox( 'colors[]', $value, 'green' === $value, $label );
		$checkbox->setId( 'input_color_'.$value );
		$list[]		= $checkbox->render();
	}
	return join( $list );
}, 'Each checkbox needs its own ID, since the ID is generated from the name by default.' );

$content[]	= DemoExample::render( 'Other icons', static function(): string {
	return join( [
		new Checkbox( 'check3', 1, TRUE, 'Short icon name, resolved for current icon set', 'star' ),
		new Checkbox( 'check4', 1, TRUE, 'Complete icon classes', 'fa fa-heart' ),
		new Checkbox( 'check5', 1, TRUE, 'Icon object', new Icon( 'thumbs-up' ) ),
	] );
}, 'Click to see the animation.' );

$content[]	= DemoExample::render( 'Data attributes', static function(): string {
	return ( new Checkbox( 'check6', 1, FALSE, 'With data attributes', 'fa fa-check', ['topic' => 'news', 'priority' => 2] ) )->render();
}, 'Data attributes are set on the input, for use with JavaScript.' );

$app->setContent( join( "\n", $content ) );
$app->run();
