<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Bootstrap\Base\BsVersion;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Common\Net\HTTP\Request\Receiver as Request;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use radiobar\RadioBar;

require __DIR__.'/lib/init.php';

$a = new DemoAppTemplate( 'RadioBar Demo', './radiobar.php' );
$a->setBootstrapVersion( 5 );
$a->setFontAwesomeVersion( 6 );
$a->addScript( 'assets/FormOptionals.js' );
$a->addScript( 'assets/radiobar.js' );
$a->addStyle( 'assets/radiobar.css' );

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
	$label1		= '<strong>'.$icon1.' Option 1</strong><br/><small>And some small print.</small>';
	$label2		= '<strong>'.$icon2.' Option 2</strong><br/><small>And some small print.</small>';
	$label3		= '<strong>'.$icon3.' Option 3</strong><br/><small>And some small print.</small>';
}

$optional1	= '
	<div class="bs2-well bs3-panel bs3-panel-default bs4-card bs5-card">
		<div class="bs3-panel-body bs4-card-body bs5-card-body">
			<h5 class="bs4-card-title bs5-card-title">Option 1</h5>	
			<div class="bs4-card-text bs5-card-text">Dagegen tadelt und hasst man mit Recht Den, welcher sich durch die Lockungen einer gegenwärtigen Lust erweichen und verführen lässt, ohne in seiner blinden Begierde zu sehen, welche Schmerzen und Unannehmlichkeiten seiner deshalb warten.</div>
		</div>
	</div>';

$optional2	= '
	<div class="bs2-well bs3-panel bs3-panel-default bs4-card bs5-card">
		<div class="bs3-panel-body bs4-card-body bs5-card-body">
			<h5 class="bs4-card-title bs5-card-title">Option 2</h5>	
			<div class="bs4-card-text bs5-card-text">Gleiche Schuld treffe Die, welche aus geistiger Schwäche, d.h. um der Arbeit und dem Schmerze zu entgehen, ihre Pflichten verabsäumen.</div>
		</div>
	</div>';

$optional3	= '
	<div class="bs2-well bs3-panel bs3-panel-default bs4-card bs5-card">
		<div class="bs3-panel-body bs4-card-body bs5-card-body">
			<h5 class="bs4-card-title bs5-card-title">Option 3</h5>	
			<div class="bs4-card-text bs5-card-text">Man kann hier leicht und schnell den richtigen Unterschied treffen; zu einer ruhigen Zeit, wo die Wahl der Entscheidung völlig frei ist und nichts hindert, das zu thun, was den Meisten gefällt, hat man jede Lust zu erfassen und jeden Schmerz abzuhalten; aber zu Zeiten trifft es sich in Folge von schuldigen Pflichten oder von sachlicher Noth, dass man die Lust zurückweisen und Beschwerden nicht von sich weisen darf. Deshalb trifft der Weise dann eine Auswahl, damit er durch Zurückweisung einer Lust dafür eine grössere erlange oder durch Übernahme gewisser Schmerzen sich grössere erspare.</div>
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

$checkbox	= HtmlTag::create( 'input', NULL, [
	'type'		=> 'checkbox',
	'name'		=> 'extended',
	'id'		=> 'input_extended',
	'class'		=> 'bs4-form-check-input bs5-form-check-input',
	'onchange'	=> 'this.form.submit()',
	'checked'	=> $extended ? 'checked' : NULL,
] );
$control	= match( BsVersion::from( $a->getBsVersion() ) ){
	BsVersion::V2	=> HtmlTag::create( 'label', [$checkbox, ' Extended'], ['class' => 'checkbox'] ),
	BsVersion::V3	=> HtmlTag::create( 'div', HtmlTag::create( 'label', [$checkbox, ' Extended'] ), ['class' => 'checkbox'] ),
	BsVersion::V4, BsVersion::V5	=> HtmlTag::create( 'div', [
		$checkbox,
		HtmlTag::create( 'label', 'Extended', ['class' => 'form-check-label', 'for' => 'input_extended'] ),
	], ['class' => 'form-check'] ),
};
$optionsControls	= HtmlTag::create( 'label', '<strong>Options</strong>' ).$control;
$a->setOptionsControls( $optionsControls );
$a->run();