<?php

namespace Invertus\Training\Controller\Admin;

use Invertus\Training\Domain\TrainingArticle\Command\DeleteTrainingArticle;
use Invertus\Training\Domain\TrainingArticle\Exception\ArticleNotFoundException;
use Invertus\Training\Domain\TrainingArticle\Exception\DeleteTrainingArticleException;
use Invertus\Training\Filter\ArticleFilters;
use Invertus\Training\Form\FormHandler\TrainingArticleFormHandler;
use PrestaShop\PrestaShop\Core\Domain\CartRule\Query\SearchCartRules;
use PrestaShop\PrestaShop\Core\Domain\CmsPage\Command\DeleteCmsPageCommand;
use PrestaShop\PrestaShop\Core\Domain\CmsPage\ValueObject\CmsPageId;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandler;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class TrainingArticleController extends FrameworkBundleAdminController
{
    public function indexAction(Request $request, ArticleFilters $articleFilters)
    {
        $presenter = $this->get('prestashop.core.grid.presenter.grid_presenter');
        $articleGrid = $this->get('training.grid.factory')->getGrid($articleFilters);
        return $this->render('@Modules/training/views/templates/admin/Article/index.html.twig', [
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons($request),
            'articleGrid' => $presenter->present($articleGrid)
        ]);
    }

    public function createAction(Request $request)
    {
        $form = $this->get('training.form.article_form_builder')->getForm();

        /**
         * @var $form Form
         */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formHandler = $this->get(TrainingArticleFormHandler::class);
            if ($formHandler->save($form->getData())) {
                $this->addFlash('success', 'Successful creation.');
                return $this->redirectToRoute('training_admin_article');
            }
        }
        return $this->render('@Modules/training/views/templates/admin/Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction($articleId, Request $request)
    {
        $form = $this->get('training.form.article_form_builder')->getFormFor($articleId);
        /**
         * @var $form Form
         */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formHandler = $this->get(TrainingArticleFormHandler::class);
            if ($formHandler->save($form->getData(), $articleId)) {
                $this->addFlash('success', 'Successful update.');
                return $this->redirectToRoute('training_admin_article');
            }
        }
        return $this->render('@Modules/training/views/templates/admin/Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction($articleId, Request $request)
    {
        try {
            $this->getCommandBus()->handle(new DeleteTrainingArticle($articleId));
            $this->addFlash('success', 'Successfully removed the article');
        } catch (DeleteTrainingArticleException $e) {
            $this->addFlash('error', 'Failed to delete');
        } catch (ArticleNotFoundException $e) {
            $this->addFlash('error', 'Article not found');
        }

        return $this->redirectToRoute('training_admin_article');

    }


    /**
     * @param Request $request
     *
     * @return array
     */
    private function getToolbarButtons(Request $request)
    {
        $toolbarButtons = [];

        $toolbarButtons['add'] = [
            'href' => $this->generateUrl('training_admin_article_create'),
            'desc' => $this->trans('Add new article', 'Admin.Catalog.Feature'),
            'icon' => 'add_circle_outline',
        ];

        return $toolbarButtons;
    }
}
