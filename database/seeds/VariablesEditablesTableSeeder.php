<?php

use Illuminate\Database\Seeder;
use App\VariableEditable;

class VariablesEditablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VariableEditable::truncate();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "TRM";
        $variableEditable->valor = 2.980;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Valor Libra";
        $variableEditable->valor = 4.850;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "IVA";
        $variableEditable->valor = 19;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Empaque";
        $variableEditable->valor = 6.980;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Cinta";
        $variableEditable->valor = 1.380;
        $variableEditable->save();

        $variableEditable = new VariableEditable;
        $variableEditable->nombre = "Costo3";
        $variableEditable->valor = 2.980;
        $variableEditable->save();
    }
}
