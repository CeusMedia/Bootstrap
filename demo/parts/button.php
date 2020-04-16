<?php
use CeusMedia\Bootstrap\Button as Button;

$buttons	= array();
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

$sizes	= array();
if( $isBs4 ){
	$sizes[]	= new Button( 'Large', [Button::STATE_SECONDARY, Button::SIZE_LARGE], 'cogs' );
	$sizes[]	= new Button( 'Default', [Button::STATE_SECONDARY, Button::SIZE_DEFAULT], 'cogs' );
	$sizes[]	= new Button( 'Small', [Button::STATE_SECONDARY, Button::SIZE_SMALL], 'cogs' );
}
else{
	$sizes[]	= new Button( 'Large', [Button::STATE_DEFAULT, Button::SIZE_LARGE], 'cogs' );
	$sizes[]	= new Button( 'Default', [Button::STATE_DEFAULT, Button::SIZE_DEFAULT], 'cogs' );
	$sizes[]	= new Button( 'Small', [Button::STATE_DEFAULT, Button::SIZE_SMALL], 'cogs' );
	$sizes[]	= new Button( 'Mini', [Button::STATE_DEFAULT, Button::SIZE_MINI], 'cogs' );
}

print '
<section>
	<h3>Buttons</h3>
	<h4>States</h4>
	<div class="btn-toolbar bs4-mb-3">'.join( '&nbsp;', $buttons ).'</div>
	<h4>Sizes</h4>
	<div class="">'.join( '&nbsp;', $sizes ).'</div>
</section>';
