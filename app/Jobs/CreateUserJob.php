<?php

namespace App\Jobs;

use App\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CreateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 最大試行回数
     * @var int
     */
    public $tries = 5;

    protected $name;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct(Request $request)
    {
        //ユーザー情報を受け取る
        $user_data = $request->all();
        $this->name = $user_data['name'];
        $this->email = $user_data['email'];
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //乱数を生成し1の場合だけ作成しそれ以外はエラーとする
        $r = rand(0, 5);
        if($r === 1) {
            //ユーザーデータの作成
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();
        }else{
            //失敗
            throw new Exception('random int = ' . $r);
        }
    }

    /**
     * 失敗したジョブの処理
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // 失敗の通知をユーザーへ送るなど…
    }
}
