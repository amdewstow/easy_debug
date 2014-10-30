<?php
    if ( isset( $_GET[ 'debug' ] ) ) {
        if ( $_GET[ 'debug' ] == 1 ) {
            $_SESSION[ 'debug' ] = 1;
        } elseif ( $_GET[ 'debug' ] == 2 ) {
            $_SESSION[ 'debug' ] = 2;
        } elseif ( $_GET[ 'debug' ] == 3 ) {
            $_SESSION[ 'debug' ] = 3;
        } else {
            $_SESSION[ 'debug' ] = 0;
        }
    }
    function d_o( $s ) {
        // data out
        return htmlspecialchars( $s, ENT_QUOTES );
    }
    function strreplace( $f, $r, $s ) {
        // clearner str_replace
        $cc = 1;
        while ( $cc ) {
            $s = str_replace( $f, $r, $s, $cc );
        }
        return $s;
    }
    function printr( $a ) {
        // replaces print_r
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] >= 1 ) ) {
            echo "\n\n<hr><pre>" . print_r( $a, true ) . "</pre>\n\n";
        }
    }
    function printrr( $n, $a ) {
        // fancy labeled print_r
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] >= 2 ) ) {
            echo "\n\n<hr>\n$n\n<pre>" . print_r( $a, true ) . "</pre>\n/$n\n\n";
        }
    }
    function printhtmlspecialchars( $a ) {
        // template debugger 
        $r = array( );
        foreach ( $a as $kk => $vv ) {
            $r[ $kk ] = htmlspecialchars( $vv, ENT_QUOTES );
        }
        printr( $r );
    }
?>