# Laravelキューサンプル
Laravelのキューサンプル

* Laravel 5.5.7

## 内容
ユーザーテーブルには、名前とメールアドレスのみ保持。  
`/users/`にアクセスすると一覧が表示され、上部にユーザー登録の入力欄がある。

入力欄から情報を入力し、登録ボタンを押下すると、登録内容がジョブとしてキューに登録される。

worker dynoにてジョブが処理されるとDBへデータが保存される。

## 必要なadd-ons

* [Heroku Postgres](https://elements.heroku.com/addons/heroku-postgresql)
* [Heroku Redis](https://elements.heroku.com/addons/heroku-redis)

どちらも無料枠で利用可能、だが、Add-onを追加するためにはクレジットカードの登録が必要。  
(勝手に課金されることはない）

## Heroku Worker Dyno
Worker Dynoで動作するコマンドは以下のように設定している。

```
worker: php artisan queue:listen --sleep=60 --delay=60 --timeout=70
```

* `sleep` : キューなしの場合のポーリング間隔は60秒
* `delay` : 失敗キューをリトライするまでに60秒遅延
* `timeout` : 処理がタイムアウトする時間。デフォルトは60

`timeout`を`sleep`と`delay`で設定した時間より多くしておかないとタイムアウトエラーが出てしまうので注意。

キューのリトライ回数はジョブクラス内にて設定。5回までにしている。

```
public $tries = 5;
```

### `queue:listen`コマンドのヘルプ

```
$ php artisan queue:listen -h
Usage:
  queue:listen [options] [--] [<connection>]

Arguments:
  connection               The name of connection

Options:
      --delay[=DELAY]      The number of seconds to delay failed jobs [default: "0"]
      --force              Force the worker to run even in maintenance mode
      --memory[=MEMORY]    The memory limit in megabytes [default: "128"]
      --queue[=QUEUE]      The queue to listen on
      --sleep[=SLEEP]      Number of seconds to sleep when no job is available [default: "3"]
      --timeout[=TIMEOUT]  The number of seconds a child process can run [default: "60"]
      --tries[=TRIES]      Number of times to attempt a job before logging it failed [default: "0"]
  -h, --help               Display this help message
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi               Force ANSI output
      --no-ansi            Disable ANSI output
  -n, --no-interaction     Do not ask any interactive question
      --env[=ENV]          The environment the command should run under
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Listen to a given queue
```

## Dynoについて
Herokuのアプリ起動後、Worker DynoのDyno数を1以上にするとWorkerが起動した状態となる。  
web dynoとworker dynoが常に起動した状態となるので注意。

無料で利用出来るdynoは、1アカウントにつき(アプリにつき、ではない）、

* 550時間
* クレジットカードを登録していればさらに450時間追加

となり、最大1000時間まで、となっている。

[Free Dyno Hours](https://devcenter.heroku.com/articles/free-dyno-hours)
  
もしwebとworker dynoを1つずつ1ヶ月使い続けたとすると、

* web dyno 24x30=720h
* worker dyno 24x30=720h
* 合計 = 1440h

となり、無料枠を越えてしまうので注意が必要。

# Deploy
## Heroku Button
[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

## セットアップ
環境変数はそのままで問題ないが、`APP_KEY`のみ、再生成しておいた方がよい。  
Heroku CLIが入っているなら以下のコマンドで直接Herokuの環境変数に設定可能。

```
$ heroku config:set APP_KEY=$(php artisan --no-ansi key:generate --show -a {your-app-name}
```

## マイグレーション
deployが完了後、ドキュメントルートへアクセスするとLaravelのロゴが表示されるとdeploy成功。  
必要なユーザーとキュー失敗時に保存するテーブルをDBに作成するためにマイグレーションを行う。

```
$ heroku run 'php artisan migrate' -a {your-app-name}
```

`/users`にアクセスすると空の一覧とフォームが表示されたら成功。

## Worker Dyno
キューを実行するためにアプリのダッシュボードの、ResourcesからworkerのDynoをオンにする。  
もしくはコマンドの場合は以下。

```
$ heroku ps:scale worker=1 -a {your-app-name}
```

