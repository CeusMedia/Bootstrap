/*
 *	Frames of compare demo: dropdown menus and modals open on click, the frame grows to show them (see compare.php).
 *	Bootstrap 4 (Popper 1) flips dropdown menus up, if there is no space below, which is the case in a small frame.
 *	So flipping is disabled, menus open below their trigger like in the other versions.
 */
if(window.jQuery && jQuery.fn.dropdown && jQuery.fn.dropdown.Constructor && jQuery.fn.dropdown.Constructor.Default)
	jQuery.fn.dropdown.Constructor.Default.flip = false;
