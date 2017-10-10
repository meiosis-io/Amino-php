<?php
namespace Meiosis\Models;

use Meiosis\Models\BaseModel;
use Meiosis\Models\Site;

class PageType extends BaseModel
{
    protected static $native = [
        'id' => null,
        'name' => null
    ];

    /**
     * Set the site ID for the object
     * @param Site $site
     */
    public function setSite($site)
    {
        if ($site instanceof Site) {
            $this->cms_site_id = $site;
        }
    }
}
