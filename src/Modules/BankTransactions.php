<?php

namespace Webleit\ZohoBooksApi\Modules;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\BankTransaction;

/**
 * Class BankTransactions
 * @package Webleit\ZohoBooksApi\Modules
 */
class BankTransactions extends Module
{
    /**
     * @param $id
     * @param array $params
     * @return Collection
     */
    public function getMatching($id, $params = [])
    {
        $data = $this->client->getList($this->getUrl() . '/uncategorized/' . $id . '/match', [], $params);

        $collection = new Collection($data['matching_transactions']);
        $collection = $collection->mapWithKeys(function ($item) {
            /** @var Module $item */
            $item = new BankTransaction($item, $this);
            return [$item->getId() => $item];
        });

        return $collection;
    }

    /**
     * @param $id
     * @param array $params
     * @return BankTransaction
     */
    public function getAssociated($id, $params = [])
    {
        $data = $this->client->get($this->getUrl() . '/' . $id . '/associated', null, $params);
        return new BankTransaction($data['transaction']);
    }

    /**
     * @param $id
     * @param array $params
     * @return bool
     */
    public function exclude($id, $params = [])
    {
        $this->client->post($this->getUrl() . '/uncategorized/' . $id . '/exclude', [], $params);
        return true;
    }

    /**
     * @param $id
     * @param array $params
     * @return bool
     */
    public function restore($id, $params = [])
    {
        $this->client->post($this->getUrl() . '/uncategorized/' . $id . '/restore', [], $params);
        return true;
    }

    /**
     * @param $id
     * @param $category
     * @param array $data
     * @param array $params
     * @return bool
     */
    public function categorizeAs($id, $category, $data = [], $params = [])
    {
        $this->client->post($this->getUrl() . '/uncategorized/' . $id . '/categorize/' . $category, $data, $params);
        return true;
    }

    /**
     * @param $id
     * @param array $params
     * @return bool
     */
    public function uncategorize($id, $params = [])
    {
        $this->client->post($this->getUrl() . '/uncategorized/' . $id . '/uncategorize/', [], $params);
        return true;
    }

    /**
     * @param $id
     * @param $data
     * @param array $params
     * @return array
     */
    public function match($id, $data, $params = [])
    {
        $data = $this->client->post($this->getUrl() . '/uncategorized/' . $id . '/match', $data, $params);
        return $data['transactions_to_be_matched'];
    }

    /**
     * @param $id
     * @return bool
     */
    public function unmatch($id)
    {
        $this->client->post($this->getUrl() . '/' . $id . '/unmatch');
        // If we arrive here without exceptions, everything went well
        return true;
    }
}
