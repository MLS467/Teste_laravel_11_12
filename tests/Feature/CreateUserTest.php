<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Carbon;

it('tests if an admin can insert a new RH user', function () {

    // cria o usuario administrador
    addAdminUser();

    // criar os departamentos
    addDepartment('Administração');
    addDepartment('Recursos Humanos');

    // login com admin
    $result = $this->post(
        '/login',
        [
            'email' => 'admin@rhmangnt.com',
            'password' => 'Aa123456'
        ]
    );

    expect($result->status())->toBe(302);

    expect($result->assertRedirect('/home'));


    // verifica se o admin consegue adicionar um user de rh
    $value = $this->post('/rh-users/create-colaborator', [
        'name' => 'RH USER 1',
        'email' => 'rhuser55@gmail.com',
        'select_department' => 2,
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => '123-City',
        'phone' => '123123123',
        'salary' => '1000.00',
        'admission_date' => '2021-01-01',
        'role' => 'rh',
        'permissions' => '["rh"]'
    ]);


    // verificar se o user rh foi inserido com sucesso
    $this->assertDatabaseHas('users', [
        'name' => 'RH USER 1',
        'email' => 'rhuser55@gmail.com',
        'role' => 'rh'
    ]);
});





it('test if a RH user can to insert an user', function () {

    // departamento
    addDepartment('Administração');
    addDepartment('Recursos Humanos');
    addDepartment('teste3');

    // adicionar um rh user
    addRHUser();

    // fazer o login de rh user
    $result = $this->post(
        '/login',
        [
            'email' => 'admin1@rhmangnt.com',
            'password' => 'Aa123456'
        ]
    );

    expect(auth()->user()->role)->toBe('rh');

    // inserir um novo colaborador
    $this->post('/rh-users/management/create-colaborator', [
        'name' => 'colaborator USER 1',
        'email' => 'colaboratoruser5@gmail.com',
        'select_department' => 3,
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => '123-City',
        'phone' => '123123123',
        'salary' => '1000.00',
        'admission_date' => '2021-01-01',
        'role' => 'colaborator',
        'permissions' => '["colaborator"]'
    ]);


    // verificar se está no banco de dados PHPUnit
    // $this->assertDatabaseHas('users', [
    //     'name' => 'colaborator USER 1',
    //     'email' => 'colaboratoruser5@gmail.com',
    //     'role' => 'colaborator',
    // ]);

    $values_where = [
        ['email', '=', 'colaboratoruser5@gmail.com'],
        ['name', '=', 'colaborator USER 1'],
        ['role', '=', 'colaborator']
    ];

    // usando pestPHP
    expect(User::where($values_where)->exists())->toBeTrue();
});



function addDepartment($name)
{
    Department::insert([
        "name" => $name,
        "created_at" => Carbon::now(),
        "updated_at" => Carbon::now(),
    ]);
}