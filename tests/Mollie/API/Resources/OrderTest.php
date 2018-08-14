<?php

namespace Tests\Mollie\Api\Resources;

use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Order;
use Mollie\Api\Resources\OrderLine;
use Mollie\Api\Resources\OrderLineCollection;
use Mollie\Api\Types\OrderLineStatus;
use Mollie\Api\Types\OrderLineType;
use Mollie\Api\Types\OrderStatus;
use Tests\Mollie\TestHelpers\AmountObjectTestHelpers;
use Tests\Mollie\TestHelpers\LinkObjectTestHelpers;
use stdClass;

class OrderTest extends \PHPUnit\Framework\TestCase
{
    use AmountObjectTestHelpers;
    use LinkObjectTestHelpers;

    /**
     * @param string $status
     * @param string $function
     * @param boolean $expected_boolean
     *
     * @dataProvider dpTestOrderStatuses
     */
    public function testOrderStatuses($status, $function, $expected_boolean)
    {
        $order = new Order($this->createMock(MollieApiClient::class));
        $order->status = $status;

        $this->assertEquals($expected_boolean, $order->{$function}());
    }

    public function dpTestOrderStatuses()
    {
        return [
            [OrderStatus::STATUS_CREATED, "isCreated", true],
            [OrderStatus::STATUS_CREATED, "isPaid", false],
            [OrderStatus::STATUS_CREATED, "isAuthorized", false],
            [OrderStatus::STATUS_CREATED, "isCanceled", false],
            [OrderStatus::STATUS_CREATED, "isRefunded", false],
            [OrderStatus::STATUS_CREATED, "isShipping", false],
            [OrderStatus::STATUS_CREATED, "isCompleted", false],
            [OrderStatus::STATUS_CREATED, "isVoid", false],

            [OrderStatus::STATUS_PAID, "isCreated", false],
            [OrderStatus::STATUS_PAID, "isPaid", true],
            [OrderStatus::STATUS_PAID, "isAuthorized", false],
            [OrderStatus::STATUS_PAID, "isCanceled", false],
            [OrderStatus::STATUS_PAID, "isRefunded", false],
            [OrderStatus::STATUS_PAID, "isShipping", false],
            [OrderStatus::STATUS_PAID, "isCompleted", false],
            [OrderStatus::STATUS_PAID, "isVoid", false],

            [OrderStatus::STATUS_AUTHORIZED, "isCreated", false],
            [OrderStatus::STATUS_AUTHORIZED, "isPaid", false],
            [OrderStatus::STATUS_AUTHORIZED, "isAuthorized", true],
            [OrderStatus::STATUS_AUTHORIZED, "isCanceled", false],
            [OrderStatus::STATUS_AUTHORIZED, "isRefunded", false],
            [OrderStatus::STATUS_AUTHORIZED, "isShipping", false],
            [OrderStatus::STATUS_AUTHORIZED, "isCompleted", false],
            [OrderStatus::STATUS_AUTHORIZED, "isVoid", false],

            [OrderStatus::STATUS_CANCELED, "isCreated", false],
            [OrderStatus::STATUS_CANCELED, "isPaid", false],
            [OrderStatus::STATUS_CANCELED, "isAuthorized", false],
            [OrderStatus::STATUS_CANCELED, "isCanceled", true],
            [OrderStatus::STATUS_CANCELED, "isRefunded", false],
            [OrderStatus::STATUS_CANCELED, "isShipping", false],
            [OrderStatus::STATUS_CANCELED, "isCompleted", false],
            [OrderStatus::STATUS_CANCELED, "isVoid", false],

            [OrderStatus::STATUS_REFUNDED, "isCreated", false],
            [OrderStatus::STATUS_REFUNDED, "isPaid", false],
            [OrderStatus::STATUS_REFUNDED, "isAuthorized", false],
            [OrderStatus::STATUS_REFUNDED, "isCanceled", false],
            [OrderStatus::STATUS_REFUNDED, "isRefunded", true],
            [OrderStatus::STATUS_REFUNDED, "isShipping", false],
            [OrderStatus::STATUS_REFUNDED, "isCompleted", false],
            [OrderStatus::STATUS_REFUNDED, "isVoid", false],

            [OrderStatus::STATUS_SHIPPING, "isCreated", false],
            [OrderStatus::STATUS_SHIPPING, "isPaid", false],
            [OrderStatus::STATUS_SHIPPING, "isAuthorized", false],
            [OrderStatus::STATUS_SHIPPING, "isCanceled", false],
            [OrderStatus::STATUS_SHIPPING, "isRefunded", false],
            [OrderStatus::STATUS_SHIPPING, "isShipping", true],
            [OrderStatus::STATUS_SHIPPING, "isCompleted", false],
            [OrderStatus::STATUS_SHIPPING, "isVoid", false],

            [OrderStatus::STATUS_COMPLETED, "isCreated", false],
            [OrderStatus::STATUS_COMPLETED, "isPaid", false],
            [OrderStatus::STATUS_COMPLETED, "isAuthorized", false],
            [OrderStatus::STATUS_COMPLETED, "isCanceled", false],
            [OrderStatus::STATUS_COMPLETED, "isRefunded", false],
            [OrderStatus::STATUS_COMPLETED, "isShipping", false],
            [OrderStatus::STATUS_COMPLETED, "isCompleted", true],
            [OrderStatus::STATUS_COMPLETED, "isVoid", false],

            [OrderStatus::STATUS_VOID, "isCreated", false],
            [OrderStatus::STATUS_VOID, "isPaid", false],
            [OrderStatus::STATUS_VOID, "isAuthorized", false],
            [OrderStatus::STATUS_VOID, "isCanceled", false],
            [OrderStatus::STATUS_VOID, "isRefunded", false],
            [OrderStatus::STATUS_VOID, "isShipping", false],
            [OrderStatus::STATUS_VOID, "isCompleted", false],
            [OrderStatus::STATUS_VOID, "isVoid", true],
        ];
    }


