<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	Examples of all components, registered for demos "components" (all examples with code)
 *	and "compare" (each example in all Bootstrap versions).
 *	Each example is a closure returning HTML; its source code is shown by DemoExample.
 */

use CeusMedia\Bootstrap\Alert;
use CeusMedia\Bootstrap\Badge;
use CeusMedia\Bootstrap\Button;
use CeusMedia\Bootstrap\Button\Group as ButtonGroup;
use CeusMedia\Bootstrap\Button\Link as ButtonLink;
use CeusMedia\Bootstrap\Button\Submit as SubmitButton;
use CeusMedia\Bootstrap\Button\Toolbar as ButtonToolbar;
use CeusMedia\Bootstrap\Card;
use CeusMedia\Bootstrap\Checkbox;
use CeusMedia\Bootstrap\Code;
use CeusMedia\Bootstrap\Dropdown\Menu as DropdownMenu;
use CeusMedia\Bootstrap\Dropdown\Trigger as DropdownTrigger;
use CeusMedia\Bootstrap\Heading;
use CeusMedia\Bootstrap\Icon;
use CeusMedia\Bootstrap\Label;
use CeusMedia\Bootstrap\Link;
use CeusMedia\Bootstrap\Modal\Dialog as ModalDialog;
use CeusMedia\Bootstrap\Modal\Trigger as ModalTrigger;
use CeusMedia\Bootstrap\Nav\Breadcrumbs;
use CeusMedia\Bootstrap\Nav\NavList;
use CeusMedia\Bootstrap\Nav\PageControl;
use CeusMedia\Bootstrap\Nav\Pills;
use CeusMedia\Bootstrap\Nav\TabbableNavbar;
use CeusMedia\Bootstrap\Nav\Tabs;
use CeusMedia\Bootstrap\Progress;
use CeusMedia\Bootstrap\Row;
use CeusMedia\Bootstrap\RowSpan;
use CeusMedia\Bootstrap\Shiftbox;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;

ModalDialog::$defaultFade	= TRUE;

/*  --  HEADINGS  --  */
DemoExample::addSection( 'Headings',
	'Headings with muted prefix and small subtitle. Muted text is <code>muted</code> in Bootstrap 2 and <code>text-muted</code> since Bootstrap 3.'
);

DemoExample::addExample( 'Prefix and subtitle', static function(): string {
	return join( [
		new Heading( 'Example Inc.', 2, 'Company' ),
		( new Heading( 'Annual report', 3 ) )->setSubtitle( '2026' ),
		( new Heading( 'Example Inc.', 4, 'Company' ) )->setSubtitle( 'since 2012' ),
	] );
} );

/*  --  ALERTS  --  */
DemoExample::addSection( 'Alerts',
	'States of any version are translated, like <code>alert-error</code> (Bootstrap 2) to <code>alert-danger</code>. An alert without state is a warning, like in Bootstrap 2.'
);

DemoExample::addExample( 'States', static function(): string {
	return join( [
		new Alert( '<b>Success!</b> Everything went fine.', Alert::CLASS_SUCCESS ),
		new Alert( '<b>Info:</b> Just for your information.', Alert::CLASS_INFO ),
		new Alert( '<b>Warning:</b> Better check this.', Alert::CLASS_WARNING ),
		new Alert( '<b>Danger:</b> Something went wrong.', Alert::CLASS_DANGER ),
	] );
} );

DemoExample::addExample( 'Further states', static function(): string {
	return join( [
		new Alert( 'Primary', Alert::CLASS_PRIMARY ),
		new Alert( 'Secondary', Alert::CLASS_SECONDARY ),
		new Alert( 'Light', Alert::CLASS_LIGHT ),
		new Alert( 'Dark', Alert::CLASS_DARK ),
	] );
}, 'Available since Bootstrap 4, older versions show these as info.' );

DemoExample::addExample( 'Dismissable', static function(): string {
	$alert	= new Alert( 'This alert can be closed.', Alert::CLASS_INFO );
	$alert->useDismiss();
	return $alert->render();
} );

/*  --  CARDS  --  */
DemoExample::addSection( 'Cards',
	'Box with header, title, body and footer: <code>well</code> in Bootstrap 2, <code>panel</code> in Bootstrap 3, <code>card</code> since Bootstrap 4.'
);

