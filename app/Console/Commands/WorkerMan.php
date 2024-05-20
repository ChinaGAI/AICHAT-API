<?php
namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkerMan extends Command
{

    protected $signature = 'wk {action} {--d}';

    protected $description = 'Start a Workerman server.';

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();

        $workerPath = storage_path('workerman/');
        if (!is_dir($workerPath))
            mkdir($workerPath, 0755, true);
        Worker::$pidFile = $workerPath . config('app.name') . '_workman.pid';
        $logPath = $workerPath . date('Ym') . '/';
        if (!is_dir($logPath))
            mkdir($logPath, 0755, true);
        Worker::$logFile = $logPath . date('d') . '.log';

        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker = new BusinessWorker();
        $worker->name = 'BusinessWorker';
        $worker->count = 3;
        $worker->registerAddress = '127.0.0.1:1236';
        $worker->eventHandler = \App\Events\Workerman::class;

    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name = 'Gateway';
        $gateway->count = 1;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2300;
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData = '{"type":"ping"}';
        $gateway->registerAddress = '127.0.0.1:1236';
    }

    private function startRegister()
    {
        new Register('text://127.0.0.1:1236');
    }
}
