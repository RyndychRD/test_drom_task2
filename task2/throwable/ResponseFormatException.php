<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

/**
 * - Сервис вернул неожиданный ответ
 */
class ResponseFormatException extends Exception implements CommentServiceThrowable
{

}