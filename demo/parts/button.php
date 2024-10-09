<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Button as Button;
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;

/** @var bool $isBs4 */

$buttons	= [];
$buttons[]	= new Button( 'Primary', Button::STATE_PRIMARY );
if( $isBs4 )
	$buttons[]	= new Button( 'Secondary', Button::STATE_SECONDARY );
$buttons[]	= new Button( 'Success', Button::STATE_SUCCESS );
$buttons[]	= new Button( 'Info', Button::STATE_INFO );
$buttons[]	= new Button( 'Warning', Button::STATE_WARNING );
$buttons[]	= new Button( 'Danger', Button::STATE_DANGER );
if( $isBs4 ){
	$buttons[]	= new Button( 'Light', Button::STATE_LIGHT );
	$buttons[]	= new Button( 'Dark', Button::STATE_DARK );
	$buttons[]	= new Button( 'Link', Button::STATE_LINK );
}
else
	$buttons[]	= new Button( 'Inverse', Button::STATE_INVERSE );

$sizes	= [];
if( $isBs4 ){
	$button		= new Button( 'Button', Button::STATE_SECONDARY, 'cogs' );
	$sizes[]	= $button->setContent( 'Large' )->setSize( Button::SIZE_LARGE )->render();
	$sizes[]	= $button->setContent( 'Default' )->setSize( Button::SIZE_DEFAULT )->render();
	$sizes[]	= $button->setContent( 'Small' )->setSize( Button::SIZE_SMALL )->render();
}
else{
	$button		= new Button( 'Button', Button::STATE_DEFAULT, 'check' );
	$sizes[]	= $button->setContent( 'Large' )->setSize( Button::SIZE_LARGE )->render();
	$sizes[]	= $button->setContent( 'Default' )->setSize( Button::SIZE_DEFAULT )->render();
	$sizes[]	= $button->setContent( 'Small' )->setSize( Button::SIZE_SMALL )->render();
	$sizes[]	= $button->setContent( 'Mini' )->setSize( Button::SIZE_MINI )->render();
}

$disabled	= new Button( 'Button', Button::STATE_DANGER, 'trash', TRUE );

$button		= new Button( 'Block Button', Button::STATE_INFO, 'plane' );
$button->setBlock();
$blocks		= [];
if( $isBs4 ){
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_LARGE )->render() )->setSize( 4 );
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_DEFAULT )->render() )->setSize( 4 );
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_SMALL )->render() )->setSize( 4 );
}
else{
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_LARGE )->render() )->setSize( 3 );
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_DEFAULT )->render() )->setSize( 3 );
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_SMALL )->render() )->setSize( 3 );
	$blocks[]	= RowSpan::create( $button->setSize( Button::SIZE_MINI )->render() )->setSize( 3 );
}

print '
<section>
	<h3>Buttons</h3>
	<h4>States</h4>
	<div class="btn-toolbar bs4-md-3">'.join( '&nbsp;', $buttons ).'</div>
	<h4>Sizes</h4>
	<div class="">'.join( '&nbsp;', $sizes ).'</div>
	<h4>Blocks</h4>
	<div class="">'.Row::create( $blocks ).'</div>
	<h4>Disabled</h4>
	<div class="btn-toolbar bs4-md-3">'.$disabled.'</div>
</section>';
