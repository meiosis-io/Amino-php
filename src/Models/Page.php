<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;

class Page extends BaseModel
{
    // Set constant Fields
    protected static $native = [
        'id'                => null,
        'name'              => null,
        'excerpt'           => null,
        'slug'              => null,
        'content'           => null,
        'parent_id'         => null,
        'cms_page_type_id'  => null,
        'published_at'      => null,
    ];

    public function set_children($children)
    {
        $childData = [];
        foreach ($children as $child) {
            $childData[] = new Page($child);
        }
        $this->data['children'] = $childData;
    }
}
