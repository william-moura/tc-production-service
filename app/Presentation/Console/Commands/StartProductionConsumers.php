<?php

declare(strict_types=1);

namespace App\Presentation\Console\Commands;

use App\Infrastructure\Messaging\Consumers\PaymentApprovedConsumer;
use Illuminate\Console\Command;
use Throwable;

final class StartProductionConsumers extends Command
{
    /**
     * Assinatura do comando CLI com suporte a filtro de filas específicas.
     * Exemplo de uso: php artisan production:consume --queue=payment-approved
     *
     * @var string
     */
    protected $signature = 'production:consume 
                            {--queue=all : The consumer queue to listen to (all, payment-approved)}';

    /**
     * Descrição do comando na listagem do Artisan.
     *
     * @var string
     */
    protected $description = 'Starts RabbitMQ consumers for tc-production-service (PaymentApproved)';

    public function handle(PaymentApprovedConsumer $paymentApprovedConsumer): int
    {
        $queue = (string) $this->option('queue');

        $this->info("Starting RabbitMQ Production Consumer(s) [Mode: {$queue}]...");

        try {
            match ($queue) {
                'payment-approved', 'all' => $this->runPaymentApproved($paymentApprovedConsumer),
                default => $this->handleInvalidOption($queue),
            };

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error("Critical error while consuming messages in production service: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    private function runPaymentApproved(PaymentApprovedConsumer $consumer): void
    {
        $this->line('<comment>[*] Listening to queue.payment.approved...</comment>');
        $this->comment('Press CTRL+C or send SIGTERM to gracefully stop the worker.');

        $consumer->handle();
    }

    private function handleInvalidOption(string $queue): void
    {
        $this->error("Invalid queue option '{$queue}'. Available options: all, payment-approved.");
    }
}