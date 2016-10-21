<?php

namespace Elao\Bundle\ReadmeBundle\Twig;

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

class Extension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('html', [$this, 'html'], ['is_safe' => ['html']]),
        ];
    }
    /**
     * @param string $html
     *
     * @return string
     */
    public function html($html)
    {
        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'elao_readme_extension';
    }
}
