<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypdf extends TCPDF
{
    public $xfootertext = '';
    public $footertext2 = '';
    public $headertext = '';
    public $header_for_first = '';
    public $header_for_all = '';
    public $y_from_2 = '';




    public function Footer() {

        // Position at 15 mm from bottom
        $this->SetY(-12);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $year = date('Y');
        // $footertext = sprintf($this->xfootertext, $year);
        $this->writeHTML($this->xfootertext, false, true, false, true);

        // Page number

        // $this->Cell(120, 10, $this->footertext2, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //  $this->Cell(60, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }
}