<?php 

/*
	FedEx Shipping Services.
	Shipping charges calculation vendor
	CakePHP 2.x,1.x
	@author Naresh Kumar
	@author email naresh.thakur1987@gmail.com
	@created on 12 Dec-2012
*/

define('METER_NUMBER', '118572428');
define('SHIP_ACCOUNT', '510087984');
define('BILL_ACCOUNT', '510087984');
define('FEDEX_KEY', 'zrPccv41XytqUaKH');
define('FREIGHT_ACCOUNT', '510087984');
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('FEDEX_PASSWORD', 'fwfDSH5PQ1GJ7tuNZ4CX2IGqg');
define('CHECK', false);
define('DUTY_ACCOUNT', 'XXX');
define('ACCOUNT_TO_VALIDATE', 'XXX');
define('SHIPPING_CHARGES_PAYMENT', 'SENDER');	
define('INTERNATIONAL_PAYMENT_TYPE', 'SENDER');
define('READY_DATE', '2012-12-01T08:44:07');
define('READY_TIME', '12:00:00-05:00');
define('CLOSE_DATE', date("Y-m-d"));
define('CLOSE_TIME', '20:00:00-05:00');
define('PICKUP_DATE', date("Y-m-d", mktime(8, 0, 0, date("m")  , date("d")+1, date("Y"))));
define('PICKUP_TIMESTAMP', mktime(8, 0, 0, date("m")  , date("d")+1, date("Y")));
define('PICKUP_LOCATION_ID', 'XXX');
define('PICKUP_CONFIRMATION_NUMBER', '1');
define('DISPATCH_DATE', date("Y-m-d", mktime(8, 0, 0, date("m")  , date("d")+1, date("Y"))));
define('DISPATCH_TIMESTAMP', mktime(8, 0, 0, date("m")  , date("d")+1, date("Y")));
define('DISPATCH_CONFIRMATION_NUMBER', '1');
define('SHIP_TIMESTAMP', mktime(10, 0, 0, date("m"), date("d")+1, date("Y")));
define('TAG_READY_TIMESTAMP', mktime(10, 0, 0, date("m"), date("d")+1, date("Y")));
define('TAG_LATEST_TIMESTAMP', mktime(20, 0, 0, date("m"), date("d")+1, date("Y")));
define('TRACKING_NUMBER', 'XXX');
define('TRACKING_ACCOUNT', 'XXX');
define('SHIP_DATE', '2012-12-01');
define('ACCOUNT', 'XXX');
define('PHONE_NUMBER', '918591698789');
define('EXPIRATION_DATE', '2012-12-01');
define('HUB_ID', '5531');
define('BEGIN_DATE', '2012-12-01');
define('END_DATE', '2012-12-03');
define('CHANGE_END_POINT', false);
define('END_POINT', '');
define('CURRENCY', 'CAD');
define('COUNTRY_CODE', 'CA');
define('TRANSACTIONS_LOG_FILE', 'fedextransactions.log');






