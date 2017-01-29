<?php
namespace App\Common;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource as GenericResource;

final class Acl extends ZendAcl
{
    public function __construct($configuration)
    {
        // setup roles
        foreach ($configuration['roles'] as $role => $parents) {
            $this->addRole(new GenericRole($role), $parents);
        }
        // setup resources
        if (array_key_exists('resources', $configuration)) {
            foreach ($configuration['resources'] as $resource => $parent) {
                $this->addResource(new GenericResource($resource), $parent);
            }
        }
        foreach ($configuration['guards'] as $guardType => $guardRules) {
            if (!in_array($guardType, ['resources', 'routes', 'callables'])) {
                throw new \Exception('Error Processing Request');
            }
            foreach ($guardRules as $rule) {
                if ('callables' === $guardType && 2 !== count($rule)) {
                    throw new \Exception('Error Processing Request');
                }
                if ('callables' !== $guardType && 3 !== count($rule)) {
                    if (('resources' === $guardType && 2 !== count($rule)) || 'routes' === $guardType) {
                        throw new \Exception('Error Processing Request');
                    } else {
                        $rule[] = null;
                    }
                }
                list($resource, $roles) = $rule;
                $privileges = (3 === count($rule) ? $rule[2] : null);
                if ('callables' === $guardType) {
                    $resource = 'callable/'.$resource;
                    $this->addResource(new GenericResource($resource));
                } elseif ('routes' === $guardType) {
                    $resource = 'route'.$resource;
                    $this->addResource(new GenericResource($resource));
                    $privileges = array_map('strtolower', $privileges);
                }
                $this->allow($roles, $resource, $privileges);
            }
        }
    }
}
