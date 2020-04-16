<?php
use CeusMedia\Bootstrap\Button\Group as ButtonGroup;
use CeusMedia\Bootstrap\Button;

$group	= new ButtonGroup();
$group->add( new Button( 'Play', Button::STATE_SUCCESS, 'play' ) );
$group->add( new Button( 'Pause', Button::STATE_WARNING, 'pause' ) );
$group->add( new Button( 'Stop', Button::STATE_DANGER, 'stop' ) );

print '<h3>Button Group</h3>'.$group;
