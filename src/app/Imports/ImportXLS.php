<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\FirstSheetImport;

class ImportXLS implements ToModel, WithMultipleSheets
{
	public function model(array $row)
    {
        return new User([
           'name'     => $row[0],
           'email'    => $row[1],
           'password' => Hash::make($row[2]),
        ]);
    }

    public function sheets(): array {
        return [
            0 => new FirstSheetImport(),
        ];
    }
}