DemoExample::addExample( 'Header, title, body and footer', static function(): string {
	$card	= new Card( '<p>Some text of the card body.</p>', 'Header' );
	$card->setTitle( 'Title' );
	$card->setFooter( 'Footer' );
	return $card->render();
} );

DemoExample::addExample( 'Colors', static function(): string {
	$list	= [];
	foreach( ['Primary' => Card::CLASS_PRIMARY, 'Success' => Card::CLASS_SUCCESS, 'Warning' => Card::CLASS_WARNING, 'Danger' => Card::CLASS_DANGER] as $label => $class )
		$list[]	= ( new Card( 'Body of '.strtolower( $label ).' card.', $label, $class ) )->render();
	return join( $list );
}, 'Colored header and border like panels of Bootstrap 3. Bootstrap 2 has no colored wells, so alerts are used.' );

DemoExample::addExample( 'Components inside', static function(): string {
	$progress	= ( new Progress() )->addBar( 70, Progress::BAR_CLASS_SUCCESS, '70%' );
	return ( new Card( [new Heading( 'Upload', 5, 'Status' ), $progress], new Badge( '3 files' ) ) )->render();
} );

/*  --  BADGES AND LABELS  --  */
DemoExample::addSection( 'Badges and labels',
	'Bootstrap 3 has gray badges only, so colors of labels are applied by style. Since Bootstrap 4, badges are rendered as pills to keep their shape.'
);

DemoExample::addExample( 'Badges', static function(): string {
	return join( ' ', [
		new Badge( '1' ),
		new Badge( '2', Badge::CLASS_SUCCESS ),
		new Badge( '3', Badge::CLASS_WARNING ),
		new Badge( '4', Badge::CLASS_IMPORTANT ),
		new Badge( '5', Badge::CLASS_INFO ),
		new Badge( '6', Badge::CLASS_INVERSE ),
	] );
} );

DemoExample::addExample( 'Labels', static function(): string {
	return join( ' ', [
		new Label( 'Default' ),
		new Label( 'Success', Label::CLASS_SUCCESS ),
		new Label( 'Warning', Label::CLASS_WARNING ),
		new Label( 'Important', Label::CLASS_IMPORTANT ),
		new Label( 'Info', Label::CLASS_INFO ),
		new Label( 'Inverse', Label::CLASS_INVERSE ),
	] );
}, 'Labels were replaced by badges in Bootstrap 4, so they are rendered as badges there.' );

/*  --  BUTTONS  --  */
DemoExample::addSection( 'Buttons',
	'State, size and block classes of any Bootstrap version are translated for the selected version.'
);

DemoExample::addExample( 'States', static function(): string {
	$states	= [
		'Default'	=> Button::STATE_DEFAULT,
		'Primary'	=> Button::STATE_PRIMARY,
		'Secondary'	=> Button::STATE_SECONDARY,
		'Success'	=> Button::STATE_SUCCESS,
		'Info'		=> Button::STATE_INFO,
		'Warning'	=> Button::STATE_WARNING,
		'Danger'	=> Button::STATE_DANGER,
		'Light'		=> Button::STATE_LIGHT,
		'Dark'		=> Button::STATE_DARK,
		'Link'		=> Button::STATE_LINK,
	];
	$buttons	= [];
	foreach( $states as $label => $state )
		$buttons[]	= ( new Button( $label, $state ) )->render();
	return join( ' ', $buttons );
} );

DemoExample::addExample( 'Outline (since Bootstrap 4)', static function(): string {
	return join( ' ', [
		new Button( 'Primary', 'btn-outline-primary' ),
		new Button( 'Success', 'btn-outline-success' ),
		new Button( 'Danger', 'btn-outline-danger' ),
	] );
}, 'Older versions render filled buttons instead.' );

DemoExample::addExample( 'Sizes', static function(): string {
	$buttons	= [];
	foreach( ['Large' => Button::SIZE_LARGE, 'Default' => Button::SIZE_DEFAULT, 'Small' => Button::SIZE_SMALL, 'Mini' => Button::SIZE_MINI] as $label => $size )
		$buttons[]	= ( new Button( $label, Button::STATE_PRIMARY ) )->setSize( $size )->render();
	return join( ' ', $buttons );
}, 'Mini becomes extra small in Bootstrap 3 and small since Bootstrap 4.' );

