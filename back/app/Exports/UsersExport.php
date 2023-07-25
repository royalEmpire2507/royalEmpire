<?php

namespace App\Exports;

use App\Models\ContactCRM;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return ContactCRM::all();
    }
}