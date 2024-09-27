<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

/**
 * - Сервис вернул 404
 */
class CommentNotFound extends Exception implements CommentServiceThrowable
{

}