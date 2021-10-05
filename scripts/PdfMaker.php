<?php
require('./vendor/autoload.php');

require("assets/tcpdf/tcpdf.php");

class PdfMaker {
    public static function generatePdfEncoding(string $jsonEncoding) {
        // pdf testing
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ASPA');
        $pdf->SetTitle('QR Check in');
        $pdf->SetSubject('Unique QR code to check into the event');
        $pdf->SetKeywords('QR, PDF, ASPA, POOL');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "ASPA QR Check In Code");

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

        // set font
        $pdf->SetFont('helvetica', '', 11);

        // add a page
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 10);

        // -----------------------------------------------------------------

        // set style for barcode
        $style = array(
            'border' => false,
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode($jsonEncoding, 'QRCODE,H', 80, 40, 50, 50, $style, 'N');
        $pdf->Text(20, 25, 'Please present this QR to the ASPA Execs upon arrival:');

        // ---------------------------------------------------------

        // create pdf encoding
        $pdfEncoding = $pdf->Output('example_050.pdf', 'S');

        return $pdfEncoding;
    }
}