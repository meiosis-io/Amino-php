<?php

namespace Meiosis\Models;

use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Exceptions\UseOtherMethodException;

class BaseModel
{
    protected $data = [];
    protected $crmObject;

    public function __construct($data = [], $crmObject = null)
    {
        if (empty($data)) {
            $this->populate(static::$native);
        }

        if (!empty($data)) {
            $this->populate((array) $data);
        }

        $this->crmObject = $crmObject;
    }

    /**
     * Return the native fields array
     * @return array
     */
    public static function getNativeFields()
    {
        return static::$native;
    }

    /**
     * Take a raw array of data and populate a new object
     * @param array $data
     * @return BaseModel
     */
    public function populate(array $data)
    {
        foreach ($data as $key => $item) {
            $func = 'set_'.$key;

            if (method_exists($this, $func)) {
                $this->{$func}($item);
                continue;
            }

            $this->data[$key] =  $item;
        }

        return $this;
    }

    /**
     * Extract the raw data array
     * @return array
     */
    public function extract()
    {
        return $this->data;
    }

    /**
     * Save our current instance
     * @return BaseModel
     */
    public function save()
    {
        if (is_null($this->crmObject)) {
            throw new UseOtherMethodException('Use ->save() method on CRM Object. Model was not instantiated with CRMObject to reference.');
        }

        $new = $this->crmObject->save($this);
        $this->populate($new->extract());

        return $this;
    }

    /**
     * Reload the data from the api
     * @return BaseModel
     */
    public function refresh()
    {
        if (is_null($this->id)) {
            throw new ObjectNotPopulatedException('Can not refresh a customer that has not been saved');
        }

        $new = $this->crmObject->find($this->id);
        $this->populate($new->extract());

        return $this;
    }

    /**
     * Set an item on the data array, or hand off to a set_ function if available
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        // Defer to the set method if it exists...
        $func = 'set_'.$name;

        if (method_exists($this, $func)) {
            return $this->{$func}($value);
        }

        $this->data[$name] = $value;
    }

    /**
     * Get an item on the data array, or hand off to a get_ function if available
     * @param string $name
     */
    public function __get($name)
    {
        // Defer to the get method if it exists...
        $func = 'get_'.strtoupper($name);
        if (method_exists($this, $func)) {
            return $this->{$func}();
        }

        // Try top get the value from the data array
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        // We have nothing!
        return null;
    }
}
