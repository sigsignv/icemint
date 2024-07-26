<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Controller;

use Sigsign\IceMint\DataStore\DataStoreInterface;
use Sigsign\IceMint\DataStore\FileDataStore;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends AbstractController
{
    private DataStoreInterface $datastore;

    public function __construct()
    {
        $this->datastore = new FileDataStore(__DIR__ . '/../../data/markdown/');
    }

    public function show(string $page): Response
    {
        $content = $this->datastore->read($page);

        return $this->render('view.twig', [
            "title" => "{$page}",
            "content" => "{$content}",
        ]);
    }

    public function edit(Request $request): Response
    {
        $title = $request->query->get('title');
        $content = $this->datastore->read($title);

        if ($content === "Not found") {
            // Todo:
            $content = '';
        }

        return $this->render('edit.twig', [
            "title" => "{$title}",
            "content" => "{$content}",
        ]);
    }

    public function update(Request $request): Response
    {
        $data = $request->getPayload();
        $title = $data->get('title');
        $content = $data->get('content');
        $this->datastore->write($title, $content);

        return new RedirectResponse("/p/{$title}", 303);
    }
}
