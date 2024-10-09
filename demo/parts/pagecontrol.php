<?php
declare(strict_types=1);

use CeusMedia\Bootstrap\Nav\PageControl;

$component	= new PageControl( "#page-", 0, 10 );
$component->patternUrl	= "%s";
print '<h3>Pagination</h3>'.$component;
