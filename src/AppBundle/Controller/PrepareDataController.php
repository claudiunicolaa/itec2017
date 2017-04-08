<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PrepareDataController extends Controller
{
    /**
     * @Route("/data", name="getData")
     */
    public function getHighChartsDataAction(): JsonResponse
    {
        $data = [
            'ro-cj' => [
                'density' => 100,
                'test1' => 10,
                'test2' => 2
            ]
        ];
        $data = ['ro-cj', 100];

        return $this->json($data);
    }

    /**
     * @Route("/densityData", name="getDensityData")
     */
    public function getDensityAction(): JsonResponse
    {
        $data = [
            'title' => '',
            'name' => 'Densitatea populatiei',
            'data' => [
                ['ro-cj', 100],
                ['ro-ab', 10]
            ],
            'valueSuffix' => ' km2',
            'max' => '10000'
        ];

        return $this->json($data);
    }

    /**
     * @Route("/driverData", name="getDriverData")
     */
    public function driverDataAction() :JsonResponse
    {
        $driversId = $this->getParameter('dataset.drivers.id');
        $resId = $this->getParameter('dataset.drivers.resource_id');
        $resource = $this->get('app.dataset_downloader')->getResource($driversId, $resId);
        $csvData = $this->parseCsv($resource->getContent());
        $csvData = $this->transformCsvArray($csvData);

        $totalColumn = 'Total detinatori';
        $maxVal = max(array_column($csvData, $totalColumn));

        $responseData = [];
        foreach ($csvData as $data) {
            $abv = $this->getCountyAbbreviation($data['Judet']);
            if (null === $abv) {
                continue;
            }
            $key = 'ro-'.$abv;
            $responseData[] = [
                $key, (int) $data[$totalColumn]
            ];
        }

        return $this->json([
            'title' => $resource->getTitle(),
            'name' => $resource->getName(),
            'data' => $responseData,
            'valueSuffix' => ' persoane',
            'max' => (int) $maxVal
        ]);
    }

    /**
     * @param $csvData
     * @return array
     */
    protected function parseCsv($csvData)
    {
        $lines = explode(PHP_EOL, $csvData);

        $resourceData = array();
        foreach ($lines as $line) {
            $rowValues = str_getcsv($line, ';;');
            $rowValues = array_map('trim', $rowValues);
            $rowValues = array_filter($rowValues, function ($v) {return $v != '';});
            if (count($rowValues) < 1) {
                continue;
            }
            $resourceData[] = $rowValues;
        }

        return $resourceData;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function transformCsvArray(array $array)
    {
        $header = array_shift($array);
        $header = array_map('trim', $header);
        $cb = function (&$row, $key, $header) {
            $row = array_combine($header, $row);
        };

        array_walk($array, $cb, $header);

        return $array;
    }

    protected function getCountyAbbreviation($county)
    {
        $county = strtolower($county);
        $county = str_replace(' ', '-', $county);
        $map = $this->getParameter('county_abbreviations');
        if (!isset($map[$county])) {
            return null;
        }

        return $map[$county];
    }
}
