<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');

use \CeusMedia\Bootstrap\Checkbox;
use \CeusMedia\Bootstrap\Code;

$body	= '
<div class="container">
	<h1><span class="muted text-muted">CeusMedia Bootstrap</span> Checkbox Demo</h1>
	<h2>Usage</h2>
	<h3>Composer</h3>
	<p>
		Of course, you will need to load the composed library loaders by starting your script with:
	</p>
	<pre>&lt;?php
require_once \'vendor/autoload.php\';</pre>
	<h3>Namespace</h3>
	<p>
		To use the class without namespace, prepend this line to your script or class:
	</p>
	<pre>use \CeusMedia\Bootstrap\Checkbox;</pre>
	<p>
		Afterwards you can create an instance by:
	</p>
	<pre>new Checkbox( ... );</pre>
	<p>
		Otherwise create a new instance with:
	</p>
	<pre>new \CeusMedia\Bootstrap\Checkbox( ... );</pre>
	<p>
		The generated HTML code will be returned immediately after construction:
	</p>
	<pre>$html	= new Checkbox( ... );</pre>

	<h2>Examples</h2>
	'.new \CeusMedia\Bootstrap\Checkbox( 'check1', 1, TRUE, 'This is the label' ).'
	'.new \CeusMedia\Bootstrap\Checkbox( 'check2', 1, FALSE, 'This one is not checked on load' ).'
	<h3>Source Code</h3>
	'.new Code( "
\$input1	= new Checkbox( 'check1', 1, TRUE, 'This is the label' );
\$input2	= new Checkbox( 'check12, 1, FALSE, 'This is the label' );" ).'
	<h3>All together now</h3>
	'.new Code( "<?php
require_once 'vendor/autoload.php';
use \CeusMedia\Bootstrap\Checkbox;
\$input	= new Checkbox( 'check1', 1, TRUE, 'This is the label' );" ).'
	<p class="alert alert-warning">
		Don\'t forget to load Bootstrap and checkbox.css!
	</p>
</div>';

$pathCDN	= "https://cdn.ceusmedia.de/";
$scripts	= [
	$pathCDN."js/jquery/1.10.2.js",
	$pathCDN."js/bootstrap.min.js",
];
$styles		= [
	$pathCDN."css/bootstrap/2.3.2/bootstrap.min.css",
//	$pathCDN."css/bootstrap/3.3.7/bootstrap.min.css",
//	$pathCDN."css/bootstrap/4.0.0/bootstrap.min.css",
//	$pathCDN."css/bootstrap-responsive.min.css",
	"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",
	"checkbox.css",
];

/*  --  OUTPUT  --  */
$page	= new UI_HTML_PageFrame();
$page->addBody( trim( $body ) );
#$page->setTitle( $config['app.title'] );
foreach( $scripts as $url ) $page->addJavaScript( $url );
foreach( $styles as $url ) $page->addStylesheet( $url );
print( $page->build() );
?>
