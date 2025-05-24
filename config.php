<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'judonorg_judo');
define('DB_USER', 'judonorg_reg');
define('DB_PASS', '1024judo');

// Database Configuration
$dbhost = "localhost";
$dbname = "judonorg_judo";
$dbuser = "judonorg_reg";
$dbpass = "1024judo";

// PayPal Configuration
$PAYPAL_ENVIRONMENT = "production"; // Change to "sandbox" for testing
$PAYPAL_CONFIG = [
    'production' => [
        'webhook_id' => '35V21119MK746351H',
        'client_id' => 'AcLO1rltASKEV9sZLdOgfjD80UTfuIJ-M0XclDTx8g8U928ZWfYaAQ9_asQ2JQnn5jaLVtj_TUAAMa9T',
        'client_secret' => 'AcLO1rltASKEV9sZLdOgfjD80UTfuIJ-M0XclDTx8g8U928ZWfYaAQ9_asQ2JQnn5jaLVtj_TUAAMa9T',
        'base_url' => 'https://api-m.paypal.com',
        'sdk_url' => 'https://www.paypal.com/sdk/js?client-id=AcLO1rltASKEV9sZLdOgfjD80UTfuIJ-M0XclDTx8g8U928ZWfYaAQ9_asQ2JQnn5jaLVtj_TUAAMa9T&currency=HKD',
        'paypal_login' => 'hkjudo@outlook.com',
        'paypal_old_url' => 'https://www.paypal.com/cgi-bin/webscr'
    ],
    'sandbox' => [
        'webhook_id' => '0K109843UX531921N',
        'client_id' => 'AZOC0Tnzo3yem_jX242e2FzbSJjUN0ySzEJSQrM059YR7N__hejuliQoUIKRNIx2nx_DbS317zjwgkgd', 
        'client_secret' => 'EFxy4yYU35LSUsxmMZRif0CIFOiF62Wv0_5zqH5tcgqls9aqD1w8KHG6wk8lts7DSFiXHQJXyUfoewpq',
        'base_url' => 'https://api-m.sandbox.paypal.com',
        'sdk_url' => 'https://www.paypal.com/sdk/js?client-id=AZOC0Tnzo3yem_jX242e2FzbSJjUN0ySzEJSQrM059YR7N__hejuliQoUIKRNIx2nx_DbS317zjwgkgd&currency=HKD',
         'paypal_login' => 'hkjudo-facilitator@outlook.com',
          'paypal_old_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr'
    ]
];

// Get current PayPal config based on environment
$PAYPAL_WEBHOOK_ID = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['webhook_id'];
$PAYPAL_CLIENT_ID = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['client_id'];
$PAYPAL_CLIENT_SECRET = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['client_secret'];
$PAYPAL_BASE_URL = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['base_url'];
$PAYPAL_SDK_URL = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['sdk_url'];
$paypal_url = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['sdk_url'];
$ac = $PAYPAL_CONFIG[$PAYPAL_ENVIRONMENT]['paypal_login'];



// Debug mode (set to false for production)
$DEBUG = ($PAYPAL_ENVIRONMENT === 'sandbox');
//$DEBUG = true; // Set to true by hardcoding for debugging purposes
//define('DEBUG_MODE', true);

// Other existing configurations...
date_default_timezone_set('Asia/Hong_Kong');
?>
