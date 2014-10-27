<?php
    if ( isset( $_GET[ 'debug' ] ) ) {
        if ( $_GET[ 'debug' ] == 1 ) {
            $_SESSION[ 'debug' ] = 1;
        } elseif ( $_GET[ 'debug' ] == 2 ) {
            $_SESSION[ 'debug' ] = 2;
        } elseif ( $_GET[ 'debug' ] == 3 ) {
            $_SESSION[ 'debug' ] = 3;
        } else ( $_GET[ 'debug' ] == 0 ) {
            $_SESSION[ 'debug' ] = 0;
        }
    }
    function d_o( $s ) {
        return htmlspecialchars( $s, ENT_QUOTES );
    }
    function strreplace( $f, $r, $s ) {
        $cc = 1;
        while ( $cc ) {
            $s = str_replace( $f, $r, $s, $cc );
        }
        return $s;
    }
    function printr( $a ) {
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] >= 1 ) ) {
            echo "\n\n<hr><pre>" . print_r( $a, true ) . "</pre>\n\n";
        }
    }
    function printrr( $n, $a ) {
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] >= 2 ) ) {
            echo "\n\n<hr>\n$n\n<pre>" . print_r( $a, true ) . "</pre>\n/$n\n\n";
        }
    }
    function printhtmlspecialchars( $a ) {
        $r = array( );
        foreach ( $a as $kk => $vv ) {
            $r[ $kk ] = htmlspecialchars( $vv );
        }
        printr( $r );
    }
?>