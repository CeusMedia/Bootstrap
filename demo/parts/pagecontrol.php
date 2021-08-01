<?php
$component	= new CeusMedia\Bootstrap\Nav\PageControl( "#page-", 0, 10 );
$component->patternUrl	= "%s";
print '<h3>Pagination</h3>'.$component;
