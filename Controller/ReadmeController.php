<?php

/*
 * This file is part of the ElaoReadmeBundle project.
 *
 * Copyright (C) 2016 ElaoReadmeBundle
 *
 * @author Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\ReadmeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadmeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $rootDir = $this->getParameter('elao_readme.root_dir');
        $index   = $this->getParameter('elao_readme.index');
        $base    = $this->getParameter('elao_readme.base');

        $path = $rootDir.$request->query->get('path', $index);

        if (!is_file($path)) {
            throw $this->createNotFoundException('File not found');
        }

        if (pathinfo($path, PATHINFO_EXTENSION) !== 'md') {
            throw $this->createNotFoundException('File not found');
        }

        $html = $this->getHtml($path);

        return $this->render($base, [
            'html'  => $html,
            'tilte' => pathinfo($path, PATHINFO_FILENAME),
        ]);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getHtml($path)
    {
        $markdown = file_get_contents($path);
        $html     = (new \Parsedown())->text($markdown);
        $document = new \DOMDocument();
        $document->loadHTML($html);

        $xpath = new \DOMXPath($document);
        $links = $xpath->query('//a/@href');

        foreach ($links as $link) {
            $parts = parse_url($link->nodeValue);

            if (!isset($parts['host'])) {
                $link->nodeValue = $this->generateUrl('elao_readme', ['path' => $link->nodeValue]);
            }
        }

        $html = $document->saveHTML();

        return $html;
    }
}
