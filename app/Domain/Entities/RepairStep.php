<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTimeImmutable;
use InvalidArgumentException;

final class RepairStep
{
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private bool $completed = false,
        private ?DateTimeImmutable $completedAt = null
    ) {
        if (trim($this->name) === '') {
            throw new InvalidArgumentException('Repair step name cannot be empty.');
        }
    }

    public static function create(string $name, string $description = ''): self
    {
        return new self(
            name: trim($name),
            description: trim($description),
            completed: false,
            completedAt: null
        );
    }

    public function complete(): void
    {
        $this->completed = true;
        $this->completedAt = new DateTimeImmutable();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'completed' => $this->completed,
            'completed_at' => $this->completedAt?->format(DATE_ATOM),
        ];
    }
}