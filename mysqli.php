<?php
    $DBS = array(
         'server',
        'user',
        'password',
        'database' 
    );
    $DB = mysqli_connect( $DBS[ 0 ], $DBS[ 1 ], $DBS[ 2 ], $DBS[ 3 ] ) or die( "Error " . mysqli_error( $DB ) );
    function d_i( $s ) {
        // data in
        global $DB;
        return mysqli_real_escape_string( $DB, $s );
    }
    function d_iw( $s ) {
        // data in wrap
        global $DB;
        return "'" . mysqli_real_escape_string( $DB, $s ) . "'";
    }
    function d_itw( $s ) {
        // data in trim , wrap
        global $DB;
        return "'" . mysqli_real_escape_string( $DB, trim( $s ) ) . "'";
    }
    function d_iwt( $s ) {
        // data in trim , wrap legacy name
        return d_itw( $s );
    }
    function db_ping( $s ) {
        // ping DB and re conect on fail
        global $DB;
        if ( $DB->ping() ) {
        } else {
            echo "\n<br>Error: " . $mysqli->error;
            $DB = mysqli_connect( $DBS[ 0 ], $DBS[ 1 ], $DBS[ 2 ], $DBS[ 3 ] ) or die( "Error " . mysqli_error( $DB ) );
        }
    }
?>