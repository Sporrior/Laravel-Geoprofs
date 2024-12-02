<?php

namespace App\Exports;

use App\Models\VerlofAanvragen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerlofaanvragenExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    public function collection()
    {
        // Retrieve all leave requests with related user and type
        return VerlofAanvragen::with('user', 'type')->get();
    }

    /**
     * Map data for each row
     */
    public function map($verlofAanvraag): array
    {
        return [
            $verlofAanvraag->id,
            $verlofAanvraag->user->voornaam . ' ' . $verlofAanvraag->user->achternaam,
            $verlofAanvraag->verlof_reden,
            $verlofAanvraag->start_datum->format('d-m-Y'),
            $verlofAanvraag->eind_datum->format('d-m-Y'),
            $verlofAanvraag->status === null ? 'Pending' : ($verlofAanvraag->status == 1 ? 'Approved' : 'Rejected'),
            $verlofAanvraag->type->type ?? 'N/A',
            $verlofAanvraag->weigerreden ?? '',
            $verlofAanvraag->created_at->format('d-m-Y H:i:s'),
        ];
    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Gebruiker',
            'Reden',
            'Start Datum',
            'Eind Datum',
            'Status',
            'Type Verlof',
            'Weigerreden',
            'Aanvraag Datum',
        ];
    }

    /**
     * Style the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the first row as bold text.
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    }
}
