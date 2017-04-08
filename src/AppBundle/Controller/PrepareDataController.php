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
            'valueSuffix' => 'km2',
            'max' => '10000'
        ];

        return $this->json($data);
    }
}
