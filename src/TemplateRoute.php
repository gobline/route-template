<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router;

use Psr\Http\Message\ServerRequestInterface;
use Gobline\Router\RouteData;
use Gobline\Router\AbstractRoute;
use Gobline\Router\Exception\NoMatchingRouteException;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class TemplateRoute extends AbstractRoute
{
    protected $routePath;
    protected $templateDir;
    protected $templateExtension;

    public function __construct($routePath, $templateDir, $templateExtension = '.html.php')
    {
        $this->routePath = rtrim($routePath, '/');
        $this->templateDir = rtrim($templateDir, '/').'/';
        $this->templateExtension = $templateExtension;

        $this->allows(['GET']);
    }

    public function match(ServerRequestInterface $request)
    {
        if (!$this->startsWith($request->getUri()->getPath(), $this->routePath)) {
            return false;
        }

        $template = trim(substr($request->getUri()->getPath(), strlen($this->routePath)), '/');

        if (!$template) {
            $template = 'index';
        }

        $template = $this->templateDir.$template.$this->templateExtension;

        if (!is_file($template)) {
            throw new NoMatchingRouteException('No matching route for request "'.$request->getUri()->getPath().'"');
        }

        return new RouteData($this->name, 
            array_merge(['_view' => ['text/html' => $template]], $this->values), $this->handler);
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $params = $routeData->getParams();
        $template = reset($params);

        return $this->routePath.'/'.$template;
    }

    private function startsWith($haystack, $needle)
    {
        return $needle === '' || strpos($haystack, $needle) === 0;
    }
}
