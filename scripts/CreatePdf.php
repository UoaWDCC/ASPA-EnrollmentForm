<?php
require('./vendor/autoload.php');

require("assets/tcpdf/tcpdf.php");

class CreatePdf {
    public static function getEncoding(string $jsonEncoding) {
        // create a new pdf document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 050');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 050', PDF_HEADER_STRING);

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

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

        // set font
        $pdf->SetFont('helvetica', '', 11);

        // add a page
        $pdf->AddPage();

        // print a message
        $txt = "You can also export 2D barcodes in other formats (PNG, SVG, HTML). Check the examples inside the barcode directory.\n";
        $pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 125, 30, true, 0, false, true, 0, 'T', false);


        $pdf->SetFont('helvetica', '', 10);

        // -----------------------------------------------------------------

        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode($jsonEncoding, 'QRCODE,H', 20, 210, 50, 50, $style, 'N');
        $pdf->Text(20, 205, 'QRCODE H');

        // new style
        $style = array(
            'border' => 2,
            'padding' => 'auto',
            'fgcolor' => array(0, 0, 255),
            'bgcolor' => array(255, 255, 64)
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode($jsonEncoding, 'QRCODE,H', 80, 210, 50, 50, $style, 'N');
        $pdf->Text(80, 205, 'QRCODE H - COLORED');

        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(128, 0, 0),
            'bgcolor' => false
        );

        // QRCODE,H : QR-CODE Best error correction
        $pdf->write2DBarcode($jsonEncoding, 'QRCODE,H', 140, 210, 50, 50, $style, 'N');
        $pdf->Text(140, 205, 'QRCODE H - NO PADDING');

        // ---------------------------------------------------------

        // create pdf encoding
        $pdfEncoding = $pdf->Output('example_050.pdf', 'S');

        return $pdfEncoding;
    }
}