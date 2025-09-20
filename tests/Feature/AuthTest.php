<?php


it('display the login page when not logget in', function () {

    // testando se houve redirecionamento pra rota "/login".
    $result = $this->get('/')->assertRedirect("/login");

    // testando se o status é 302
    expect($result->status())->toBe(302);

    // tentado se a rota de login está certa e retorna status 200
    expect($this->get('/login')->status())->toBe(200);

    // verifica se existe o texto de "Esqueceu a sua senha?" na tela
    expect($this->get('/login')->content())->toContain("Esqueceu a sua senha?");
});



it("Forgot password", function () {
    $result = $this->get('/forgot-password');

    expect($result->status())->toBe(200);

    expect($result->content())->toContain("Já sei a minha senha?");
});