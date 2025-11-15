protected $routeMiddleware = [
    // ...
    'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,
];
