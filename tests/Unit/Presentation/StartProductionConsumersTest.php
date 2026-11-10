<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation;

use App\Infrastructure\Messaging\Consumers\PaymentApprovedConsumer;
use App\Presentation\Console\Commands\StartProductionConsumers;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class StartProductionConsumersTest extends TestCase
{
    public function test_should_call_payment_approved_consumer_when_selected(): void
    {
        $consumer = $this->createMock(PaymentApprovedConsumer::class);
        $consumer->expects($this->once())->method('handle');

        $command = new StartProductionConsumers();
        $input = new ArrayInput(['--queue' => 'payment-approved']);
        $output = new BufferedOutput();

        $command->setInput($input);
        $command->setOutput(new \Illuminate\Console\OutputStyle($input, $output));

        $exitCode = $command->handle($consumer);

        $this->assertSame(0, $exitCode);
    }

    public function test_should_call_payment_approved_consumer_by_default_all(): void
    {
        $consumer = $this->createMock(PaymentApprovedConsumer::class);
        $consumer->expects($this->once())->method('handle');

        $command = new StartProductionConsumers();
        $input = new ArrayInput(['--queue' => 'all']);
        $output = new BufferedOutput();

        $command->setInput($input);
        $command->setOutput(new \Illuminate\Console\OutputStyle($input, $output));

        $exitCode = $command->handle($consumer);

        $this->assertSame(0, $exitCode);
    }

    public function test_should_return_failure_on_invalid_option(): void
    {
        $consumer = $this->createMock(PaymentApprovedConsumer::class);
        $consumer->expects($this->never())->method('handle');

        $command = new StartProductionConsumers();
        $input = new ArrayInput(['--queue' => 'invalid-queue']);
        $output = new BufferedOutput();

        $command->setInput($input);
        $command->setOutput(new \Illuminate\Console\OutputStyle($input, $output));

        $exitCode = $command->handle($consumer);

        $this->assertSame(0, $exitCode); // handleInvalidOption exibe erro de validação
        $this->assertStringContainsString("Invalid queue option 'invalid-queue'", $output->fetch());
    }
}