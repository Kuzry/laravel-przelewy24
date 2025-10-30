<?php

namespace Kuzry\Przelewy24\Tests\Unit;

use Kuzry\Przelewy24\Data\Przelewy24ConfigData;
use Kuzry\Przelewy24\Data\Przelewy24ConfigPosData;
use Kuzry\Przelewy24\Data\TransactionRegisterNotificationData;
use Kuzry\Przelewy24\Enums\Currency;
use Kuzry\Przelewy24\Notification;
use Kuzry\Przelewy24\Tests\TestCase;

class NotificationTest extends TestCase
{
    private Notification $notification;

    protected function setUp(): void
    {
        parent::setUp();

        $config = new Przelewy24ConfigData(
            pos: [
                'default' => new Przelewy24ConfigPosData(
                    merchantId: 123456789,
                    posId: 987654321,
                    reportKey: '123456789',
                    crc: '987654321',
                ),
            ],
        );

        $this->notification = new Notification($config);
    }

    public function test_validate_notification_signature()
    {
        $notificationData = new TransactionRegisterNotificationData(
            sessionId: 'SESSION_123456789',
            amount: 1000,
            originAmount: 1000,
            currency: Currency::PLN,
            orderId: 123456789,
            methodId: 1,
            statement: 'Payment for order #123456789'
        );

        $correctHash = hash(
            'sha384',
            json_encode([
                'merchantId' => 123456789,
                'posId' => 987654321,
                'sessionId' => 'SESSION_123456789',
                'amount' => 1000,
                'originAmount' => 1000,
                'currency' => Currency::PLN->value,
                'orderId' => 123456789,
                'methodId' => 1,
                'statement' => 'Payment for order #123456789',
                'crc' => '987654321',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $result = $this->notification->isTransactionRegisterValid($correctHash, $notificationData);

        $this->assertTrue($result);
    }
}
