<?php

namespace Invertus\Training\Twig;

use Twig\Extension\AbstractExtension;

class TrainingExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
          new \Twig_SimpleFunction('training_function', function ($ID) {
              return $this->trainingFunction($ID);
          })
        ];
    }

    public function trainingFunction($ID)
    {
        return 'TRAINING ' . $ID;

    }
}
