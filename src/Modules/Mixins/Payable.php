<?php

namespace Webleit\ZohoBooksApi\Modules\Mixins;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\Payment;

trait Payable
{
    /**
     * @param $id
     * @return Collection
     */
    public function getPayments($id)
    {
        return $this->getPropertyList('payments', $id);
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function applyCredits($id, $data = [])
    {
        $data = $this->client->post($this->getUrl() . '/' . $id . '/credits', $data);

        return $data['use_credits'];
    }

    /**
     * @param $id
     * @param string|Payment $idOrPayment
     * @return bool
     */
    public function deletePayment($id, $idOrPayment)
    {
        if ($idOrPayment instanceof Payment) {
            $idOrPayment = $idOrPayment->getId();
        }

        $this->client->delete($this->getUrl() . '/' . $id . '/payments/' . $idOrPayment);
        return true;
    }
}
