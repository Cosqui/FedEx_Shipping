@document title FedEx Shipping Vendor read me
@author Naresh Kumar
@author email naresh.thakur1987@gmail.com

1. DIRECTORY STURCTUR:
	
	- FEDEX
		- wsdl
			- RateService_v13
				- RateService_v13.wsdl
				- ...

		- fedex_config.php
		
		- FedExShipping.php
		
		- fedextransactions.log

2. HOW TO USE: 
	
	a. Download the FEDEX.zip folder.

	b. Extact FEDEX.zip in app/Vendor/ directory.

	c. Rename it as FEDEX.

	d. Change the values in fedex_config.php. For example
		METER_NUMBER
		BILL_ACCOUNT
		SHIP_ACCOUNT
		FEDEX_KEY
		FEDEX_PASSWORD etc..

	e. Import the Vendor in controller file:-
		i. App::import('Vendor', 'FEDEX/FedExShipping.php');
		ii. Or if it does not work then use require_once($_SERVER['DOCUMENT_ROOT'].'app/Vendor/FEDEX/FedExShipping.php');

	f. Create object of FedExShipping class. $fedex = new FedExShipping();

	g. Call the method rateGetShippingPrice(array()). $fedex->rateGetShippingPrice($details). $detail will contain the following array information:
	
	$details = array(

					'shipper' => array(
							'Contact' => array(
									'PersonName'  => 'Sender Name',
									'CompanyName' => 'Company Name'
								),
							'Address' => array(
									'StreetLines' 			=> array('139 Basaltic Road'),
									'City'       			=> 'Concord',
									'StateOrProvinceCode' 	=> 'ON',
									'PostalCode' 			=> 'L4K 1G4',
									'CountryCode' 			=> 'CA'
								)
						),

					'recipient' => array(
							'Contact' => array(
									'PersonName'  => 'Sender Name',
									'CompanyName' => 'Company Name'
								),
							'Address' => array(
									'StreetLines' 			=> array('139 Basaltic Road'),
									'City'       			=> 'Concord',
									'StateOrProvinceCode' 	=> 'ON',
									'PostalCode' 			=> 'L4K 1G4',
									'CountryCode' 			=> 'CA',
									'Residential' 			=> false
								)
						),

					'sequence_number' => 1,
					'group_package_count' => 1,
					'weight' => array(
										'value' => 3.6,
										'units' => 'LB'
						),

					'dimensions' => array(
										'lenght' => 12,
										'width'	 => 9,
										'height' => 9,
										'units'  => 'IN'
						),


			);

	h. The method rateGetShippingPrice(array()), will return an array.



3. VALID VALUES :

	a. dropoffType : BUSINESS_SERVICE_CENTER, DROP_BOX, REGULAR_PICKUP,

	b. ServiceType : EUROPE_FIRST_INTERNATIONAL_PRIORITY, FEDEX_1_DAY_FREIGHT, FEDEX_2_DAY, FEDEX_2_DAY_AM, FEDEX_2_DAY_FREIGHT, FEDEX_3_DAY_FREIGHT, FEDEX_EXPRESS_SAVER, FEDEX_FIRST_FREIGHT, FEDEX_FREIGHT_ECONOMY, FEDEX_FREIGHT_PRIORITY, FEDEX_GROUND, FIRST_OVERNIGHT, GROUND_HOME_DELIVERY, INTERNATIONAL_ECONOMY, INTERNATIONAL_ECONOMY_FREIGHT, INTERNATIONAL_FIRST, INTERNATIONAL_PRIORITY, INTERNATIONAL_PRIORITY_FREIGHT, PRIORITY_OVERNIGHT, SMART_POST, STANDARD_OVERNIGHT

	c. PackagingType : FEDEX_10KG_BOX, FEDEX_25KG_BOX, FEDEX_BOX, FEDEX_ENVELOPE, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING

	d. RateRequestType : RECIPIENT, SENDER and THIRD_PARTY

	e. PaymentType : RECIPIENT, SENDER and THIRD_PARTY