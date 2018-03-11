<?php

class BW_Helpers {
	function join_with_and( $array ) {
		$oxf_comma = ( count( $array ) > 2 ? ',' : '' );
		return join( $oxf_comma . ' and ', array_filter( array_merge( array( join( ', ', array_slice( $array, 0, -1 ) ) ), array_slice( $array, -1 ) ), 'strlen' ) );
	}
}


$helpers = new BW_Helpers();
