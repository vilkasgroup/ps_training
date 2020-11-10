<?php

namespace Invertus\Training\Domain\TrainingArticle\Command;

class DeleteTrainingArticle
{
    /**
     * @var int
     */
    private $idTrainingArticle;

    public function __construct(int $idTrainingArticle)
    {
        $this->idTrainingArticle = $idTrainingArticle;
    }

    public function getIdTrainingArticle(): int
    {
        return $this->idTrainingArticle;
    }
}
