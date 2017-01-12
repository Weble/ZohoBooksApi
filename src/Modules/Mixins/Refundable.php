<?php

namespace Webleit\ZohoBooksApi\Modules\Mixins;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\Refund;

trait Refundable
{
    /**
     * @return mixed
     */
    public function getRefunds($id)
    {
        $url = $this->getUrl() . '/' . $id . '/refunds';
        $list = $this->client->getList($url);

        $prefix = Inflector::singularize(strtolower($this->getName())) . '_';

        $collection = new Collection($list[$prefix . 'comments']);
        $collection = $collection->mapWithKeys(function ($item) {
            /** @var Model $item */
            $item = new Refund($item, $this);
            return [$item->getId() => $item];
        });

        return $collection;
    }
}