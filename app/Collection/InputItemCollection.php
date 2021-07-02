<?php

namespace App\Collection;

class InputItemCollection
{
    private array $items;

    public function __construct(?string $items) {
        $this->items = $this->createTrimmedArray($items);
    }

    public function add(string $item): void
    {
        $this->items[] = $item;
    }

    public function all(): array
    {
        return $this->items;
    }

    private function createTrimmedArray(?string $inputData): array
    {
        if ($inputData === null) {
            return [];
        }

        $arrayList = explode(',', $inputData);

        array_walk($arrayList, function (&$value) {
            $value = trim($value);
        });

        return $arrayList;
    }
}
