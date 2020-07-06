<?php
namespace Webleit\ZohoBooksApi\Modules;


use Webleit\ZohoBooksApi\Exceptions\ErrorResponseException;
use Webleit\ZohoBooksApi\Models\Contact;

class Import extends Module
{
    /**
     * @param $crm_contact_id
     * @return Contact
     * @throws ErrorResponseException
     */
    public function contact($crm_contact_id): Contact
    {
        return $this->importFromCRM($crm_contact_id, 'contact');
    }


    /**
     * @param $crm_account_id
     * @return Contact
     * @throws ErrorResponseException
     */
    public function account($crm_account_id): Contact
    {
        return $this->importFromCRM($crm_account_id, 'account');
    }


    /**
     * @param $crm_vendor_id
     * @return Contact
     * @throws ErrorResponseException
     */
    public function vendor($crm_vendor_id): Contact
    {
        return $this->importFromCRM($crm_vendor_id, 'vendor');
    }


    /**
     * @param $crm_id
     * @param $urlPath
     * @throws ErrorResponseException
     */
    public function importFromCRM ($crm_id, $urlPath)
    {
        $data = $this->client->post(
            'crm/' . $urlPath . '/'. $crm_id . '/import', ['blank'=>'']
        );

        if (isset( $data['data']['customer_id'])) {
            return (new Contacts($this->getClient()))->get($data['data']['customer_id']);
        }

        throw new ErrorResponseException('Response from Zoho is not success. Message: ' . $data['message'], $data['code'] ?? 500);
    }


}
