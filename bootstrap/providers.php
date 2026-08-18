<?php

return [
    App\Providers\AppServiceProvider::class,
    // Tambahkan baris ini di bawahnya:
    App\Providers\FortifyServiceProvider::class,
    Mews\Captcha\CaptchaServiceProvider::class,
];