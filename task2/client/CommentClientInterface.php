<?php

namespace Poman\TestDrom\Task2\client;

use Poman\TestDrom\Task2\throwable\RequestFormatException;
use Poman\TestDrom\Task2\throwable\ServiceError;

interface CommentClientInterface
{
    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    public function getComments(): array;

    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    public function postComment(string $json): array;

    /**
     * @throws ServiceError
     * @throws RequestFormatException
     */
    public function updateComment(int $id, string $json): array;

}