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

        $this->client->delete($this->getUrl() . '/' . $id . '/invoices/' . $idOrCreditNoteInvoice);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     */
    public function applyToInvoices($id, $data)
    {
        $data = $this->client->post($this->getUrl() . '/' . $id . '/invoices', null, $data);

        return $data['apply_to_invoices']['invoices'];
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
        return $this->client->get($this->getUrl() . '/' . $id . '/emailhistory');
    }
}
