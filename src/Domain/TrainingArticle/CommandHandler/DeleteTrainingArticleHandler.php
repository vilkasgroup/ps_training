<?php

namespace Invertus\Training\Domain\TrainingArticle\CommandHandler;

use Invertus\Training\Domain\TrainingArticle\Command\DeleteTrainingArticle;
use Invertus\Training\Domain\TrainingArticle\Exception\ArticleNotFoundException;
use Invertus\Training\Domain\TrainingArticle\Exception\DeleteTrainingArticleException;
use TrainingArticle;

class DeleteTrainingArticleHandler
{
    public function handle(DeleteTrainingArticle $command)
    {
        $idTrainingArticle = $command->getIdTrainingArticle();
        $article = new TrainingArticle($idTrainingArticle);
        if (!$article->id) {
            throw new ArticleNotFoundException();
        }
        if (!$article->delete()) {
            throw new DeleteTrainingArticleException();
        }
    }
}
