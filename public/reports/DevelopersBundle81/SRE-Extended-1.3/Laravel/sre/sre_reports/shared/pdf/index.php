<?php
require_once("dompdf/autoload.inc.php");
ob_start();
?>

<html>
<table>
<tr>
<td> <img src="images/01tz.png" > </td>
<td><font color="red">test</font> </td>

</tr>

</table>

</html>

<?php
$html = ob_CLEANED_clean();
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("sample.pdf");
?>