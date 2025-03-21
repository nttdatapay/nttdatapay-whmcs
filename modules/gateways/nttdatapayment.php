<?php
/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * This sample file demonstrates how a payment gateway module for WHMCS should
 * be structured and all supported functionality it can contain.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "gatewaymodule" and therefore all functions
 * begin "nttdatapayment_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see http://docs.whmcs.com/Gateway_Module_Developer_Docs
 *
 * @copyright Copyright (c) WHMCS Limited 2015
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see http://docs.whmcs.com/Gateway_Module_Meta_Data_Parameters
 *
 * @return array
 */
function nttdatapayment_MetaData()
{
    return array(
        'DisplayName' => 'NTT DATA Payment Services',
        'APIVersion' => '1.0', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function nttdatapayment_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'NTT DATA Payment Services',
        ),

        // a text field type allows for single line text input
        'login' => array(
            'FriendlyName' => 'Merchant Id',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your merchant id provided by NDPS',
        ),
        // a password field type allows for masked text input
        'password' => array(
            'FriendlyName' => 'Password',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your password provided by NDPS',
        ),
        'reqEnckey' => array(
            'FriendlyName' => 'Request Encryption Key',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter your request enc key provided by NDPS',
        ), 
        'reqSaltkey' => array(
            'FriendlyName' => 'Request Salt Key',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter your request salt key provided by NDPS',
        ),
        'resEnckey' => array(
            'FriendlyName' => 'Response Encryption Key',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter your response enc key provided by NDPS',
        ), 
        'resSaltkey' => array(
            'FriendlyName' => 'Response Salt Key',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter your response salt key provided by NDPS',
        ),
        'setAuthURL' => array(
            'FriendlyName' => 'Auth URL',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter Auth URL provided by NDPS',
        ),
        'setCDNLink' => array(
            'FriendlyName' => 'CDN Link',
            'Type' => 'text',
            'Size' => '100',
            'Default' => '',
            'Description' => 'Enter CDN link provided by NDPS',
        ),
        'productid' => array(
            'FriendlyName' => 'Product Id',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your product id provided by NDPS',
        ),
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see http://docs.whmcs.com/Payment_Gateway_Module_Parameters
 *
 * @return string
 */


function nttdatapayment_link($params)
{

    // Gateway Configuration Parameters
    $ndps_mid = $params['login'];
    $ndps_password = $params['password'];
    $ndps_reqenckey =$params['reqEnckey'];
    $ndps_reqsaltkey =$params['reqSaltkey'];
    $ndps_resEnckey =$params['resEnckey'];
    $ndps_resSaltkey =$params['resSaltkey'];
    $ndps_setAuthURL =$params['setAuthURL'];
    $ndps_setCDNLink =$params['setCDNLink'];
    $ndps_productid = $params['productid'];

    // Invoice Parameters
    $merchTxnId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

     // System Parameters
     $companyName = $params['companyname'];
     $systemUrl = $params['systemurl'];
     $returnbackUrl = $params['returnurl'];
     $langPayNow = $params['langpaynow'];
     $moduleDisplayName = $params['name'];
     $moduleName = $params['paymentmethod'];
     $whmcsVersion = $params['whmcsVersion'];

     $firstname = $params['clientdetails']['firstname'];
     $lastname = $params['clientdetails']['lastname'];
     $email = $params['clientdetails']['email'];
     $address1 = $params['clientdetails']['address1'];
     $address2 = $params['clientdetails']['address2'];
     $city = $params['clientdetails']['city'];
     $state = $params['clientdetails']['state'];
     $postcode = $params['clientdetails']['postcode'];
     $country = $params['clientdetails']['country'];
     $phone = $params['clientdetails']['phonenumber'];

     $returnUrl =  $params['systemurl'] . 'modules/gateways/callback/nttdatapayment.php';
    //  echo "<br>returnUrl: ". $returnUrl;


     if($phone == "" || $phone == null){
        $phone = "9999999999";
     }
     if($email == "" || $email == null){
        $email = "abc@xyz.com";
     }

    $curl = curl_init();

    $jsondata = '{
	  "payInstrument": {
            "headDetails": {
              "version": "OTSv1.1",      
              "api": "AUTH",  
              "platform": "FLASH"	
            },
            "merchDetails": {
              "merchId": "'. $ndps_mid .'",
              "userId": "",
              "password": "'. $ndps_password .'",
              "merchTxnId": "'. $merchTxnId .'",      
              "merchTxnDate": "2024-09-04 20:46:00"
            },
            "payDetails": {
              "amount":  "'. $amount .'",
              "product": "'. $ndps_productid .'",
              "custAccNo": "213232323",
              "txnCurrency": "'. $currencyCode .'"
            },
            "custDetails": {
              "custEmail": "'. $firstname  .' '. $lastname.'",
              "custMobile": "'. $phone .'"
            },
            "extras": {
              "udf1":"",
              "udf2":"",
              "udf3":"",
              "udf4":"",
              "udf5":""
            }
	     }  
	   }';


    // encryption logic
    $method = "AES-256-CBC";
    $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    $chars = array_map("chr", $iv);
    $IVbytes = join($chars);

    $salt1 = mb_convert_encoding($ndps_reqenckey, "UTF-8"); //Encoding to UTF-8
    $key1 = mb_convert_encoding($ndps_reqsaltkey, "UTF-8"); //Encoding to UTF-8

    //SecretKeyFactory Instance of PBKDF2WithHmacSHA1 Java Equivalent
    $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 

    $encrypted = openssl_encrypt($jsondata, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);

     $encData = bin2hex($encrypted);
    //getcwd() . '/modules/gateways/cacert.pem',
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$ndps_setAuthURL",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_CAINFO => __DIR__ . "/cacert.pem",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "encData=".$encData."&merchId=".$ndps_mid,
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/x-www-form-urlencoded"
        ),
      ));

    $atomTokenId = null;
    $response = curl_exec($curl); 
    // echo "<br><br> response:". $response;  

    $getresp = explode("&", $response); 

    $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);       
    // echo "<br><br> encresp:".$encresp;
    $dataEncypted = hex2bin($encresp);
    $method = "AES-256-CBC";

    //Converting Array to bytes
    $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    $chars = array_map("chr", $iv);
    $IVbytes = join($chars);

    $salt1 = mb_convert_encoding($ndps_resSaltkey, "UTF-8");//Encoding to UTF-8
    $key1 = mb_convert_encoding($ndps_resEnckey, "UTF-8");//Encoding to UTF-8

    //SecretKeyFactory Instance of PBKDF2WithHmacSHA1 Java Equivalent
    $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 

    $decData = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);

    // echo "<br><br>dec:".$decData; 
    
    if(curl_errno($curl)) {
        $error_msg = curl_error($curl);
        echo "error = ".$error_msg;
    }      

    if(isset($error_msg)) {
        // TODO - Handle cURL error accordingly
        echo "error = ".$error_msg;
    }
      
    curl_close($curl);

    $res = json_decode($decData, true);  

    if($res){
        if($res['responseDetails']['txnStatusCode'] == 'OTS0000'){
          $atomTokenId = $res['atomTokenId'];
        }else{
          echo "Error getting AtomTokenId!";
           $atomTokenId = null;
        }
      }
    // echo "<br><br>atomTokenId:".$atomTokenId;

 
    return <<<EOT
    <button onclick='openPay()'>Pay Now</button>
    <script src="$ndps_setCDNLink"></script> 
    <!--    <script src="https://psa.atomtech.in/staticdata/ots/js/atomcheckout.js"></script>  for production-->
        <script>
        function openPay(){
            const options = {
            "atomTokenId": "$atomTokenId",
            "merchId": "$ndps_mid",
            "custEmail": "$email",
            "custMobile": "$phone",
            "returnUrl":" $returnUrl"
            
            }
            let atom = new AtomPaynetz(options,'uat');
        }

        </script>
    EOT;

    
}

