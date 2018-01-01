<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;

/**
 * swoole启动/停止/重启命令
 */
class swoole extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'swoole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole启动/停止/重启命令';

    /**
     * swoole对象
     *
     * @val object
     */
    public $swooleServ = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $arg = $this->argument('option');
        if (!in_array($arg, ['start', 'stop', 'restart'])) {
            $this->info('参数有误! 支持参数: start | stop | restart');
        }

        switch ($arg) {
            case 'start':
                $this->start();
                $this->info('swoole observer start');
        }
    }

    protected function getArguments()
    {
        return [
            ['option', InputArgument::REQUIRED, 'start|stop|restart']
        ];
    }

    /**
     * 启动swoole_http服务
     */
    private function start() {
        $this->swooleServ = new swoole_server('0.0.0.0', 9501);
        $this->swooleServ->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 1000,
            'dispatch_mode' => 2,
            'debug_mode' => 1,
        ]);
        $handler = App::make('handler\swooleHandler');
        $this->swooleServ->on('Start', [$handler, 'onStart']);
        $this->swooleServ->on('Connect', [$handler, 'onConnect']);
        $this->swooleServ->on('Receive', [$handler, 'onReceive']);
        $this->swooleServ->on('Close', [$handler, 'onClose']);
        $this->swooleServ->start();
    }

}