<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/17/2019
 * Time: 16:36
 */

namespace app\controller;

use app\model\Config;
use app\model\Products;
use think\Controller;
use think\Exception;
use think\Request;
use think\Session;
use think\Url;

class Admin extends Controller
{
    /*** @var \app\model\Admin */
    protected $loginUser = null;

    public function __construct(Request $request = null) {
        parent::__construct($request);
    }

    private function checkAuth(Request $request) {
        if (!$request->session('isAdminLogin') || !$request->session('adminUserId')) {
            header('Location:' . Url::build('admin/login'));
            exit(403);
        }
        $this->loginUser = \app\model\Admin::get($request->session('adminUserId'));
        $this->assign('loginUser', $this->loginUser);
    }

    public function index(Request $request) {
        $this->checkAuth($request);
        $products = Products::all();
        $this->assign("products", $products);
        return $this->fetch();
    }

    // http://query.suezwts.cn/login
    public function login() {
        return $this->fetch();
    }

    public function xhrLogin(Request $request) {
        $account = $request->post('login_name');
        $password = $request->post('pwd');
        $admin = \app\model\Admin::findByAccount($account);
        if (empty($admin)) {
            return [
                'code' => 403,
                'data' => [],
                'msg' => '管理员密码错误'
            ];
        }
        if (!$admin->isValidPwd($password)) {
            return [
                'code' => 403,
                'data' => [],
                'msg' => '管理员密码错误'
            ];
        }
        if (!$admin->isEnabled()) {
            return [
                'code' => 403,
                'data' => [],
                'msg' => '账号未启用'
            ];
        }

        // $admin->getId();
        Session::set("isAdminLogin", 1);
        Session::set("adminUserId", $admin->getId());

        // 记录退出登录之前的url
        $server = $request->server();
        $referrer = $server['HTTP_REFERER'];
        $pos = strpos($referrer, '?');
        if ($pos!==false) {
            $jump = substr($referrer, strpos($referrer, '?') + strlen("?referrer="));
            $jump = urldecode($jump);
        } else {
            $jump = Url::build('index');
        }
        return [
            'code' => 200,
            'data' => $jump,
            'msg' => '登录成功'
        ];
    }

    public function logout(Request $request) {
        $server = $request->server();
        $referrer = urlencode($server['HTTP_REFERER']);

        Session::delete("isAdminLogin");
        Session::delete("adminUserId");

        echo "<script>window.location.href=\"login?referrer=".$referrer."\"</script>";
    }

