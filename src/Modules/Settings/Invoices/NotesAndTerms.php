<?php

namespace Webleit\ZohoBooksApi\Modules\Settings\Invoices;

use Webleit\ZohoBooksApi\Modules\PreferenceModule;

/**
 * Class NotesAndTerms
 * @package Webleit\ZohoBooksApi\Modules
 */
class NotesAndTerms extends PreferenceModule
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/invoices/notesandterms';
    }

    /**
     * @return string
     */
    protected function getAnswerKeyName()
    {
        return 'notes_and_terms';
    }
}