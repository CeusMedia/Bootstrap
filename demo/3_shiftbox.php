<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

use CeusMedia\Bootstrap\Shiftbox;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Common\UI\HTML\PageFrame as HtmlPage;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

/**
 *	@link		http://www.larentis.eu/switch/ Bootstrap switch examples
 *	@link		https://github.com/nostalgiaz/bootstrap-switch bootstrap-switch@GitHub
 */

$iconPower		= HtmlTag::create( 'i', '', ['class' => 'fa fa-fw fa-power-off'] );
$iconSwitch		= HtmlTag::create( 'i', '', ['class' => 'fa fa-fw fa-arrows-h'] );
$iconCircle		= HtmlTag::create( 'i', '', ['class' => 'fa fa-fw fa-circle-o-notch'] );
$iconCheck		= HtmlTag::create( 'i', '', ['class' => 'fa fa-fw fa-check'] );
$iconRemove		= HtmlTag::create( 'i', '', ['class' => 'fa fa-fw fa-remove'] );


/*  --  YOUR CODE  --  */
/*  ..  please your coding flow here  .. */
$body	= '
<div class="container">
	<h1><span class="muted text-muted">CeusMedia Bootstrap</span> Shiftbox Demo</h1>
	<div class="alert alert-info">
		Check the <a href="https://bootstrapswitch.com/">Bootstrap Switch examples</a> to know, what this is about.
	</div>
	<h2>Usage</h2>
	<h3>Composer</h3>
	<p>
		You will need <a href="https://github.com/nostalgiaz/bootstrap-switch">bootstrap-switch@GitHub</a>.
		Extend your project with composer:
	</p>
 	<pre>$> composer require nostalgiaz/bootstrap-switch</pre>
	<p>
		Of course, you will need to load the composed library loaders by starting your script with:
	</p>
	<pre>&lt;?php
require_once \'vendor/autoload.php\';</pre>
	<h3>Namespace</h3>
	<p>
		To use the class without namespace, prepend this line to your script or class:
	</p>
	<pre>use \CeusMedia\Bootstrap\Shiftbox;</pre>
	<p>
		Afterwards you can create an instance by:
	</p>
	<pre>new Shiftbox( ... );</pre>
	<p>
		Otherwise create a new instance with:
	</p>
	<pre>new \CeusMedia\Bootstrap\Shiftbox( ... );</pre>
	<p>
		The generated HTML code will be returned immediately after construction:
	</p>
	<pre>$html	= new Shiftbox( ... );</pre>
	<h2>Examples</h2>
	<div>
		<h3>Default size and labels</h3>
		<p>
			'.new Shiftbox( 'check1', 'on', TRUE ).'
		</p>
		'.new Code( "new Shiftbox( 'check1', 'on', TRUE );" ).'
	</div>
	<div>
		<h3>Large and yes/no labels</h3>
		<p>
			'.new Shiftbox( 'check2', 'on', TRUE, [
				'size'		=> 'large',
				'on-text'	=> 'YES',
				'off-text'	=> 'NO'
			] ).'
		</p>
		'.new Code( "new Shiftbox( 'check2', 'on', TRUE, [
	'size'		=> 'large',
	'on-text'	=> 'YES',
	'off-text'	=> 'NO'
] );", FALSE, NULL, TRUE ).'
	</div>
	<div>
		<h3>Small and german on/off labels</h3>
		<p>
			'.new Shiftbox( 'check4', 'on', TRUE, [
				'size'			=> 'small',
				'on-text'		=> 'an',
				'off-text'		=> 'aus',
			] ).'
		</p>
		'.new Code( "new Shiftbox( 'check4', 'on', TRUE, [
	'size'			=> 'small',
	'on-text'		=> 'an',
	'off-text'		=> 'aus'
] );" ).'
	</div>
	<div>
		<h3>Small and icon labels and button icon label</h3>
		<p>
			'.new Shiftbox( 'check4', 'on', TRUE, [
				'size'			=> 'small',
				'label-text'	=> $iconSwitch,
				'on-text'		=> $iconCheck,
				'off-text'		=> $iconRemove
			] ).'
		</p>
		'.new Code( "new Shiftbox( 'check4', 'on', TRUE, [
	'size'			=> 'small',
	'label-text'	=> '".$iconSwitch."',
	'on-text'		=> '".$iconCheck."',
	'off-text'		=> '".$iconRemove."'
] );" ).'
	</div>
	<div>
		<h3>Different colors, small and german yes/no labels </h3>
		<p>
			<label class="checkbox">
				'.new Shiftbox( 'check5', 'on', TRUE, [
					'size'			=> 'mini',
					'on-color'		=> 'success',
					'off-color'		=> 'danger',
					'on-text'		=> '&nbsp;I&nbsp;',
					'off-text'		=> '&nbsp;O&nbsp;',
					'label-text'	=> $iconPower
	 			] ).'&nbsp;
				This label will trigger the switch aswell.
			</label>
		</p>
		'.new Code( "new Shiftbox( 'check5', 'on', TRUE, [
	'size'			=> 'mini',
	'on-color'		=> 'success',
	'off-color'		=> 'danger',
	'on-text'		=> '&nbsp;I&nbsp;',
	'off-text'		=> '&nbsp;O&nbsp;',
	'label-text'	=> ".$iconPower.",
] );" ).'
	</div>
	<div>
		<h3>All together now</h3>
		<p>
			<label class="checkbox">
				'.new Shiftbox( 'check6', 'on', TRUE, [
					'size'			=> 'large',
					'on-color'		=> 'success',
					'off-color'		=> 'danger',
					'on-text'		=> '&nbsp;I&nbsp;',
					'off-text'		=> $iconCircle,
					'label-text'	=> $iconPower
	 			] ).'&nbsp;
				Label to describe switch and change switch on click.
			</label>
		</p>
		'.new Code( "<?php
