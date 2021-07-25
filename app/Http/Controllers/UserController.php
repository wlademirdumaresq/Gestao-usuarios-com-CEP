<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Testing\Fluent\Concerns\Has;
use Yajra\DataTables\Facades\DataTables;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request)
    {
        if ($request->ajax()) {

            return DataTables::of(User::all())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })->addColumn('username', function ($data) {
                    return $data->username;
                })->addColumn('email', function ($data) {
                    return $data->email;
                })->addColumn('birth_date', function ($data) {
                    return $data->birth_date;
                })->addColumn('active', function ($data) {
                    $etiqueta = '<span class="badge badge-success">ATIVO</span>';
                    if (!$data->active) {
                        $etiqueta = '<span class="badge badge-danger">INATIVO</span>';
                    }
                    return $etiqueta;
                })->addColumn('action', function ($data) {
                    return "<a href='" . route('user', ['id' => $data->id]) . "' title='Visualizar'>
                                    <i style='font-size: 15px' class='far fa-eye'></i>
                                </a>";
                })->addColumn('picture', function ($data) {
                    return '<img src="' . url('storage/users/') . '/' . $data->picture . '" width="40px" height="40px" class="rounded-circle mr-3">';
                })
                ->rawColumns(['name', 'username', 'email', 'birth_date', 'active', 'action', 'picture'])
                ->make(true);
        }
    }

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = User::where('id', intval($id))->first();
        $address = Address::where('user_id', intval($id))->first();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100',
            'birth_date' => 'required|date_format:Y-m-d',
            'tel' => 'required|string|max:11',
            'username' => 'required|string|max:50',
            'CPF' => 'required|string|max:11',
            'CEP' => 'required|string|max:8',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ]);


        $upload = false;
        if($request->hasFile('picture') && $request->file('picture')->isValid()){
            $upload = $request->file('picture')->storeAs('public/users',$request->CPF.'.'.$request->picture->extension());
            if(!$upload){
                return redirect()->back()->with('error', 'Imagem não foi salva');
            }
        }
        $user_date = [
            'name'=>!isEmpty($request->name) ? $user->name : $request->name,
            'email'=>!isEmpty($request->email) ? $user->email : $request->email,
            'birth_date'=>!isEmpty($request->birth_date) ? $user->birth_date : $request->birth_date,
            'tel'=>!isEmpty($request->tel) ? $user->tel : $request->tel,
            'username'=>!isEmpty($request->username) ? $user->username : $request->username,
            'CPF'=>!isEmpty($request->CPF) ? $user->CPF : $request->CPF,
            'picture'=> $upload ? $request->CPF.'.'.$request->picture->extension() : $user->picture
        ];

        $address_date = [
            'CEP' => !isEmpty($request->CEP) ? $address->CEP : $request->CEP,
            'address' => !isEmpty($request->address) ? $address->address : $request->address,
            'district' => !isEmpty($request->district) ? $address->district : $request->district,
            'city' => !isEmpty($request->city) ? $address->city : $request->city,
            'state' => !isEmpty($request->state) ? $address->state : $request->state,
            'complement' => !isEmpty($request->complement) ? $address->complement : $request->complement,
        ];
        try {
            DB::beginTransaction();
            $update_user = $user->update($user_date);
            $update_address = $address->update($address_date);
            DB::commit();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Usuario não alterado, verificar se dados estão duplicados com outros usuários');
        }
        return $update_user && $update_address?
            redirect()->back()->with('success', 'Usuario alterado') :
            redirect()->back()->with('error', 'Usuario não alterado');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCEP(Request $request, $CEP)
    {
        if ($request->ajax()) {
            return Http::get('https://viacep.com.br/ws/' . $CEP . '/json/');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $address = Address::where('user_id', $id)->first();
        $user_register = User::find($user->user_id);
        return view('user', ['user' => $user, 'address' => $address, 'user_register' => $user_register]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $address = Address::where('user_id', $id)->first();
        return view('auth/update', ['user' => $user, 'address' => $address]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateActive(Request $request, $id, $key)
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', intval($id))->first();
            $update = $user->update(['active' => $key ? true : false]);
            DB::commit();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Usuario não alterado');
        }
        return $update ?
            redirect()->back()->with('success', 'Usuario alterado') :
            redirect()->back()->with('error', 'Usuario não alterado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try {
            DB::beginTransaction();
            $user = User::where('id', intval($id))->first();
            $update = $user->update(['password' => Hash::make($request->password)]);
            DB::commit();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Usuario não alterado');
        }
        return $update ?
            redirect()->back()->with('success', 'Usuario alterado') :
            redirect()->back()->with('error', 'Usuario não alterado');
    }
}
