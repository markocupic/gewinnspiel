<?php if (!defined('TL_ROOT'))
       die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 * @filesource
 */

// include fpdf and barcode classes
require_once(TL_ROOT . '/system/modules/gewinnspiel/plugins/fpdf/fpdf.php');
require_once(TL_ROOT . '/system/modules/gewinnspiel/plugins/qr_barcode/BarcodeQR.php');

/**
 * Class GenerateCertificate
 *
 * @copyright  Marko Cupic 2013
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @package    Gewinnspiel
 */
class GenerateCertificate extends Controller
{
       public function __construct()
       {
              // define the fontpath
              define('FPDF_FONTPATH', TL_ROOT . "/system/modules/gewinnspiel/plugins/fpdf/font/");

       }

       /**
        * @param object $objCode
        * @param object $objPrize
        * @param object $objMember
        */
       public function generateCertificate($strCodeDecoded, $objCode, $objPrize, $objMember, $objModule)
       {

              // Create the fpdf object
              $pdf = new FPDF('P', 'mm', 'A4');

              // Set document properties
              $pdf->SetTitle('Gewinnzertifikat 2013 - Frei AG - Frick, Lenzburg und Rheinfelden');
              $pdf->SetSubject('Ihr Gewinnzertifikat Gewinnspiel ' . date('Y'));
              $pdf->SetAuthor('Frei AG - in Lenzburg, Frick, Rheinfelden');
              $pdf->SetKeywords(utf8_decode('Frey AG Uhren, Schmuck, Optik, Gravuren und Piercing in Frick, Lenzburg und Rheinfelden'));
              $pdf->AddPage();
              $pdf->SetFont('Arial', 'B', 18);
              $pdf->SetFillColor(255, 255, 255);

              // title
              $pdf->Cell(190, 8, utf8_decode("Gewinnbestätigung"), 'B', '', 'L');
              $pdf->Ln();
              $pdf->Ln();
              
              // add prize image
              $headerLogo = $objModule->prizeImagesFolder . '/certificate_banner.jpg';
              if (file_exists(TL_ROOT . '/' . $headerLogo))
              {
                     $pdf->Image(TL_ROOT . '/' . $headerLogo, 10, 20, 190, 0, '', '');
              } else {
                     // default image
                     $bannerSrc = 'system/modules/gewinnspiel/assets/images/certificate_banner.jpg';
                     $pdf->Image(TL_ROOT . '/' . $bannerSrc, 10, 20, 190, 0, '', '');
              }
              
              // generate Barcode code128
              $tmp_filename_barcode = 'system/tmp/' . md5(time()) . '.png';
              $this->generateBarcode(TL_ROOT . '/' . $tmp_filename_barcode, $strCodeDecoded, 'code128');
              //check if tmp-dir is writable
              if (!is_file(TL_ROOT . '/' . $tmp_filename_barcode))
              {
                     $error_message = 'Probably the system/tmp directory is not writable. Error in ' . __FILE__ . ' on ' . __LINE__ . ' at ' . __METHOD__ . '()';
                     $this->log('Probably the system/tmp directory is not writable. Error in ' . __FILE__ . ' on ' . __LINE__ . ' at ' . __METHOD__ . '()', __METHOD__, TL_ERROR);
                     die($error_message);
              }

              $pdf->Image(TL_ROOT . '/' . $tmp_filename_barcode, 10, 90, 0, 0, '', '');
              $objFile = new File($tmp_filename_barcode);
              $objFile->delete();

              $pdf->SetY(110);
              // add a new embeded font
              //$pdf->addFont('Itckrist', '', 'ITCKRIST.php');
              //$pdf->SetFont('Itckrist', '', 14);

              // gender
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, "Anrede:", '', '', '');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->Cell(190, 8, $objMember->gender == 'female' ? 'Frau' : 'Herr', '', '', 'L');
              $pdf->Ln();

