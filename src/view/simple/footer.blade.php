{{-- Lábléc --}}
<table>
    <tr>
        <td width="50%" valign="top">
            <script type = "text/php">
                    if (isset($pdf))
    {
        $x = 64;
        $y = 763;
        $text = "{PAGE_NUM}/{PAGE_COUNT}";
        $font = $fontMetrics->get_font("helvetica", "bold");
        $size = 7;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
                </script>
            <span class="page-counter">{{ trans('invoice::image.page') }}</span><br>
            <span class="legal-notice">{{ trans('invoice::image.legal_notice') }}</span>
        </td>
        <td width="50%" valign="top" align="right">
            <span class="software-info">{{ trans('invoice::image.software_info') }}</span><br>
            <span class="software-info">www.paytech.hu</span><br>
        </td>
    </tr>
</table>
