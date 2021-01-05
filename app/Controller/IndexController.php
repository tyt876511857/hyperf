<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Amqp\Producer\DemoProducer;
use App\Helper\Log;
use App\Middleware\Auth\FooMiddleware;
use App\Exception\BusinessException;
use App\Model\Role;
use Hyperf\Amqp\Producer;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\ApplicationContext;

/**
 * @Controller()
 */
class IndexController extends AbstractController
{
    /**
     * @Inject()
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;


    /**
     * @RequestMapping(path="login", methods="post")
     *
     * @return array
     * @author tyt
     */
    public function login()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        $this->session->set('user_info',[
            'user' => $user,
            'method' => $method,
        ]);

        return [
            'status' => 1,
            'data' => []
        ];
    }

    /**
     * @RequestMapping(path="logout", methods="post")
     *
     * @return array
     * @author tyt
     */
    public function logOut()
    {
        $this->session->clear();
        return [
            'status' => 1,
            'data' => []
        ];
    }

    /**
     * @RequestMapping(path="/", methods="get,post")
     * @Middleware(FooMiddleware::class)
     */
    public function index()
    {
        $data = Role::paginate(10,['role_name','role_id']);
        $data['name'] = $this->request->getAttribute('name');

        return [
            'status' => 1,
            'data' => $data
        ];
    }

    /**
     * @RequestMapping(path="save", methods="get,post")
     * @Middleware(FooMiddleware::class)
     */
    public function save()
    {
        $roleName = $this->request->input('role_name', 'Hyperf');
        $roleId = $this->request->input('role_id', 0);
        $object = Role::findOrNew($roleId);
        $object->role_name = $roleName;
        if ($roleId) {
            $object->update_time = time();
        } else {
            $object->create_time = time();
        }
        if (!$object->save()) {
            throw new BusinessException(100);
        }
        $pushArray = [
            'id' => $roleId,
            'data' => Role::where('role_id', $roleId)->first()->toArray()
        ];
        Log::get('log')->info('保存用户信息消息推送', $pushArray);
        $message = new DemoProducer($pushArray);
        $producer = ApplicationContext::getContainer()->get(Producer::class);
        $producer->produce($message);

        return [
            'status' => 1,
            'data' => $object->role_id,
        ];
    }

    /**
     * @RequestMapping(path="delete", methods="get,post")
     */
    public function delete()
    {
        $roleId = $this->request->input('role_id', 0);
        Role::query()->where('role_id', $roleId)->delete();

        return [
            'status' => 1,
            'data' => []
        ];
    }
}
