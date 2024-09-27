<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

/**
 * - Сервис вернул 400
 */
class RequestFormatException extends Exception implements CommentServiceThrowable
{

}