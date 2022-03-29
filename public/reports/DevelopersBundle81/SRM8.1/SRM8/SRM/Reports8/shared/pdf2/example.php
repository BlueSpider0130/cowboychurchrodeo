<?php
date_default_timezone_set('UTC');

include_once 'Cezpdf.php';
//$pdf = new CezPDF('a4');

$pdf = new Cezpdf('a4', 'landscape', 'none', null);

$all = $pdf->openObject();
$pdf->saveState();

// header line and text
$pdf->addText(20, 540, 14, 'This is header text');
$pdf->line(20, 530, 820, 530);

// footer line and text
$pdf->line(20, 40, 820, 40);
$pdf->addText(20, 30, 8, 'Left side header text');
$pdf->addText(820, 30, 8, 'Right side header text', 0, 'right');

$pdf->restoreState();
$pdf->closeObject();

$pdf->addObject($all,'all');

$pdf->ezSetMargins(100, 100, 50, 50);

// content text
$text = str_repeat("This is your content.\n", 100);
$pdf->ezText($text, 0, ['justification' => 'full']);

// output
$pdf->ezStream(['Content-Disposition' => 'mypdf.pdf']);
