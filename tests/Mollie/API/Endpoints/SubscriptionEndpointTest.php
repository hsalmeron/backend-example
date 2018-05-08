<?php

namespace Tests\Mollie\Api\Endpoints;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mollie\Api\Resources\Customer;
use Mollie\Api\Resources\Subscription;
use Mollie\Api\Resources\SubscriptionCollection;
use Mollie\Api\Types\SubscriptionStatus;

class SubscriptionEndpointTest extends BaseEndpointTest
{
    public function testCreateWorks()
    {
        $this->mockApiCall(
            new Request('POST', '/v2/customers/cst_FhQJRw4s2n/subscriptions'),
            new Response(
                200,
                [],
                '{
                  "resource": "subscription",
                  "id": "sub_wByQa6efm6",
                  "mode": "test",
                  "createdAt": "2018-04-24T11:41:55+00:00",
                  "status": "active",
                  "amount": {
                    "value": "10.00",
                    "currency": "EUR"
                  },
                  "description": "Order 1234",
                  "method": null,
                  "times": null,
                  "interval": "1 month",
                  "startDate": "2018-04-24",
                  "webhookUrl": null,
                  "_links": {
                    "self": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6",
                      "type": "application/hal+json"
                    },
                    "customer": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n",
                      "type": "application/hal+json"
                    },
                    "documentation": {
                      "href": "https://www.mollie.com/en/docs/reference/subscriptions/create",
                      "type": "text/html"
                    }
                  }
                }'
            )
        );

        $customer = $this->getCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->createSubscription([
            "amount" => [
                    "value" => "10.00",
                    "currency" => "EUR"
                ],
            "interval" => "1 month",
            "description" => "Order 1234"
        ]);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals("subscription", $subscription->resource);
        $this->assertEquals("sub_wByQa6efm6", $subscription->id);
        $this->assertEquals("test", $subscription->mode);
        $this->assertEquals("2018-04-24T11:41:55+00:00", $subscription->createdAt);
        $this->assertEquals(SubscriptionStatus::STATUS_ACTIVE, $subscription->status);
        $this->assertEquals((object) ["value" => "10.00", "currency" => "EUR"], $subscription->amount);
        $this->assertEquals("Order 1234", $subscription->description);
        $this->assertNull($subscription->method);
        $this->assertNull($subscription->times);
        $this->assertEquals("1 month", $subscription->interval);
        $this->assertEquals("2018-04-24", $subscription->startDate);

        $selfLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6", "type" => "application/hal+json"];
        $this->assertEquals($selfLink, $subscription->_links->self);

        $customerLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n", "type" => "application/hal+json"];
        $this->assertEquals($customerLink, $subscription->_links->customer);

        $documentationLink = (object)["href" => "https://www.mollie.com/en/docs/reference/subscriptions/create", "type" => "text/html"];
        $this->assertEquals($documentationLink, $subscription->_links->documentation);

    }

    public function testGetWorks()
    {
        $this->mockApiCall(
            new Request('GET', '/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6'),
            new Response(
                200,
                [],
                '{
                  "resource": "subscription",
                  "id": "sub_wByQa6efm6",
                  "mode": "test",
                  "createdAt": "2018-04-24T11:41:55+00:00",
                  "status": "active",
                  "amount": {
                    "value": "10.00",
                    "currency": "EUR"
                  },
                  "description": "Order 1234",
                  "method": null,
                  "times": null,
                  "interval": "1 month",
                  "startDate": "2018-04-24",
                  "webhookUrl": null,
                  "_links": {
                    "self": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6",
                      "type": "application/hal+json"
                    },
                    "customer": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n",
                      "type": "application/hal+json"
                    },
                    "documentation": {
                      "href": "https://www.mollie.com/en/docs/reference/subscriptions/get",
                      "type": "text/html"
                    }
                  }
                }'
            )
        );

        $customer = $this->getCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->getSubscription("sub_wByQa6efm6");

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals("subscription", $subscription->resource);
        $this->assertEquals("sub_wByQa6efm6", $subscription->id);
        $this->assertEquals("test", $subscription->mode);
        $this->assertEquals("2018-04-24T11:41:55+00:00", $subscription->createdAt);
        $this->assertEquals(SubscriptionStatus::STATUS_ACTIVE, $subscription->status);
        $this->assertEquals((object) ["value" => "10.00", "currency" => "EUR"], $subscription->amount);
        $this->assertEquals("Order 1234", $subscription->description);
        $this->assertNull($subscription->method);
        $this->assertNull($subscription->times);
        $this->assertEquals("1 month", $subscription->interval);
        $this->assertEquals("2018-04-24", $subscription->startDate);

        $selfLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6", "type" => "application/hal+json"];
        $this->assertEquals($selfLink, $subscription->_links->self);

        $customerLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n", "type" => "application/hal+json"];
        $this->assertEquals($customerLink, $subscription->_links->customer);

        $documentationLink = (object)["href" => "https://www.mollie.com/en/docs/reference/subscriptions/get", "type" => "text/html"];
        $this->assertEquals($documentationLink, $subscription->_links->documentation);

    }

    public function testListWorks()
    {
        $this->mockApiCall(
            new Request('GET', '/v2/customers/cst_FhQJRw4s2n/subscriptions'),
            new Response(
                200,
                [],
                '{
                  "_embedded": {
                    "subscriptions": [
                      {
                        "resource": "subscription",
                        "id": "sub_wByQa6efm6",
                        "mode": "test",
                        "createdAt": "2018-04-24T11:41:55+00:00",
                        "status": "active",
                        "amount": {
                          "value": "10.00",
                          "currency": "EUR"
                        },
                        "description": "Order 1234",
                        "method": null,
                        "times": null,
                        "interval": "1 month",
                        "startDate": "2018-04-24",
                        "webhookUrl": null,
                        "_links": {
                          "self": {
                            "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6",
                            "type": "application/hal+json"
                          },
                          "customer": {
                            "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n",
                            "type": "application/hal+json"
                          }
                        }
                      }
                    ]
                  },
                  "count": 1,
                  "_links": {
                    "documentation": {
                      "href": "https://www.mollie.com/en/docs/reference/subscriptions/list",
                      "type": "text/html"
                    },
                    "self": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions?limit=50",
                      "type": "application/hal+json"
                    },
                    "previous": null,
                    "next": null
                  }
                }'
            )
        );

        $customer = $this->getCustomer();

        $subscriptions = $customer->subscriptions();

        $this->assertInstanceOf(SubscriptionCollection::class, $subscriptions);

        $this->assertEquals(count($subscriptions), $subscriptions->count);

        $documentationLink = (object)["href" => "https://www.mollie.com/en/docs/reference/subscriptions/list", "type" => "text/html"];
        $this->assertEquals($documentationLink, $subscriptions->_links->documentation);

        $selfLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions?limit=50", "type" => "application/hal+json"];
        $this->assertEquals($selfLink, $subscriptions->_links->self);

        foreach ($subscriptions as $subscription) {
            $this->assertInstanceOf(Subscription::class, $subscription);
            $this->assertEquals("subscription", $subscription->resource);
            $this->assertNotEmpty($subscription->createdAt);
        }

    }

    public function testCancelWorks()
    {
        $this->mockApiCall(
            new Request('DELETE', '/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6'),
            new Response(
                200,
                [],
                '{
                  "resource": "subscription",
                  "id": "sub_wByQa6efm6",
                  "mode": "test",
                  "createdAt": "2018-04-24T11:41:55+00:00",
                  "status": "canceled",
                  "amount": {
                    "value": "10.00",
                    "currency": "EUR"
                  },
                  "description": "Order 1234",
                  "method": null,
                  "times": null,
                  "interval": "1 month",
                  "startDate": "2018-04-24",
                  "webhookUrl": null,
                  "canceledAt": "2018-04-24T12:31:32+00:00",
                  "_links": {
                    "self": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6",
                      "type": "application/hal+json"
                    },
                    "customer": {
                      "href": "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n",
                      "type": "application/hal+json"
                    },
                    "documentation": {
                      "href": "https://www.mollie.com/en/docs/reference/subscriptions/cancel",
                      "type": "text/html"
                    }
                  }
                }'
            )
        );

        $customer = $this->getCustomer();

        /** @var Subscription $subscription */
        $subscription = $customer->cancelSubscription("sub_wByQa6efm6");

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals("subscription", $subscription->resource);
        $this->assertEquals("sub_wByQa6efm6", $subscription->id);
        $this->assertEquals("test", $subscription->mode);
        $this->assertEquals(SubscriptionStatus::STATUS_CANCELED, $subscription->status);
        $this->assertEquals("2018-04-24T11:41:55+00:00", $subscription->createdAt);
        $this->assertEquals("2018-04-24T12:31:32+00:00", $subscription->canceledAt);


        $selfLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n/subscriptions/sub_wByQa6efm6", "type" => "application/hal+json"];
        $this->assertEquals($selfLink, $subscription->_links->self);

        $customerLink = (object)["href" => "https://api.mollie.com/v2/customers/cst_FhQJRw4s2n", "type" => "application/hal+json"];
        $this->assertEquals($customerLink, $subscription->_links->customer);

        $documentationLink = (object)["href" => "https://www.mollie.com/en/docs/reference/subscriptions/cancel", "type" => "text/html"];
        $this->assertEquals($documentationLink, $subscription->_links->documentation);

    }

    /**
     * @return Customer
     */
    private function getCustomer()
    {
        $customerJson = '{
                  "resource": "customer",
                  "id": "cst_FhQJRw4s2n",
                  "mode": "test",
                  "name": "John Doe",
                  "email": "johndoe@example.org",
                  "locale": null,
                  "metadata": null,
                  "recentlyUsedMethods": [],
                  "createdAt": "2018-04-19T08:49:01+00:00",
                  "_links": {
                    "documentation": {
                      "href": "https://www.mollie.com/en/docs/reference/customers/get",
                      "type": "text/html"
                    }
                  }
                }';

        return $this->copy(json_decode($customerJson), new Customer($this->apiClient));
    }

}