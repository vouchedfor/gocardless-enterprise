<?php
namespace GoCardless\Enterprise\Model;

/**
 * Class MetadataModel
 * @package GoCardless\Enterprise\Model
 */
abstract class MetadataModel extends Model
{

    /**
     * @var array
     */
    protected $metadata;

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function setMetadata($key, $value)
    {
        $this->metadata[$key] = (string) $value;

        if (count($this->metadata) > 3) {
            throw new \Exception('The Gocardless API allows a maximum of 3 metadata keys');
        }
    }

    /**
     * @param $key
     */
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

        $metadata = $this->getMetadata();
        if ($metadata) {
            $arr["metadata"] = $metadata;
        }

        return $arr;
    }
}
