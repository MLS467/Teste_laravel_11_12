<?php

it('testing if has access in a protected screen', function () {

    // criar o admin
    addAdminUser();

    // fazer o login como admin
    auth()->loginUsingId(1);

    // testar se tem acesso à rota
    expect($this->get('/rh-users')->status())->toBe(200);
});


it('test if user is not logged can access home page', function () {

    //testando com a espera negativa
    // expect($this->get('/home')->status())->not()->toBe(200);

    // testando com a espera correta
    expect($this->get('/home')->status())->toBe(302);
});