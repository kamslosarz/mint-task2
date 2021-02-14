<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorController extends AbstractController
{
    public function show(Throwable $exception): Response
    {
        if($this->getParameter('kernel.environment') === 'dev')
        {
            dump($exception);
        }

        return $this->render('exception/error.html.twig', [
            'message' => $exception->getMessage()
        ]);
    }
}