require_once 'vendor/autoload.php';
use \CeusMedia\Bootstrap\Shiftbox;
\$input	= new Shiftbox( 'check6', 'on', TRUE, [
	'size'			=> 'large',
	'on-color'		=> 'success',
	'off-color'		=> 'danger',
	'on-text'		=> '&nbsp;I&nbsp;',
	'off-text'		=> '".$iconCircle."',
	'label-text'	=> '".$iconPower."',
] );" ).'
		<p class="alert alert-warning">
			Don\'t forget to load jQuery, Bootstrap 3 and Bootstrap Switcht for Bootstap 3!
		</p>
	</div>
</div>
<script>
$(document).ready(function(){
	$(":input[type=checkbox].shiftbox").bootstrapSwitch();
	$(":input[type=checkbox].shiftbox").on("change",function(e){
		console.log($(this).attr("id")+": "+$(this).is(":checked"))
	});
});
</script>
';


$pathCDN	= "https://cdn.ceusmedia.de/";
$scripts	= [
	$pathCDN."js/jquery/1.10.2.js",
	$pathCDN."js/bootstrap.min.js",
	"../vendor/nostalgiaz/bootstrap-switch/dist/js/bootstrap-switch.min.js",
#	"script.js",
];
$styles		= [
	$pathCDN."css/bootstrap/2.3.2/bootstrap.min.css",
//	$pathCDN."css/bootstrap/3.3.7/bootstrap.min.css",
//	$pathCDN."css/bootstrap/4.0.0/bootstrap.min.css",
//	$pathCDN."css/bootstrap-responsive.min.css",
	"../vendor/nostalgiaz/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css",
	"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",
#	"style.css",
];

/*  --  OUTPUT  --  */
$page	= new HtmlPage();
$page->addBody( trim( $body ) );
#$page->setTitle( $config['app.title'] );
foreach( $scripts as $url ) $page->addJavaScript( $url );
foreach( $styles as $url ) $page->addStylesheet( $url );
print( $page->build() );
