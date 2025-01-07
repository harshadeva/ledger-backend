<?php

namespace App\Http\Resources;

use App\Enums\TransactionTypeEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total' => $this->total,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'total_income'=>$this->transactions()->where('type', TransactionTypeEnum::INCOME)->sum('amount'),
            'total_expense'=>$this->transactions()->where('type', TransactionTypeEnum::EXPENSE)->sum('amount'),
            'status' => $this->status,
        ];
    }
}
