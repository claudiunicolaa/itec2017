<?php

namespace AppBundle\Service;

use Unirest\Request;

class DatasetDownloader
{
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getResource($datasetId, $resourceId, $format = 'CSV')
    {
        $data = $this->showPackageAction($datasetId);

        if ($data['success'] !== true) {
            throw new \Exception(sprintf('Could not retrieve information about dataset with id %s.', $datasetId));
        }

        $resourceUrl = null;
        foreach ($data['result']['resources'] as $resource) {
            if ($resource['format'] == $format && $resource['id'] === $resourceId) {
                $resourceUrl = $resource['url'];
                break;
            }
        }

        if (null === $resourceUrl) {
            throw new \Exception(sprintf('Could not find a resource for dataset with id %s.', $datasetId));
        }
        $response = Request::get($resourceUrl)->raw_body;
        return $response;
    }

    public function showPackageAction($id)
    {
        $url = sprintf('%s/action/package_show', $this->baseUrl);
        $response = Request::get($url, [], ['id' => $id]);
        return json_decode($response->raw_body, true);
    }
}