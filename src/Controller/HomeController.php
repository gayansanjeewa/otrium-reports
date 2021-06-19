<?php

namespace App\Controller;

use App\Repository\Contract\GMVRepositoryInterface;
use League\Csv\Writer;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController
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
        echo $this->twig->render('home.twig');
    }
}