<?php

namespace App\Common\Config\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @inheritdoc
 */
class DbConfigDefinition implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(null);

        /*
         * verify the following structure
         * return [
         *       'definition' => DBConfigDefinition::class,
         *
         *       'database' => [
         *           'connections' => [
         *               'default' => [
         *                   'driver'    => 'mysql',
         *                   'host'      => 'localhost',
         *                   'database'  => 'bootstrapi',
         *                   'username'  => 'root',
         *                   'password'  => 'qwerty',
         *                   'charset'   => 'utf8',
         *                   'collation' => 'utf8_unicode_ci',
         *                   'prefix'    => '',
         *               ],
         *           ],
         *       ],
         *   ];
         */
        $rootNode
            ->children()
                // 'definition' => DBConfigDefinition::class,
                ->scalarNode('definition')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()

                // 'database' => []
                ->arrayNode('database')
                    ->children()
                        // 'connections' => []
                        ->arrayNode('connections')

                            // multiple connection specifications can be inside 'connections' section
                            // each of connections specifications has its own name
                            ->useAttributeAsKey('name')
                            ->prototype('array')

                                ->children()
                                    // connection params

                                    ->scalarNode('driver')
                                        ->defaultValue('mysql')
                                    ->end()
                                    ->scalarNode('host')
                                        ->defaultValue('localhost')
                                    ->end()
                                    ->scalarNode('database')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('username')
                                        ->defaultValue('root')
                                    ->end()
                                    ->scalarNode('password')
                                        ->defaultValue(null)
                                    ->end()
                                    ->scalarNode('charset')->end()
                                    ->scalarNode('collation')->end()
                                    ->scalarNode('prefix')->end()

                                    ->scalarNode('self-filled')
                                        ->defaultValue('some value')
                                    ->end()

                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
