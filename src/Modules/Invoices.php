<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\Invoice\CreditsApplied;
use Webleit\ZohoBooksApi\Modules\Mixins\Creditable;
use Webleit\ZohoBooksApi\Modules\Mixins\Payable;

/**
 * Class Invoices
 * @package Webleit\ZohoBooksApi\Modules
 */
class Invoices extends Documents
{
    use Creditable, Payable;

    /** @var string */
    protected $creditsAppliedClass = CreditsApplied::class;

    /**
     * @param  array  $ids
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

    /**
     * Apply a credit note to an invoice.
     *
     * @param  string  $invid  Invoice ID
     * @param  string  $creditid  Credit Note ID
     * @param  float  $amount  Amount of credit note to apply.
     *
     * @return bool
     * @see Invoices::applyCreditNotes()
     *
     */
    public function applyCreditNote($invid, $creditid, $amount)
    {
        return $this->applyCreditNotes($invid, [$creditid => $amount]);
    }

    /**
     * Apply multiple credit notes to an invoice.
     *
     * Note that is is NOT DOCUMENTED in their API, and the way they say
     * to do it does not work. I had to reverse engineer this by looking at
     * what the React web client uses in the developer console.
     *
     * @param  string  $invid  Invoice ID
     * @param  array  $creditNotesAmounts  Associative array of [$creditNoteId => $amountToApply]
     *
     * @return bool
     */
    public function applyCreditNotes($invid, array $creditNotesAmounts)
    {
        $creditData = [];

        foreach ($creditNotesAmounts as $creditId => $amount) {
            $creditData[] = [
                "creditnote_id" => $creditId,
                "amount_applied" => $amount
            ];
        }

        $data = [
            'apply_creditnotes' => $creditData
        ];

        $this->client->post($this->getUrl() . '/' . $invid . '/credits', $data);
        return true;
    }
}
