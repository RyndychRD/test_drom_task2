<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

/**
 * Сервис вернул не 2**, не 400 и не 404, или невозможно установить curl соединение
 */
class ServiceError extends Exception implements CommentServiceThrowable
{

}