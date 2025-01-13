bs.util.registerNamespace( 'bs.contextMenu' );
bs.contextMenu._storage = {
	popups: {}
};
bs.contextMenu.openMenuOn = function( $el, title ) {
	if ( !title ) {
		title = $el.data( 'bs-title' );
	}
	const cached = bs.contextMenu._storage.popups[title] || null;
	let popup;
	if ( !cached ) {
		popup = new bs.contextMenu.ContextMenu( {
			forTitle: title,
			$floatableContainer: $el
		} );
		$( 'body' ).append( popup.$element );
		popup.initialize();
		bs.contextMenu._storage.popups[title] = popup;
	} else {
		popup = cached;
	}
	popup.setFloatableContainer( $el );
	popup.toggle( true );
};
$( function() {
	$( document ).on( 'contextmenu', 'a', function( e ) {
		const mode = mw.user.options.get( 'bs-contextmenu-modus', 'ctrl' );
		if ( ( mode === 'no-ctrl' && e.ctrlKey ) || ( mode === 'ctrl' && !e.ctrlKey ) ) {
			return true;
		}
		const $anchor = $( this );
		if ( $anchor.hasClass('external') ) {
			return true;
		}
		bs.contextMenu.openMenuOn( $anchor );
		return false;
	});
} );
