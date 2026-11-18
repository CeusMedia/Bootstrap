<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Code;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

require __DIR__.'/lib/init.php';

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'Overview', './index.php' );
$app->setBootstrapVersion( 5 );
$app->setFontAwesomeVersion( 6 );
$app->addStyle( 'assets/examples.css' );

$demos	= [
	'components.php'	=> ['Components', 'All components of this library, each with its source code and generated HTML.'],
	'compare.php'		=> ['Compare versions', 'Each example of the components demo in Bootstrap 2, 3, 4 and 5 side by side.'],
	'modal.php'			=> ['Modal', 'Modal dialogs with all options: sizes, forms, layout and behavior.'],
	'checkbox.php'		=> ['Checkbox', 'Custom styled checkboxes with icons, needing assets/checkbox.css.'],
	'shiftbox.php'		=> ['Shiftbox', 'Switches: by jQuery plugin bootstrap-switch up to Bootstrap 3, built-in since Bootstrap 4.'],
	'radiobar.php'		=> ['RadioBar', 'Horizontal bar of radio buttons with optional contents (demo component).'],
];
$rows	= [];
foreach( $demos as $file => [$title, $description] )
	$rows[]	= HtmlTag::create( 'tr', [
		HtmlTag::create( 'td', HtmlTag::create( 'a', $title, ['href' => $app->getDemoUrl( $file )] ) ),
		HtmlTag::create( 'td', $description ),
	] );

$content	= join( [
	HtmlTag::create( 'p', 'PHP components to generate HTML for Bootstrap 2, 3, 4 and 5. '.
		'The same code renders the markup of the selected Bootstrap version. '.
		'Select Bootstrap and FontAwesome version above, the selection is kept when switching demos.', ['class' => 'lead'] ),
	HtmlTag::create( 'h3', 'Demos' ),
	HtmlTag::create( 'table', HtmlTag::create( 'tbody', $rows ), ['class' => 'table table-striped'] ),
	HtmlTag::create( 'h3', 'Quick start' ),
	( new Code( "use CeusMedia\\Bootstrap\\Base\\Abstraction;
use CeusMedia\\Bootstrap\\Button;
use CeusMedia\\Bootstrap\\Icon;

Abstraction::setDefaultBsVersion( '5.3.3' );		//  default: 2.3.2
Icon::\$defaultSet	= 'fontawesome6';

echo new Button( 'Save', Button::STATE_PRIMARY, 'check' );" ) )->render(),
	HtmlTag::create( 'p', 'See README.md and CHANGELOG.md of this library for details.' ),
] );

$app->setContent( $content );
$app->run();
