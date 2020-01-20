<?php

namespace Invertus\Training\Twig;

use Twig\Extension\AbstractExtension;

/**
 * Registers custom Twig functions
 */
class TrainingExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('training_get_name', function ($ID) {
                return 'TRAINING' . $ID;
            }),
        ];
    }
}