    /**
     * 管理员用户列表
     * @param Request $request
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function user_list(Request $request) {
        $this->checkAuth($request);

        $users = \app\model\Admin::all();
        $this->assign('users', $users);
        $this->assign("loginUserId", $request->session("adminUserId"));

        return $this->fetch();
    }

    /**
     * 编辑用户页面
     * @param Request $request
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function user_edit(Request $request) {
        $this->checkAuth($request);

        if ($request->isPost()) {
            $id = $request->post('user_id');
            $account = $request->post('login_name');
            $oPwd = $request->post('original_password');
            $pwd = $request->post('password');
            /** @var $user \app\model\Admin */
            $user = \app\model\Admin::get($id);
            if ($user) {
                if (!$user->isValidPwd($oPwd)) {
                    return [
                        'code' => 403,
                        'data' => $user,
                        'msg' => '原始密码错误'
                    ];
                }
                $user->setPassword($pwd);
                return [
                    'code' => 200,
                    'data' => $user,
                    'msg' => '用户密码修改成功'
                ];
            }
            // new User
            try {
                $user = new \app\model\Admin();
                $user->setAccount($account);
                $user->setPassword($pwd);
                $user->setEnabled(1);
                $user->save();
                return [
                    'code' => 200,
                    'data' => $user,
                    'msg' => '新增用户成功'
                ];
            } catch (Exception $e) {
                return [
                    'code' => 500,
                    'data' => null,
                    'msg' => $e->getTraceAsString()
                ];
            }
        }
        $id = $request->get('key');
        if ($id === "new") {
            $user = new \app\model\Admin();
            $user->setAccount('');
            $user->setPassword('');
        } else {
            $user = \app\model\Admin::get($id);
        }
        $this->assign('key', $id);
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function user_enable(Request $request) {
        $this->checkAuth($request);
        $user_id = $request->post('user_id');
        $enable = $request->post('enable');
        if (strcmp($enable, "禁用")===0 || strcmp($enable, "0")===0) {
            $enabled = 0;
        } else {
            $enabled = 1;
        }
        /** @var $user \app\model\Admin */
        $user  = \app\model\Admin::get($user_id);
        if (!$user) {
            return [
                'code' => 404,
                'data' => null,
                'msg' => '用户不存在'
            ];
        }
        $user->enabled = $enabled;
        $ret = \app\model\Admin::where("id", "=", $user_id)->update([
            "enabled" => $enabled
        ]);
        if (!$ret) {
            return [
                'code' => 500,
                'data' => null,
                'msg' => $user->getError()
            ];
        }
        return [
            'code' => 200,
            'data' => $user,
            'msg' => 'Success'
        ];
    }

    public function user_delete(Request $request) {
        $this->checkAuth($request);
        $userId  = $request->post("user_id");

        if ($this->loginUser->getId() == $userId) {
            return [
                'code' => 403,
                'data' => null,
                'msg' => '不能删除当前用户'
            ];
        }
        $ret = \app\model\Admin::destroy($userId);
        if (!$ret) {
            return [
                'code' => 500,
                'data' => null,
                'msg' => '删除失败'
            ];
        }

        return [
            'code' => 200,
            'data' => $ret,
            'msg' => '删除成功'
        ];
    }

    public function config(Request $request) {
        $this->checkAuth($request);
        $this->assign('email_server', Config::getByName('email_server'));
        $this->assign('email_account', Config::getByName('email_account'));
        $this->assign('email_password', Config::getByName('email_password'));
        $this->assign('sender_email', Config::getByName('sender_email'));
        $this->assign('qr_prefix', Config::getByName('qr_prefix'));

        return $this->fetch();
    }

    public function xhrConfig(Request $request) {
        $this->checkAuth($request);

        $post = $request->post();
        $ans = [];
        foreach ($post as $name => $value) {
            $ans[$name] = Config::setPair($name, $value);
        }
        $cnt = 0;
        foreach ($ans as $name => $value) {
            $cnt += $value;
        }
        if ($cnt < 1) {
            return [
                'code' => 500,
                'data'=> [],
                'msg' => '配置更新失败'
            ];
        }
        return [
            'code' => 200,
            'data' => $ans,
            'msg' => '配置更新成功'
        ];

    }


    /**
     * 新增/编辑产品
     * @param Request $request
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function product_edit(Request $request) {
        $this->checkAuth($request);

        if ($request->isPost()) {
            $id = $request->post('product_id');
            $batchNumber = $request->post('batch_number');
            $mm6 = $request->post('mm6');
            $mm7 = $request->post('mm7');
            $language = $request->post('language');
            $weight = $request->post('weight');

            $product = Products::get($id);
            if ($product) {
                Products::where("id", "=", $id)->update([
                    'batch_number' => $batchNumber,
                    'mm6' => $mm6,
                    'mm7' => $mm7,
                    'language' => $language,
                    'weight' => $weight
                ]);
                // update
                return [
                    'code' => 200,
                    'data' => $product,
                    'msg' => '产品修改成功'
                ];
            }
            // new product
            try {
                $product = new Products();
                $product->setBatchNumber($batchNumber);
                $product->set6mm($mm6);
                $product->set7mm($mm7);
                $product->setLanguage($language);
                $product->setWeight($weight);
                $product->save();

                return [
                    'code' => 200,
                    'data' => $product,
                    'msg' => '新增产品成功'
                ];
            } catch (Exception $e) {
                return [
                    'code' => 500,
                    'data' => null,
                    'msg' => $e->getTraceAsString()
                ];
            }
        }

        $id = $request->get('key');
        if ($id === "new") {
            $product = Products::emptyInstance();
        } else {
            $product = Products::get($id);
        }
        $this->assign('product', $product);

        return $this->fetch();
    }


}