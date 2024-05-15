<?php

declare(strict_types=1);

/*
 * This file is part of the Modelflow AI package.
 *
 * (c) Johannes Wachter <johannes@sulu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ModelflowAi\Core\Model;

use ModelflowAi\Core\Behaviour\SupportsBehaviour;
use ModelflowAi\Core\Request\AIRequestInterface;
use ModelflowAi\Core\Response\AIResponseInterface;

interface AIModelAdapterInterface extends SupportsBehaviour
{
    public function handleRequest(AIRequestInterface $request): AIResponseInterface;
}
