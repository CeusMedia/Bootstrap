<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Modal\Dialog as ModalDialog;
use CeusMedia\Bootstrap\Modal\Trigger as ModalTrigger;

/** @var bool $isBs4 */

ModalDialog::$defaultFade	= TRUE;

if( $isBs4 ){
	$modal1		= new ModalDialog( 'modal-demo-1' );
	$modal1->setHeading( 'Small Modal' );
	$modal1->setBody( '<h4>Hello World!</h4><p>Lorem ipsum ...</p>' );
	$modal1->setCloseButtonClass( 'btn btn-primary' );
	$modal1->setCloseButtonIconClass( 'check' );
	$modal1->setCloseButtonLabel( 'okay' );
	//$modal1->setFade( TRUE );
	$modal1->setCentered( TRUE );
	$modal1->setSize( ModalDialog::SIZE_SMALL );

	$modal2		= new ModalDialog( 'modal-demo-2' );
	$modal2->setFormAction( '#' );
	$modal2->setHeading( 'Large Modal with Form' );
	$modal2->setBody( '<h4>Hello World!</h4><p>Lorem ipsum ...</p>' );
	$modal2->setCloseButtonClass( 'btn btn-secondary btn-small' );
	$modal2->setCloseButtonIconClass( 'remove' );
	$modal2->setCloseButtonLabel( 'close' );
	$modal2->setSubmitButtonClass( 'btn btn-primary' );
	$modal2->setSubmitButtonIconClass( 'arrow-right' );
	$modal2->setSubmitButtonLabel( 'save' );
	$modal2->setSize( ModalDialog::SIZE_LARGE );

	$modalTrigger1	= new ModalTrigger( 'modal-demo-1', 'open small and centered', 'btn-info', 'bars' );
	$modalTrigger2	= new ModalTrigger( 'modal-demo-2', 'open large with form', 'btn-info', 'bars' );

	print '<h3>Modal</h3>'.join( ' ', [
		$modalTrigger1->render(),
		$modalTrigger2->render(),
	] ).$modal1->render().$modal2->render();
}
else {
	$modal		= new ModalDialog( 'modal-demo-1' );
	$modal->setFormAction( '#' );
	$modal->setHeading( 'Large Modal with Form' );
	$modal->setBody( '<h4>Hello World!</h4><p>Lorem ipsum ...</p>' );
	$modal->setCloseButtonClass( 'btn btn-secondary btn-small' );
	$modal->setCloseButtonIconClass( 'remove' );
	$modal->setCloseButtonLabel( 'close' );
	$modal->setSubmitButtonClass( 'btn btn-primary' );
	$modal->setSubmitButtonIconClass( 'arrow-right' );
	$modal->setSubmitButtonLabel( 'save' );

	$modalTrigger	= new ModalTrigger( 'modal-demo-1', 'open Modal with Form', 'btn-info', 'bars' );

	print '<h3>Modal</h3>'.$modalTrigger->render().$modal->render();
}
print '<br/>';
print '<br/>';
