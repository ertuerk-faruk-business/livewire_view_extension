<?php

namespace Livewire\ViewExtension\Collection;

class Order
{
    private string $key;
    private string $id;
    private string $direction;
    private mixed $meta;

    function __construct(string $key, string $direction = 'desc', mixed $meta = null)
    {
        $this->key = $key;
        $this->id = $key.'_'.$direction;
        $this->direction = $direction;
        $this->meta = $meta;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['key'], $data['direction'], $data['meta'] ?? null);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getMeta(): mixed
    {
        return $this->meta;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'direction' => $this->direction,
            'meta' => $this->meta,
        ];
    }
}
