<?php
declare(strict_types=1);

namespace App\Exception;

use Ramsey\Uuid\Uuid;

class HttpException extends \DomainException
{
    const STATUS_CODE_LIMIT = 600;

    /**
     * Unique error identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Exception description.
     *
     * @var string
     */
    protected $description;

    /**
     * HTTP status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * HTTP Exception constructor.
     *
     * @param string          $message
     * @param string          $description
     * @param int             $code
     * @param int             $statusCode
     * @param \Throwable|null $previous
     *
     * @throws \RuntimeException
     */
    public function __construct(
        string $message,
        string $description,
        int $code,
        int $statusCode,
        \Throwable $previous = null
    ) {
        if ($statusCode < StatusCodeInterface::STATUS_BAD_REQUEST || $statusCode >= static::STATUS_CODE_LIMIT) {
            throw new \RuntimeException(\sprintf('%s is not a valid HTTP error status code', $statusCode));
        }

        parent::__construct(\trim($message), $code, $previous);

        $this->identifier = Uuid::uuid4();
        $this->description = \trim($description);
        $this->statusCode = $statusCode;
    }

    /**
     * Get exception unique identifier.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get exception description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
