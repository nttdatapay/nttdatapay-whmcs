# nttdatapay-whmcs

## Introduction

The purpose of the document is to define the integration steps of the NTT DATA Payment Services payment gateway with WHMCS.

## Prerequisites
1. WHMCS server up and running.
2. Admin access.
3. Server SSH or FTP access.
4. Requires PHP 7.3 or newer and OpenSSL PHP extension.
5. MID and Keys: The UAT MID and keys will be provided by the NTT DATA Payment Services. Production keys will be distributed following the completion of the UAT signoff.

Begin processing payments within minutes by utilizing the fully digital onboarding process and the feature-rich NTT DATA Payment Gateway, integrated seamlessly with the WHMCS plugin.

## Kit Content
1. module
    - gateways
        - nttdatapayment.php
        - Callback
            - nttdatapayment.php
        - nttdatapayment
            - whmcs.json
        - cacert.pem

## Installation

1. Download the plugin from nttdatapay.com or the GitHub repository.
2. Unzip the plugin and copy and replace the folder with the 'module/gateways/' directory.
3. Upload all the contents of the module file to the following location:
   `${whmcs installation directory}/modules/`

## Enable NTTDATA Payment Services Payment Gateway
1. Login to the WHMCS admin account.
2. Go to System Settings -> General Settings -> Payments -> Payment Gateways.
3. Click on “Visit App Integration”.
4. Search for nttdatapayment and select it.
5. Click on the manage button to enter the details.
6. Enter the following details:
    - Display Name: Any display name for the payment option.
    - Merchant ID: Provided by NTT DATA Payment Services.
    - Password: Provided by NTT DATA Payment Services.
    - Request Encryption Key: Provided by NTT DATA Payment Services.
    - Request Salt Key: Provided by NTT DATA Payment Services.
    - Response Encryption Key: Provided by NTT DATA Payment Services.
    - Response Salt Key: Provided by NTT DATA Payment Services.
    - Auth URL: Provided by NTT DATA Payment Services.
    - CDN Link: Provided by NTT DATA Payment Services.
    - Product ID: Provided by NTT DATA Payment Services.
7. Click on Save Changes.