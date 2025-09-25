<?php

namespace App\Services;



class GeneralServices
{

    public static function checkIfSalaryIsGreaterThan($salary, $amount)
    {
        return $salary > $amount;
    }

    public static  function createPhraseWithNameAndSalary($name, $salary)
    {
        return "O name é -> $name e o salário é R$ $salary";
    }

    public static function getSalaryWithBonus($salary, $porcent_bonus)
    {

        return $salary * (($porcent_bonus + 100) / 100);
    }

    public static function fakeDataInJson()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name' => \Faker\Factory::create()->name(),
                'email' => \Faker\Factory::create()->email(),
                'phone' => \Faker\Factory::create()->phoneNumber(),
                'address' => \Faker\Factory::create()->address(),
            ];
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}