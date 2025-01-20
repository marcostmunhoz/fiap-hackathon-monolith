<?php

namespace App\Shared\Interface\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use JsonSerializable;

readonly class JsonResponse implements Responsable
{
    public function __construct(
        public ?JsonSerializable $data = null,
        public int $status = 200
    ) {
    }

    public function toResponse($request): BaseJsonResponse
    {
        $data = $this->data
            ? ['data' => $this->data]
            : [];

        return new BaseJsonResponse($data, $this->status);
    }

    public static function created(?JsonSerializable $data = null): self
    {
        return new self($data, 201);
    }

    public static function ok(?JsonSerializable $data = null): self
    {
        return new self($data, 200);
    }
}