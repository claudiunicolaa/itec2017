<?php

namespace AppBundle\Service;

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

    public function getResource($datasetId, $format = 'CSV')
    {
        $data = $this->showPackageAction($datasetId);
        if ($data['success'] !== true) {
            throw new \Exception(sprintf('Could not retrieve information about dataset with id %s.', $datasetId));
        }

        $resourceUrl = null;
        foreach ($data['result']['resources'] as $resource) {
            if ($resource['format'] == $format) {
                $resourceUrl = $resource['url'];
            }
        }

        if (null === $resourceUrl) {
            throw new \Exception(sprintf('Could not find a resource for dataset with id %s.', $datasetId));
        }
        $file = file_get_contents($resourceUrl);
//        $path = '/home/marius/PhpStormProjects/itec/web/datasets/new.csv';
//        file_put_contents($path, $file);
        return $file;
    }

    public function showPackageAction($id)
    {
        $url = sprintf(
            '%s/action/package_show?id=%s',
            $this->baseUrl,
            urlencode($id)
        );
        return json_decode(file_get_contents($url), true);
    }
}