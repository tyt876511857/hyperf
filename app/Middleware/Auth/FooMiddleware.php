<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Di\Annotation\Inject;



class FooMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    protected $session;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $this->session->get('user_info');
        if (!$session) {
            return $this->response->json(
                [
                    'code' => -1,
                    'data' => [
                        'error' => '中间件验证token无效，阻止继续向下执行111',
                    ],
                ]
            );
        }
        // $request 和 $response 为修改后的对象
        $request = $request->withAttribute('name', $session['user']);
        $request = $request->withAttribute('id', $session['method']);

        \Hyperf\Utils\Context::set(ServerRequestInterface::class, $request);
        return $handler->handle($request);
    }
}