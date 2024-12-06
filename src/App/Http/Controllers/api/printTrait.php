<?php
namespace Amerhendy\Drivers\App\Http\Controllers\api;
use File;
use \Amerhendy\Drivers\App\Models\offics_employersmamorias;
use Illuminate\Support\Facades\DB;
use \Amerhendy\Amer\App\Models\Governorates;
use \Amerhendy\Amer\App\Models\Cities;
use Illuminate\Validation\Rule;
use Amerhendy\Drivers\App\Rules\checkPrintIdExists;
use Illuminate\Support\Facades\View;
trait printTrait{
    public static function PrinFullMamoria($data){
        $config=config('Amer.TCPDF');
        $pdf = new \Amerhendy\Pdf\Helpers\AmerPdf($config['PageOrientation'],$config['PDFUnit'],$config['PageSize'], true, 'UTF-8', false);
        $pdf->setViewerPreferences($config['ViewerPreferences']);
        $pdf->SetCreator($config['PDFCreator']);
        $pdf->SetAuthor(config('Amer.amer.co_name'));
        $pdf->SetTitle($data->headerTitle);
        $pdf->SetSubject($data->headerTitle) ;
        $pdf->SetKeywords(implode(',',explode(' ',$data->headerTitle." ".config('Amer.amer.co_name'))));
        $pdf->setImageScale($config['ImageScaleRatio']);
        if (@file_exists($config['packagePath'].'lang/ara.php')) {
            require_once($config['packagePath'].'lang/ara.php');
            $pdf->setLanguageArray($l);
        }
        $pageFooter=View::make("Drivers::pdf.pdfFooter",['config'=>$config])->render();
        $pdf->setFooterHtml($font=['aealarabiya', 'B', 10],$hs=$pageFooter, $tc=array(0,0,0), $lc=array(0,0,0),$line=true);
        $pdf->setFooterFont(Array($config['Font']['Date']['name'], '', $config['Font']['Date']['Size']));
        $pdf->SetDefaultMonospacedFont($config['Font']['MONOSPACED']);
        //$pdf->SetMargins(10,33,10);
        $pdf->SetMargins(10,20,10);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(20);
        $pdf->SetAutoPageBreak(TRUE, $config['PdfMargin']['MarginBottom']);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('aealarabiya', '', 11, '', true);
        $pdf->setRTL(true);
        $pdf->startPageGroup();
        $tablewidth=190;
        $css=View::make("Drivers::pdf.css.CssTable",['data'=>['tablewidth'=>$tablewidth]])->render();
        foreach ($data->data as $key => $value) {
            $pageheader=View::make("Drivers::pdf.pdfheader",['config'=>$config])->render();
            $pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$pageheader, $tc=array(0,0,0), $lc=array(0,0,0));
            $pdf->AddPage();
            $html=View::make("Drivers::pdf.TableForCollection",['config'=>$config,'data'=>$value])->render();
            $pdf->writeHTML($css.$html, true, false, false, false, 'right');
            $tagvs = [
                'div' => [
                    ['h' => 0.5, 'n' => 0.01],['h' => 0.5, 'n' => 0.01]
                ]
            ];
            $pdf->setHtmlVSpace($tagvs);
        }
        $filename="A.pdf";
        return $pdf->Output($filename, 'E');
    }
    public static function PrinShortMamoria($data){
        $config=config('Amer.TCPDF');
        $pdf = new \Amerhendy\Pdf\Helpers\AmerPdf($config['PageOrientation'],$config['PDFUnit'],$config['PageSize'], true, 'UTF-8', false);
        $pdf->setViewerPreferences($config['ViewerPreferences']);
        $pdf->SetCreator($config['PDFCreator']);
        $pdf->SetAuthor(config('Amer.amer.co_name'));
        $pdf->SetTitle($data->headerTitle);
        $pdf->SetSubject($data->headerTitle) ;
        $pdf->SetKeywords(implode(',',explode(' ',$data->headerTitle." ".config('Amer.amer.co_name'))));
        $pdf->setImageScale($config['ImageScaleRatio']);
        if (@file_exists($config['packagePath'].'lang/ara.php')) {
            require_once($config['packagePath'].'lang/ara.php');
            $pdf->setLanguageArray($l);
        }
        //$pageFooter=View::make("Drivers::pdf.pdfFooter",['config'=>$config])->render();
        //$pdf->setFooterHtml($font=['aealarabiya', 'B', 10],$hs=$pageFooter, $tc=array(0,0,0), $lc=array(0,0,0),$line=true);
        $pdf->setFooterFont(Array($config['Font']['Date']['name'], '', $config['Font']['Date']['Size']));
        $pdf->SetDefaultMonospacedFont($config['Font']['MONOSPACED']);
        $pdf->SetMargins(10,5,10);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(20);
        $pdf->SetAutoPageBreak(TRUE, $config['PdfMargin']['MarginBottom']);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('aealarabiya', '', 11, '', true);
        $pdf->setRTL(true);
        $pdf->startPageGroup();
        $tablewidth=190;
        //$css=View::make("Drivers::pdf.css.CssTable",['data'=>['tablewidth'=>$tablewidth]])->render();
        $css=View::make("Drivers::pdf.css.Itinerary",['data'=>['tablewidth'=>$tablewidth]])->render();
        //dd($css);
        $pdf->AddPage();
        $html='';
        foreach ($data->data as $key => $value) {
            //$pageheader=View::make("Drivers::pdf.pdfheader",['config'=>$config])->render();
            //$pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$pageheader, $tc=array(0,0,0), $lc=array(0,0,0));
            $html.=View::make("Drivers::pdf.Itinerary",['config'=>$config,'data'=>$value])->render();
        }
        $pdf->writeHTML($css.$html, true, false, false, false, 'right');
            $tagvs = [
                'div' => [
                    ['h' => 0.5, 'n' => 0.01],['h' => 0.5, 'n' => 0.01]
                ]
            ];
            $pdf->setHtmlVSpace($tagvs);
        $filename="A.pdf";
        return $pdf->Output($filename, 'E');
    }
}
