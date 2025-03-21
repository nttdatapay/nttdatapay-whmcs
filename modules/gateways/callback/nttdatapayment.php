<?php
/**
 * WHMCS Sample Payment Callback File
 *
 * This sample file demonstrates how a payment gateway callback should be
 * handled within WHMCS.
 *
 * It demonstrates verifying that the payment gateway module is active,
 * validating an Invoice ID, checking for the existence of a Transaction ID,
 * Logging the Transaction for debugging and Adding Payment to an Invoice.
 *
 * For more information, please refer to the online documentation.
 *
 * @see http://docs.whmcs.com/Gateway_Module_Developer_Docs
 *
 * @copyright Copyright (c) WHMCS Limited 2015
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

    $dataEncypted = hex2bin($_POST['encData']);

    $method = "AES-256-CBC";

    //Converting Array to bytes
    $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    $chars = array_map("chr", $iv);
    $IVbytes = join($chars);

    $salt1 = mb_convert_encoding($gatewayParams["resSaltkey"], "UTF-8");//Encoding to UTF-8
    $key1 = mb_convert_encoding($gatewayParams["resEnckey"], "UTF-8");//Encoding to UTF-8

    //SecretKeyFactory Instance of PBKDF2WithHmacSHA1 Java Equivalent
    $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 

    $decrypted = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);

    // echo "<br><br>";
    // echo $decrypted;

    $jsonData = json_decode($decrypted, true);
    // echo "<br><br>JsonData:";
    // print_r($jsonData);

    if($jsonData['payInstrument']['responseDetails']['statusCode']  == "OTS0000"){
        $success = true;
    }
    else{
        $success = false;
    }


    // echo "<br> success:". $success; exit;
    $invoiceId = $jsonData['payInstrument']['merchDetails']['merchTxnId'];
    $transactionId = $jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'];
    $paymentAmount = $jsonData['payInstrument']['payDetails']['amount'];
    $paymentFee="0";

$transactionStatus = $success ? 'Success' : 'Failure';


$invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);

//echo $invoiceId;exit;
checkCbTransID($transactionId);

logTransaction($gatewayParams['name'], $arrayofdata, $transactionStatus);

if ($success) {

    /**
     * Add Invoice Payment.
     *
     * Applies a payment transaction entry to the given invoice ID.
     *
     * @param int $invoiceId         Invoice ID
     * @param string $transactionId  Transaction ID
     * @param float $paymentAmount   Amount paid (defaults to full balance)
     * @param float $paymentFee      Payment fee (optional)
     * @param string $gatewayModule  Gateway module name
     */
    addInvoicePayment(
        $invoiceId,
        $transactionId,
        $paymentAmount,
        $paymentFee,
        $gatewayModuleName
    );

}

callback3DSecureRedirect($invoiceId, $success);
?>