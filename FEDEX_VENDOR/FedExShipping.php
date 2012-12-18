<?php
/*
	FedEx Shipping Services.
	Shipping charges calculation vendor
	CakePHP 2.x,1.x
	@author Naresh Kumar
	@author email naresh.thakur1987@gmail.com
	@created on 12 Dec-2012
*/
require_once('fedex_config.php');

class FedExShipping{

	var $wsdl;
		
	public function __construct(){
		$this->wsdl = "/var/www/projects/2012/MPITurbo/app/Vendor/FEDEX_VENDOR/wsdl/RateService_v13/RateService_v13.wsdl";
	}

	function initialize_soap(){
		ini_set("soap.wsdl_cache_enabled", "0");
		return new SoapClient($this->wsdl, array('trace' => 1));
	}

	function rateGetShippingPrice($details){

		$client = $this->initialize_soap();
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => FEDEX_KEY, 
				'Password' => FEDEX_PASSWORD
			)
		);
		$request['ClientDetail'] = array(
			'AccountNumber' => SHIP_ACCOUNT, 
			'MeterNumber' => METER_NUMBER
		);
		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v13 using CakePHP Vendor ***');
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '13', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; 
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		$request['RequestedShipment']['ServiceType'] = 'FEDEX_GROUND';
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING';
		$request['RequestedShipment']['TotalInsuredValue']=array(
																	'Ammount'=>100,
																	'Currency'=>CURRENCY
																);
		$request['RequestedShipment']['Shipper'] = $details['shipper'];
		$request['RequestedShipment']['Recipient'] = $details['recipient'];
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
																			'PaymentType' => SHIPPING_CHARGES_PAYMENT,
																			'Payor' => array(
																				'ResponsibleParty' => array(
																					'AccountNumber' => BILL_ACCOUNT,
																					'CountryCode' => COUNTRY_CODE )
																			)
																		);
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RateRequestTypes'] = 'ACCOUNT';
		$request['RequestedShipment']['PackageCount'] = $details['package_count'];
		$request['RequestedShipment']['RequestedPackageLineItems'] = array(
																			'SequenceNumber'=>$details['sequence_number'],
																			'GroupPackageCount'=>$details['group_package_count'],
																			'Weight' => array(
																				'Value' => !empty($details['weight']['value'])?$details['weight']['value']:8,
																				'Units' => $details['weight']['units']
																			),
																			'Dimensions' => array(
																				'Length' => !empty($details['dimensions']['length'])?$details['dimensions']['length']:12,
																				'Width' => !empty($details['dimensions']['width'])?$details['dimensions']['width']:9,
																				'Height' => !($details['dimensions']['height'])?$details['dimensions']['height']:9,
																				'Units' => $details['dimensions']['units']
																			)
																		);
		
		try{

			if(CHANGE_END_POINT){
				$newLocation = $client->__setLocation(END_POINT);
			}
			
			$response = $client ->getRates($request);
			    
		    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
				
				$rateReply = $response->RateReplyDetails;
		    	$request['RequestedShipment']['ServiceType'] = $rateReply->ServiceType;
		        $request['RequestedShipment']['Amount'] = number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
		        if(array_key_exists('DeliveryTimestamp',$rateReply)){
		        	 $request['RequestedShipment']['DeliveryDate'] = $rateReply->DeliveryTimestamp;
		        }else if(array_key_exists('TransitTime',$rateReply)){
		        	 $request['RequestedShipment']['DeliveryDate'] = $rateReply->TransitTime;
		        }

		        $this->writeToLog($client); 
		        return $request;
		    
		    }else{
				$error = array('error' => 'error', 'response' => $response);
				$this->writeToLog($client);
				return $error;
		    } 
		    
		        

		}catch(SoapFault $exception){
			return 'error';
		}

	}

	function smartPostRateGetShippingCharges($details){
		
		$client = $this->initialize_soap();
		
		$request['WebAuthenticationDetail'] = array(
														'UserCredential' => array(
															'Key' => FEDEX_KEY, 
															'Password' => FEDEX_PASSWORD
															)
														); 
		$request['ClientDetail'] = array(
											'AccountNumber' => SHIP_ACCOUNT, 
											'MeterNumber' => METER_NUMBER
											);

		$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** SmartPost Rate Request v13 using CakePHP ***');
		$request['Version'] = array(
										'ServiceId' => 'crs', 
										'Major' => '9999', 
										'Intermediate' => '0', 
										'Minor' => '0'
									);
		
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP';
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		$request['RequestedShipment']['ServiceType'] = 'FEDEX_GROUND';
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING';
		$request['RequestedShipment']['Shipper'] = $details['shipper'];
		$request['RequestedShipment']['Recipient'] = $details['recipient'];
		$request['RequestedShipment']['PricingCodeType'] = 'LTL_FREIGHT';
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
																	'PaymentType' => 'SENDER',
			                                                        'Payor' => array(
																		'ResponsibleParty' => array(
																			'AccountNumber' => BILL_ACCOUNT,
																			'CountryCode' => COUNTRY_CODE)
																		)
																);															 
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RequestedShipment']['SmartPostDetail'] = array( 'Indicia' => 'PARCEL_SELECT',
		                                                          'AncillaryEndorsement' => 'CARRIER_LEAVE_IF_NO_RESPONSE',
		                                                          'SpecialServices' => 'USPS_DELIVERY_CONFIRMATION',
		                                                          'HubId' => HUB_ID,
		                                                          'CustomerManifestId' => 'XXX'
		                                                          );
		$request['RequestedShipment']['PackageCount'] = $details['package_count'];
		$request['RequestedShipment']['RequestedPackageLineItems'] = array(
																			'SequenceNumber'=>$details['sequence_number'],
																			'GroupPackageCount'=>$details['group_package_count'],
																			'Weight' => array(
																				'Value' => $details['weight']['value'],
																				'Units' => $details['weight']['units']
																			),
																			'Dimensions' => array(
																				'Length' => $details['dimensions']['length'],
																				'Width' => $details['dimensions']['width'],
																				'Height' => $details['dimensions']['height'],
																				'Units' => $details['dimensions']['units']
																			)
																		);
		try{

			if(CHANGE_END_POINT){
				$newLocation = $client->__setLocation(END_POINT);
			}
			$response = $client ->getRates($request);
			if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
			
				$rateReply = $response->RateReplyDetails;
		    	$request['RequestedShipment']['ServiceType'] = $rateReply->ServiceType;
		        $request['RequestedShipment']['Amount'] = number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",");
		        if(array_key_exists('DeliveryTimestamp',$rateReply)){
		        	 $request['RequestedShipment']['DeliveryDate'] = $rateReply->DeliveryTimestamp;
		        }else if(array_key_exists('TransitTime',$rateReply)){
		        	 $request['RequestedShipment']['DeliveryDate'] = $rateReply->TransitTime;
		        }
		        $this->writeToLog($client); 
		        return $request;
		    
		    }else{
				$error = array('error' => 'error', 'response' => $response);
				$this->writeToLog($client);
				return $error;
		    } 
		    
		        

		}catch(SoapFault $exception){
			return 'error';
		}
	}

	function writeToLog($client){

		if (!$logfile = fopen(TRANSACTIONS_LOG_FILE, "a")){
		   error_func("Cannot open " . TRANSACTIONS_LOG_FILE . " file.\n", 0);
		   exit(1);
		}
		fwrite($logfile, sprintf("\r%s:- %s",date("D M j G:i:s T Y"), $client->__getLastRequest(). "\n\n" . $client->__getLastResponse()));

	}

	function printRequestResponse($client){
		$result = array(
				'request' => $client->__getLastRequest(),
				'response' => $client->__getLastResponse()
			);
		return $result;
	}
}

?>