<?php

namespace Invertus\Training\Domain\Carrier\Query;

/**
 * Example of CQRS implementation in prestashop this is Query which simply holds the data.
 * Check GetOrdersHandler for more information
 */
class GetOrdersQuery
{
    private $idCarrier;

    public function __construct($idCarrier)
    {
        $this->idCarrier = $idCarrier;
    }

    /**
     * @return mixed
     */
    public function getIdCarrier()
    {
        return $this->idCarrier;
    }
}
