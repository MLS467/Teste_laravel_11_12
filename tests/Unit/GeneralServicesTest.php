<?php

use App\Services\GeneralServices;


it('check if the salary is greather than a specific amount', function () {
    $salary = 1000;
    $amout = 500;

    $result = GeneralServices::checkIfSalaryIsGreaterThan($salary, $amout);

    expect($result)->toBeTrue();
});

it('check if the salary is not greather than a specific amount', function () {
    $salary = 400;
    $amout = 500;

    $result = GeneralServices::checkIfSalaryIsGreaterThan($salary, $amout);

    expect($result)->toBeFalse();
});

it('tests if the phrase is correctly', function () {
    $nome = "Maisson";
    $salario = 4500;


    $result = GeneralServices::createPhraseWithNameAndSalary($nome, $salario);


    expect($result)->toBe("O name é -> Maisson e o salário é R$ 4500");
})->todo('tem que melhorar');

it('tests if the salary has bonus correctly', function () {
    $salario = 4500;
    $bonus = 10;


    $result = GeneralServices::getSalaryWithBonus($salario, $bonus);


    expect($result)->toBe(4950.0);
});
// })->only();


it('test if json structure is correctly', function () {

    $json = GeneralServices::fakeDataInJson();

    $json_result = json_decode($json, true);

    expect($json_result)->toBeGreaterThan(1);

    expect($json_result[0])->toHaveKeys(['name', 'email', 'phone', 'address']);

    // geralmente os dados vindo de uma api são mais complexos mas o jeito de tratar é o mesmo

    /**
     * ex:
     * 
     * [
     *  'name'=>'john doe',
     *  'age' => 25,
     *  'phones'=> [
     *      'mobile' => [
     *          989889898998,
     *          12312312313213
     *          ],
     *      
     *       'phone' => [
     *              333333333
     *          ] 
     *           ]
     * ]
     * 
     */

    // apenas deve-se navegar nos níveis para testar a estrutura
})->skip('Inativo temporariamente');