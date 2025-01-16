<?php

declare(strict_types=1);

namespace JsonHub\SDK\Exception;

class ApiViolationException extends \RuntimeException
{
    private array $violations;

    public function getViolations(): array
    {
        return $this->violations;
    }

    private function addViolation(string $field, string $violation): void
    {
        $this->violations[$field] = $violation;
    }

    public static function violations(array $lines): self
    {
        $exception = new self();

        foreach ($lines as $field => $violation) {
            $exception->addViolation($field, $violation);
        }

        return $exception;
    }
}
