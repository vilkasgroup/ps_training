<?php

declare(strict_types=1);

namespace Invertus\Training\Provider;

use training;

class ConfigurationOptionsProvider
{

    /**
     * @var \training
     */
    private $training;
    private $somename;

    public function __construct(training $training, $somename)
    {
        $this->training = $training;
        $this->somename = $somename;
    }

    public function getArticlesPerPageOptions(): array
    {
        return [
            [
                'id' => '1',
                'name' => $this->training->l('One'),
            ],
            [
                'id' => '2',
                'name' => $this->training->l('Two'),
            ],
            [
                'id' => '5',
                'name' => $this->training->l('Five'),
            ],
        ];
    }
}
