## Requirements ##
To use the Mollie API client, the following things are required:

+ Get yourself a free [Mollie account]. No sign up costs.
+ Now you're ready to use the Mollie API client in test mode.
+ Follow this to enable payment methods in live mode, and let us handle the rest.
+ PHP >= 5.6
+ Up-to-date OpenSSL (or other SSL/TLS toolkit)

## Composer Installation ##


    $ composer require mollie/mollie-api-php:^2.0

    {
        "require": {
            "mollie/mollie-api-php": "^2.0"
        }
    }

## How to receive payments ##

To successfully receive a payment, these steps should be implemented:

1. Use the Mollie API client to create a payment with the requested amount, currency, description and optionally, a payment method. It is important to specify a unique redirect URL where the customer is supposed to return to after the payment is completed.

2. Immediately after the payment is completed, our platform will send an asynchronous request to the configured webhook to allow the payment details to be retrieved, so you know when exactly to start processing the customer's order.

3. The customer returns, and should be satisfied to see that the order was paid and is now being processed.


## Getting started ##

Initializing the Mollie API client, and setting your API key.

```php
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM");
``` 

Creating a new payment.

```php
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => "10.00"
    ],
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
]);
```
_After creation, the payment id is available in the `$payment->id` property. You should store this id with your order._

After storing the payment id you can send the customer to the checkout using the `$payment->getCheckoutUrl()`.  

```php
header("Location: " . $payment->getCheckoutUrl(), true, 303);
```
_This header location should always be a GET, thus we enforce 303 http response code_

## Retrieving payments ##
We can use the `$payment->id` to retrieve a payment and check if the payment `isPaid`.

```php
$payment = $mollie->payments->get($payment->id);

if ($payment->isPaid())
{
    echo "Payment received.";
}
```

Or retrieve a collection of payments.

```php
$payments = $mollie->payments->page(); 
```


## Payment webhook ##

When the status of a payment changes the `webhookUrl` we specified in the creation of the payment will be called.  
There we can use the `id` from our POST parameters to check te status and act upon that.

## Multicurrency ##
Since 2.0 it is now possible to create non-EUR payments for your customers.

```php
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "USD",
        "value" => "10.00"
    ],
    "description" => "Order #12345",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
]);
```
_After creation, the `settlementAmount` will contain the EUR amount that will be settled on your account._


### Fully integrated iDEAL payments ###

If you want to fully integrate iDEAL payments in your web site, some additional steps are required. First, you need to
retrieve the list of issuers (banks) that support iDEAL and have your customer pick the issuer he/she wants to use for
the payment.

Retrieve the iDEAL method and include the issuers

```php
$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
```

_`$method->issuers` will be a list of objects. Use the property `$id` of this object in the
 API call, and the property `$name` for displaying the issuer to your customer. For a more in-depth example, see [Example - iDEAL payment]
 
 Create a payment with the selected issuer:

```php
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => "10.00"
    ],
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
    "method"      => \Mollie\Api\Types\PaymentMethod::IDEAL,
    "issuer"      => $selectedIssuerId, // e.g. "ideal_INGBNL2A"
]);
```

_The `_links` property of the `$payment` object will contain an object `checkout` with a `href` property, which is a URL that points directly to the online banking environment of the selected issuer.
A short way of retrieving this URL can be achieved by using the `$payment->getCheckoutUrl()`._

### Refunding payments ###

The API also supports refunding payments. Note that there is no confirmation and that all refunds are immediate and
definitive. refunds are supported for all methods except for paysafecard and gift cards.

```php
$payment = $mollie->payments->get($payment->id);

// Refund € 2 of this payment
$refund = $payment->refund([
    "amount" => [
        "currency" => "EUR",
        "value" => "2.00"
    ]
]);
```
## License ##
[BSD (Berkeley Software Distribution) License](https://opensource.org/licenses/bsd-license.php).
Copyright (c) 2013-2018, Mollie B.V.
