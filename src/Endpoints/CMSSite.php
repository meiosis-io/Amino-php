<?php
namespace Meiosis\Endpoints;

use Meiosis\Constants\Api;
use Meiosis\Endpoints\CRMObject;
use Meiosis\Endpoints\CRMObjectInterface;
use Meiosis\Exceptions\ObjectNotFoundException;
use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\Site;

class CMSSite extends CRMObject implements CRMObjectInterface
{
    protected $endpoint = 'cms/site/';

    /**
     * Given an identifier for our object, find and return exactly one
     * @param string $identifier
     * @return type
     */
    public function find($identifier)
    {
        $site = $this->apiClient->get(
            $this->endpoint . $identifier,
            $this->payload()
        );
        return new Site($site);
    }

    /**
     * Save method - Overwrite base since we use token instead of ID
     * @param Site $object
     * @return type
     */
    public function save($object)
    {
        if (is_null($object->id)) {
            $result = $this->create($object);
            return $this->find($result->token);
        }

        $result = $this->update($object);
        return $this->find($result->token);
    }

    /**
     * Retun an empty instance of the appropriate Model
     * @return type
     */
    public function blueprint()
    {
        return new Site([], $this);
    }
}
