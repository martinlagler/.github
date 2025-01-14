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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use ModelflowAi\DecisionTree\DecisionTreeInterface;
use ModelflowAi\Image\AIImageRequestHandler;
use ModelflowAi\Image\AIImageRequestHandlerInterface;
use ModelflowAi\Image\Middleware\HandleMiddleware;
use ModelflowAi\Integration\Symfony\DecisionTree\DecisionTreeDecorator;
use ModelflowAi\Integration\Symfony\ModelflowAiBundle;

/*
 * @internal
 */
return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('modelflow_ai.image_request_handler.decision_tree', DecisionTreeDecorator::class)
        ->args([
            tagged_iterator(ModelflowAiBundle::TAG_IMAGE_DECISION_TREE_RULE),
        ])
        ->alias(DecisionTreeInterface::class, 'modelflow_ai.chat_request_handler.decision_tree');

    $container->services()
        ->set('modelflow_ai.image_request_handler.middleware.handle', HandleMiddleware::class)
        ->args([
            service('modelflow_ai.image_request_handler.decision_tree'),
        ]);

    $container->services()
        ->set('modelflow_ai.image_request_handler', AIImageRequestHandler::class)
        ->args([
            service('modelflow_ai.image_request_handler.middleware.handle'),
        ])
        ->alias(AIImageRequestHandlerInterface::class, 'modelflow_ai.image_request_handler');
};
