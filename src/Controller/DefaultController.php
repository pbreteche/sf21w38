<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function homepage(Request $request): Response
    {
        $response = new Response(
            'Ceci est le corps de ma réponse HTTP',
            Response::HTTP_OK,
            [
                'Content-type' => 'text/plain',
            ]
        );

        // Tout accès aux données provenant de la requête HTTP
        // doit se faire via l'objet Request
        // On s'interdit donc d'utiliser:
        // les superglobales $_GET, $_POST, $_SERVER, etc
        $output = 'URI: '.$request->getUri().'<br>';
        $output .= 'method: '.$request->getMethod().'<br>';
        $output .= 'host: '.$request->getHost().'<br>';
        $output .= 'En-tête "accept": '.$request->headers->get('accept', 'non-définie').'<br>';
        // Proviendrai de $_GET
        $output .= 'Query-string: '.$request->query->get('lang', 'fr').'<br>';
        // Proviendrai de $_POST
        $output .= 'Données "POST": '.$request->request->get('username', 'none').'<br>';
        // Proviendrai de $_SESSION
        $output .= 'Données de session: '.$request->getSession()->get('cookie-choice', 'none');

        // Toute manipulation de la réponse HTTP doit se faire
        // au travers de cet objet "response"
        // Ceci implique l'interdiction formelle d'utiliser:
        // echo, print, print_r, var_dump, header, http_response_code
        $response
            ->setContent($output)
            ->setCharset('utf8')
            ->headers->set('Content-type', 'text/html')
        ;

        return $response;
    }

    public function hello(string $name): Response
    {
        return new Response('Bonjour '.$name.' !');
    }

    public function article(int $id): Response
    {
        return new Response('Contenu de l\'article '.$id);
    }
}
