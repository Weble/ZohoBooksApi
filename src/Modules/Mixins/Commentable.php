<?php

namespace Webleit\ZohoBooksApi\Modules\Mixins;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\Comment;

trait Commentable
{
    /**
     * @return mixed
     */
    public function getComments($id)
    {
        $url = $this->getUrl() . '/' . $id . '/comments';
        $list = $this->client->getList($url);

        $prefix = $this->inflector->singularize(strtolower($this->getName())) . '_';

        $collection = new Collection($list[$prefix . 'comments']);
        $collection = $collection->mapWithKeys(function ($item) {
            /** @var Model $item */
            $item = new Comment($item, $this);
            return [$item->getId() => $item];
        });

        return $collection;
    }
}
