<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\Provider\Gobline;

use Gobline\Router\TemplateRoute;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class TemplateRouteFactory
{
    public function __invoke(array $data)
    {
        $routePath = $data['routePath'];
        $templateDir = $data['templateDir'];
        $templateExtension = $data['templateExtension'];
        $name = !empty($data['name']) ? $data['name'] : null;
        $values = !empty($data['values']) ? $data['values'] : [];
        $allows = !empty($data['allows']) ? $data['allows'] : [];
        $allows = is_array($allows) ? $allows : [$allows];

        $route = new TemplateRoute($routePath, $templateDir, $templateExtension);

        if ($name) {
            $route->setName($name);
        }

        $route->values($values)
              ->allows($allows);

        return $route;
    }
}
