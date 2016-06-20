<?php
    include( 'geocoder.php' );
    echo "<pre>" . print_r( getLocation( "1 Main St Houston Texas 77002" ), true ) . "</pre>";