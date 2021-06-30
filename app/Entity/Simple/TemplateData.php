<?php

namespace App\Entity\Simple;

class TemplateData
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var array
     */
    private array $data;

    /**
     * EmailData constructor.
     * @param string $name
     * @param array $data
     */
    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
