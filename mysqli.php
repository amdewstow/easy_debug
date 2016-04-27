<?php
    $DBS = array(
         'server',
        'user',
        'password',
        'database' 
    );
    $DB  = new mysqli( $DBS[ 0 ], $DBS[ 1 ], $DBS[ 2 ], $DBS[ 3 ] );
    if ( $DB->connect_errno == 1049 ) {
        $DB  = new mysqli( $DBS[ 0 ], $DBS[ 1 ], $DBS[ 2 ] );
        $sql = "CREATE DATABASE `" . $DBS[ 3 ] . "`;";
        $DB->query( $sql ) or die( "You had No DATABASE, I tried to make it for you but failed<br>Pleas make `" . $DBS[ 3 ] . "` and refresh this page" );
        exit( "You had No DATABASE, I made it for you, refresh the page" );
    }
    if ( $DB->connect_errno !== 0 ) {
        die( 'Connect Error: ' . $DB->connect_errno . " : " . $DB->connect_error );
    }
    if ( $DB === false ) {
        die( "Error Z\n" . "\n\n" . print_r( $DB, true ) . "<br>\n" . __FILE__ . '@' . __LINE__ );
    }
    $DB->query( 'SET NAMES utf8' );
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
    function d_o( $s ) {
        return htmlspecialchars( $s, ENT_QUOTES );
    }
    function insert_array( $t, $a ) {
        return "INSERT INTO `" . $t . "` (`" . implode( "`, `", array_keys( $a ) ) . "`) VALUES (" . implode( ",", $a ) . ");";
    }
    function update_array( $t, $a, $wh ) {
        $aa = array( );
        foreach ( $a as $kk => $vv ) {
            $aa[ ] = "`" . $kk . "` = " . $vv;
        }
        return "UPDATE `" . $t . "` SET " . implode( " , ", $aa ) . " WHERE " . $wh . ";";
    }
?>