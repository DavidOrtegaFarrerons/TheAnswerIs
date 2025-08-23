<?php

namespace App\Dto\Editor;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionFieldDto
{
    #[Assert\NotBlank(message:'The field to update is mandatory')]
    #[Assert\Choice(choices: ['title', 'optionA', 'optionB', 'optionC', 'optionD', 'correctAnswer', 'difficulty'])]
    private string $field;

    private ?string $value = null;

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): QuestionFieldDto
    {
        $this->field = $field;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): QuestionFieldDto
    {
        $this->value = $value;
        return $this;
    }
}
