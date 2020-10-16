<?php

namespace Webleit\ZohoBooksApi\Modules\Mixins;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\CreditsApplied;

trait Creditable
{
    /**
     * @param $id
     * @return Collection
     */
    public function getCreditsApplied($id)
    {
        return $this->getPropertyList('creditsapplied', $id, $this->creditsAppliedClass ?? null, 'credits');
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