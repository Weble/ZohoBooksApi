<?php

namespace Webleit\ZohoBooksApi\Modules\Settings\CreditNotes;

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
        return 'settings/creditnotes/notesandterms';
    }

    /**
     * @return string
     */
    protected function getAnswerKeyName()
    {
        return 'notes_and_terms';
    }
}