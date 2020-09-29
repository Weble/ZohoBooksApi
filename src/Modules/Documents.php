<?php

namespace Webleit\ZohoBooksApi\Modules;

use Illuminate\Support\Collection;
use Psr\Http\Message\StreamInterface;
use Webleit\ZohoBooksApi\Models\Template;
use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;

/**
 * Class Document
 * @package Webleit\ZohoBooksApi\Modules
 */
abstract class Documents extends Module
{
    use Commentable;

    /**
     * @param $id
     * @param array $params
     * @return array
     */
    public function getEmailContent($id, $params = [])
    {
        return $this->client->get($this->getUrl() . '/' . $id . '/email', null, $params);
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
     * @param array $data
     * @return boolean
     */
    public function updateBillingAddress($id, $data = [])
    {
        $this->client->put($this->getUrl() . '/' . $id . '/address/billing', null, $data);
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
        $this->client->put($this->getUrl() . '/' . $id . '/address/shipping', null, $data);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsSent($id)
    {
        return $this->markAs($id, 'sent');
    }

    /**
     * @param $id
     * @param $data
     * @param $params
     * @return bool
     */
    public function emailOne($id, $data, $params)
    {
        return $this->doAction($id, 'email', $data, $params);
    }

    /**
     * @param array $ids
     * @return StreamInterface The content of the pdf as a stream
     */
    public function exportPdfList($ids = [])
    {
        $params['invoice_ids'] = implode(",", $ids);

        return $this->client->rawGet($this->getUrl() . '/pdf', $params);
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

        $this->client->put($this->getUrl() . '/' . $id . '/templates/' . $idOrTemplate);
        // If we arrive here without exceptions, everything went well
        return true;
    }
}
