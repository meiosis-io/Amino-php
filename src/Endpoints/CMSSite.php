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
    private $endpoint = 'cms/site/';

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
     * Retun an empty instance of the appropriate Model
     * @return type
     */
    public function blueprint()
    {
        return new Site([], $this);
    }

    /**
     * Given an object, save or update it
     * @param object $object
     * @return type
     */
    public function save($site)
    {
        if (is_null($site->id)) {
            $result = $this->create($site);
        }

        if (!is_null($site->id)) {
            $result = $this->update($site);
        }

        return $this->find($result->token);
    }

    /**
     * Creates a site if it doesn't
     * @param Site $site
     * @return
     */
    protected function create($site)
    {
        return $this
            ->apiClient
            ->post($this->endpoint, $this->payload($site->extract()));
    }

    /**
     * Updates an existing site
     * @param Site $site
     * @return type
     */
    protected function update($site)
    {
        $updateEndpoint = $this->endpoint . $site->id;
        return $this
            ->apiClient
            ->post($updateEndpoint, $this->payload($site->extract()));
    }

    /**
     * Delete a site
     * @param object|string $item
     * @return type
     */
    public function delete($identifier)
    {
        if ($identifier instanceof Site) {
            $deleteEndpoint = $this->endpoint . $identifier->id;
        }

        if (gettype($identifier) == 'string') {
            $deleteEndpoint = $this->endpoint . $identifier;
        }

        return $this
            ->apiClient
            ->delete($deleteEndpoint, $this->payload());
    }
}