DemoExample::addExample( 'Icon, disabled and block', static function(): string {
	return join( ' ', [
		new Button( 'Save', Button::STATE_SUCCESS, 'check' ),
		new Button( 'Delete', Button::STATE_DANGER, 'trash', TRUE ),
		( new Button( 'Full width', Button::STATE_INFO, 'arrows-h' ) )->setBlock(),
	] );
} );

DemoExample::addExample( 'Links as buttons and submit buttons', static function(): string {
	$confirm	= new ButtonLink( '#deleted', 'Delete with confirmation', Button::STATE_DANGER, 'trash' );
	$confirm->setConfirm( 'Really delete?' );
	return join( ' ', [
		new ButtonLink( '#', 'Link as button', Button::STATE_PRIMARY, 'link' ),
		new ButtonLink( '#', 'Disabled link', NULL, NULL, TRUE ),
		$confirm,
		new SubmitButton( 'save', 'Submit', Button::STATE_SUCCESS, 'paper-plane' ),
	] );
} );

DemoExample::addExample( 'Groups and toolbar', static function(): string {
	$group1	= new ButtonGroup( [
		new Button( '', NULL, 'align-left' ),
		new Button( '', NULL, 'align-center' ),
		new Button( '', NULL, 'align-right' ),
	] );
	$group2	= new ButtonGroup( [
		new Button( 'Play', Button::STATE_SUCCESS, 'play' ),
		new Button( 'Stop', Button::STATE_DANGER, 'stop' ),
	] );
	return ( new ButtonToolbar( [$group1, $group2] ) )->render();
} );

DemoExample::addExample( 'Vertical group', static function(): string {
	return ( new ButtonGroup( [
		new Button( 'Top' ),
		new Button( 'Middle' ),
		new Button( 'Bottom' ),
	], TRUE ) )->render();
} );

/*  --  ICONS  --  */
DemoExample::addSection( 'Icons',
	'Icons are rendered for the selected icon set (see FontAwesome selection above).'
);

DemoExample::addExample( 'Icons, sizes and styles', static function(): string {
	return join( ' ', [
		new Icon( 'star' ),
		new Icon( 'star', NULL, 'x2' ),
		new Icon( 'star', 'regular', 'x2' ),
		new Icon( 'cog', NULL, 'x3 spin' ),
	] );
}, 'Short names are prefixed for the icon set, complete classes like <code>fa-solid fa-star</code> are kept.' );

/*  --  LINKS  --  */
DemoExample::addSection( 'Links' );

DemoExample::addExample( 'Links', static function(): string {
	$withEvent	= new Link( '#', 'Link with click event' );
	$withEvent->setEvent( 'click', 'alert("clicked"); return false;' );
	return join( ' &middot; ', [
		new Link( '#', 'Normal link' ),
		new Link( '#', 'Link with icon', NULL, 'external-link' ),
		new Link( '#', 'Disabled link', NULL, NULL, TRUE ),
		$withEvent,
	] );
} );

/*  --  DROPDOWNS  --  */
DemoExample::addSection( 'Dropdowns' );

DemoExample::addExample( 'Dropdown buttons', static function(): string {
	$menu	= new DropdownMenu();
	$menu->add( '#edit', 'Edit', NULL, 'pencil' );
	$menu->add( '#copy', 'Copy', NULL, 'copy' );
	$menu->addDivider();
	$menu->add( '#delete', 'Delete', NULL, 'trash', TRUE );

	$right	= clone $menu;
	$right->setAlign( FALSE );

	return HtmlTag::create( 'div', new DropdownTrigger( 'Actions', Button::STATE_PRIMARY, 'cog' ).$menu, ['class' => 'btn-group'] ).' '.
		HtmlTag::create( 'div', new DropdownTrigger( 'Aligned right' ).$right, ['class' => 'btn-group'] ).' '.
		HtmlTag::create( 'div', new DropdownTrigger( 'Without caret', NULL, NULL, FALSE ).$menu, ['class' => 'btn-group'] );
}, 'The last item is disabled. Bootstrap 2 and 3 render a caret element, later versions draw it by CSS.' );

DemoExample::addExample( 'Dropdown link', static function(): string {
	$menu		= ( new DropdownMenu() )->add( '#profile', 'Profile' )->add( '#logout', 'Logout' );
	$trigger	= ( new DropdownTrigger( 'Account', NULL, 'user' ) )->asLink();
	return HtmlTag::create( 'div', $trigger.$menu, ['class' => 'dropdown'] );
} );

