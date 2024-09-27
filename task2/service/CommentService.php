<?php

namespace Poman\TestDrom\Task2\service;

use Poman\TestDrom\Task2\client\CommentClientInterface;
use Poman\TestDrom\Task2\dto\CommentDto;
use Poman\TestDrom\Task2\model\Comment;
use Poman\TestDrom\Task2\throwable\ResponseFormatException;

class CommentService
{
    private CommentClientInterface $client;

    public function __construct(CommentClientInterface $client)
    {
        $this->client = $client;
    }

    public function getComments(): array
    {
        $response = $this->client->getComments();
        $result = [];
        foreach ($response as $respComment) {
            self::validateOneCommentFromResponse($respComment);
            $result[] = new Comment($respComment['id'], $respComment['name'], $respComment['text']);
        }
        return $result;
    }

    private function validateOneCommentFromResponse(array $response): void
    {
        if (!isset($response['id']) || !isset($response['name']) || !isset($response['text'])) {
            throw new ResponseFormatException();
        }
    }

    public function postComment(string $name, string $text): Comment
    {
        $comment = new Comment($name, $text);
        $commentDto = new CommentDto($comment);
        $response = $this->client->postComment($commentDto->toJson());
        self::validateOneCommentFromResponse($response);
        $result = new Comment($response['id'], $response['name'], $response['text']);
        return $result;
    }

    public function updateComment(int $id, ?string $name = null, ?string $text = null): Comment
    {
        $comment = new Comment($name, $text);
        $commentDto = new CommentDto($comment);
        $response = $this->client->updateComment($id, $commentDto->toJson());
        self::validateOneCommentFromResponse($response);
        $result = new Comment($response['id'], $response['name'], $response['text']);
        return $result;
    }
}