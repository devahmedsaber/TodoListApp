<?php

namespace Tests\ExpectedResponse;

use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;

class ExpectedResponse
{
    public function __construct(
        protected int $status,
        protected array $data,
        protected array $structure,
        protected bool $strict = false,
        protected bool $is_array = false,
        protected string $path = 'data',
    ) {
    }

    public function assert(TestResponse $response): void
    {
        $response->assertStatus($this->status)->assertJsonStructure($this->getStructure());
        if ($this->strict) {
            $data = json_encode($this->data);
            $response->assertContent($data);
        } else {
            if ($this->is_array) {
                $content = json_decode($response->content(), true);
                $data = Arr::get($content, $this->path);
                Assert::assertCount(count($data), $this->data, sprintf('Response Payload has %u items while expected %u', count($data), count($this->data)));
                $this->assertKey($this->data, $data);
            } else {
                $response->assertJsonFragment($this->data);
            }
        }
    }

    protected function assertKey($expected, $actual)
    {
        Assert::assertEquals(gettype($expected), gettype($actual));
        if (is_array($expected)) {
            foreach ($expected as $index => $item) {
                $this->assertKey($item, $actual[$index]);
            }
        } else {
            Assert::assertEquals($expected, $actual);
        }
    }

    protected function getStructure(): array
    {
        $output = [
            'status',
            'message',
        ];
        if (! empty($this->path)) {
            if (! $this->is_array) {
                Arr::set($output, $this->path, $this->structure);
            } else {
                Arr::set($output, $this->path, ['*' => $this->structure]);
            }
        }

        return $output;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
