<?php

namespace Livewire\ViewExtension\Collection;

class Order
{
    public $id;
    public $direction;
    public $meta;
    public $key;

    function __construct(string $key, string $direction = 'desc', mixed $meta = null)
    {
        $this->key = $key;
        $this->id = $key.'_'.$direction;
        $this->meta = $meta;
        $this->direction = $direction;
    }

    public static function parse(array $data): self
    {
        return new self($data['key'], $data['direction'], $data['meta'] ?? null);
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