    public function testCanGetLinesAsResourcesOnOrderResource()
    {
        $order = new Order($this->createMock(MollieApiClient::class));
        $orderLine = new stdClass;
        $lineArray = [
            'resource' => 'orderline',
            'id' => 'odl_dgtxyl',
            'orderId' => 'ord_pbjz8x',
            'type' => 'physical',
            'name' => 'LEGO 42083 Bugatti Chiron',
            'productUrl' => 'https://shop.lego.com/nl-NL/Bugatti-Chiron-42083',
            'imageUrl' => 'https://sh-s7-live-s.legocdn.com/is/image//LEGO/42083_alt1?$main$',
            'sku' => '5702016116977',
            'type' => 'physical',
            'status' => 'created',
            'quantity' => 2,
            'unitPrice' => (object) [
                'value' => '399.00',
                'currency' => 'EUR',
            ],
            'vatRate' => '21.00',
            'vatAmount' => (object) [
                'value' => '121.14',
                'currency' => 'EUR',
            ],
            'discountAmount' => (object) [
                'value' => '100.00',
                'currency' => 'EUR',
            ],
            'totalAmount' => (object) [
                'value' => '698.00',
                'currency' => 'EUR',
            ],
            'createdAt' => '2018-08-02T09:29:56+00:00',
            '_links' => [
                'self' => (object) [
                    'href' => 'https://api.mollie.com/v2/orders/ord_pbjz8x/orderlines/odl_dgtxyl',
                    'type' => 'application/hal+json',
                ],
            ],
        ];

        foreach ($lineArray as $key => $value) {
            $orderLine->{$key} = $value;
        }

        $order->lines = [$orderLine];

        $lines = $order->lines();

        $this->assertInstanceOf(OrderLineCollection::class, $lines);
        $this->assertCount(1, $lines);

        $line = $lines[0];

        $this->assertInstanceOf(OrderLine::class, $line);

        $this->assertEquals("orderline", $line->resource);
        $this->assertEquals("odl_dgtxyl", $line->id);
        $this->assertEquals('ord_pbjz8x', $line->orderId);
        $this->assertEquals("LEGO 42083 Bugatti Chiron", $line->name);
        $this->assertEquals("https://shop.lego.com/nl-NL/Bugatti-Chiron-42083", $line->productUrl);
        $this->assertEquals('https://sh-s7-live-s.legocdn.com/is/image//LEGO/42083_alt1?$main$', $line->imageUrl);
        $this->assertEquals("5702016116977", $line->sku);
        $this->assertEquals(OrderLineType::TYPE_PHYSICAL, $line->type);
        $this->assertEquals(OrderLineStatus::STATUS_CREATED, $line->status);
        $this->assertEquals(2, $line->quantity);
        $this->assertAmountObject("399.00", "EUR", $line->unitPrice);
        $this->assertEquals("21.00", $line->vatRate);
        $this->assertAmountObject("121.14", "EUR", $line->vatAmount);
        $this->assertAmountObject("100.00", "EUR", $line->discountAmount);
        $this->assertAmountObject("698.00", "EUR", $line->totalAmount);
        $this->assertEquals("2018-08-02T09:29:56+00:00", $line->createdAt);

        $this->assertLinkObject(
            "https://api.mollie.com/v2/orders/ord_pbjz8x/orderlines/odl_dgtxyl",
            "application/hal+json",
            $line->_links['self']
        );
    }
}
