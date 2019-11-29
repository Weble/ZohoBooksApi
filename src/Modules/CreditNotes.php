<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\CreditNote;
use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;
use Webleit\ZohoBooksApi\Modules\Mixins\Refundable;

/**
 * Class CreditNotes
 * @package Webleit\ZohoBooksApi\Modules
 */
class CreditNotes extends Documents
{
    use Commentable, Refundable;

    /**
     * @param $id
     * @param $idOrCreditNoteInvoice
     * @return bool
     */
    public function deleteFromInvoices($id, $idOrCreditNoteInvoice)
    {
        if ($idOrCreditNoteInvoice instanceof CreditNote\Invoice) {
            $idOrCreditNoteInvoice = $idOrCreditNoteInvoice->getId();
        }

        $this->client->delete($this->getUrl().'/'.$id.'/invoices/'.$idOrCreditNoteInvoice);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param  array  $data  Associative array of [$invoiceId => $amount]
     * @return bool
     */
    public function applyToInvoices($id, array $data)
    {
        // This does not work, and the documentation is wrong
        // $data = $this->client->post($this->getUrl() . '/' . $id . '/invoices', null, $data);
        // return $data['apply_to_invoices']['invoices'];

        $result = true;
        foreach ($data as $invoiceId => $amount) {
            $result = $result && $this->applyToInvoice($id, $invoiceId, $amount);
        }

        return $result;
    }

    /**
     * Apply a credit note to an invoice
     * @param $id
     * @param $invoiceId
     * @param $amount
     * @return bool
     */
    public function applyToInvoice($id, $invoiceId, $amount)
    {
        // This does not work, and the documentation is wrong
        // $data = $this->client->post($this->getUrl() . '/' . $id . '/invoices', null, $data);
        // return $data['apply_to_invoices']['invoices'];

        // let's use Invoices instead
        $invoices = new Invoices($this->getClient());
        return $invoices->applyCreditNote($id, $invoiceId, $amount);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getInvoices($id)
    {
        return $this->getPropertyList('invoices', $id, null, 'invoices_credited');
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
     * @param $id
     * @return array
     */
    public function emailHistory($id)
    {
        return $this->client->get($this->getUrl().'/'.$id.'/emailhistory');
    }
}
