document.addEventListener( 'DOMContentLoaded', () => {
    const form = document.querySelector( '#appcss-form' );
    if( ! form ) return;

    form.addEventListener( 'submit', () => {
        form.classList.add( 'apcss-loading' );
    } );
} );