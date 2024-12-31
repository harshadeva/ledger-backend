<?php

namespace App;

trait TestingTrait
{
    private function getGenericErrorStructure()
    {
        return ["error" => ["error_message"]];
    }

    private function getErrorStructure($fields)
    {
        return ["error" => $fields];
    }
}
