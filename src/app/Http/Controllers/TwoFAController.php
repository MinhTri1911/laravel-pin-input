<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwoFAController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('auth.2fa');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $pinCode = auth()->user()->getPinCode();

        if (!is_null($pinCode) && $pinCode === trim($request->get('code'))) {
            $request->session()->put('user_2fa', auth()->user()->id);

            $user = User::where('id', auth()->user()->id)->first();
            $user->clearPinCode();
            $user->save();

            return new JsonResponse([
                'shouldRedirect' => true,
                'url' => route('home'),
            ], 200);
        }

        return new JsonResponse([
            'shouldRedirect' => false,
        ], 200);
    }
}
