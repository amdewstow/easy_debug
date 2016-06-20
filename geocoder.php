<?php
    function getLocation( $address ) {
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";
        $url .= urlencode( $address );
        $resp_json = file_get_contents_cached_json( $url );
        $resp      = json_decode( $resp_json, true );
        if ( $resp[ 'status' ] == 'OK' ) {
            $ar            = $resp[ 'results' ][ 0 ][ 'address_components' ];
            $city          = null;
            $county        = null;
            $country       = null;
            $state         = null;
            $street_number = null;
            $street_name   = null;
            $zip           = null;
            $zip4          = null;
            foreach ( $ar as $kk => $vv ) {
                foreach ( $vv as $kki => $vvi ) {
                    if ( $kki == "short_name" ) {
                        $lastn = $vvi;
                    }
                    if ( $kki == "long_name" ) {
                        $lastnl = $vvi;
                    }
                    if ( $kki == "types" ) {
                        foreach ( $vvi as $kkii => $vvii ) {
                            if ( $vvii == 'locality' ) {
                                $city = $lastn;
                            }
                            if ( $vvii == 'administrative_area_level_2' ) {
                                $county = $lastn;
                            }
                            if ( $vvii == 'administrative_area_level_1' ) {
                                $state = $lastnl;
                            }
                            if ( $vvii == 'street_number' ) {
                                $street_number = $lastn;
                            }
                            if ( $vvii == 'route' ) {
                                $street_name = $lastn;
                            }
                            if ( $vvii == 'postal_code' ) {
                                $zip = $lastn;
                            }
                            if ( $vvii == 'postal_code_suffix' ) {
                                $zip4 = $lastn;
                            }
                            if ( $vvii == 'country' ) {
                                $country = $lastnl;
                            }
                        }
                    }
                }
            }
            //                $resp[ 'results' ][ 0 ][ 'geometry' ][ 'location' ][ 'city' ] = $city;
            //$resp['results'][0]['geometry']['location']['ar'] = $ar;
            //$resp['results'][0]['geometry']['location']['a'] = $resp;
            $rr              = $resp[ 'results' ][ 0 ][ 'geometry' ][ 'location' ];
            /*
            $rr[ 'street_number' ] = $street_number;
            $rr[ 'street_name' ]   = $street_name;
            */
            $rr[ 'Address' ] = $street_number . ' ' . $street_name;
            $rr[ 'City' ]    = $city;
            $rr[ 'State' ]   = $state;
            $rr[ 'Zip' ]     = $zip;
            if ( $zip4 != null ) {
                $rr[ 'Zip' ] .= '-' . $zip4;
            }
            $rr[ 'County' ]   = $county;
            $rr[ 'Country' ]  = $country;
            $rr[ 'RAW_URL' ]  = $resp[ 'RAW_URL' ];
            $rr[ 'C_F' ]      = $resp[ 'C_F' ];
            $rr[ 'Formated' ] = $street_number . ' ' . $street_name . "\n<br>" . $city . ', ' . $state . ' ' . $rr[ 'Zip' ] . "\n<br>" . $county;
            return $rr;
        } else {
            return false;
        }
    }
    function file_get_contents_cached_json( $url ) {
        $cf = __DIR__ . "/cache/" . sha1( $url ) . ".json";
        if ( is_file( $cf ) && ( ( ( time() - filemtime( $cf ) ) / 86400 ) < 7 ) ) {
            return file_get_contents( $cf );
        } else {
            $raw                = file_get_contents( $url );
            $resp               = json_decode( $raw, true );
            $resp[ 'got_this' ] = date( 'r' );
            $resp[ 'RAW_URL' ]  = $url;
            $resp[ 'C_F' ]      = $cf;
            $rawz               = json_encode( $resp );
            file_put_contents( $cf, $rawz );
            return $rawz;
        }
    }