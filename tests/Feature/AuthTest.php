<?php

use App\Models\User;

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


it('testing if an admin user can login with success', function () {

    addAdminUser();

    // testando se faz o login
    $result = $this->post(
        '/login',
        [
            'email' => 'admin@rhmangnt.com',
            'password' => 'Aa123456'
        ]
    );

    // testando se houve redirecionamento cod 302 redirect
    expect($result->status())->toBe(302);

    // testando se o redirecionamento chegou home com cod 200 ok
    expect($result->assertRedirect('/home'));
});


it('testing if an rh user can login in Admin route', function () {

    addRHUser();

    // testando se faz o login
    $result = $this->post(
        '/login',
        [
            'email' => 'admin1@rhmangnt.com',
            'password' => 'Aa123456'
        ]
    );

    // testando se houve redirecionamento cod 302 redirect
    expect($result->status())->toBe(302);

    // todas as rotas após login são redirecionadas para o home
    expect($result->assertRedirect('/home'));

    // testando se o redirecionamento chegou home com cod 200 ok
    expect($this->get('rh-users/management/home')->status())->toBe(200);
});


it('Testing if a collaborator can go to the home route.', function () {

    addCollaborator();


    $result = $this->post('/login', [
        'email' => 'worker@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    expect($result->status())->toBe(302);

    expect($result->assertRedirect('home'));

    expect($this->get('rh-users/management/home')->status())->not()->toBe(200);
});






function addCollaborator()
{
    User::insert([
        'department_id' => 1,
        'name' => 'collaborator',
        'email' => 'worker@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'collaborator',
        'permissions' => '["colaborator"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


function addAdminUser()
{
    // cria o usuário no banco de dados em memória
    User::insert([
        'department_id' => 1,
        'name' => 'Administrador',
        'email' => 'admin@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'admin',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addRHUser()
{
    // cria o usuário no banco de dados em memória
    User::insert([
        'department_id' => 2,
        'name' => 'Administrador',
        'email' => 'admin1@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'rh',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}