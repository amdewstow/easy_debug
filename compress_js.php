<?php
    $dira    = array( );
    $dira[ ] = __DIR__ . "/js/";
    $savedt  = 0;
    foreach ( $dira as $dir ) {
        if ( is_dir( $dir ) ) {
            if ( $dh = opendir( $dir ) ) {
                while ( ( $file = readdir( $dh ) ) !== false ) {
                    if ( filetype( $dir . $file ) == "file" ) {
                        if ( ( substr( $file, -3 ) == ".js" ) && ( substr( $file, -7 ) != ".min.js" ) ) {
                            echo "\n<br> " . $dir . $file;
                            $sz_was        = filesize( $dir . $file );
                            $raw           = file_get_contents( $dir . $file );
                            $url           = 'https://javascript-minifier.com/raw';
                            $fields_string = 'input=' . urlencode( $raw );
                            $ch            = curl_init();
                            curl_setopt( $ch, CURLOPT_URL, $url );
                            curl_setopt( $ch, CURLOPT_POST, 1 );
                            curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
                            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                            $result = curl_exec( $ch );
                            if ( $result !== false ) {
                                $save_as = $dir . str_replace( ".js", ".min.js", $file );
                                file_put_contents( $save_as, $result );
                            } else {
                                die( "Error getting result from javascript-minifier.com" );
                            }
                            $sz_now = filesize( $save_as );
                            $saved  = ( $sz_was - $sz_now );
                            $savedt += $saved;
                            echo " saved " . number_format( $saved, 0 );
                            curl_close( $ch );
                        }
                    }
                }
                closedir( $dh );
            }
        }
    }
    echo "<hr>Saved " . number_format( $savedt / 1000, 2 ) . 'Kb';
?>