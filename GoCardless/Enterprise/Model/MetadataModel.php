<?php

namespace GoCardless\Enterprise\Model;


use GoCardless\Enterprise\Exceptions\ApiException;

abstract class MetadataModel extends Model
{
    protected $metadata;

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata($key, $value)
    {
        $this->metadata[$key] = (string) $value;

        if (count($this->metadata) > 3)
        {
            throw new \Exception('The Gocardless API allows a maximum of 3 metadata keys');
        }

    }

    public function removeMetadata($key)
    {
        unset($this->metadata[$key]);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        $arr["metadata"] = $this->getMetadata();

        return $arr;
    }


}