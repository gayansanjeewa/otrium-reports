<?php

namespace App\Controller;

use EasyCSRF\EasyCSRF;
use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use EasyCSRF\NativeSessionProvider;

class BaseController
{
    /**
     * @return string
     */
    protected function getCSRFToken(): string
    {
        $sessionProvider = new NativeSessionProvider();
        $easyCSRF = new EasyCSRF($sessionProvider);

        return $easyCSRF->generate('csrf_token');
    }

    /**
     * @param array $params
     * @throws InvalidCsrfTokenException
     */
    protected function validateCSRFToken(array $params): void
    {
        $sessionProvider = new NativeSessionProvider();
        $easyCSRF = new EasyCSRF($sessionProvider);
        $easyCSRF->check('csrf_token', $params['csrfToken']);
    }
}