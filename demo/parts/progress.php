<?php

use CeusMedia\Bootstrap\Progress;

$progress	= new Progress();
$progress->addBar( '50', Progress::BAR_CLASS_DANGER );
$progress->addBar( '25', Progress::BAR_CLASS_WARNING );
$progress->addBar( '12.5', Progress::BAR_CLASS_INFO );

print '<h3>Progress</h3>'.$progress;
