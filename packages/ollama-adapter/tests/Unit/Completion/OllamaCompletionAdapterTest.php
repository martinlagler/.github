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

namespace ModelflowAi\OllamaAdapter\Tests\Unit\Completion;

use ModelflowAi\ApiClient\Responses\MetaInformation;
use ModelflowAi\Completion\Request\AICompletionRequest;
use ModelflowAi\Completion\Response\AICompletionResponse;
use ModelflowAi\DecisionTree\Criteria\CriteriaCollection;
use ModelflowAi\Ollama\ClientInterface;
use ModelflowAi\Ollama\Resources\CompletionInterface;
use ModelflowAi\Ollama\Responses\Completion\CreateResponse;
use ModelflowAi\OllamaAdapter\Completion\OllamaCompletionAdapter;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class OllamaCompletionAdapterTest extends TestCase
{
    use ProphecyTrait;

    public function testHandleRequest(): void
    {
        $completion = $this->prophesize(CompletionInterface::class);
        $client = $this->prophesize(ClientInterface::class);
        $client->completion()->willReturn($completion->reveal());

        $completion->create([
            'model' => 'llama2',
            'prompt' => 'Prompt message',
        ])->willReturn(CreateResponse::from([
            'model' => 'llama2',
            'created_at' => '2024-01-13T12:01:31.929209Z',
            'response' => 'Lorem Ipsum',
            'context' => [0.1, 0.2, 0.3],
            'done' => true,
            'total_duration' => 6_259_208_916,
            'load_duration' => 3_882_375,
            'prompt_eval_duration' => 267_650_000,
            'prompt_eval_count' => 0,
            'eval_count' => 169,
            'eval_duration' => 5_981_849_000,
        ], MetaInformation::from([])));

        $request = new AICompletionRequest('Prompt message', new CriteriaCollection(), [], fn () => null);

        $adapter = new OllamaCompletionAdapter($client->reveal());
        $result = $adapter->handleRequest($request);

        $this->assertInstanceOf(AICompletionResponse::class, $result);
        $this->assertSame('Lorem Ipsum', $result->getContent());
    }

    public function testHandleRequestAsJson(): void
    {
        $completion = $this->prophesize(CompletionInterface::class);
        $client = $this->prophesize(ClientInterface::class);
        $client->completion()->willReturn($completion->reveal());

        $completion->create([
            'model' => 'llama2',
            'format' => 'json',
            'prompt' => 'Prompt message',
        ])->willReturn(CreateResponse::from([
            'model' => 'llama2',
            'created_at' => '2024-01-13T12:01:31.929209Z',
            'response' => 'Lorem Ipsum',
            'context' => [0.1, 0.2, 0.3],
            'done' => true,
            'total_duration' => 6_259_208_916,
            'load_duration' => 3_882_375,
            'prompt_eval_duration' => 267_650_000,
            'prompt_eval_count' => 0,
            'eval_count' => 169,
            'eval_duration' => 5_981_849_000,
        ], MetaInformation::from([])));

        $request = new AICompletionRequest('Prompt message', new CriteriaCollection(), ['format' => 'json'], fn () => null);

        $adapter = new OllamaCompletionAdapter($client->reveal());
        $result = $adapter->handleRequest($request);

        $this->assertInstanceOf(AICompletionResponse::class, $result);
        $this->assertSame('Lorem Ipsum', $result->getContent());
    }
}
