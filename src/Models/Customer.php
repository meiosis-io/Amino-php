<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;

class Customer extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'id'                => null,
        'first'             => null,
        'middle'            => null,
        'last'              => null,
        'email'             => null,
        'organization_id'   => null,
        'created'           => null,
        'updated'           => null,
        'user_id'           => null
    ];
}
