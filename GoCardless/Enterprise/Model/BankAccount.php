<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 15:53
 */

namespace GoCardless\Enterprise\Model;


class BankAccount extends Model
{
    /**
     * @var string
     */
    protected $account_number;

    /**
     * @var string
     */
    protected $sort_code;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $account_holder_name;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Mandate[]
     */
    protected $mandates;

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if(array_key_exists("customer", $arr)){
            unset($arr["customer"]);
        }

        if($this->getCustomer() instanceof Customer)
        {
            $arr["links"]["customer"] = $this->getCustomer()->getId();
        }

        return $arr;
    }

    /**
     * @param string $account_holder_name
     */
    public function setAccountHolderName($account_holder_name)
    {
        $this->account_holder_name = $account_holder_name;
    }

    /**
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->account_holder_name;
    }

    /**
     * @param string $account_number
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param \GoCardless\Enterprise\Model\Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $sort_code
     */
    public function setSortCode($sort_code)
    {
        $this->sort_code = $sort_code;
    }

    /**
     * @return string
     */
    public function getSortCode()
    {
        return $this->sort_code;
    }

    /**
     * @param \GoCardless\Enterprise\Model\Mandate[] $mandates
     */
    public function setMandates($mandates)
    {
        $this->mandates = $mandates;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Mandate[]
     */
    public function getMandates()
    {
        return $this->mandates;
    }


} 