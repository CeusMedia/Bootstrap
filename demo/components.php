<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Code;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

require __DIR__.'/lib/init.php';

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'All Components', './components.php' );
$app->setBootstrapVersion( 5 );
$app->setFontAwesomeVersion( 6 );
$app->addStyle( 'assets/examples.css' );
$app->addStyle( 'assets/style.css' );
$app->addStyle( '../assets/checkbox.css' );												//  for checkboxes up to Bootstrap 3

require __DIR__.'/lib/examples.php';

/*  --  STATUS  --  */
$status	= [
	'Version aware (renders markup per Bootstrap version)'	=> 'Alert, Badge, Button, Button\Link, Button\Submit, Card, Code, Dropdown\Menu, Dropdown\Trigger, Heading, Label, Modal\Dialog, Modal\Trigger, Nav\Breadcrumbs, Nav\NavList, Nav\PageControl, Nav\Pills, Nav\TabbableNavbar, Nav\Tabs, Progress, Row, RowSpan, Shiftbox',
	'Same markup in all versions'							=> 'Button\Group, Button\Toolbar, Checkbox (needs assets/checkbox.css), Icon (depends on icon set), Link',
];
$rows	= [];
foreach( $status as $state => $components )
	$rows[]	= HtmlTag::create( 'tr', HtmlTag::create( 'th', $state ).HtmlTag::create( 'td', $components ) );

$intro	= '<p>
	All components of this library, each with its source code.
	Switch the Bootstrap version above to see the markup of each version.
	All examples need these imports:
</p>'.( new Code( join( "\n", array_map(
	static fn( string $class ): string => 'use '.$class.';',
	[
		'CeusMedia\Bootstrap\Alert', 'CeusMedia\Bootstrap\Badge', 'CeusMedia\Bootstrap\Button',
		'CeusMedia\Bootstrap\Button\Group as ButtonGroup', 'CeusMedia\Bootstrap\Button\Link as ButtonLink',
		'CeusMedia\Bootstrap\Button\Submit as SubmitButton', 'CeusMedia\Bootstrap\Button\Toolbar as ButtonToolbar',
		'CeusMedia\Bootstrap\Card', 'CeusMedia\Bootstrap\Code', 'CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu',
		'CeusMedia\Bootstrap\Dropdown\Trigger as DropdownTrigger', 'CeusMedia\Bootstrap\Heading', 'CeusMedia\Bootstrap\Icon', 'CeusMedia\Bootstrap\Label',
		'CeusMedia\Bootstrap\Link', 'CeusMedia\Bootstrap\Modal\Dialog as ModalDialog',
		'CeusMedia\Bootstrap\Modal\Trigger as ModalTrigger', 'CeusMedia\Bootstrap\Nav\Breadcrumbs',
		'CeusMedia\Bootstrap\Nav\NavList', 'CeusMedia\Bootstrap\Nav\PageControl', 'CeusMedia\Bootstrap\Nav\Pills',
		'CeusMedia\Bootstrap\Nav\TabbableNavbar', 'CeusMedia\Bootstrap\Nav\Tabs', 'CeusMedia\Bootstrap\Progress',
		'CeusMedia\Bootstrap\Row', 'CeusMedia\Bootstrap\RowSpan', 'CeusMedia\Bootstrap\Checkbox', 'CeusMedia\Bootstrap\Shiftbox',
		'CeusMedia\Common\UI\HTML\Tag as HtmlTag',
	]
) ) ) )->render().
	HtmlTag::create( 'h3', 'Status' ).
	HtmlTag::create( 'table', $rows, ['class' => 'demo-status table table-condensed table-sm'] ).
	HtmlTag::create( 'h3', 'Contents' ).
	DemoExample::renderTableOfContents();

$app->setContent( $intro.DemoExample::renderAll() );
$app->run();
