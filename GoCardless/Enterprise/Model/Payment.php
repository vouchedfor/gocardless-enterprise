<?php
namespace GoCardless\Enterprise\Model;

/**
 * Class Payment
 * @package GoCardless\Enterprise\Model
 */
class Payment extends MetadataModel
{

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $charge_date;

    /**
     * @var int
     */
    protected $transaction_fee;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Mandate
     */
    protected $mandate;

    /**
     * @var array
     */
    protected $metadata = array();

    /**
     * @var string
     */
    protected $reference;

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \GoCardless\Enterprise\Model\Mandate $mandate
     */
    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Mandate
     */
    public function getMandate()
    {
        return $this->mandate;
    }

    /**
     * Note: Sam Anthony 09-10-2014 I am not sure what method is for, I am guessing it is a typing error?
     *       I did not want to remove this in case other users of the library are using it.
     *
     * @param string $charge_date
     * @deprecated please use setChargeDate()
     */
    public function setCollectedAt($charge_date)
    {
        $this->charge_date = $charge_date;
    }

    /**
     * @param string $charge_date_mixed
     */
    public function setChargeDate( $charge_date_mixed)
    {
        if ($charge_date_mixed instanceof \DateTime) {
            $this->charge_date = $charge_date_mixed->format('Y-m-d');
        } else {
            $this->charge_date = $charge_date_mixed;
        }
    }

    /**
     * @return string
     */
    public function getChargeDate()
    {
        return $this->charge_date;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @param int $transaction_fee
     */
    public function setTransactionFee($transaction_fee)
    {
        $this->transaction_fee = $transaction_fee;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addMetadata($key, $value)
    {
        $this->metadata[$key] = (string) $value;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return int
     */
    public function getTransactionFee()
    {
        return $this->transaction_fee;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if(array_key_exists("mandate", $arr)) {
            unset($arr["mandate"]);
        }

        if($this->getMandate()) {
            $arr["links"]["mandate"] = $this->getMandate()->getId();
        }

        return $arr;
    }
} 
