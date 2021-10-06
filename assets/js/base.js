// Preloader Script
$( document ).ready( function () {
    //    console.log( "ready!" );
    // $( '.' ).matchHeight();
    // $( 'select' ).formSelect();
    $( '.datepicker' ).datepicker();
} );

function Numeric( evt ) {
    evt = ( evt ) ? evt : window.event;
    var charCode = ( evt.which ) ? evt.which : evt.keyCode;
    if ( !( evt.shiftKey == false && ( charCode == 46 || charCode == 8 || charCode == 37 || charCode == 39 || ( charCode >= 48 && charCode <= 57 ) ) ) ) {
        evt.preventDefault();
    }
}

function Numeric_with_min( evt ) {
    evt = ( evt ) ? evt : window.event;
    var charCode = ( evt.which ) ? evt.which : evt.keyCode;
    if ( !( evt.shiftKey == false && ( charCode == 45 || charCode == 46 || charCode == 8 || charCode == 37 || charCode == 39 || ( charCode >= 48 && charCode <= 57 ) ) ) ) {
        evt.preventDefault();
    }
}

function numberFormat( x ) {

    return x.toString().replace( /\B(?=(\d{3})+(?!\d))/g , "," );
}

var preloader = document.getElementById( "loading" );

function loading() {
    preloader.style.display = 'none';
}
