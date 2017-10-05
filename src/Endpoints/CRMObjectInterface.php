<?php
namespace Meiosis\Endpoints;

interface CRMObjectInterface
{
    /**
     * Given an identifier for our object, find and return exactly one
     * @param string $identifier
     * @return type
     */
    public function find($identifier);

    /**
     * Retun an empty instance of the appropriate Model
     * @return type
     */
    public function blueprint();

    /**
     * Given an object, save or update it
     * @param object $object
     * @return type
     */
    public function save($object);

    /**
     * Given an object, Delete it
     * @param object $object
     * @return type
     */
    public function delete($item);
}
