<?php
    require_once( '../formaters.php' );
    $_SITE_        = "Exampel.com";
    $_TITLE_       = " Foo Bar";
    //load main template 
    $html          = load_template( 'example.html' );
    //load sub templates 
    $row           = load_template( 'example_row.html' );
    $big           = array( );
    //exampel fake data
    $data          = '
    [
    {"ID":"2","FirstName":"Jeremy","LastName":"James"},
    {"ID":"3","FirstName":"Gary","LastName":"Bell"},
    {"ID":"4","FirstName":"Walter","LastName":"Martinez"},
    {"ID":"5","FirstName":"Harry","LastName":"Nelson"},
    {"ID":"6","FirstName":"Ezequiel","LastName":"Johnson"},
    {"ID":"7","FirstName":"Ralph","LastName":"Perez"},
    {"ID":"8","FirstName":"Peter","LastName":"Carter"},
    {"ID":"9","FirstName":"Linda","LastName":"Walker"},
    {"ID":"10","FirstName":"Carol","LastName":"Powell"},
    {"ID":"11","FirstName":"Angela","LastName":"Smith"}
    ]';
    $ar            = json_decode( $data, true );
    $big[ 'rows' ] = array( );
    //walk your data to add rows of the data
    foreach ( $ar as $vv ) {
        $vv[ 'Name' ]     = $vv[ 'FirstName' ] . " " . $vv[ 'LastName' ];
        $big[ 'rows' ][ ] = smart_template( $row, $vv );
    }
    echo smart_template( $html, $big );