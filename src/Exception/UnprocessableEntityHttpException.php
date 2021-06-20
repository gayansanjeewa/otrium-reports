<?php
declare(strict_types=1);

namespace App\Exception;

/**
 * HTTP 422 Unprocessable Entity exception class.
 */
class UnprocessableEntityHttpException extends HttpException
{
    /**
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
            $message ?? 'Unprocessable Entity',
            $description ?? '',
            $code ?? StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
            StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
            $previous
        );
    }
}
