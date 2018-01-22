<?php
namespace Meiosis\Endpoints;

use Meiosis\Amino;
use Meiosis\Constants\Api;
use Meiosis\Models\BaseModel;

/**
 * Base CRM Object class
 */
abstract class CRMObject
{
    /**
     * @var Common Data - Appended to all requests
     */
    public $commonData = [];

    /**
     * @var Amino - the Amino class instance
     */
    public $amino;

    /**
     * @var string - Class Implementation
     */
    protected static $returnType = BaseModel::class;

    // Instantiate the object with the API credentials, and build the client
    public function __construct(Amino $amino)
    {
        $this->amino = $amino;
    }

    /**
     * Given an ID, return the object
     * @param string $identifier
     * @return BaseModel
     */
    public function find($identifier)
    {
        $result = $this->amino->client()->get(
            $this->endpoint . $identifier,
            $this->payload()
        );

        return new static::$returnType($result, $this);
    }

    /**
     * Generate an empty object that implements BaseModel for populating
     * @return BaseModel
     */
    public function blueprint()
    {
        return new static::$returnType([], $this);
    }

    /**
     * Given an array of key:value pairs, perform a search
     * @param array $searchArray
     * @return BaseModel
     */
    public function search($searchArray)
    {
        $result = $this->amino->client()->get(
            $this->endpoint,
            $this->payload($searchArray)
        );

        $data = [];
        foreach ($result as $item) {
            $data[] = new static::$returnType($item, $this);
        }

        return $data;
    }

    /**
     * Build the payload payload to supply to the APIClient
     * @param array $data
     * @return array
     */
    public function payload(array $data = [])
    {
        return array_merge($this->commonData, $data);
    }

    /**
     * Given an object, save it
     * @param BaseModel $object
     * @return type
     */
    public function save($object)
    {
        if (is_null($object->id)) {
            $result = $this->create($object);
        }

        if (!is_null($object->id)) {
            $result = $this->update($object);
        }

        return $this->find($result->id);
    }

    /**
     * Create a new BaseModel
     * @param BaseModel $object
     * @return
     */
    protected function create($object)
    {
        return $this
            ->amino
            ->client()
            ->post($this->endpoint, $this->payload($object->extract()));
    }

    /**
     * Updates an existing BaseModel
     * @param BaseModel $object
     * @return type
     */
    protected function update($object)
    {
        $updateEndpoint = $this->endpoint . $object->id;
        return $this
            ->amino
            ->client()
            ->post($updateEndpoint, $this->payload($object->extract()));
    }

    /**
     * Deletes an Existing Object
     * @param Object|String $identifier
     * @return type
     */
    public function delete($identifier)
    {
        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        if ($identifier instanceof BaseModel) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        return $this
            ->amino
            ->client()
            ->delete($deleteEndpoint, $this->payload());
    }

    /**
     * Allow for proper Serialization and deserialization
     * @return array
     */
    public function __sleep()
    {
        return ['amino'];
    }
}
