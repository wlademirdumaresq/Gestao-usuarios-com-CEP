<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Adress;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use function PHPUnit\Framework\isEmpty;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'birth_date' => 'required|date_format:Y-m-d',
            'tel' => 'required|string|max:11',
            'username' => 'required|string|max:50|unique:users',
            'CPF' => 'required|string|max:11|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $upload = false;
        if($request->hasFile('picture') && $request->file('picture')->isValid()){

            $upload = $request->file('picture')->storeAs('public/users',$request->CPF.'.'.$request->picture->extension());
            if(!$upload){
                return redirect()->back()->with('error', 'Imagem não foi salva');
            }
        }
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'birth_date' => $request->birth_date,
                'tel' => $request->tel,
                'username' => $request->username,
                'picture' => $upload ? $request->CPF.'.'.$request->picture->extension() : 'picture-none.png',
                'CPF' => $request->CPF,
                'user_id' => Auth::user()->id,
                'password' => Hash::make($request->password),
            ]);
            $adress = Address::create([
                'address' => $request->address,
                'district' => $request->district,
                'city' => $request->city,
                'state' => $request->state,
                'complement' => isEmpty($request->complement) ? 'S/C' : $request->complement,
                'CEP' => $request->CEP,
                'user_id' => $user->id,
            ]);
            DB::commit();
        }catch (\Exception $e){

            return redirect()->back()->with('error', 'Cadastro não realizado');
        }

        event(new Registered($user));
        return $adress && $user ?
            redirect()->back()->with('success', 'Cadastro realizado') :
            redirect()->back()->with('error', 'Cadastro não realizado');
    }
}
