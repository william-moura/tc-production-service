<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use App\Application\DTOs\StartRepairInputDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

final class StartRepairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'mechanic_id' => ['required', 'integer', 'min:1'],
        ];
    }

    public function toDto(int $workOrderId): StartRepairInputDto
    {
        return new StartRepairInputDto(
            workOrderId: $workOrderId,
            mechanicId: (int) $this->input('mechanic_id')
        );
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}