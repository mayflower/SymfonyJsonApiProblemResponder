<?php

declare(strict_types=1);

namespace Customerce\JsonApiProblemResponder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JsonApiProblemResponderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
