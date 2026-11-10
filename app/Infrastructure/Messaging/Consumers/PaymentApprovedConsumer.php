<?php

declare(strict_types=1);

namespace App\Infrastructure\Messaging\Consumers;

use App\Application\UseCases\ReceiveProductionOrderUseCase;
use App\Infrastructure\Messaging\RabbitMQService;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;

final class PaymentApprovedConsumer
{
    private const QUEUE_NAME = 'queue.payment.approved';

    public function __construct(
        private readonly RabbitMQService $rabbitMQService,
        private readonly ReceiveProductionOrderUseCase $receiveUseCase
    ) {}

    public function handle(): void
    {
        $this->rabbitMQService->consume(self::QUEUE_NAME, function (AMQPMessage $message): void {
            try {
                $payload = json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

                $workOrderId = (int) $payload['work_order_id'];
                $customerId = (int) $payload['customer_id'];

                $this->receiveUseCase->execute($workOrderId, $customerId);

                Log::info("PaymentApprovedConsumer: ProductionOrder received for Work Order #{$workOrderId}.");
                $message->ack();
            } catch (Exception $e) {
                Log::error("PaymentApprovedConsumer: Failed to process event: {$e->getMessage()}");
                $message->nack(requeue: false);
            }
        });
    }
}