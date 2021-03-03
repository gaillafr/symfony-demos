<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/", name="home")
 */
class IndexController extends AbstractController
{
    public function __invoke(HttpClientInterface $ifconfig): Response
    {
        try {
            $response = $ifconfig->request(Request::METHOD_GET, '/');
            $payload = $response->getContent();

            $ip = \json_decode($payload, true)['ip'];
        } catch (HttpExceptionInterface | TransportExceptionInterface $e) {
            $error = 'Unable to get public IP.';
        }

        return $this->render('home/index.html.twig', [
            'error' => $error ?? null,
            'ip' => $ip ?? null,
        ]);
    }
}
