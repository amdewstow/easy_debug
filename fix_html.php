<?php
    require( 'indenter.php' ); //https://github.com/gajus/dindent/blob/master/src/Indenter.php
    //
    $dirs    = array( );
    $base    = realpath( __DIR__ . "/../" ) . "/";
    $dirs[ ] = $base . "foo/bar/";
    foreach ( $dirs as $fid => $dir ) {
        echo "<br><a href='?fid=" . $fid . "'>" . $dir . "</a>";
    }
    echo "<br><a href='?fid=ALL'>ALL</a>";
    if ( isset( $_GET[ 'fid' ] ) ) {
        echo "<hr>";
        $options = array(
             'indentation_character' => '  ' 
        );
        $ii      = new Indenter( $options );
        if ( $_GET[ 'fid' ] == "ALL" ) {
            foreach ( $dirs as $fid => $dir ) {
                i_folder( $dir );
            }
        } else {
            $dir = $dirs[ $_GET[ 'fid' ] ];
            i_folder( $dir );
        }
    }
    function i_folder( $dir ) {
        global $ii;
        if ( is_dir( $dir ) ) {
            if ( $dh = opendir( $dir ) ) {
                while ( ( $file = readdir( $dh ) ) !== false ) {
                    if ( filetype( $dir . $file ) == "file" ) {
                        $fz = $dir . $file;
                        if ( substr( $fz, -5 ) == ".html" ) {
                            if ( is_writable( $fz ) ) {
                                echo "\n<br>" . $fz;
                                $cleand = $ii->indent( file_get_contents( $fz ) );
                                file_put_contents( $fz, $cleand );
                            }
                        }
                    }
                }
                closedir( $dh );
            }
        } else {
            echo "<br>Not a folder " . $dir;
        }
    }