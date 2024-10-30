<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <p>Olá,</p>
    <p>Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.</p>
    <p>Para redefinir sua senha, clique no link abaixo:</p>
    <a href="{{ $url }}">{{ $url }}</a>
    <p>Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.</p>
    <p>Obrigado,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
