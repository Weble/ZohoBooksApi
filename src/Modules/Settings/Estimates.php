<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\PreferenceModule;
use Webleit\ZohoBooksApi\Modules\Settings\Estimates\NotesAndTerms;

/**
 * Class Preferences
 * @package Webleit\ZohoBooksApi\Modules
 */
class Estimates extends PreferenceModule
{
    /**
     * @var NotesAndTerms
     */
    public $notesandterms;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->notesandterms = new NotesAndTerms($client);
    }

    /**
     * @return string
     */
    protected function getAnswerKeyName()
    {
        return 'estimate_settings';
    }
}