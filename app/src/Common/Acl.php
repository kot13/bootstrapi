<?php
namespace App\Common;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource as GenericResource;

final class Acl extends ZendAcl
{
    /**
     * @var string privilege to make POST request to the specified resource
     */
    const PRIVILEGE_POST = 'post';

    /**
     * @var string privilege to mekt GET request to the specified resource
     */
    const PRIVILEGE_GET = 'get';

    /**
     * @var string guard can be applied to resources
     */
    const GUARD_TYPE_RESOURCE = 'resources';

    /**
     * @var string guard can be applied to routes
     */
    const GUARD_TYPE_ROUTE = 'routes';

    /**
     * @var string guard can be applied to callables
     */
    const GUARD_TYPE_CALLABLE = 'callables';

    /**
     * Acl constructor.
     *
     * @param array $configuration ACL configuration - see app settings ACL section
     * @throws \Exception
     */
    public function __construct($configuration)
    {
        // setup roles
        foreach ($configuration['roles'] as $role => $parents) {
            $this->addRole(new GenericRole($role), $parents);
        }

        // setup resources
        if (array_key_exists('resources', $configuration)) {
            foreach ($configuration['resources'] as $resource => $parent) {
                // create resource
                $this->addResource(new GenericResource($resource), $parent);
            }
        }

        // setup guards
        foreach ($configuration['guards'] as $guardType => $guardRules) {
            foreach ($guardRules as $rule) {
                // parse rule into parts
                list($resource, $roles, $privileges) = $this->parseRule($guardType, $rule);

                if ($guardType != static::GUARD_TYPE_RESOURCE) {
                    // resources were already build earlier
                    $resource = static::buildResourceName($guardType, $resource);
                    $this->addResource(new GenericResource($resource));
                }

                // allow access to this resource
                $this->allow($roles, $resource, $privileges);
            }
        }
    }

    /**
     * Verify guard rule correctness
     *
     * @param $guardType
     * @param array $rule
     * @return bool
     * @throws \Exception
     */
    protected function verifyRule($guardType, array $rule)
    {
        switch ($guardType) {
            case static::GUARD_TYPE_RESOURCE:
                // resources guards must have 2 parts

            case static::GUARD_TYPE_CALLABLE:
                // callables guards must have 2 parts
                // 'callables' => [
                //    // resource, roles, privileges
                //    ['App\Controller\CrudController',              ['user']],
                //    ['App\Controller\CrudController:actionIndex',  ['user']],
                if (count($rule) == 2) {
                    // all OK
                    return true;
                }
                break;

            case static::GUARD_TYPE_ROUTE:
                // routes guards must have 3 parts
                // 'routes' => [
                // // resource, roles, privileges
                // ['/api/token', ['guest'], ['post']],
                // ['/api/user',  ['user'],  ['get']],
                if (count($rule) == 3) {
                    // all OK
                    return true;
                }
                break;
        }

        // unknown guard type or incorrect rule structure
        throw new \Exception('Error Processing Request');
    }

    /**
     * Parse rule description into separate parts
     *
     * @param $guardType
     * @param $rule
     * @return array
     * @throws \Exception
     */
    protected function parseRule($guardType, $rule)
    {
        $this->verifyRule($guardType, $rule);

        // resource name is located in $rule[0]
        // roles array is located in $rule[1]
        // privileges are located in $rule[2]
        // return lower-cased array of privileges

        $resource   = $rule[0];
        $roles      = $rule[1];
        $privileges = count($rule) == 3 ? array_map('strtolower', $rule[2]) : null;

        return [$resource, $roles, $privileges];
    }

    /**
     * Build name of the resource based on guard type
     *
     * @param string $guardType guard type
     * @param string $base base part of the resource name to be built
     * @return string ready resource name
     * @throws \Exception
     */
    public static function buildResourceName($guardType, $base)
    {
        switch ($guardType) {
            case static::GUARD_TYPE_CALLABLE:
                return sprintf('callable//%s', $base);

            case static::GUARD_TYPE_ROUTE:
                return sprintf('route//%s', $base);
        }

        // unknown guard type
        throw new \Exception('Error Processing Request');
    }

    /**
     * Get one of PRIVILEGE_XXX constants based on HTTP method - GET, POST, PUT, etc
     *
     * @param string $method HTTP method GET, POST, PUT, etc
     * @return null|string
     */
    public static function getPrivilegeByHTTPMethod($method)
    {
        switch (strtolower($method)) {
            case 'post':
                return static::PRIVILEGE_POST;

            case 'get':
                return static::PRIVILEGE_GET;
        }

        return null;
    }
}
