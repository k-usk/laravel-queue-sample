<?php

namespace App\Http\Controllers;

use App\Jobs\CreateUserJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * ユーザー一覧
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    /**
     * キューに追加する
     */
    public function add_queue(Request $request)
    {
        //バリデーションチェックがあればする

        //ジョブをキューへ追加
        CreateUserJob::dispatch($request);
        //1o分後に起動するキューを追加する場合
//        CreateUserJob::dispatch($request)->delay(Carbon::now()->addMinute(10));

        //キューへ追加した事を知らせる
        return view('queue_completed');
    }
}
