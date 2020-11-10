<?php


namespace Invertus\Training\Form\DataProvider;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class ArticleDataProvider implements FormDataProviderInterface
{
    public function getData($id)
    {
        $article = new \TrainingArticle($id);
        return [
            'type' => $article->type,
            'name' => $article->name,
            'description' => $article->description,
            'id_product' => $article->id_product,

        ];
    }

    public function getDefaultData()
    {
        return [
            'type' => 'customType'
        ];
    }
}
