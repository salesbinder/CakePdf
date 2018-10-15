<?php
namespace CakePdf\Pdf\Engine;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class MpdfEngine extends AbstractPdfEngine
{

    /**
     * Generates Pdf from html
     *
     * @return string raw pdf data
     */
    public function output()
    {
        $orientation = $this->_Pdf->orientation() === 'landscape' ? 'L' : 'P';
        $format = $this->_Pdf->pageSize();
        if (is_string($format)
            && $orientation === 'L'
            && strpos($format, '-L') === false
        ) {
            $format .= '-' . $orientation;
        }

        $options = [
            'mode' => $this->_Pdf->encoding(),
            'format' => $format,
            'orientation' => $orientation,
            'tempDir' => TMP,
        ];
        $options = array_merge($options, (array)$this->getConfig('options'));

        $Mpdf = $this->_createInstance($options);

        if (!empty($options['header']))
          $Mpdf->SetFooter($options['header']);

        if (!empty($options['htmlHeader']))
          $Mpdf->SetHTMLHeader($options['htmlHeader']);

        if (!empty($options['footer']))
          $Mpdf->SetFooter($options['footer']);

        if (!empty($options['htmlFooter']))
          $Mpdf->SetHTMLFooter($options['htmlFooter']);

        if (!empty($options['javascript']))
          $Mpdf->SetJS($options['javascript']);

        $Mpdf->WriteHTML($this->_Pdf->html());

        return $Mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * Creates the Mpdf instance.
     *
     * @param array $options The engine options.
     * @return \Mpdf\Mpdf
     */
    protected function _createInstance($options)
    {
        return new Mpdf($options);
    }
}
