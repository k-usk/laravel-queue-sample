<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ユーザー一覧</title>

</head>
<body>

<div style="margin: 20px 0 40px 10px;">
    {!! Form::open(array('url' => URL::to('/users', [], false))) !!}
    <table border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td>{!! Form::label('name', '名前') !!}</td>
            <td>{!! Form::text('name') !!}</td>
        </tr>
        <tr>
            <td>{!! Form::label('email', 'メールアドレス') !!}</td>
            <td>{!! Form::email('email') !!}</td>
        </tr>
        <tr>
            <td align="center" colspan="2">{!! Form::submit('ユーザーの追加') !!}</td>
        </tr>
    </table>
    {!! Form::close() !!}
</div>

<table border="1" bordercolor="#666" cellpadding="8" cellspacing="0">
    <tr>
        <td>ID</td>
        <td>名前</td>
        <td>メールアドレス</td>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>