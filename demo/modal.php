<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Base\Abstraction;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Bootstrap\Modal\Dialog as ModalDialog;
use CeusMedia\Bootstrap\Modal\Trigger as ModalTrigger;
use CeusMedia\Bootstrap\Progress;

require __DIR__.'/lib/init.php';

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'Modal Demo', './modal.php' );
$app->setBootstrapVersion( 5 );
$app->setFontAwesomeVersion( 6 );
$app->addStyle( 'assets/examples.css' );

ModalDialog::$defaultFade	= TRUE;

$content	= [];

$content[]	= '<p>
	A modal consists of a <b>dialog</b> and at least one <b>trigger</b>, which opens the dialog by its ID.
	The same code renders the markup of the selected Bootstrap version:
	dialog containers, header layout, close button and data attributes differ between versions.
	Below each example, you will find the code producing it.
</p>';
$content[]	= ( new Code( "use CeusMedia\\Bootstrap\\Modal\\Dialog as ModalDialog;
use CeusMedia\\Bootstrap\\Modal\\Trigger as ModalTrigger;

ModalDialog::\$defaultFade = TRUE;                  //  animate all dialogs
Abstraction::setDefaultBsVersion( '5.3.3' );       //  render for Bootstrap 5 (default: 2.3.2)" ) )->render();

/*  --  BASICS  --  */
$content[]	= DemoExample::section( 'Basics' );

$content[]	= DemoExample::render( 'Simple dialog', static function(): string {
	$dialog		= new ModalDialog( 'modal-simple' );
	$dialog->setHeading( 'Hello World' );
	$dialog->setBody( '<p>This is a simple modal dialog.</p>' );

	$trigger	= new ModalTrigger( 'modal-simple', 'Open simple dialog', 'btn-primary' );
	return $trigger->render().$dialog->render();
}, 'Heading, body and a footer with a close button. The trigger is a button by default.' );

$content[]	= DemoExample::render( 'Trigger as link and with icon', static function(): string {
	$dialog		= new ModalDialog( 'modal-trigger-link' );
	$dialog->setHeading( 'Opened by link' );
	$dialog->setBody( '<p>Triggers can be rendered as link and carry an icon.</p>' );

	$trigger	= new ModalTrigger( 'modal-trigger-link', 'Open by link', 'btn-info', 'info-circle' );
	$trigger->asLink();
	return $trigger->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Several triggers for one dialog', static function(): string {
	$dialog		= new ModalDialog( 'modal-shared' );
	$dialog->setHeading( 'Shared dialog' );
	$dialog->setBody( '<p>Any number of triggers can open the same dialog.</p>' );

	$triggers	= [
		ModalTrigger::create( 'modal-shared', 'First trigger', 'btn-success' )->render(),
		ModalTrigger::create( 'modal-shared', 'Second trigger', 'btn-warning' )->render(),
		ModalTrigger::create( 'modal-shared', 'Third trigger', 'btn-link' )->asLink()->render(),
	];
	return join( ' ', $triggers ).$dialog->render();
} );

/*  --  SIZE AND POSITION  --  */
$content[]	= DemoExample::section( 'Size and position',
	'Sizes are supported since Bootstrap 3 (extra large since Bootstrap 4), centering since Bootstrap 4. Older versions ignore these settings.'
);

$content[]	= DemoExample::render( 'Sizes', static function(): string {
	$sizes	= [
		'small'			=> ModalDialog::SIZE_SMALL,
		'medium'		=> ModalDialog::SIZE_MEDIUM,
		'large'			=> ModalDialog::SIZE_LARGE,
		'extra-large'	=> ModalDialog::SIZE_EXTRA_LARGE,
	];
	$html	= [];
	foreach( $sizes as $label => $size ){
		$dialog	= new ModalDialog( 'modal-size-'.$label );
		$dialog->setHeading( ucfirst( $label ).' dialog' );
		$dialog->setBody( '<p>Size: <code>'.$size.'</code></p>' );
		$dialog->setSize( $size );
		$html[]	= ModalTrigger::create( 'modal-size-'.$label, ucfirst( $label ) )->render();
		$html[]	= $dialog->render();
	}
	return join( ' ', $html );
} );

$content[]	= DemoExample::render( 'Vertically centered', static function(): string {
	$dialog		= new ModalDialog( 'modal-centered' );
	$dialog->setHeading( 'Centered dialog' );
	$dialog->setBody( '<p>This dialog is centered vertically.</p>' );
	$dialog->setCentered( TRUE );
	$dialog->setSize( ModalDialog::SIZE_SMALL );

	return ModalTrigger::create( 'modal-centered', 'Open centered dialog' )->render().$dialog->render();
} );

/*  --  FORMS  --  */
$content[]	= DemoExample::section( 'Forms',
	'If a form action is set, the dialog is wrapped into a form and gets a submit button.'
);

$content[]	= DemoExample::render( 'Dialog with form', static function(): string {
	$dialog		= new ModalDialog( 'modal-form' );
	$dialog->setHeading( 'Edit name' );
	$dialog->setBody( '<label for="input_name">Name</label><input type="text" name="name" id="input_name" class="form-control" value="John Doe"/>' );
	$dialog->setFormAction( '#saved' );
	$dialog->setCloseButtonLabel( 'cancel' );
	$dialog->setCloseButtonIconClass( 'times' );
	$dialog->setSubmitButtonLabel( 'save' );
	$dialog->setSubmitButtonClass( 'btn-primary' );
	$dialog->setSubmitButtonIconClass( 'check' );

	return ModalTrigger::create( 'modal-form', 'Edit name', 'btn-primary', 'pencil' )->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Upload form with submit handler', static function(): string {
	$dialog		= new ModalDialog( 'modal-upload' );
	$dialog->setHeading( 'Upload file' );
	$dialog->setBody( '<input type="file" name="upload" class="form-control"/>' );
	$dialog->setFormAction( '#uploaded', ['id' => 'form-upload'] );
	$dialog->setFormIsUpload();
	$dialog->setFormSubmit( 'alert("Upload is not part of this demo")' );
	$dialog->setSubmitButtonLabel( 'upload' );
	$dialog->setSubmitButtonClass( 'btn-success' );
	$dialog->setSubmitButtonIconClass( 'upload' );

	return ModalTrigger::create( 'modal-upload', 'Upload file', 'btn-success', 'upload' )->render().$dialog->render();
}, 'Sets the form encoding for uploads and calls JavaScript on submit (the form is not sent here).' );

/*  --  LAYOUT  --  */
$content[]	= DemoExample::section( 'Layout' );

$content[]	= DemoExample::render( 'Components as body', static function(): string {
	$progress	= new Progress();
	$progress->addBar( 60, Progress::BAR_CLASS_SUCCESS, '60%' );
	$progress->addBar( 15, Progress::BAR_CLASS_WARNING );

	$dialog		= new ModalDialog( 'modal-components' );
	$dialog->setHeading( 'Status' );
	$dialog->setBody( new Alert( 'Body can be any string or component.', Alert::CLASS_INFO ).$progress );

	return ModalTrigger::create( 'modal-components', 'Show status' )->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Without footer', static function(): string {
	$dialog		= new ModalDialog( 'modal-no-footer' );
	$dialog->setHeading( 'No footer' );
	$dialog->setBody( '<p>Close this dialog by the button in the header, by pressing Escape or by clicking outside.</p>' );
	$dialog->useFooter( FALSE );

	return ModalTrigger::create( 'modal-no-footer', 'Open without footer' )->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Without header and footer', static function(): string {
	$dialog		= new ModalDialog( 'modal-body-only' );
	$dialog->setBody( '<p class="lead">Just the body.</p><p>Close by pressing Escape or clicking outside.</p>' );
	$dialog->useHeader( FALSE );
	$dialog->useFooter( FALSE );

	return ModalTrigger::create( 'modal-body-only', 'Open body only' )->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Custom close buttons and classes', static function(): string {
	$dialog		= new ModalDialog( 'modal-custom' );
	$dialog->setHeading( 'Customized' );
	$dialog->setBody( '<p>Footer close button with label, class and icon, and an additional class for the dialog.</p>' );
	$dialog->setHeaderCloseButtonIcon( '&times;' );
	$dialog->setCloseButtonLabel( 'Got it' );
	$dialog->setCloseButtonClass( 'btn-success' );
	$dialog->setCloseButtonIconClass( 'thumbs-up' );
	$dialog->setDialogClass( 'my-dialog' );

	return ModalTrigger::create( 'modal-custom', 'Open customized dialog' )->render().$dialog->render();
}, 'The header close icon is used up to Bootstrap 4, Bootstrap 5 draws its own close icon.' );

/*  --  BEHAVIOR  --  */
$content[]	= DemoExample::section( 'Behavior' );

$content[]	= DemoExample::render( 'Without animation', static function(): string {
	$dialog		= new ModalDialog( 'modal-no-fade' );
	$dialog->setHeading( 'No animation' );
	$dialog->setBody( '<p>This dialog appears without fading in.</p>' );
	$dialog->setFade( FALSE );

	return ModalTrigger::create( 'modal-no-fade', 'Open without animation' )->render().$dialog->render();
} );

$content[]	= DemoExample::render( 'Static backdrop', static function(): string {
	$dialect	= Abstraction::getDefaultBsVersion()->dialect();

	$dialog		= new ModalDialog( 'modal-static' );
	$dialog->setHeading( 'Static backdrop' );
	$dialog->setBody( '<p>Clicking outside or pressing Escape will not close this dialog. Use the buttons.</p>' );
	$dialog->setAttributes( [
		$dialect->data( 'backdrop' )	=> 'static',
		$dialect->data( 'keyboard' )	=> 'false',
	] );

	return ModalTrigger::create( 'modal-static', 'Open with static backdrop', 'btn-warning' )->render().$dialog->render();
}, 'Additional attributes are set on the modal. The dialect of the current version provides the data attribute names (<code>data-backdrop</code> or <code>data-bs-backdrop</code>).' );

$app->setContent( join( "\n", $content ) );
$app->run();
