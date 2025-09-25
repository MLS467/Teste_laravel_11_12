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
}