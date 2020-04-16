<?php
$component	= new CeusMedia\Bootstrap\PageControl( "#page-", 0, 10 );
$component->patternUrl	= "%s";
print '<h3>Pagination</h3>'.$component;
