<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
class PdfController extends Controller
{
    public function index() 
	{
        return view('adminDashboard/pdf_view');
    }
    function htmlToPDF(){
        $dompdf = new \Dompdf\Dompdf(); 
        $dompdf->loadHtml(view('pdf_view'));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }
}