<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\PreferenceModule;
use Webleit\ZohoBooksApi\Modules\Settings\CreditNotes\NotesAndTerms;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class CreditNotes
 * @package Webleit\ZohoBooksApi\Modules
 */
class CreditNotes extends PreferenceModule
{
    /**
     * @var NotesAndTerms
     */
    public $notesandterms;

    public function __construct(Client $client, ZohoBooks $zohoBook)
    {
        parent::__construct($client, $zohoBook);

        $this->notesandterms = new NotesAndTerms($client, $zohoBook);
    }

    /**
     * @return string
     */
    protected function getAnswerKeyName()
    {
        return 'creditnote_settings';
    }
}