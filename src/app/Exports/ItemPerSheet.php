<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;

class ItemPerSheet implements FromView,
    \Maatwebsite\Excel\Concerns\WithEvents, 
    WithTitle
{
    protected $row;
    protected $template;
    protected $title;

    public function __construct(string $template, string $title, array $row)
    {
        $this->template = $template;
        $this->title = $title;
        $this->row = $row;

        $this->paperSize = $row['paperSize'] == 'A4' ? \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4 : \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
        $this->orientation = $row['orientation'] == 'landscape' ? \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE : \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize($this->paperSize);
                $event->sheet->getPageSetup()->setOrientation($this->orientation);

                $event->sheet->getPageMargins()->setTop(0.25);
                $event->sheet->getPageMargins()->setRight(0.25);
                $event->sheet->getPageMargins()->setLeft(0.25);
                $event->sheet->getPageMargins()->setBottom(0.25);
                // $event->sheet->getColumnDimension('A')->setWidth(10);
                // $event->sheet->getRowDimension('1')->setRowHeight(20);
                /*$event->sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                    )
                ));*/
                // $event->sheet->setAllBorders('thin');
                foreach ($event->sheet->getColumnIterator() as $column) {
                    // if ($column->getColumnIndex() == 'A') {
                    //     $event->sheet->getColumnDimension('A')->setAutoSize(false);
                    // } else {
                        $event->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                    // }
                }
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        return view($this->template, $this->row);
    }
}