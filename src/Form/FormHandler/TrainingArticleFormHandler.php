<?php

namespace Invertus\Training\Form\FormHandler;

class TrainingArticleFormHandler
{
    public function save(array $data, ?int $articleId = null)
    {
        if ($articleId) {
            $article =new \TrainingArticle($articleId);
        } else {
            $article = new \TrainingArticle();
        }
        $article->type = $data['type'];
        $article->name = $data['name'];
        $article->description = $data['description'];
        $article->id_product = $data['id_product'];
        return $article->save();
    }
}
