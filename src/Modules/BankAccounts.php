<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\BankAccount\Statement;
use Webleit\ZohoBooksApi\Models\BankAccount;

/**
 * Class BankAccounts
 * @package Webleit\ZohoBooksApi\Modules
 */
class BankAccounts extends Module
{
    /**
     * @param $id
     * @return Statement
     */
    public function getLastImportedStatement($id)
    {
        $data = $this->client->get($this->getUrl() . '/' . $id . '/statement/lastimported');
        return new Statement($data['statement']);
    }

    /**
     * @param $id
     * @param $idOrStatement
     * @return bool
     */
    public function deleteStatement($id, $idOrStatement)
    {
        if ($idOrStatement instanceof BankAccount\Statement) {
            $idOrStatement = $idOrStatement->getId();
        }

        $this->client->delete($this->getUrl() . '/' . $id . '/statement/' . $idOrStatement);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deactivate($id)
    {
        return $this->doAction($id, 'inactice');
    }

    /**
     * @param $id
     * @return bool
     */
    public function activate($id)
    {
        return $this->doAction($id, 'active');
    }
}