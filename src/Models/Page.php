<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;

class Page extends BaseModel
{
    public function setChildren($children)
    {
        $childData = [];
        foreach ($children as $child) {
            $childData[] = new Page($child);
        }
        $this->data['children'] = $childData;
    }
}
