<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'project_name' => $this->project->name ?? '',
            'account_name' => $this->account->name ?? '',
            'stakeholder_name' => $this->stakeholder->name ?? '',
            'category_name' => $this->category->name ?? '',
            'reference' => $this->ref,
            'type' => $this->type,
            'date' => $this->date,
            'description' => $this->description,
        ];
    }
}
