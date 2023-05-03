<?php

namespace App\Http\Controllers\Passports;

use App\Http\Controllers\Controller;
use http\Client\Response;
use Illuminate\Http\Request;
use Intervention\Image\Imagick;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrController extends Controller
{
    public function __invoke(Request $request)
    {
        $qrValue = $request->get('qrVal') ?? 'https://lebergroup.ru/';
        return view('/content/passports/generate-qr', [ "qrValue" => $qrValue ]);
    }

    public function index(Request $request)
    {
        $qrValue = $request->get('qrVal') ?? 'https://lebergroup.ru/';
        return view('/content/passports/generate-qr')->with(compact('qrValue', 'request'));
    }

    public function valueInput(Request $request)
    {
        $qrValue = $request->get('qrVal') ?? 'https://lebergroup.ru/';
        $qrCode = base64_encode((string)QrCode::size(420)->format('png')->merge('images/passports/ava_insta_logo.png', .4, true)->errorCorrection('H')->generate($qrValue));
        activity()->event('qr.create')->withProperties(['url' => $qrValue])->log('Создание QR кода');
        return response()->json(['post' => $qrValue, 'qr' => $qrCode]);
    }
}
