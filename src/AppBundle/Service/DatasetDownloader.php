<?php

namespace AppBundle\Service;

use AppBundle\Entity\Dataset;
use AppBundle\Entity\Resource;
use Symfony\Component\Serializer\Serializer;
use Unirest\Request;

class DatasetDownloader
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct($baseUrl, Serializer $serializer, $proxyConfig)
    {
        $this->baseUrl = $baseUrl;
        $this->serializer = $serializer;
        if ($proxyConfig['ip'] != null) {
            Request::proxy($proxyConfig['ip'], $proxyConfig['port']);
        }
    }

    /**
     * @param $datasetId
     * @param $resourceId
     * @param string $format
     * @return \AppBundle\Entity\Resource
     * @throws \Exception
     */
    public function getResource($datasetId, $resourceId, $format = 'CSV')
    {
        $data = $this->showPackageAction($datasetId);

        $resource = null;
        /** @var \AppBundle\Entity\Resource $r */
        foreach ($data->getResources() as $r) {
            if ($r->getFormat() == $format && $r->getId() === $resourceId) {
                $resource = $r;
                $resource->setTitle($data->getNotes());
                $resource->setName($data->getName());
                break;
            }
        }

        if (null === $resource) {
            throw new \Exception(sprintf('Could not find a resource for dataset with id %s.', $datasetId));
        }
        $response = Request::get($resource->getUrl());
        $resource->setContent($response->raw_body);

        return $resource;
    }

    public function showPackageAction($id)
    {
        $url = sprintf('%s/action/package_show', $this->baseUrl);
        $response = Request::get($url, [], ['id' => $id])->raw_body;
        $response = json_decode($response, true);
        if ($response['success'] !== true) {
            throw new \Exception(sprintf('Could not retrieve information about dataset with id %s.', $datasetId));
        }

        /** @var Dataset $dataset */
        $dataset = $this->serializer->deserialize(json_encode($response['result']), Dataset::class, 'json');
        $rs = $dataset->getResources();
        $resources = array();
        foreach ($rs as $resource) {
            $resource = $this->serializer->deserialize(json_encode($resource), Resource::class, 'json');
            $resources[] = $resource;
        }
        $dataset->setResources($resources);

        return $dataset;
    }
}