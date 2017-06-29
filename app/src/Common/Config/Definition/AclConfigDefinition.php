<?php

namespace App\Common\Config\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use App\Common\Acl;

/**
 * @inheritdoc
 */
class AclConfigDefinition implements ConfigurationInterface
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
         *   return [
         *       'definition' => ACLConfigDefinition::class,
         *
         *       'acl' => [
         *           'default_role' => 'guest',
         *
         *           'roles' => [
         *               // role => [multiple parents specification as array]
         *               'admin' => ['user'],
         *           ],
         *
         *           'resources' => [
         *              // resource => parent
         *           ],
         *
         *           // where we specify the guarding!
         *           'guards' => [
         *
         *              Acl::GUARD_TYPE_RESOURCE => [
         *
         *              ],
         *
         *              Acl::GUARD_TYPE_ROUTE => [
         *                   // resource, [roles as array], [privileges as array]
         *                   ['/api/token', ['guest'], [Acl::PRIVILEGE_POST]],
         *              ],
         *
         *              Acl::GUARD_TYPE_CALLABLE => [
         *                   // resource, [roles as array], [privileges as array]
         *                   ['App\Controller\CrudController',              ['user']],
         *              ],
         *           ],
         *       ],
         *   ];
         */
        $rootNode
            ->children()
                // 'definition' => ACLConfigDefinition::class,
                ->scalarNode('definition')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()

                // 'acl' => []
                ->arrayNode('acl')
                    ->children()

                        // 'default_role' => 'guest',
                        ->scalarNode('default_role')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()

                        // 'roles' => [
                        //      'user'  => ['guest'],
                        //  ]
                        ->arrayNode('roles')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()

                        // 'resources' => []
                        ->arrayNode('resources')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                        ->end()

                        // 'guards' => []
                        ->arrayNode('guards')
                            ->children()
                                ->arrayNode(Acl::GUARD_TYPE_RESOURCE)
                                    // ['user', ['admin']]
                                    ->prototype('array')
                                        ->children()
                                            // 'user'
                                            ->scalarNode(0)
                                            ->end()
                                            // ['admin']
                                            ->arrayNode(1)
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()

                                ->arrayNode(Acl::GUARD_TYPE_ROUTE)
                                    // ['route', ['roles'], ['privilege1', 'privilege2']]
                                    ->prototype('array')
                                        ->children()
                                            // 'route'
                                            ->scalarNode(0)
                                            ->end()
                                            // ['roles']
                                            ->arrayNode(1)
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                            // ['privilege1', 'privilege2']
                                            ->arrayNode(2)
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()

                                ->arrayNode(Acl::GUARD_TYPE_CALLABLE)
                                    // ['callable', ['roles']]
                                    ->prototype('array')
                                        ->children()
                                            // 'callable'
                                            ->scalarNode(0)
                                            ->end()
                                            // ['roles']
                                            ->arrayNode(1)
                                                ->prototype('scalar')
                                                ->end()
                                            ->end()
                                        ->end()
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
