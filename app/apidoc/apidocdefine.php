<?php
/**
 * @apiDefine UnauthorizedError
 *
 * @apiSuccessExample {json} Не авторизован (401)
 *     HTTP/1.1 401 Unauthorized
 *     {
 *       "errors": [
 *         {
 *           "status": "401",
 *           "code": "401",
 *           "title": "Not authorized",
 *           "detail": "The user must be authorized"
 *         }
 *       ]
 *     }
 */

/**
 * @apiDefine StandardErrors
 *
 * @apiSuccessExample {json} Неверный запрос (400)
 *     HTTP/1.1 400 Unauthorized
 *     {
 *       "errors": [
 *         {
 *           "id": "user",
 *           "status": "400",
 *           "code": "400",
 *           "title": "Invalid Attribute",
 *           "detail": "Not required attributes - data."
 *         }
 *       ]
 *     }
 * @apiSuccessExample {json} Ошибка сервера (500)
 *     HTTP/1.1 500 Internal Server Error
 *     {
 *       "errors": [
 *         {
 *           "status": "500",
 *           "code": "500",
 *           "title": "Internal server error",
 *           "detail": "Internal server error"
 *         }
 *       ]
 *     }
 */

/**
 * @apiDefine NotFoundError
 *
 * @apiSuccessExample {json} Не найдено (404)
 *     HTTP/1.1 404 Not found
 *     {
 *       "errors": [
 *         {
 *           "status": "404",
 *            "code": "404",
 *            "title": "Not found",
 *           "detail": "Entities not found"
 *         }
 *       ]
 *     }
 */

/**
 * @apiDefine Filter
 *
 *
 */

/**
 * @apiDefine user Необходима авторизация
 * Эта опция доступна только авторизованному пользователю с ролью user.
 * Необходимо отправить HTTP заголовок Authorization
 */

/**
 * @apiDefine admin Необходима авторизация
 * Эта опция доступна только авторизованному пользователю с ролью admin.
 * Необходимо отправить HTTP заголовок Authorization
 */
