<?php

namespace Poman\TestDrom\Task2\dto;

use Poman\TestDrom\Task2\model\Comment;

class CommentDto
{
    private ?string $name = null;
    private ?string $text = null;

    public function __construct(Comment $comment)
    {
        $this->name = $comment->getName();
        $this->text = $comment->getText();
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $result = [];
        if ($this->name) {
            $result['name'] = $this->name;
        }
        if ($this->text) {
            $result['text'] = $this->text;
        }
        return $result;
    }

}