              // name
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, "Name:", '', '', '');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->Cell(190, 8, utf8_decode($objMember->firstname . ' ' . $objMember->lastname), '', '', 'L');
              $pdf->Ln();

              // email
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, "E-Mail:", '', '', '');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->Cell(190, 8, $objMember->email, '', '', 'L');
              $pdf->Ln();

              // winner code
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, "Gewinncode:", '', '', 'L');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->Cell(190, 8, $strCodeDecoded, '', '', 'L');
              $pdf->Ln();

              // Prize
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, "Ihr Preis:", '', '', 'L');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->MultiCell(190, 8, utf8_decode($objPrize->description), '', '', 'L');
              $pdf->Ln();

              // add prize image
              $prizeSrc = $objModule->prizeImagesFolder . '/preis_' . $objCode->prizeGroup . '.jpg';
              if (file_exists(TL_ROOT . '/' . $prizeSrc))
              {
                     $pdf->Image(TL_ROOT . '/' . $prizeSrc, 125, 90, 75, 0, '', '');
              }

              //valid until
              $pdf->SetFont('Arial', 'B', 12);
              $pdf->Cell(190, 8, utf8_decode("Preis im Ladengeschäft einlösbar bis:"), '', '', 'L');
              $pdf->Ln();
              $pdf->SetFont('Arial', '', 12);
              $pdf->Cell(190, 8, date('d.m.Y', $objCode->validUntil), '', '', 'L');

              

              /*
              // generate Barcode QR
              //http://www.shayanderson.com/php/php-qr-code-generator-class.htm
              $qr = new BarcodeQR();
              $qr->url("www.kletterkader.com/de/gewinnspiel.html?key=" . $objCode->code . '&token=' . $objCode->token);
              sleep(2);
              // add barcode image
              $qr->draw(150, TL_ROOT . '/system/tmp/qr.png');
              $pdf->Image(TL_ROOT . '/system/tmp/qr.png', 120, 120, 0, 0, '', '');
              $objFile = new File('system/tmp/qr.png');
              $objFile->delete();
              */

              // output
              $pdf->Output('ihr-gewinn-zertifikat-bei-frey-uhren.pdf', 'D');
       }

       /**
        * @param string $filename
        * @param string $code
        * @param string $type
        */
       private function generateBarcode($filename = '', $code, $type)
       {
              define('IN_CB', true);
              // Including all required classes
              require(TL_ROOT . '/system/modules/gewinnspiel/plugins/barcode/class/index.php');
              require(TL_ROOT . '/system/modules/gewinnspiel/plugins/barcode/class/FColor.php');
              require(TL_ROOT . '/system/modules/gewinnspiel/plugins/barcode/class/BarCode.php');
              require(TL_ROOT . '/system/modules/gewinnspiel/plugins/barcode/class/FDrawing.php');
              // including the barcode technology
              include(TL_ROOT . '/system/modules/gewinnspiel/plugins/barcode/class/' . $type . '.barcode.php');
              // Creating some Color (arguments are R, G, B)
              $color_black = new FColor(0, 0, 0);
              $color_white = new FColor(255, 255, 255);
              /* Here is the list of the arguments:
              1 - Thickness
              2 - Color of bars
              3 - Color of spaces
              4 - Resolution
              5 - Text
              6 - Text Font (0-5)
              */
              $class = $type;
              $code_generated = new $class(60, $color_black, $color_white, 1, $code, 3);
              /* Here is the list of the arguments
              1 - Width
              2 - Height
              3 - Filename (empty : display on screen)
              4 - Background color
              */
              $drawing = new FDrawing(1024, 1024, $filename, $color_white);
              $drawing->init(); // You must call this method to initialize the image
              $drawing->add_barcode($code_generated);
              $drawing->draw_all();
              $im = $drawing->get_im();
              // Next line create the little picture, the barcode is being copied inside
              $im2 = imagecreate($code_generated->lastX, $code_generated->lastY);
              imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
              $drawing->set_im($im2);
              // Header that says it is an image (remove it if you save the barcode to a file)
              if ($filename == "")
                     header('Content-Type: image/png');
              // Draw (or save) the image into PNG format.
              $drawing->finish(IMG_FORMAT_PNG);
       }
}

