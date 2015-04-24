<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 11:30
 */

namespace GoCardless\Enterprise;


use GoCardless\Enterprise\Exceptions\ApiException;
use GoCardless\Enterprise\Model\CreditorBankAccount;
use GoCardless\Enterprise\Model\CustomerBankAccount;
use GoCardless\Enterprise\Model\Creditor;
use GoCardless\Enterprise\Model\Customer;
use GoCardless\Enterprise\Model\Mandate;
use GoCardless\Enterprise\Model\Model;
use GoCardless\Enterprise\Model\Payment;
use GoCardless\Enterprise\Model\Refund;
use Guzzle\Http\Exception\BadResponseException;

class Client
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var array
     */
    protected $defaultHeaders;

    /**
     * @var string
     */
    protected $password;

    const ENDPOINT_CUSTOMER = "customers";

    const ENDPOINT_CUSTOMER_BANK = "customer_bank_accounts";

    const ENDPOINT_MANDATE = "mandates";

    const ENDPOINT_PAYMENTS = "payments";

    const ENDPOINT_REFUNDS = "refunds";

    const ENDPOINT_CREDITORS = "creditors";

    const ENDPOINT_CREDITOR_BANK = "creditor_bank_accounts";

    const CANCEL_ACTION ="/actions/cancel";

    /**
     * @param \Guzzle\Http\Client $client
     * @param array $config
     * ["baseUrl" => ?, "username" => ?, "password" => ?]
     */
    public function __construct(\Guzzle\Http\Client $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config["baseUrl"];
        $this->username = $config["username"];
        $this->password = $config["password"];
        $this->defaultHeaders = ["GoCardless-Version" => $config["gocardlessVersion"]];
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    public function createCustomer(Customer $customer)
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER, $customer->toArray());
        $customer->fromArray($response);

        return $customer;
    }

    /**
     * @param $id
     * @return Customer
     */
    public function getCustomer($id)
    {
        $customer = new Customer();
        $customer->fromArray($this->get(self::ENDPOINT_CUSTOMER, [], $id));
        return $customer;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     *
     * @return array
     */
    public function listCustomers($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER, $parameters);
        $customers = $this->responseToObjects(new Customer(), $response);

        return $customers;
    }

    /**
     * @param CustomerBankAccount $account
     * @return CustomerBankAccount
     */
    public function createCustomerBankAccount(CustomerBankAccount $account)
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER_BANK, $account->toArray());
        $account->fromArray($response);
        return $account;
    }

    /**
     * @param $id
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount($id)
    {
        $account = new CustomerBankAccount();
        $account->fromArray($this->get(self::ENDPOINT_CUSTOMER_BANK, [], $id));
        return $account;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     *
     * @return array
     */
    public function listCustomerBankAccounts($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER_BANK, $parameters);
        $accounts = $this->responseToObjects(new CustomerBankAccount(), $response);

        return $accounts;
    }

    /**
     * @param Mandate $mandate
     * @return Mandate
     */
    public function createMandate(Mandate $mandate)
    {
        $response = $this->post(self::ENDPOINT_MANDATE, $mandate->toArray());
        $mandate->fromArray($response);
        return $mandate;
    }

    /**
     * @param $id
     * @return Mandate
     */
    public function getMandate($id)
    {
        $mandate = new Mandate();
        $mandate->fromArray($this->get(self::ENDPOINT_MANDATE, [], $id));
        return $mandate;
    }


    public function getMandatePdf($id)
    {
        try{
            $response = $this->client->get($this->makeUrl(self::ENDPOINT_MANDATE, $id), $this->defaultHeaders + ["Accept" => "application/pdf", "GoCardless"])->setAuth($this->username, $this->password)->send();
            return $response->getBody(true);
        } catch(BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     * @return array
     */
    public function listMandates($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);
        $mandates = $this->responseToObjects(new Mandate(), $response);

        return $mandates;
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function createPayment(Payment $payment)
    {
        $response = $this->post(self::ENDPOINT_PAYMENTS, $payment->toArray());
        $payment->fromArray($response);
        return $payment;
    }

    /**
     * @param $id
     * @return Payment
     */
    public function getPayment($id)
    {
        $payment = new Payment();
        $payment->fromArray($this->get(self::ENDPOINT_PAYMENTS, [], $id));
        return $payment;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @return Payment[]
     */
    public function listPayments($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_PAYMENTS, $parameters);
        $payments = $this->responseToObjects(new Payment(), $response);

        return $payments;
    }


    /**
     * @param Refund $refund
     * @return Refund
     * @throws \Exception
     */
    public function createRefund(Refund $refund)
    {
        $arr = $refund->toArray($refund);

        $arr['total_amount_confirmation'] = $arr['amount'];

        $response = $this->post(self::ENDPOINT_REFUNDS, $arr);
        $refund->fromArray($response);
        return $refund;
    }

    /**
     * @param Payment $payment
     * @return Payment
     * @throws \Exception
     */
    public function cancelPayment(Payment $payment)
    {
        $response = $this->post(self::ENDPOINT_PAYMENTS, [], $payment->getId() . self::CANCEL_ACTION);
        $payment->fromArray($response);
        return $payment;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @return Creditor[]
     */
    public function listCreditors($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CREDITORS, $parameters);
        $creditors = $this->responseToObjects(new Creditor(), $response);

        return $creditors;
    }

    /**
     * @param $id
     * @return Creditor
     */
    public function getCreditor($id)
    {
        $creditor = new Creditor();
        $creditor->fromArray($this->get(self::ENDPOINT_CREDITORS, [], $id));
        return $creditor;
    }

    /**
     * @param CreditorBankAccount $account
     * @param bool $setAsDefault
     * @return CreditorBankAccount
     */
    public function createCreditorBankAccount(CreditorBankAccount $account, $setAsDefault = false)
    {
        $response = $this->post(self::ENDPOINT_CREDITOR_BANK, ["set_as_default_payout_account" => $setAsDefault]+$account->toArray());
        $account->fromArray($response);

        return $account;
    }

    /**
     * @param $id
     * @return CreditorBankAccount
     */
    public function getCreditorBankAccount($id)
    {
        $account = new CreditorBankAccount();
        $account->fromArray($this->get(self::ENDPOINT_CREDITOR_BANK, [], $id));
        return $account;
    }


    public function disableCreditorBankAccount($id)
    {
        $response = $this->post(self::ENDPOINT_CREDITOR_BANK, '', $id.'/actions/disable');

        $account = new CreditorBankAccount();
        $account->fromArray($response);

        return $account;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @return CreditorBankAccounts[]
     */
    public function listCreditorBankAccounts($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CREDITOR_BANK, $parameters);
        $creditorBankAccounts = $this->responseToObjects(new CreditorBankAccount(), $response);

        return $creditorBankAccounts;
    }


    /**
     * @param Model $example
     * @param $response
     * @return Model[]
     */
    protected function responseToObjects(Model $example, $response)
    {
        $objects = array_map(function($data) use ($example){
            $object = clone $example;
            $object->fromArray($data);
            return $object;
        }, $response);

        return $objects;
    }

    /**
     * @param $endpoint
     * @param $path
     * @return string
     */
    protected function makeUrl($endpoint, $path = false)
    {
        return $this->baseUrl.$endpoint.($path ? "/".$path : "");
    }

    /**
     * @param $endpoint
     * @param $body
     * @param null $path
     * @return mixed
     * @throws \Exception
     */
    protected function post($endpoint, $body, $path = null)
    {
        try{
            if (!empty($body)) {
                $body = json_encode([$endpoint => $body]);
            }

            $response = $this->client->post($this->makeUrl($endpoint, $path) . '/', $this->defaultHeaders + ["Content-Type" => "application/vnd.api+json"], $body)->setAuth($this->username, $this->password)->send();

            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch(BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param $endpoint
     * @param array $parameters
     * @param null $path
     * @return mixed
     * @throws \Exception
     */
    protected function get($endpoint, $parameters = [], $path = null)
    {
        try{
            $response = $this->client->get($this->makeUrl($endpoint, $path), $this->defaultHeaders, ["query" => $parameters])->setAuth($this->username, $this->password)->send();
            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch (BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    public function rawRequest($endpoint, $rawbody, $httpMethod)
    {
        try{
            if (strtolower($httpMethod) == 'get')
            {
                $response = $this->client->get($this->makeUrl($endpoint), $this->defaultHeaders, ["query" => [] ])->setAuth($this->username, $this->password)->send();
            }
            elseif(strtolower($httpMethod) == 'post')
            {
                $response = $this->client->post($this->makeUrl($endpoint), $this->defaultHeaders + ["Content-Type" => "application/vnd.api+json"], $rawbody)->setAuth($this->username, $this->password)->send();
            }
            else
            {
                throw new \Exception('At the moment this function only supports get and post');
            }
            return $response->getBody(true);

        } catch (BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    /***************************************************
     * Start: Direct Debit Guarantee related functions
     **************************************************/
    public function getDirectDebitGuaranteeData($companyName, $workingDays)
    {
        $data['heading'] = "The Direct Debit Guarantee";
        $data['paragraphs'][] = "This Guarantee is offered by all banks and building societies that accept instructions to pay Direct Debit";
        $data['paragraphs'][] = "If there are any change to the amount, date or frequency of your Direct Debit Gocardless Ltd re: {$companyName} will notify you {$workingDays} working days in advanced of your account being debited or as otherwise agreed. If you request GoCardless Ltd re: {$companyName} to collect a payment, confirmation of the amount and date will be given to you at the time of the request";
        $data['paragraphs'][] = "If an error is made in the payment of your Direct Debit, by GoCardless Ltd re: {$companyName} or your bank or building society, you are entitled to a full and immediate refund of the amount paid from your bank or building society";
        $data['paragraphs'][] = "If you receive a refund you are not entitled to, you must pay it back when GoCardless Ltd re: {$companyName} asks you to";
        $data['paragraphs'][] = "You can cancel a Direct Debit at any time by simply contacting your bank or building society. Written confirmation may be required. Please also notify us.";

        return $data;
    }

    public function getDirectDebitGuaranteeHtml($companyName, $workingDays, $headingTag = 'h1')
    {
        $data = $this->getDirectDebitGuaranteeData($companyName, $workingDays);

        $html = "<{$headingTag}>{$data['heading']}</{$headingTag}>";
        $html .= "<ul>";
        foreach ($data['paragraphs'] as $paragraph)
        {
            $html .= "<li>{$paragraph}</li>";
        }
        $html .= "</ul>";

        return $html;
    }
    /**************************************************
     * End: Direct Debit Guarantee related functions
     *************************************************/


}