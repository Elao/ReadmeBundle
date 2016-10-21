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
use Symfony\Component\Finder\Finder;
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

        $path = $rootDir.$request->query->get('path');

        if (is_dir($path)) {

            if (is_file($path.$index)) {
                return $this->renderFile($path.$index);
            }

            return $this->renderIndex($path);
        }

        if (is_file($path)) {
            return $this->renderFile($path);
        }

        throw $this->createNotFoundException('Not found.');
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

    /**
     * @param $path
     *
     * @return Response
     */
    private function renderFile($path)
    {
        $base = $this->getParameter('elao_readme.base_template');

        if (pathinfo($path, PATHINFO_EXTENSION) !== 'md') {
            throw $this->createNotFoundException('File not found');
        }

        $html = $this->getHtml($path);

        return $this->render($base, [
            'html'  => $html,
            'title' => pathinfo($path, PATHINFO_FILENAME),
            'path'  => $this->getRelativePath(realpath($path)),
        ]);
    }

    /**
     * @param string $path
     *
     * @return Response
     */
    private function renderIndex($path)
    {
        $base = $this->getParameter('elao_readme.index_template');

        $finder = new Finder();
        $finder->depth('== 0');

        $directories = array_map(function (\SplFileInfo $fileInfo) {
            return [
                'name' => $fileInfo->getFilename(),
                'path' => $this->getRelativePath($fileInfo->getRealPath()),
                'file' => $fileInfo
            ];
        }, iterator_to_array($finder->directories()->in($path)));

        $files = array_map(function (\SplFileInfo $fileInfo) {
            return [
                'name' => $fileInfo->getFilename(),
                'path' => $this->getRelativePath($fileInfo->getRealPath()),
                'file' => $fileInfo
            ];
        }, iterator_to_array($finder->files()->in($path)->name('*.md')->getIterator()));

        return $this->render($base, [
            'title'       => $this->getRelativePath(realpath($path)) ? : '/',
            'path'        => $this->getRelativePath(realpath($path)) ? : '/',
            'directories' => $directories,
            'files'       => $files,
        ]);
    }

    private function getRelativePath($path)
    {
        return str_replace(realpath($this->getParameter('elao_readme.root_dir')), '', $path);
    }
}
