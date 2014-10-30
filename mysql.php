<?php
    function data_i( $s ) {
        //legecy data in
        return mysql_real_escape_string( $s );
    }
    function d_i( $s ) {
        // data in
        return mysql_real_escape_string( $s );
    }
    function d_it( $s ) {
        // data in trim
        return mysql_real_escape_string( trim( $s ) );
    }
    function d_itw( $s ) {
        // data in trim, wrap
        return "'" . mysql_real_escape_string( trim( $s ) ) . "'";
    }
    function d_iwt( $s ) {
        // data in trim , wrap legacy name
        return d_itw( $s );
    }
?>