/*  --  MODALS  --  */
DemoExample::addSection( 'Modals',
	'See <a href="modal.php">Modal Demo</a> for all options.'
);

DemoExample::addExample( 'Modal dialog', static function(): string {
	$dialog	= new ModalDialog( 'modal-demo' );
	$dialog->setHeading( 'Hello World' );
	$dialog->setBody( '<p>Lorem ipsum ...</p>' );
	return ModalTrigger::create( 'modal-demo', 'Open dialog', Button::STATE_PRIMARY )->render().$dialog->render();
} );

/*  --  NAVIGATION  --  */
DemoExample::addSection( 'Navigation' );

DemoExample::addExample( 'Tabs', static function(): string {
	$tabs	= new Tabs( 'demo-tabs' );
	$tabs->add( 'tab-home', '#tab-home', 'Home', '<p>Content of first tab.</p>' );
	$tabs->add( 'tab-profile', '#tab-profile', 'Profile', '<p>Content of second tab.</p>' );
	$tabs->add( 'tab-other', './components.php', 'Other page' );
	$tabs->add( 'tab-disabled', '#tab-disabled', 'Disabled', '', TRUE );
	return $tabs->render();
}, 'Tabs with URL <code>#</code> or <code>#ID</code> switch panes, others link to other pages.' );

DemoExample::addExample( 'Pills with dropdown', static function(): string {
	$menu	= ( new DropdownMenu() )->add( '#sub-1', 'Sub item 1' )->add( '#sub-2', 'Sub item 2' );

	$pills	= new Pills();
	$pills->add( '#pill-1', 'Home', NULL, 'home' );
	$pills->addLink( new Link( '#pill-2', 'Profile', NULL, 'user' ) );
	$pills->addDropdown( $menu, 'More', NULL, 'folder', 'folder-open' );
	$pills->setActive( 0 );
	return $pills->render();
} );

DemoExample::addExample( 'Stacked pills', static function(): string {
	$pills	= ( new Pills() )
		->add( '#stacked-1', 'First' )
		->add( '#stacked-2', 'Second' )
		->add( '#stacked-3', 'Third' )
		->setActive( 1 )
		->setStacked();
	return HtmlTag::create( 'div', $pills, ['style' => 'max-width: 15em'] );
} );

DemoExample::addExample( 'Navigation list', static function(): string {
	$list	= new NavList();
	$list->addHeader( 'Topic 1', 'cube' );
	$list->add( '#link-1', 'Link 1', 'star' );
	$list->add( '#link-2', 'Link 2', 'road' );
	$list->addDivider();
	$list->addHeader( 'Topic 2', 'folder' );
	$list->add( '#link-3', 'Link 3', 'book' );
	$list->setCurrent( '#link-2' );
	return HtmlTag::create( 'div', $list, ['style' => 'max-width: 15em'] );
}, 'Bootstrap 2 has a list navigation, later versions use stacked pills.' );

DemoExample::addExample( 'Breadcrumbs', static function(): string {
	$crumbs	= new Breadcrumbs();
	$crumbs->addLink( new Link( '#', 'CeusMedia', NULL, 'folder-open' ) );
	$crumbs->add( 'Bootstrap', '#', NULL, 'folder-open' );
	$crumbs->addCurrent( 'Demo', 'file' );

	$custom	= new Breadcrumbs( '>' );
	$custom->add( 'Home', '#' )->add( 'Library', '#' )->addCurrent( 'Demo' );

	return $crumbs->render().$custom->render();
}, 'Custom dividers are supported by Bootstrap 2 and 5 only, Bootstrap 3 and 4 need CSS for that.' );

DemoExample::addExample( 'Page control', static function(): string {
	$control	= new PageControl( '#page-', 3, 10 );
	$control->patternUrl	= '%s';

	$small		= new PageControl( '#page-', 0, 3 );
	$small->patternUrl	= '%s';
	$small->size		= PageControl::SIZE_SMALL;

	return $control->render().$small->render();
}, 'Rendered as pagination of the selected version. On the first page, links to first and previous page are disabled.' );

