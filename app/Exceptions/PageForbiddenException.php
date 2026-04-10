<?php

namespace App\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\Exceptions\HTTPExceptionInterface;

class PageForbiddenException extends FrameworkException implements HTTPExceptionInterface
{
    public static function forPageForbidden(string $message = 'Anda tidak memiliki akses untuk halaman ini.'): self
    {
        return new self($message, 403);
    }
}
