<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/** @var bool $isBs4 */

$list	= [];

$list[] = HtmlTag::create( 'div', [
	HtmlTag::create( 'div', new Alert( 'Success', Alert::CLASS_SUCCESS ), ['class' => 'span3 col-lg-3'] ),
	HtmlTag::create( 'div', new Alert( 'Danger', Alert::CLASS_DANGER ), ['class' => 'span3 col-lg-3'] ),
	HtmlTag::create( 'div', new Alert( 'Warning', Alert::CLASS_WARNING ), ['class' => 'span3 col-lg-3'] ),
	HtmlTag::create( 'div', new Alert( 'Info', Alert::CLASS_INFO ), ['class' => 'span3 col-lg-3'] ),
], ['class' => 'row row-fluid'] );


if( $isBs4 ){
	$list[] = HtmlTag::create( 'div', [
		HtmlTag::create( 'div', new Alert( 'Primary', Alert::CLASS_PRIMARY ), ['class' => 'col-lg-3'] ),
		HtmlTag::create( 'div', new Alert( 'Secondary', Alert::CLASS_SECONDARY ), ['class' => 'col-lg-3'] ),
		HtmlTag::create( 'div', new Alert( 'Light', Alert::CLASS_LIGHT ), ['class' => 'col-lg-3'] ),
		HtmlTag::create( 'div', new Alert( 'Dark', Alert::CLASS_DARK ), ['class' => 'col-lg-3'] ),
	], ['class' => 'row'] );
}

print '<h3>Alerts</h3>'.join( $list );
