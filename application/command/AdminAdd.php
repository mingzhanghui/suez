<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 12/18/2019
 * Time: 11:00
 */

namespace app\command;


use app\model\Admin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class AdminAdd extends Command
{
    protected function configure() {
        parent::configure();
        $this->setName('adminadd')
            ->addArgument('account', Argument::REQUIRED, "管理员账号")
            ->addArgument("password", Argument::REQUIRED, "管理员密码")
            ->setDescription("创建管理员账号密码");
    }

    /**
     * php artisan adminadd admin admin
     * @param Input $input
     * @param Output $output
     * @return int|null|void
     */
    protected function execute(Input $input, Output $output) {
        $account = trim($input->getArgument("account"));
        $password = trim($input->getArgument("password"));

        $admin = Admin::findByAccount($account);
        if (is_null($admin)) {
            $admin = new Admin();
            $admin->setAccount($account);
            $admin->setPassword($password);
            $ret = $admin->save();
        } else {
            $ret = Admin::updatePwdByAccount($account, $password);
        }
        if ($ret != 1) {
            $output->write(sprintf("创建失败: %s\n", $admin->getError()));
            return;
        }
        $output->write(sprintf("创建管理员账号成功!\n账号:%s\n密码:%s\n", $account,$password));
    }
}