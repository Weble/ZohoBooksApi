<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\VendorCredit;
use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Modules\Mixins\Refundable;

/**
 * Class VendorCredits
 * @package Webleit\ZohoBooksApi\Modules
 */
class VendorCredits extends Module
{
    use Commentable, Refundable;

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function applyToBill($id, $data)
    {
        $data = $this->client->post($this->getUrl() . '/' . $id . '/bills', $data);

        return $data['bills'];
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getBills($id)
    {
        $class = '\\Webleit\\ZohoBooksApi\\Models\\VendorCredit\\Bill';
        return $this->getPropertyList('applytobills', $id, $class, 'bills_credited');
    }

    /**
     * @param $id
     * @param $idOrBill
     * @return bool
     */
    public function deleteFromBill($id, $idOrBill)
    {
        if ($idOrBill instanceof VendorCredit\Bill) {
            $idOrBill = $idOrBill->getId();
        }

        $this->client->delete($this->getUrl() . '/' . $id . '/bills/' . $idOrBill);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsOpen($id)
    {
        return $this->markAs($id, 'open');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsVoid($id)
    {
        return $this->markAs($id, 'void');
    }

    /**
     * Override returned key
     *
     * This overrides the key that is returned from zoho, as they
     * send back 'vendor_credits' instead of 'vendorcredits'
     *
     * @return string
     */
    public function getResourceKey()
    {
        return 'vendor_credits';
    }
}
