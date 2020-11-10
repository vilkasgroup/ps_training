<?php

namespace Invertus\Training\Controller\Admin;

use Invertus\Training\Filter\ArticleFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class TrainingArticleController extends FrameworkBundleAdminController
{
    public function indexAction(ArticleFilters $articleFilters)
    {
        $presenter = $this->get('prestashop.core.grid.presenter.grid_presenter');
        $articleGrid = $this->get('training.grid.factory')->getGrid($articleFilters);
        return $this->render('@Modules/training/views/templates/admin/index.html.twig', [
            'articleGrid' => $presenter->present($articleGrid)
        ]);
    }
}
