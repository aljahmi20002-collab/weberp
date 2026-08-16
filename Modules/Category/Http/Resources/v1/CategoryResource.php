<?php

namespace Modules\Category\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
                "id"                => $this->id,
                "name"              => $this->name ,
                "category"          => optional($this->category)->name,
                "description"       => $this->description,
                "image"             => (string) $this->image,
                "my_status"         => (string)__('status.'.$this->status),
                "status"            => (string) $this->status,
                "position"          => (string) $this->position,
            ];
    }

    private function categoryName ($category){
        $name =  @$category->name;
        if($category->parent_id !=null):
            $name = '-- '.$name;
        endif;
        return $name;
    }

}
