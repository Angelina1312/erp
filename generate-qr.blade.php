@extends('layouts/contentLayoutMaster')
@section('title', 'Генератор QR-кодов')
@section('content')
    <div class="breadcrumb">
        <h3>Генератор QR-кодов</h3>
    </div>
    <div class="row">
        <div class="col l-mb-1">
            <x-card>
                <form action="#" method="POST" aria-describedby="orderHelpBlock">
                    <label class="unit-label">Url-адрес</label>
                    <div class="d-flex flex-column unit-contents justify-content-center align-items-start w-100 flex-shrink-1">
                        <div class="relative l-input d-flex w-100 size-sm">
                            <input id="setQr" name="qrUrl" class="w-100" type="text" value="{{ $qrValue }}" style="max-width: 500px;">
                        </div>
                    </div>
                    @csrf
                </form>
                <button class="l-button align-items-center d-inline-flex justify-content-center size-md type-primary color-accent" type="submit" id="clickQr">Сгенерировать QR-код</button>
            </x-card>
        </div>
        <div class="col">
            <x-card>
                <div id="canvas" class="border">
                    <img src="data:image/png;base64, {!! base64_encode((string)QrCode::size(420)->format('png')->merge('images/passports/ava_insta_logo.png', .4, true)->errorCorrection('H')->generate($qrValue)) !!} " id="qrCode" class="w-100">
                </div>
                <a href="data:image/png;base64, {!! base64_encode((string)QrCode::size(420)->format('svg')->merge('images/passports/ava_insta_logo.png', .4, true)->errorCorrection('H')->generate( $qrValue )) !!}" class="l-button align-items-center d-inline-flex justify-content-center size-md type-primary color-accent" id="downloadQr" download="qr">Скачать QR-код</a>
            </x-card>
        </div>
    </div>

@endsection

@section('page-script')

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#clickQr").on('click', function () {
            $.ajax({
                dataType: "json",
                url: route('valueInput'),
                type: "POST",
                data: {qrVal: $("#setQr").val()},
                success: function (data) {
                    $("#setQr").replaceWith('<input id="setQr" name="qrUrl" class="form-control me-2 mb-1" type="text" value="' + data.post + '" style="max-width: 500px;">');
                    $("#qrCode").attr('src', 'data:image/png;base64,' + data.qr);
                    $("#downloadQr").attr('href', 'data:image/png;base64,' + data.qr);
                },
                error: function (data) {
                    console.log(data.error);
                }
            })
        });

    </script>

@endsection


