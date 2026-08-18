<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 204);
        }

        $user = auth()->user();

        if ($user->isAdmin()) {
            return new RedirectResponse(route('admin.dashboard'));
        }

        if ($user->isOwner()) {
            return new RedirectResponse(route('owner.dashboard'));
        }

        if ($user->isCustomer()) {
            return new RedirectResponse(route('home'));
        }

        return new RedirectResponse(url(config('fortify.home')));
    }
}
