<?php

namespace Webleit\ZohoBooksApi\Modules;

use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use Webleit\ZohoBooksApi\Models\Invoice\CreditsApplied;
use Webleit\ZohoBooksApi\Models\Invoice\Payment;
use Webleit\ZohoBooksApi\Models\Invoice\Template;

/**
 * Class Invoices
 * @package Webleit\ZohoBooksApi\Modules
 */
class Invoices extends Module
{
    /**
     * @param array $ids
     * @return bool
     */
    public function sendPaymentReminderList($ids = [])
    {
        $params['invoice_ids'] = implode(",", $ids);

        $this->client->post($this->getUrl() . '/paymentreminder', [], $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param array $ids
     * @return StreamInterface The content of the pdf as a stream
     */
    public function exportPdfList($ids = [])
    {
        $params['invoice_ids'] = implode(",", $ids);

        return $this->client->rawGet($this->getUrl() . '/pdf', null, $params);
    }

    /**
     * @param $id
     * @return bool
     */
    public function disablePaymentReminder($id)
    {
        $this->client->post($this->getUrl() . '/' . $id . '/paymentreminder/disable');
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function enablePaymentReminder($id)
    {
        $this->client->post($this->getUrl() . '/' . $id . '/paymentreminder/enable');
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param $data
     * @param $params
     * @return bool
     */
    public function sendPaymentReminder($id, $data = [], $params = [])
    {
        $this->client->post($this->getUrl() . '/' . $id . '/paymentreminder', $data, $params);
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
     * @param array $params
     * @return array
     */
    public function getEmailContent($id, $params = [])
    {
        return $this->client->get($this->getUrl() . '/' . $id . '/email', null, null, $params);
    }

    /**
     * @return Collection
     */
    public function getTemplates()
    {
        return $this->getPropertyList('templates');

    }

    /**
     * @param string $id
     * @param string|Template $idOrTemplate
     * @return bool
     */
    public function updateTemplate($id, $idOrTemplate)
    {
        if ($idOrTemplate instanceof Template) {
            $idOrTemplate = $idOrTemplate->getId();
        }

        $this->client->put($this->getUrl() . '/' . $id .'/templates/' . $idOrTemplate);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param string $id
     * @param array $data
     * @param array $params
     * @return bool
     */
    public function email($id, $data = [], $params = [])
    {
        return $this->emailOne($id, $data, $params);
    }

    /**
     * @param array $ids
     * @param array $data
     * @return bool
     */
    public function emailList($ids = [], $data = [])
    {
        $params['invoice_ids'] = implode(",", $ids);

        $this->client->post($this->getUrl() . '/email', $data, $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function void($id)
    {
        $this->client->post($this->getUrl() . '/' . $id .'/status/void');
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @return boolean
     */
    public function updateBillingAddress($id, $data = [])
    {
        $this->client->put($this->getUrl() . '/' . $id .'/address/billing', null, null, $data);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @return boolean
     */
    public function updateShippingAddress($id, $data = [])
    {
        $this->client->put($this->getUrl() . '/' . $id .'/address/shipping', null, null, $data);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function writeOff($id)
    {
        $this->client->post($this->getUrl() . '/' . $id .'/writeoff');

        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function cancelWriteOff($id)
    {
        $this->client->post($this->getUrl() . '/' . $id .'/writeoff/cancel');

        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsSent($id)
    {
        $this->client->post($this->getUrl() . '/' . $id .'/status/sent');

        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsDraft($id)
    {
        $this->client->post($this->getUrl() . '/' . $id .'/status/sent');

        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param $data
     * @param $params
     * @return bool
     */
    public function emailOne($id, $data, $params)
    {
        $this->client->post($this->getUrl() . '/' . $id . '/email', $data, $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

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
     * @return Collection
     */
    public function getCreditsApplied($id)
    {
        return $this->getPropertyList('creditsapplied', $id, null, 'credits');
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

    /**
     * @param $id
     * @param string|CreditsApplied $idOrCredit
     * @return bool
     */
    public function deleteAppliedCredit($id, $idOrCredit)
    {
        if ($idOrCredit instanceof CreditsApplied) {
            $idOrCredit = $idOrCredit->getId();
        }

        $this->client->delete($this->getUrl() . '/' . $id . '/creditsapplied/' . $idOrCredit);
        return true;
    }
}