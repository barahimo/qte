<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student::select('cin','nom','prenom','age')->get();
        return $students;
    }
    public function headings(): array
    {
        return [
            'cin',
            'nom',
            'prenom',
            'age',
        ];
    }
}