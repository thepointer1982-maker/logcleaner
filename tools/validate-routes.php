<?php
/**
 * Validate that every app route points to an existing controller method.
 * This script is intentionally dependency-free so it can run in GitHub Actions
 * without a full Nextcloud checkout.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$routesFile = $root . '/appinfo/routes.php';
$controllerDir = $root . '/lib/Controller';

if (!is_file($routesFile)) {
    fwrite(STDERR, "Missing route file: {$routesFile}\n");
    exit(1);
}

$routes = require $routesFile;
if (!is_array($routes) || !isset($routes['routes']) || !is_array($routes['routes'])) {
    fwrite(STDERR, "Invalid route file shape. Expected ['routes' => [...]]\n");
    exit(1);
}

$failures = [];
$routeKeys = [];

foreach ($routes['routes'] as $route) {
    if (!is_array($route) || !isset($route['name'], $route['url'], $route['verb'])) {
        $failures[] = 'Route entry is missing name, url, or verb.';
        continue;
    }

    $name = (string)$route['name'];
    $url = (string)$route['url'];
    $verb = strtoupper((string)$route['verb']);
    $routeKey = $verb . ' ' . $url . ' -> ' . $name;

    if (isset($routeKeys[$routeKey])) {
        $failures[] = "Duplicate route: {$routeKey}";
    }
    $routeKeys[$routeKey] = true;

    if (!preg_match('/^([A-Za-z0-9_]+)#([A-Za-z0-9_]+)$/', $name, $matches)) {
        $failures[] = "Invalid route name format: {$name}";
        continue;
    }

    $controller = $matches[1];
    $method = $matches[2];
    $controllerFile = $controllerDir . '/' . $controller . 'Controller.php';

    if (!is_file($controllerFile)) {
        $failures[] = "Route {$name} points to missing controller file {$controllerFile}";
        continue;
    }

    $source = file_get_contents($controllerFile);
    if ($source === false) {
        $failures[] = "Cannot read controller file {$controllerFile}";
        continue;
    }

    if (!preg_match('/function\\s+' . preg_quote($method, '/') . '\\s*\\(/', $source)) {
        $failures[] = "Route {$name} points to missing method {$controller}Controller::{$method}()";
    }
}

if ($failures !== []) {
    foreach ($failures as $failure) {
        fwrite(STDERR, $failure . "\n");
    }
    exit(1);
}

fwrite(STDOUT, sprintf("Validated %d routes.\n", count($routes['routes'])));
