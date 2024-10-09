<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Code;

$component	= new Badge( "2", Badge::CLASS_INFO );

print '<h3>Badge</h3>'.$component;
print new Code( '
$component	= new \CeusMedia\Bootstrap\Badge( "2",  \CeusMedia\Bootstrap\Badge::CLASS_INFO );
' );
