<?php

namespace Invertus\Training\Controller\Admin;

use Invertus\Training\Form\Type\TrainingArticleType;
use Invertus\Training\Filter\ArticleFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Tools;
use TrainingArticle;

/**
 * This is example of an new controller in module.
 * listingAction or indexAction is usually used to generate list
 * Create action is used to create creation form
 * Edit action is used for create form, but also loads data of object you are editing
 * Class TrainingArticleController
 * @package Invertus\Training\Controller\Admin
 */
class TrainingArticleController extends FrameworkBundleAdminController
{

    public function listingAction(ArticleFilters $filters)
    {
        $presenter = $this->get('prestashop.core.grid.presenter.grid_presenter');
        $productGrid = $this->get('training.grid.factory')->getGrid($filters);

        return $this->render('@Modules/training/views/Admin/Training/index.html.twig', [
            'articleGrid' => $presenter->present($productGrid),
            'layoutHeaderToolbarBtn' => [
                'add' => [
                    'href' => $this->generateUrl('training_admin_article_create'),
                    'desc' => 'Create new',
                    'icon' => 'add_circle_outline',
                ],
            ],
        ]);
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(TrainingArticleType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = new TrainingArticle();
            $data = $form->getData();
            $article->type = $data['type'];
            $article->name = $data['name'];
            $article->description = $data['description'];
            $article->save();
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            Tools::redirectAdmin($this->get('router')->generate('training_admin_article'));
        }
        return $this->render('@Modules/training/views/Admin/Training/create.html.twig', [
            'layoutTitle' => 'Form example',
            'form' => $form->createView(),
        ]);
    }


    /**
     * Perform search on products list
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function listingSearchAction(Request $request)
    {
        $definitionFactory = $this->get('training.grid.definition.product_grid_definition_factory');
        $productDefinition = $definitionFactory->getDefinition();
        $gridFilterFormFactory = $this->get('prestashop.core.grid.filter.form_factory');
        $filtersForm = $gridFilterFormFactory->create($productDefinition);
        $filtersForm->handleRequest($request);
        $filters = [];
        if ($filtersForm->isSubmitted()) {
            $filters = $filtersForm->getData();
        }
        return $this->redirectToRoute('training_admin_article', ['filters' => $filters]);
    }

    public function editAction(Request $request)
    {
        $idArticle = $request->attributes->get('articleId');
        $article = new TrainingArticle($idArticle);

        $form = $this->createForm(TrainingArticleType::class);
        $form->setData($article->toArray());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = new TrainingArticle();
            $data = $form->getData();
            $article->type = $data['type'];
            $article->name = $data['name'];
            $article->description = $data['description'];
            $article->save();
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
            Tools::redirectAdmin($this->get('router')->generate('training_admin_article'));
        }
        return $this->render('@Modules/training/views/Admin/Training/create.html.twig', [
            'layoutTitle' => 'Form example',
            'form' => $form->createView(),
        ]);
    }


}
