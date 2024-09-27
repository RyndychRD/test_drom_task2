<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

/**
 * Сервис вернул не 200 и не 400, или невозможно установить curl соединение
 */
class ServiceError extends Exception implements CommentServiceThrowable
{

}