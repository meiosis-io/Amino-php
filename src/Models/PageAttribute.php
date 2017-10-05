<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;

class PageAttribute extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'id'                => null,
        'name'              => null,
        'key'               => null,
        'type'              => null,
        'options'           => null,
        'rules'             => 'none',
        'ordinal'           => 0,
        'cms_page_type_id'  => null
    ];
}
