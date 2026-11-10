<?php

declare(strict_types=1);

namespace App\Application\Services;

interface MessagePublisherInterface
{
    /**
     * Publica uma mensagem formatada como array associativo na fila informada.
     *
     * @param string $queue Nome da fila destino (ex: queue.repair.completed, queue.production.aborted)
     * @param array<string, mixed> $payload Dados estruturados do evento
     */
    public function publish(string $queue, array $payload): void;
}