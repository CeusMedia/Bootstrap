<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

ini_set('display_errors', 'On');

use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Net\HTTP\Request\Receiver as Request;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use radiobar\RadioBar;

(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');
require __DIR__.'/radiobar/RadioBar.php';
require __DIR__.'/VersionProcessor.php';
require __DIR__.'/DemoAppTemplate.php';


$a = new DemoAppTemplate( 'RadioBar Demo', './3_radiobar.php' );
$a->setBootstrapVersion( 5 );
$a->setFontAwesomeVersion( 6 );
$a->addScript( 'FormOptionals.js' );
$a->addScript( 'radiobar/radiobar.js' );
$a->addStyle( 'radiobar/radiobar.css' );

$request	= new Request();
$extended	= FALSE;
if( '' !== $request->get( 'extended', '' ) )
	$extended	= TRUE;

$icon1	= Icon::create( 'car' );
$icon2	= Icon::create( 'plane' );
$icon3	= Icon::create( 'rocket' );

$label1		= 'Option 1';
$label2		= 'Option 2';
$label3		= 'Option 3';

if( $extended ){
	$label1		= '<div class="fs-5">'.$icon1.' Option 1</div><small>And some small print.</small>';
	$label2		= '<div class="fs-5">'.$icon2.' Option 2</div><small>And some small print.</small>';
	$label3		= '<div class="fs-5">'.$icon3.' Option 3</div><small>And some small print.</small>';
}

$optional1	= '
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Option 1</h5>	
			<div class="card-text">Dagegen tadelt und hasst man mit Recht Den, welcher sich durch die Lockungen einer gegenwärtigen Lust erweichen und verführen lässt, ohne in seiner blinden Begierde zu sehen, welche Schmerzen und Unannehmlichkeiten seiner deshalb warten.</div>
		</div>
	</div>';

$optional2	= '
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Option 2</h5>	
			<div class="card-text">Gleiche Schuld treffe Die, welche aus geistiger Schwäche, d.h. um der Arbeit und dem Schmerze zu entgehen, ihre Pflichten verabsäumen.</div>
		</div>
	</div>';

$optional3	= '
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Option 3</h5>	
			<div class="card-text">Man kann hier leicht und schnell den richtigen Unterschied treffen; zu einer ruhigen Zeit, wo die Wahl der Entscheidung völlig frei ist und nichts hindert, das zu thun, was den Meisten gefällt, hat man jede Lust zu erfassen und jeden Schmerz abzuhalten; aber zu Zeiten trifft es sich in Folge von schuldigen Pflichten oder von sachlicher Noth, dass man die Lust zurückweisen und Beschwerden nicht von sich weisen darf. Deshalb trifft der Weise dann eine Auswahl, damit er durch Zurückweisung einer Lust dafür eine grössere erlange oder durch Übernahme gewisser Schmerzen sich grössere erspare.</div>
		</div>
	</div>';




$t = new RadioBar( 'flexRadioDefault' );
$t->useOptionals();
#$t->useAnimation( T::ANIMATION_SLIDE );
$t->setValue( '2' );
$t->addOption( '1', $label1, $extended ? NULL : $icon1, $optional1 );
$t->addOption( '2', $label2, $extended ? NULL : $icon2, $optional2 );
$t->addOption( '3', $label3, $extended ? NULL : $icon3, $optional3 );
$a->setContent( $t->render() );


switch( $a->getBsVersion() ){
	case 5:
	case 4:
		$optionsControls	= join( '', [
			HtmlTag::create( 'label', '<strong>Options</strong>', ['class' => ''] ),
			HtmlTag::create( 'div', [
				HtmlTag::create( 'input', NULL, [
					'type'		=> 'checkbox',
					'name'		=> 'extended',
					'id'		=> 'input_extended',
					'class' 	=> 'bs2-span12 bs4-form-check-input bs5-form-check-input',
					'onchange'	=> 'this.form.submit()',
					'checked'	=> $extended ? 'checked' : NULL,
				] ),
				HtmlTag::create( 'label', 'Extended', [
					'class'		=> 'bs4-form-check-label bs5-form-check-label',
					'for'		=> 'input_extended',
				] ),
			], ['class' => 'bs4-form-check bs5-form-check'] ),
		] );
		break;
	case 3:
	case 2:
	default:
		$optionsControls	= join( '', [
			HtmlTag::create( 'label', '<strong>Options</strong>', ['class' => ''] ),
			HtmlTag::create( 'div', [
				HtmlTag::create( 'label', [
					HtmlTag::create( 'input', NULL, [
						'type'		=> 'checkbox',
						'name'		=> 'extended',
						'class' 	=> 'bs2-span12',
						'onchange'	=> 'this.form.submit()',
						'checked'	=> $extended ? 'checked' : NULL,
					] ),
					'Extended',
				] ),
			], ['class' => 'checkbox'] ),
		] );
		break;

}
$a->setOptionsControls( $optionsControls );
$a->run();