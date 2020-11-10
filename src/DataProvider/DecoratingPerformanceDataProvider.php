<?php

namespace Invertus\Training\DataProvider;

use GuzzleHttp\Client;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class DecoratingPerformanceDataProvider implements FormDataProviderInterface
{
    /**
     * @var \PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface
     */
    private $performanceDataProvider;

    public function __construct(FormDataProviderInterface $performanceDataProvider)
    {
        $this->performanceDataProvider = $performanceDataProvider;
    }

    public function getData()
    {
        return $this->performanceDataProvider->getData();
    }

    public function setData(array $data)
    {
        if ($data['text'] != 'something') {
            return ['Text doesn\'t equal something'];
        }
        return $this->performanceDataProvider->setData($data);
    }
}