/**
 * Refund transaction.
 *
 * Called when a refund is requested for a previously successful transaction.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see http://docs.whmcs.com/Payment_Gateway_Module_Parameters
 *
 * @return array Transaction response status
 */
// function nttdatapayment_refund($params)
// {

//     // Gateway Configuration Parameters
//     $accountId = $params['accountID'];
//     $secretKey = $params['secretKey'];
//     $testMode = $params['testMode'];
//     $dropdownField = $params['dropdownField'];
//     $radioField = $params['radioField'];
//     $textareaField = $params['textareaField'];

//     // Transaction Parameters
//     $transactionIdToRefund = $params['transid'];
//     $refundAmount = $params['amount'];
//     $currencyCode = $params['currency'];

//     // Client Parameters
//     $firstname = $params['clientdetails']['firstname'];
//     $lastname = $params['clientdetails']['lastname'];
//     $email = $params['clientdetails']['email'];
//     $address1 = $params['clientdetails']['address1'];
//     $address2 = $params['clientdetails']['address2'];
//     $city = $params['clientdetails']['city'];
//     $state = $params['clientdetails']['state'];
//     $postcode = $params['clientdetails']['postcode'];
//     $country = $params['clientdetails']['country'];
//     $phone = $params['clientdetails']['phonenumber'];

//     // System Parameters
//     $companyName = $params['companyname'];
//     $systemUrl = $params['systemurl'];
//     $langPayNow = $params['langpaynow'];
//     $moduleDisplayName = $params['name'];
//     $moduleName = $params['paymentmethod'];
//     $whmcsVersion = $params['whmcsVersion'];

//     // perform API call to initiate refund and interpret result

//     return array(
//         // 'success' if successful, otherwise 'declined', 'error' for failure
//         'status' => 'success',
//         // Data to be recorded in the gateway log - can be a string or array
//         'rawdata' => $responseData,
//         // Unique Transaction ID for the refund transaction
//         'transid' => $refundTransactionId,
//         // Optional fee amount for the fee value refunded
//         'fees' => $feeAmount,
//     );
// }

/**
 * Cancel subscription.
 *
 * If the payment gateway creates subscriptions and stores the subscription
 * ID in tblhosting.subscriptionid, this function is called upon cancellation
 * or request by an admin user.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see http://docs.whmcs.com/Payment_Gateway_Module_Parameters
 *
 * @return array Transaction response status
 */
// function nttdatapayment_cancelSubscription($params)
// {
//     // Gateway Configuration Parameters
//     $accountId = $params['accountID'];
//     $secretKey = $params['secretKey'];
//     $testMode = $params['testMode'];
//     $dropdownField = $params['dropdownField'];
//     $radioField = $params['radioField'];
//     $textareaField = $params['textareaField'];

//     // Subscription Parameters
//     $subscriptionIdToCancel = $params['subscriptionID'];

//     // System Parameters
//     $companyName = $params['companyname'];
//     $systemUrl = $params['systemurl'];
//     $langPayNow = $params['langpaynow'];
//     $moduleDisplayName = $params['name'];
//     $moduleName = $params['paymentmethod'];
//     $whmcsVersion = $params['whmcsVersion'];

//     // perform API call to cancel subscription and interpret result

//     return array(
//         // 'success' if successful, any other value for failure
//         'status' => 'success',
//         // Data to be recorded in the gateway log - can be a string or array
//         'rawdata' => $responseData,
//     );
// }