<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

require __DIR__.'/lib/init.php';

/**
 *	Shows each example of the components demo in all Bootstrap versions side by side.
 *	Each version is rendered in its own frame (this script with parameter "frame"),
 *	loading Bootstrap and FontAwesome of that version.
 */

//  set up demo app first: it applies selected Bootstrap and FontAwesome version to all components
$app	= new DemoAppTemplate( 'Compare Versions', './compare.php' );
$app->setBootstrapVersion( 5 );
$app->setFontAwesomeVersion( 6 );
$app->addStyle( 'assets/examples.css' );
$app->addStyle( 'assets/style.css' );
$app->addStyle( '../assets/checkbox.css' );

require __DIR__.'/lib/examples.php';

$sections	= DemoExample::getSections();
$getNumber	= static fn( string $key ): int => isset( $_GET[$key] ) && is_numeric( $_GET[$key] ) ? (int) $_GET[$key] : 0;
$sectionNr	= min( max( 0, $getNumber( 'section' ) ), count( $sections ) - 1 );
$isWide		= 1 === $getNumber( 'wide' );													//  one frame per row, for grid layouts
$section	= $sections[$sectionNr];

/*  --  FRAME: ONE EXAMPLE IN ONE VERSION  --  */
if( isset( $_GET['frame'] ) ){
	$app->addScript( 'assets/frame.js' );												//  dropdown menus of Bootstrap 4 open below, also in small frame
	CeusMedia\Bootstrap\Modal\Dialog::$defaultFade	= FALSE;						//  modals appear at once, so frame fits to final position
	$example	= $section['examples'][$getNumber( 'example' )] ?? NULL;
	$app->runFrame( NULL !== $example ? DemoExample::execute( $example['closure'] ) : 'Example not found' );
	return;
}

/*  --  PAGE: ALL EXAMPLES OF SECTION IN ALL VERSIONS  --  */
$navItems	= [];
foreach( $sections as $nr => $item ){
	$isCurrent	= $nr === $sectionNr;
	$link		= HtmlTag::create( 'a', $item['title'], [
		'href'	=> $app->getDemoUrl( 'compare.php', ['section' => $nr, 'wide' => (int) $isWide] ),
		'class'	=> 'bs4-nav-link bs5-nav-link'.( $isCurrent ? ' bs4-active bs5-active' : '' ),
	] );
	$navItems[]	= HtmlTag::create( 'li', $link, ['class' => 'bs4-nav-item bs5-nav-item'.( $isCurrent ? ' bs2-active bs3-active' : '' )] );
}

$examples	= [];
foreach( $section['examples'] as $exampleNr => $example ){
	$frames	= [];
	foreach( [2, 3, 4, 5] as $version ){
		$url		= $app->getDemoUrl( 'compare.php', ['frame' => 1, 'section' => $sectionNr, 'example' => $exampleNr, 'bootstrap' => $version] );
		$frames[]	= HtmlTag::create( 'div', [
			HtmlTag::create( 'div', 'Bootstrap '.$version, ['class' => 'demo-compare-label'] ),
			HtmlTag::create( 'iframe', '', ['src' => $url, 'title' => $example['title'].', Bootstrap '.$version, 'onload' => 'demoResizeFrame(this)'] ),
		] );
	}
	$examples[]	= HtmlTag::create( 'div', [
		HtmlTag::create( 'h4', $example['title'] ),
		'' !== $example['description'] ? HtmlTag::create( 'p', $example['description'] ) : '',
		HtmlTag::create( 'div', $frames, ['class' => 'demo-compare'.( $isWide ? ' demo-compare-wide' : '' )] ),
	], ['class' => 'demo-example'] );
}

$script	= '<script>
/*  Fits height of frame to its content, also to opened modals, which are positioned fixed.  */
function demoResizeFrame(frame){
	var doc = frame.contentWindow.document;
	var resize = function(){
		var bottom = 0;
		doc.querySelectorAll("body *").forEach(function(element){
			var rect = element.getBoundingClientRect();
			if(rect.width > 0 && rect.height > 0 && !element.classList.contains("modal-backdrop") && !element.classList.contains("modal"))
				bottom = Math.max(bottom, rect.bottom + doc.defaultView.scrollY);
		});
		var dialog = doc.querySelector(".modal.in .modal-content, .modal.show .modal-content") || doc.querySelector(".modal.in");
		if(dialog)																//  open modal: natural height, since it is limited to frame
			bottom = Math.max(bottom, Math.max(0, dialog.getBoundingClientRect().top) + dialog.scrollHeight + 40);	//  top may be negative while sliding in
		frame.style.height = Math.ceil(bottom + 12) + "px";
	};
	var resizeLater = function(){												//  after animations and repositioning
		[20, 200, 500, 800].forEach(function(delay){ setTimeout(resize, delay); });
	};
	resize();
	new MutationObserver(resizeLater).observe(doc.body, {attributes: true, childList: true, subtree: true});
	doc.addEventListener("transitionend", resize, true);						//  modal finished sliding or fading in
	if(window.ResizeObserver)
		new ResizeObserver(resizeLater).observe(doc.body);					//  icons of FontAwesome 5+ are replaced later
}
</script>';

$app->setContent( join( [
	HtmlTag::create( 'p', 'Each example of the <a href="'.$app->getDemoUrl( 'components.php' ).'">components demo</a> '.
		'rendered for Bootstrap 2, 3, 4 and 5, each with its own styles. '.
		'The FontAwesome version selected above is used for all frames. '.
		'Dropdown menus and modals open on click, the frame grows to show them.' ),
	HtmlTag::create( 'ul', $navItems, ['class' => 'nav nav-pills demo-compare-sections'] ),
	HtmlTag::create( 'p', HtmlTag::create( 'a', $isWide ? 'Show two frames per row' : 'Show one frame per row, in full width (for grid layouts)', [
		'href'	=> $app->getDemoUrl( 'compare.php', ['section' => $sectionNr, 'wide' => (int) !$isWide] ),
	] ) ),
	HtmlTag::create( 'h2', $section['title'], ['class' => 'demo-section'] ),
	'' !== $section['description'] ? HtmlTag::create( 'p', $section['description'] ) : '',
	join( $examples ),
	$script,
] ) );
$app->run();