DemoExample::addExample( 'Navbar with tabs', static function(): string {
	$navbar	= new TabbableNavbar();
	$navbar->setBrand( 'Brand', '#' );
	$navbar->add( 'navbar-tab-1', 'Tab 1', '<p>Content of tab 1.</p>' );
	$navbar->add( 'navbar-tab-2', 'Tab 2', '<p>Content of tab 2.</p>' );
	return $navbar->render();
} );

/*  --  PROGRESS  --  */
DemoExample::addSection( 'Progress' );

DemoExample::addExample( 'Colors and labels', static function(): string {
	$progress	= new Progress();
	$progress->addBar( 40, Progress::BAR_CLASS_SUCCESS, '40%' );
	return $progress->render();
} );

DemoExample::addExample( 'Stacked bars', static function(): string {
	$progress	= new Progress();
	$progress->addBar( 50, Progress::BAR_CLASS_DANGER );
	$progress->addBar( 25, Progress::BAR_CLASS_WARNING );
	$progress->addBar( 12.5, Progress::BAR_CLASS_INFO );
	return $progress->render();
} );

DemoExample::addExample( 'Striped and animated', static function(): string {
	$striped	= new Progress( Progress::CLASS_STRIPED );
	$striped->addBar( 60, Progress::BAR_CLASS_INFO );

	$animated	= new Progress( Progress::CLASS_STRIPED.' '.Progress::CLASS_ACTIVE );
	$animated->addBar( 80, Progress::BAR_CLASS_SUCCESS );

	return $striped->render().$animated->render();
} );

/*  --  LAYOUT  --  */
DemoExample::addSection( 'Layout' );

DemoExample::addExample( 'Row with spans', static function(): string {
	$spans	= [
		( new RowSpan( '<div class="demo-preview">span 4</div>' ) )->setSize( 4 ),
		( new RowSpan( '<div class="demo-preview">span 3</div>' ) )->setSize( 3 ),
		( new RowSpan( '<div class="demo-preview">span 5</div>' ) )->setSize( 5 ),
	];
	return ( new Row( $spans ) )->render();
}, 'Bootstrap 2 uses <code>span4</code>, later versions <code>col-md-4</code>. Rows can take a list of spans.' );

DemoExample::addExample( 'Responsive columns', static function(): string {
	$spans	= [];
	foreach( [1, 2, 3] as $nr )
		$spans[]	= ( new RowSpan( '<div class="demo-preview">column '.$nr.'</div>' ) )
			->setSize( 12, RowSpan::BREAKPOINT_XS )
			->setSize( 6, RowSpan::BREAKPOINT_MD )
			->setSize( 4, RowSpan::BREAKPOINT_XL );
	return ( new Row( $spans ) )->render();
}, 'Full width on phones, two columns on tablets, three on large screens (resize the window). Bootstrap 3 has no <code>xl</code> and uses <code>lg</code> instead, Bootstrap 2 has no breakpoints and uses the medium size.' );

DemoExample::addExample( 'Code', static function(): string {
	$short	= new Code( "<?php\necho 'Hello World';" );
	$long	= new Code( join( "\n", array_map( static fn( int $nr ): string => 'Line '.$nr, range( 1, 30 ) ) ), TRUE );
	return $short->render().$long->render();
}, 'Code is escaped and tabs are converted to spaces. Scrollable code is limited in height.' );

/*  --  FORM CONTROLS  --  */
DemoExample::addSection( 'Form controls',
	'Checkboxes with icons need <code>assets/checkbox.css</code> in all versions. Up to Bootstrap 3, switches need the jQuery plugin bootstrap-switch, since Bootstrap 4 they are built in. See <a href="checkbox.php">Checkbox</a> and <a href="shiftbox.php">Shiftbox</a> demos for details and <a href="radiobar.php">RadioBar</a> demo for another control.'
);

DemoExample::addExample( 'Checkbox', static function(): string {
	return join( [
		new Checkbox( 'demo_check_1', 1, TRUE, 'Checked' ),
		new Checkbox( 'demo_check_2', 1, FALSE, 'Not checked' ),
		new Checkbox( 'demo_check_3', 1, TRUE, 'Other icon', 'star' ),
	] );
} );

DemoExample::addExample( 'Switch', static function(): string {
	return ( new Shiftbox( 'demo_switch', 'on', TRUE ) )->setLabel( 'Switch' )->render();
}, 'Up to Bootstrap 3, the plugin turns this checkbox into a switch, which is not loaded here.' );
