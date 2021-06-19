<?php
declare(strict_types=1);

namespace App\Exception;

class NotFoundHttpException extends HttpException
{
    /**
     * Not Found Exception constructor.
     *
     * @param string|null     $message
     * @param string|null     $description
     * @param int|null        $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = null,
        string $description = null,
        int $code = null,
        \Throwable $previous = null
    ) {
        parent::__construct(
            $message ?? 'Not Found',
            $description ?? '',
            $code ?? StatusCodeInterface::STATUS_NOT_FOUND,
            StatusCodeInterface::STATUS_NOT_FOUND,
            $previous
        );
    }
}