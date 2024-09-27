<?php

namespace Poman\TestDrom\Task2\client;

interface CommentClientInterface
{
    public function getComments(): array;

    public function postComment(string $json): array;

    public function updateComment(int $id, string $json): array;

}