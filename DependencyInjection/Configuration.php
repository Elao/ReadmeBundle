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

namespace Elao\Bundle\RestActionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('elao_readme');

        $rootNode
            ->children()
                ->scalarNode('root_dir')
                    ->defaultValue('%kernel.root_dir%/../')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('index')
                    ->defaultValue('README.md')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('base')
                    ->defaultValue('ElaoReadmeBundle:Readme:index.html.twig')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
