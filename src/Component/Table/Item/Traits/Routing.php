<?php
namespace App\Component\Table\Item\Traits;

use App\Component\Table\Exceptions\ParseException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait Routing
{
    private $router;
    private $route;
    private $param;
    private $url;

    public function generateUrl(): void
    {
        try{
            if (!$this->router instanceof UrlGeneratorInterface) {
                throw new ParseException("Column name: {$this->columnName}. Router problem!");
            }

            $this->url = $this->router->generate($this->route, $this->param);

        }  catch(ParseException $exception) {
            $this->addError($exception->getMessage());
        } catch (RouteNotFoundException $exception) {
            $this->addError($exception->getMessage());
        } catch (MissingMandatoryParametersException $exception) {
            $this->addError($exception->getMessage());
        }
    }

    public static function getBaseRoute($controllerFullName)
    {
        $name = $controllerFullName;

        if ($name) {
            if ($name::ROUTE_BASE !== null) {
                $name = $name::ROUTE_BASE;
            } else {
                $name = str_replace('Controller', '', $name);
                $position = strrpos($name, '\\');
                if ($position !== false) {
                    $name = (substr($name, ++$position));
                }
                $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
            }
        }

        return $name;
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getRouter(): UrlGeneratorInterface
    {
        return $this->router;
    }

    /**
     * @param UrlGeneratorInterface $router
     */
    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }

    /**
     * @param array $route
     */
    public function setRouting(array $route)
    {
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }

    /**
     * @param mixed $param
     *
     * @throws ParseException
     */
    public function setParam($param)
    {
        $this->param = [];
        if (empty($param)) {
        } elseif (is_string($param)) {
            $this->param[$param] = "@@{$param}@@";
        } elseif (is_array($this->param)) {
            foreach ($param as $paramKey => $paramValue) {
                if (is_int($paramKey)) {
                    $this->param[$paramValue] = "@@{$paramValue}@@";
                } elseif (is_string($paramKey)) {
                    $this->param[$paramKey] = "@@{$paramValue}@@";
                } else {
                    throw new ParseException("Column name: {$this->columnName}. Route param problem!");
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}