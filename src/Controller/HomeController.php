<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class HomeController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function __invoke()
    {
        echo $this->twig->render('home.twig', [
            'csrf_token'=> $this->getCSRFToken()
        ]);
    }

    /**
     * @return string
     */
    private function getCSRFToken(): string
    {
        $sessionProvider = new \EasyCSRF\NativeSessionProvider();
        $easyCSRF = new \EasyCSRF\EasyCSRF($sessionProvider);

        return $easyCSRF->generate('csrf_token');
    }
}