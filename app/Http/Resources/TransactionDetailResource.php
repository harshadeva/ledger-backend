<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource  extends TransactionResource
{
      /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         // Extend or customize the parent implementation
         $data = parent::toArray($request);

         // Add additional fields or modify existing ones
         $data['account_id'] = $this->account_id;
         $data['project_id'] = $this->project_id;
         $data['category_id'] = $this->category_id;
         $data['stakeholder_id'] = $this->stakeholder_id;

         return $data;
    }
}
