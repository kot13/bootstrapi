<?php
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="hostname",
 *     basePath="/v1",
 *     @SWG\Info(
 *         version="0.0.1",
 *         title="Skeleton JSONAPI",
 *         description="This is skeleton JSONAPI",
 *         @SWG\Contact(
 *             email="apiteam@wordnik.com"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Swagger",
 *         url="http://swagger.io"
 *     )
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="api_key"
 * )
 */
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="petstore_auth",
 *   type="oauth2",
 *   authorizationUrl="http://petstore.swagger.io/api/oauth/dialog",
 *   flow="implicit",
 *   scopes={
 *     "read:pets": "read your pets",
 *     "write:pets": "modify pets in your account"
 *   }
 * )
 */