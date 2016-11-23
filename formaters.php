<?php
    function file_save_str( $s ) {
        $a = str_split( $s, 1 );
        $o = '';
        foreach ( $a as $l ) {
            $c = ord( $l );
            if ( ( $c >= 48 ) && ( $c <= 57 ) ) {
                $o .= $l;
            } elseif ( ( $c >= 65 ) && ( $c <= 90 ) ) {
                $o .= $l;
            } elseif ( ( $c >= 97 ) && ( $c <= 122 ) ) {
                $o .= $l;
            } else {
                $o .= '_';
            }
        }
        $k = 1;
        while ( $k > 0 ) {
            $o = str_replace( '__', '_', $o, $k );
        }
        return $o;
    }
    function appid_format( $n ) {
        $padto = ( ceil( strlen( $n ) / 3 ) ) * 3;
        if ( $padto <= 9 ) {
            $padto = 9;
        }
        $n = str_pad( $n, $padto, "0", STR_PAD_LEFT );
        return implode( "-", str_split( $n, 3 ) );
    }
    function phone_human( $s ) {
        if ( strlen( $s ) == 10 ) {
            // (713) 123-4567
            return preg_replace( "/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $s );
        } elseif ( strlen( $s ) == 11 ) {
            // +1 (713) 123-4567
            return preg_replace( "/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "+$1($2) $3-$4", $s );
        } elseif ( strlen( $s ) > 4 ) {
            //pffttt no idea just add a - every 3 letters
            return implode( "-", str_split( substr( $s, 0, -4 ), 3 ) ) . '-' . substr( $s, -4 );
        } else {
            return $s;
            return preg_replace( "/[^0-9]/", "", $s );
        }
    }
    function formatSSN( $ssn ) {
        //123-45-678
        return preg_replace( "/^(\d{3})(\d{2})(\d{4})$/", "$1-$2-$3", $ssn );
    }
    function formatTAXID( $ssn ) {
        //12-345678
        return preg_replace( "/^(\d{2})(\d{7})$/", "$1-$2", $ssn );
    }
    function clean_tempalte( $s ) {
        $before            = strlen( $s );
        $ars               = array( );
        $ars[ '      ' ]   = ' ';
        $ars[ '        ' ] = ' ';
        $ars[ "\n\n" ]     = "\n";
        //
        //make them unreadable
        //
        //   $ars[ "\n" ]       = '';
        ##
        $ars[ "\t" ]       = ' ';
        $ars[ '  ' ]       = ' ';
        $ars[ '; ' ]       = ';';
        $ars[ '" >' ]      = '">';
        $ars[ ' </' ]      = '</';
        $ars[ "' >" ]      = "'>";
        $ars[ "  <" ]      = " <";
        $ars[ "\n<td" ]    = "<td";
        $ars[ "\n</tr" ]   = "</tr";
        $ars[ "//FK//" ]   = "";
        foreach ( $ars as $f => $t ) {
            $n = 1;
            while ( $n ) {
                $s = str_replace( $f, $t, $s, $n );
            }
        }
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] == 1 ) ) {
            $s     = str_replace( "<!--", "\n<!--", $s );
            $s     = str_replace( "-->", "-->\n", $s );
            $after = strlen( $s );
            $s .= "<!-- Saved " . ( $before - $after ) . " -->";
        }
        return $s;
    }
    function smart_template( $s, $ar, $looped = false ) {
        global $_HOST_;
        $ar[ '_HOST_' ] = $_HOST_;
        if ( isset( $_SESSION[ 'debug' ] ) && ( $_SESSION[ 'debug' ] >= 3 ) ) {
            printr( htmlspecialchars( $s ) );
            printhtmlspecialchars( $ar );
        }
        foreach ( $ar as $kk => $vv ) {
            //            echo "\n<br>:".$kk.":".$vv;
            if ( is_array( $vv ) ) {
                $vv = implode( '', $vv );
            }
            $s = str_replace( "{" . $kk . "}", $vv, $s );
        }
        $s = clean_tempalte( $s );
        $n = 1;
        while ( $n ) {
            $s = str_replace( " </", '</', $s, $n );
        }
        if ( $looped === false ) {
            if ( strstr( $s, '{' ) !== false ) {
                $s = smart_template( $s, $ar, true );
            }
        }
        $sk = md5( $s );
        return "\n<!-- START $sk -->\n" . $s . "\n<!-- END $sk -->\n";
    }
    function ulli_implode( $a ) {
        return '<ul><li>' . implode( '</li><li>', $a ) . '</li></ul>';
    }
    function li_implode( $a ) {
        return '<li>' . implode( '</li><li>', $a ) . '</li>';
    }
    function sql_implode( $a ) {
        if ( is_array( $a ) && ( count( $a ) > 0 ) ) {
            return implode( ',', $a );
        } else {
            return '-1';
        }
    }
    function is_selv( $a, $b ) {
        $o = ' value="' . $a . '" ';
        if ( $a == $b ) {
            $o .= " selected ";
        }
        return $o;
    }
    function encrypt_( $string, $mk ) {
        $output         = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key     = sha1( '9401d4d19ca7cd80e281c3146583c8f5' . $mk );
        $secret_iv      = md5( '94L0OPJEjTWmM' . $mk );
        // hash
        $key            = hash( 'sha256', $secret_key );
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        $output         = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
        $output         = base64_encode( $output );
        return $output;
    }
    function decrypt_( $string, $mk ) {
        $output         = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key     = sha1( '9401d4d19ca7cd80e281c3146583c8f5' . $mk );
        $secret_iv      = md5( '94L0OPJEjTWmM' . $mk );
        // hash
        $key            = hash( 'sha256', $secret_key );
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        $output         = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        return $output;
    }
    function dpad( $d ) {
        $dd = ( $d - 1 );
        if ( $dd > 0 ) {
            return str_repeat( '-', ( $d - 1 ) * 2 ) . '| ';
        }
    }
    function is_chv( $a, $b ) {
        $o = ' value="' . $a . '" ';
        if ( $a == $b ) {
            $o .= " checked ";
        }
        return $o;
    }
    function easy_input( $id, $val = '' ) {
        $o = "\n\n";
        $o .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $val . '">';
        return $o;
    }
    function is_checked( $a, $b ) {
        if ( $a == $b ) {
            return " checked ";
        }
    }
    function is_checkedv( $a, $b ) {
        $o = ' value="' . $a . '" ';
        if ( $a == $b ) {
            $o .= " checked ";
        }
        return $o;
    }
    function is_selected( $a, $b ) {
        if ( $a == $b ) {
            return " selected ";
        }
    }
    function is_selectedv( $a, $b ) {
        $o = ' value="' . $a . '" ';
        if ( $a == $b ) {
            $o .= " selected ";
        }
        return $o;
    }
    function pdate( $date ) {
        if ( $date == NULL ) {
            return ' -- ';
        }
        $dc = strtotime( $date );
        if ( $dc === false ) {
            return ' ! ';
        }
        return date( 'm/d/Y', $dc );
    }
    function button_select( $ar, $pid, $picked = -1, $onc = null ) {
        printr( $ar );
        $out    = array( );
        $out[ ] = '<input type="hidden" id="' . $pid . '" name="' . $pid . '" value="' . $picked . '">';
        foreach ( $ar as $kk => $vv ) {
            // $out[] = '<option '.is_selv($kk,$picked).' >'.$vv.'</option>';
            $out[ ] = '<span class="btn_select"> <a class="btn' . ( $kk == $picked ? ' btn_SELECTED ' : '' ) . '" onclick="$(\'#' . $pid . '\').val(\'' . $kk . '\'); ' . $onc . '" ><i class="icon_box-checked" style="display:none"></i>' . $vv . '</a></span>';
        }
        return implode( "\n", $out );
    }
    function my_xml2array( $contents ) {
        $xml_values = array( );
        $parser     = xml_parser_create( '' );
        if ( !$parser ) {
            return false;
        }
        xml_parser_set_option( $parser, XML_OPTION_TARGET_ENCODING, 'UTF-8' );
        xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
        xml_parse_into_struct( $parser, trim( $contents ), $xml_values );
        xml_parser_free( $parser );
        if ( !$xml_values ) {
            return array( );
        }
        $xml_array = array( );
        $last_tag_ar =& $xml_array;
        $parents             = array( );
        $last_counter_in_tag = array(
             1 => 0 
        );
        foreach ( $xml_values as $data ) {
            switch ( $data[ 'type' ] ) {
                case 'open':
                    $last_counter_in_tag[ $data[ 'level' ] + 1 ] = 0;
                    $new_tag                                     = array(
                         'name' => $data[ 'tag' ] 
                    );
                    if ( isset( $data[ 'attributes' ] ) )
                        $new_tag[ 'attributes' ] = $data[ 'attributes' ];
                    if ( isset( $data[ 'value' ] ) && trim( $data[ 'value' ] ) )
                        $new_tag[ 'value' ] = trim( $data[ 'value' ] );
                    $last_tag_ar[ $last_counter_in_tag[ $data[ 'level' ] ] ] = $new_tag;
                    $parents[ $data[ 'level' ] ] =& $last_tag_ar;
                    $last_tag_ar =& $last_tag_ar[ $last_counter_in_tag[ $data[ 'level' ] ]++ ];
                    break;
                case 'complete':
                    $new_tag = array(
                         'name' => $data[ 'tag' ] 
                    );
                    if ( isset( $data[ 'attributes' ] ) ) {
                        $new_tag[ 'attributes' ] = $data[ 'attributes' ];
                    }
                    if ( isset( $data[ 'value' ] ) && trim( $data[ 'value' ] ) ) {
                        $new_tag[ 'value' ] = trim( $data[ 'value' ] );
                    }
                    $last_count                                                = count( $last_tag_ar ) - 1;
                    $last_tag_ar[ $last_counter_in_tag[ $data[ 'level' ] ]++ ] = $new_tag;
                    break;
                case 'close':
                    $last_tag_ar =& $parents[ $data[ 'level' ] ];
                    break;
                default:
                    break;
            }
        }
        return $xml_array;
    }
?>