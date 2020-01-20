<?php

namespace Invertus\Training\Domain\Carrier\QueryHandler;

use Db;
use Invertus\Training\Domain\Carrier\Query\GetOrdersQuery;


/**
 * Example of CQRS implementation in prestashop this is QueryHandler which uses Query to
 * do business logic. Query is mapped to Handler is mapped in services.yml and then CommandBus finds
 * handler by it's query.
 * Query is meant for getting data that doesn't affect anything in shop.
 * If you want to affect shop you should use command instead, which is the same as query but named diffrently
 * so you can clearly know what function is for.
 */
class GetOrdersHandler
{
    public function handle(GetOrdersQuery $query)
    {
        $dbQuery = new \DbQuery();
        $dbQuery->select('*');
        $dbQuery->where('id_carrier = ' . (int) $query->getIdCarrier());
        $dbQuery->from('orders');
        return Db::getInstance()->executeS($dbQuery);
    }

}
