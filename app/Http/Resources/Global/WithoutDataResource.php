<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithoutDataResource extends JsonResource
{
    public $status;
    public $message;

    public function __construct($status, $message)
    {
        parent::__construct(null);
        $this->status = $status;
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
