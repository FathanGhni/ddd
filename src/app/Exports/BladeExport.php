<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\ItemPerSheet;

class BladeExport implements FromView, 
    WithMultipleSheets
{
    use Exportable;
    private $data;


    public function __construct($data)
    {
        $this->data = $data;
        
        $this->marginTop = isset($data['marginTop']) ? $data['marginTop'] : 5;
        $this->marginRight = isset($data['marginRight']) ? $data['marginRight'] : 5;
        $this->marginBottom = isset($data['marginBottom']) ? $data['marginBottom'] : 5;
        $this->marginLeft = isset($data['marginLeft']) ? $data['marginLeft'] : 5;

        $this->headers = (empty($data['data']['headers'])?null:$data['data']['headers']);
        $this->userDetail = (empty($data['data']['userDetail'])?[]:$data['data']['userDetail']);
        $this->downloadFile = (empty($this->data['downloadFile'])?'pdf':($this->data['downloadFile']));

        $this->paperSize = $this->data['paperSize'] == 'A4' ? \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4 : \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_B5;
        $this->orientation = $this->data['orientation'] == 'landscape' ? \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE : \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
        $this->mpdf=null;
        if($this->downloadFile=='pdf'){
            $orientation = ($this->data['orientation']=='landscape'?'L':'P');
            $this->mpdf = new \Mpdf\Mpdf([
                'tempDir' => storage_path('tempdir'),
                'mode' => 'utf-8', 
                'format' => ($this->data['paperSize']?$this->data['paperSize']:$this->paperSize).'-'.$orientation, 
                'orientation' => $orientation,
                'displayDefaultOrientation' => true,
                'margin_left' => $this->marginLeft,
                'margin_right' => $this->marginRight,
                'margin_top' => $this->marginTop,
                'margin_bottom' => $this->marginBottom,
            ]);
        }
    }

    /**
     * @return array
     * 
     * $result[] = ['sheet_name'=> Sheet1, 'list'=> []]
     */
    public function sheets(): array
    {
        $sheets = [];
        $this->data['all'] = $this->data;
        if (isset($this->data['data']['multiplesheet'])) {
            $result = $this->data['data']['result'];
            $sheetObj = $this->data;
            foreach ($result as $key => $value) {
                $sheetObj['sheet'] = $value;
                if ($value['sheet_name']) {
                    $sheets[] = new ItemPerSheet($this->data['template'], $value['sheet_name'], $sheetObj);
                }
            }
        } else {
            $sheets[] = new ItemPerSheet($this->data['template'], 'Worksheet', $this->data);
        }
        return $sheets;
    }

    public function view($returnType = null): View
    {
        if ($returnType == 'html') {
            $this->data['data']['logo'] = 'https://api.cortech.id/uploads/brand/'.$this->userDetail['custom']->branch_logo;
        }
        return view($this->data['template'], [
            'data' => $this->data['data'],
            'downloadFile'=> $this->data['downloadFile'],
            'all' => $this->data,
        ]);
    }

    public function pdf($value='')
    {
        if($this->mpdf && $this->downloadFile=='pdf'){
            $req = $this->headers;
            $branch = (empty($this->userDetail['user_branchaktif'])?null:$this->userDetail['user_branchaktif']);
            if(!empty($this->data['headerDefault'])){
                $this->data['PAGENO'] = '{PAGENO}';
                $header = view('prints.header.header_printout_pdf', $this->data);
                $this->mpdf->SetHTMLHeader($header);
                $this->mpdf->SetMargins(0, 0, 40);
            }
            self::checkPagination();

            $output = $this->mpdf->Output('data.pdf','S');
            $pdfBase64 = base64_encode($output);
            return response()->json('data:application/pdf;base64,' . $pdfBase64);
        }
    }

    public function checkPagination() {
        if (isset($this->data['data']['multiplesheet'])) {
            $result = $this->data['data']['result'];
            $sheetObj = $this->data;
            foreach ($result as $key => $value) {
                $sheetObj['sheet'] = $value;
                $html = view($this->data['template'], $sheetObj)->render();
                $this->mpdf->AddPage();
                $this->mpdf->WriteHTML($html);
            }
        } else {
            $html = view($this->data['template'], $this->data)->render();
            $this->mpdf->WriteHTML($html);
        }
    }
}