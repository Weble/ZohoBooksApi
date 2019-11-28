<?php

namespace Webleit\ZohoBooksApi\Modules;
use Webleit\ZohoBooksApi\Modules\Mixins\Creditable;
use Webleit\ZohoBooksApi\Modules\Mixins\Payable;

/**
 * Class Invoices
 * @package Webleit\ZohoBooksApi\Modules
 */
class Invoices extends Documents
{
    use Creditable, Payable;

    /**
     * @param array $ids
     * @return bool
     */
    public function sendPaymentReminderList($ids = [])
    {
        $params['invoice_ids'] = implode(",", $ids);

        $this->client->post($this->getUrl() . '/paymentreminder', null, [], $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function disablePaymentReminder($id)
    {
        return $this->markAs($id, 'disable', 'paymentreminder');
    }

    /**
     * @param $id
     * @return bool
     */
    public function enablePaymentReminder($id)
    {
        return $this->markAs($id, 'enable', 'paymentreminder');
    }

    /**
     * @param $id
     * @param $data
     * @param $params
     * @return bool
     */
    public function sendPaymentReminder($id, $data = [], $params = [])
    {
        $this->client->post($this->getUrl() . '/' . $id . '/paymentreminder', null, $data, $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return array
     */
    public function getPaymentReminderEmailContent($id)
    {
        return $this->client->get($this->getUrl() . '/' . $id . '/paymentreminder');
    }


    /**
     * @param $id
     * @return bool
     */
    public function void($id)
    {
        $this->markAs($id, 'void');
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function writeOff($id)
    {
        return $this->doAction($id, 'writeoff');
    }

    /**
     * @param $id
     * @return boolean
     */
    public function cancelWriteOff($id)
    {
        return $this->doAction($id, 'writeoff/cancel');
    }


    /**
     * @param $id string
     * @return boolean
     */
    public function markAsDraft($id)
    {
        return $this->markAs($id, 'draft');
    }
}
