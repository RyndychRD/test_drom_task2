<?php

namespace Poman\TestDrom\Task2\throwable;

use Exception;

class ServiceError extends Exception implements CommentServiceThrowable
{

}