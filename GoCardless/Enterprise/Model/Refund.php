<?php
namespace GoCardless\Enterprise\Model;

/**
 * Class Refund
 * @package GoCardless\Enterprise\Model
 */
class Refund extends MetadataModel
{

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var Payment
     */
    protected $payment;

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
     * @param \GoCardless\Enterprise\Model\Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists("payment", $arr)) {
            unset($arr["payment"]);
        }

        if ($this->getPayment()) {
            $arr["links"]["payment"] = $this->getPayment()->getId();
        }

        return $arr;
    }
}
