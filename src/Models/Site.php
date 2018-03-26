<?php
namespace Meiosis\Models;

use Meiosis\Exceptions\ObjectNotPopulatedException;
use Meiosis\Models\BaseModel;

class Site extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'id'                => null,
        'name'              => null,
        'domains'           => null,
        'description'       => null,
        'stagingEnabled'    => false
    ];
}
