<?php

namespace Invertus\Training\Grid\Definition;

use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\LinkColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use training;

/**
 * Grid definition factory is responsible for setting which fields we will see in grid.
 */
final class ArticleGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    /**
     * @var training
     */
    private $module;

    public function __construct(HookDispatcherInterface $hookDispatcher = null, training $module)
    {
        parent::__construct($hookDispatcher);
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return 'ps_training_article';
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return 'Articles';
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_training_article'))
                ->setName($this->module->l('ID'))
                ->setOptions([
                    'field' => 'id_training_article',
                ])
            )
            ->add((new DataColumn('name'))
                ->setName('Name')
                ->setOptions([
                    'field' => 'name',
                ])
            )
            ->add((new DataColumn('description'))
                ->setName('Description')
                ->setOptions([
                    'field' => 'description',
                ])
            )
            ->add((new DataColumn('type'))
                ->setName('Type')
                ->setOptions([
                    'field' => 'type',
                ])
            )
//            ->add((new LinkColumn('name'))
//                ->setName($this->trans('Name', [], 'Modules.PsTraining.Admin'))
//                ->setOptions([
//                    'field' => 'name',
//                    'route' => 'admin_product_form',
//                    'route_param_name' => 'id',
//                    'route_param_field' => 'id_product',
//                ])
//            )
            ->add((new ActionColumn('actions'))
                ->setName('Actions')
                ->setOptions([
                    'actions' => $this->getRowActions(),
                ])
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * Define filters and associate them with columns.
     * Note that you can add filters that are not associated with any column.
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_training_article', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('id_training_article')
            )
            ->add((new Filter('name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('name')
            )
//            ->add((new Filter('in_stock', YesAndNoChoiceType::class))
//                ->setAssociatedColumn('in_stock')
//            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'training_admin_article',
                    'redirect_route' => 'training_admin_article',
                ])
                ->setAssociatedColumn('actions')
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * Here we define what actions our products grid will have.
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName('Refresh list')
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName('Show SQL query')
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName('Export to SQL Manager')
                ->setIcon('storage')
            )
        ;
    }

    /**
     * Extracted row action definition into separate method.
     */
    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add((new LinkRowAction('edit'))
                ->setName('Edit')
                ->setOptions([
                    'route' => 'training_admin_article_edit',
                    'route_param_name' => 'articleId',
                    'route_param_field' => 'id_training_article',
                ])
                ->setIcon('edit')
            )
        ;
    }
}
