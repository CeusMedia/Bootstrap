<?php
declare(strict_types=1);

use CeusMedia\Common\UI\HTML\PageFrame as HtmlPage;

(@include '../vendor/autoload.php') or die('Please use composer to install required packages.');
require __DIR__.'/VersionProcessor.php';
require __DIR__.'/DemoAppTemplate.php';

use CeusMedia\Bootstrap\Checkbox;
use CeusMedia\Bootstrap\Code;

$content	= '
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
	'.new Checkbox( 'check1', 1, TRUE, 'This is the label' ).'
	'.new Checkbox( 'check2', 1, FALSE, 'This one is not checked on load' ).'
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
	</p>';


$a = new DemoAppTemplate( 'Checkbox Demo', './2_checkbox.php' );
$a->setBootstrapVersion( 5 );
$a->setFontAwesomeVersion( 6 );
$a->setContent( $content );
$a->addStyle( 'checkbox.css' );
$a->run();
