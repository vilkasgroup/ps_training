<?php

declare(strict_types=1);

namespace Invertus\Training\Provider;

use Context;

class ContextProvider
{
    public function getCookies()
    {
        return Context::getContext()->cookie;
    }
}
