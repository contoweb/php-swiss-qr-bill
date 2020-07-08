<?php

namespace Sprain\Tests\SwissQrBill\PaymentPart\Output\HtmlOutput;

use PHPUnit\Framework\TestCase;
use Sprain\SwissQrBill\PaymentPart\Output\HtmlOutput\HtmlOutput;
use Sprain\SwissQrBill\QrBill;
use Sprain\SwissQrBill\QrCode\QrCode;
use Sprain\Tests\SwissQrBill\TestQrBillCreatorTrait;

class HtmlOutputTest extends TestCase
{
    use TestQrBillCreatorTrait;

    /**
     * @dataProvider validQrBillsProvider
     */
    public function testValidQrBills(string $name, QrBill $qrBill)
    {
        $variations = [
            [
                'printable' => false,
                'format' => QrCode::FILE_FORMAT_SVG,
                'file' => __DIR__ . '/../../../TestData/HtmlOutput/' . $name . '.svg.html'
            ],
            [
                'printable' => true,
                'format' => QrCode::FILE_FORMAT_SVG,
                'file' => __DIR__ . '/../../../TestData/HtmlOutput/' . $name . '.svg.print.html'
            ],
            [
                'printable' => false,
                'format' => QrCode::FILE_FORMAT_PNG,
                'file' => __DIR__ . '/../../../TestData/HtmlOutput/' . $name . '.png.html'
            ],
            [
                'printable' => true,
                'format' => QrCode::FILE_FORMAT_PNG,
                'file' => __DIR__ . '/../../../TestData/HtmlOutput/' . $name . '.png.print.html'
            ]
        ];

        foreach ($variations as $variation) {
            $file = $variation['file'];

            $output = (new HtmlOutput($qrBill, 'en'));
            $output
                ->setPrintable($variation['printable'])
                ->setQrCodeImageFormat($variation['format'])
                ->getPaymentPart();

            if ($this->regenerateReferenceFiles) {
               file_put_contents($file, $output);
            }

            $this->assertSame(
                file_get_contents($file),
                $output
            );
        }
    }
}