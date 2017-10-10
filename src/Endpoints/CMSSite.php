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

    protected static $returnType = Site::class;

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
}
