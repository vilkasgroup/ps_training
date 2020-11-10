<?php

namespace Invertus\Training\Grid\QueryBuilder;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

/**
 * QueryBuilder is responsible for getting data from database to put it in Grid.
 */
final class ArticleQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var int
     */
    private $contextLangId;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param int $contextLangId
     * @param int $contextShopId
     */
    public function __construct(Connection $connection, $dbPrefix, $contextLangId)
    {
        parent::__construct($connection, $dbPrefix);

        $this->contextLangId = $contextLangId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
//        $quantitiesQuery = $this->connection
//            ->createQueryBuilder()
//            ->select('id_training_article')
//            ->from($this->dbPrefix.'stock_available', 'sa')
//            ->groupBy('id_product');
//
//        $qb = $this->getBaseQuery($searchCriteria->getFilters());
//        $qb->select('p.id_product, pl.name, q.quantity')
//            ->leftJoin(
//                'p',
//                sprintf('(%s)', $quantitiesQuery->getSQL()),
//                'q',
//                'p.id_product = q.id_product'
//            )
//            ->leftJoin(
//                'p',
//                $this->dbPrefix . 'product_shop',
//                'ps',
//                'ps.id_product = p.id_product AND ps.id_shop = :context_shop_id'
//            )
//            ->setParameter('context_shop_id', $this->contextShopId)
//            ->orderBy(
//                $searchCriteria->getOrderBy(),
//                $searchCriteria->getOrderWay()
//            )
//            ->setFirstResult($searchCriteria->getOffset())
//            ->setMaxResults($searchCriteria->getLimit())
//        ;

        return $this->getBaseQuery($searchCriteria->getFilters());
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());
        $qb->select('COUNT(ta.id_training_article)');

        return $qb;
    }

    /**
     * Base query is the same for both searching and counting
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getBaseQuery(array $filters)
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('ta.id_training_article, tal.name, tal.description, ta.type')
            ->from($this->dbPrefix.'training_article', 'ta')
            ->leftJoin(
                'ta',
                $this->dbPrefix.'training_article_lang',
                'tal',
                'ta.id_training_article = tal.id_training_article AND tal.id_lang = :context_lang_id'
            )
            ->setParameter('context_lang_id', $this->contextLangId)
        ;

        foreach ($filters as $filterName => $filterValue) {
            if ('id_training_article' === $filterName) {
                (bool) $filterValue ?
                    $qb->where('ta.id_training_article = :' . $filterName) :
                    $qb->setParameter($filterName, $filterValue);

                continue;
            }

            $qb->andWhere("$filterName LIKE :$filterName");
            $qb->setParameter($filterName, '%'.$filterValue.'%');
        }

        return $qb;
    }
}
