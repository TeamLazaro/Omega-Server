<?php

namespace CRM;

ini_set( "display_errors", 0 );
ini_set( "error_reporting", E_ALL );

require_once __DIR__ . '/../../../vendor/autoload.php';

use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use CristianPontes\ZohoCRMClient\Exception as ZohoException;

/*
 *
 * Declare constants
 *
 */
$authToken = 'd26aa791c15cd144fff5857ad96aeb39';



function getLead ( $phoneNumber/*, $email*/ ) {

	global $authToken;
	$zohoClient = new ZohoCRMClient( 'Leads', $authToken, 'com', 0 );

	try {
		$records = $zohoClient->searchRecords()
					->where( 'Phone', $phoneNumber )
					// ->orWhere( 'Email', $email )
					->request();
		$records = array_values( $records );
	} catch ( Exception | ZohoException\NoDataException $e ) {
		$records = [ ];
	}

	if ( empty( $records ) ) {
		return null;
	}

	if ( count( $records ) > 1 ) {
		throw new Exception( 'More than one lead found with the provided phone number and email.' );
	}

	$existingLead = [
		'SMOWNERID' => $records[ 0 ]->data[ 'SMOWNERID' ],
		'LEADID' => $records[ 0 ]->data[ 'LEADID' ],
		'Full Name' => $records[ 0 ]->data[ 'Full Name' ],
		'Email' => $records[ 0 ]->data[ 'Email' ]
	];

	return $existingLead;

}

function getProspect ( $phoneNumber/*, $email*/ ) {

	global $authToken;
	$zohoClient = new ZohoCRMClient( 'Contacts', $authToken, 'com', 0 );

	try {
		$records = $zohoClient->searchRecords()
					->where( 'Phone', $phoneNumber )
					// ->orWhere( 'Email', $email )
					->request();
		$records = array_values( $records );
	} catch ( Exception | ZohoException\NoDataException $e ) {
		$records = [ ];
	}

	if ( empty( $records ) ) {
		return null;
	}

	if ( count( $records ) > 1 ) {
		throw new Exception( 'More than one prospect found with the provided phone number and email.' );
	}

	$existingProspect = [
		'SMOWNERID' => $records[ 0 ]->data[ 'SMOWNERID' ],
		'CONTACTID' => $records[ 0 ]->data[ 'CONTACTID' ],
		'Full Name' => $records[ 0 ]->data[ 'Full Name' ],
		'Email' => $records[ 0 ]->data[ 'Email' ]
	];

	return $existingProspect;

}
