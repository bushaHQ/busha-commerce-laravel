# Official Busha Commerce Laravel SDK

## Installation

You can install the package via composer:

```bash
composer require busha/commerce
```

Or add the following line to the `require` block of your composer.json file.

```composer
"busha/commerce": "*"
```

## Setup
The package will automatically register a service provider.

You need to publish the configuration file:

```php artisan vendor:publish --provider="Busha\Commerce\BushaServiceProvider"```

### Publish config
This is the default content of the config file ```busha.php```:
```php
<?php

return [
    "secret_key"     => env("BUSHA_SECRET_KEY"),
];
```

## Update environment (.env)
Update Your Projects `.env` with their credentials from your business daashboard under API KEYS:
```bash
BUSHA_SECRET_KEY={SECRET KEY FROM DASHBOARD}
```

## Test

```php
composer test
```

## Usage

### Charges

```php
<?php

use Busha\Commerce\BushaCommerce;

$commerce  =  new BushaCommerce();

/*
* Create a Charge
*/

$charge_created = $commerce->createCharge([
   'name' => 'Name of charge',
   'description' => 'Description',
   'local_currency' => 'NGN',
   'local_amount' => 100,
   'fixed_price' => true,
   "meta" => [
        "email" => "sarah.shaw@example.co", 
        "name" => "Sarah Shaw"
   ]
]);

// Show/Retrieve a Charge
$charge = $commerce->getCharge($chargeID);

// List Charges
$charges = $commerce->listCharges(['limit' => 10,'page' => 1, 'sort' => 'asc']);

// Resolve Charge
$charge = $commerce->resolveCharge($chargeID);

// Cancel Charge
$charge = $commerce->cancelCharge($chargeID);
```

### Payment Link

```php
/*
* Create a Payment Link (Donation)
*/

$paymentLinkCreated = $commerce->createPaymentLink([
    "name" => "New Payment Link",
    "description" => "Mastering the Transition to the Information Age",
    "payment_link_type" => "donation",
    "requested_info" => ['name', 'email']
]);

/*
* Create a Payment Link (Fixed Price)
*/

$paymentLinkCreated = $commerce->createPaymentLink([
    "name" => "The Sovereign Individual",
    "description" => "Mastering the Transition to the Information Age",
    "payment_link_type" => "fixed_price",
    "local_amount" => 5000,
    "local_currency" => "NGN",
    "requested_info" => ['name','email','phone']
]);

// Get Payment Link
$paymentLink = $commerce->getPaymentLink($paymentLinkID);

// List Payment Links
$paymentLinks = $commerce->listPaymentLinks(['limit' => 10,'page' => 1, 'sort' => 'asc']);

// Delete Payment Link
$deletedPaymentLink = $commerce->deletePaymentLink($paymentLinkID);

// Update Payment Link
$paymentLink = $commerce->updatePaymentLink($paymentLinkID, [
    "name" => "The Sovereign Individual",
    "description" => "Mastering the Transition to the Information Age",
    "payment_link_type" => "fixed_price",
    "local_amount" => 5000,
    "local_currency" => "NGN",
    "requested_info" => ['name','email','phone']
]);

// Toggle Payment Link
$paymentLink = $commerce->togglePaymentLink($paymentLinkID);

//Create Charge for Payment Link 
$paymentLinkCharge = $commerce->createPaymentLinkCharge($paymentLinkID, [
    "local_amount" => 50000,
    "local_currency" => "NGN"
    "meta" => [
        "email" => "sarah.shaw@example.co", 
        "name" => "Sarah Shaw"
    ]
]);
```

### Invoice

```php
/*
* Create an Invoice
*/

$invoice_created = $commerce->createInvoice([
    "name" => "The Sovereign Individual",
    "customer_email" => "sarah.shaw@example.co",
    "local_amount" => 100.00,
    "local_currency" => "NGN",
    "customer_name" =>  "Sarah Shaw",
    "description" => "Mastering the Transition to the Information Age"
]);

// List Invoices
$invoices = $commerce->listInvoices(['limit' => 10,'page' => 1, 'sort' => 'asc']);

// Get Invoice
$invoice = $commerce->getInvoice($invoiceID);

// Void Invoice
$invoice = $commerce->voidInvoice(invoiceID)

//Create Charge for Invoice 
$invoiceCharge = $commerce->createInvoiceCharge($invoiceID, [
    "meta" => [
        "email" => "sarah.shaw@example.co", 
        "name" => "Sarah Shaw"
    ]
]);
```

### Events

```php

// List Events
$events = $commerce->listEvents(['limit' => 10,'page' => 1, 'sort' => 'asc']);

// Get Event
$event = $commerce->getEvent($eventID);
```

## Extra

Refer to the [documentation](https://docs.commerce.busha.co) for more information as regards setting up and integrating the Commerce API.

## TODO
- [ ] Update Documentation
