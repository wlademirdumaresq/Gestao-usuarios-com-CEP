<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__('User')}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-table-card>
                        @if($user->active)
                            <div class="custom-control custom-switch custom-control-inline ml-1 float-right">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                <label class="custom-control-label" for="customSwitch1"><span
                                        class="badge badge-primary ">ATIVO</span></label>
                            </div>
                        @else
                            <div class="custom-control custom-switch custom-control-inline ml-1 float-right">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1"><span
                                        class="badge badge-secondary ">INATIVO</span></label>
                            </div>
                        @endif
                        <x-slot name="logo">
                            <h3>
                                {{$user->name}}
                            </h3>

                        </x-slot>
                        <div class="div-table table">
                            <div class="table">
                                <table class="table-borderless" width="100%">
                                    <caption>Lista de dados do usuário
                                        <div class="btn-group float-right">
                                            <button class="btn btn-sm btn-danger " data-toggle="modal" data-target="#set-password">
                                                Alterar senha
                                            </button>
                                            <a href='{{route('user.update', ['id' => $user->id])}}'
                                               class="btn btn-sm btn-primary ">
                                                Editar Usuário
                                            </a>
                                        </div>
                                    </caption>
                                    <tbody class="border-bottom h5">
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Username') }}:</th>
                                        <td>{{$user->username}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Name') }}:</th>
                                        <td>{{$user->name}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('CPF') }}:</th>
                                        <td>{{$user->CPF}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Email') }}:</th>
                                        <td>{{$user->email}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Tel') }}:</th>
                                        <td>{{$user->tel}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Birth date') }}:</th>
                                        <td>{{$user->birth_date}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('CEP') }}:</th>
                                        <td>{{$address->CEP}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Address') }}:</th>
                                        <td>{{$address->address}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('complement') }}:</th>
                                        <td>{{$address->complement}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('district') }}:</th>
                                        <td>{{$address->district}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('city') }}:</th>
                                        <td>{{$address->city}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('state') }}:</th>
                                        <td>{{$address->state}}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th scope="row">{{ __('Created') }}:</th>
                                        <td>{{$user->created_at}}</td>
                                    </tr>
                                    @empty($user_register)
                                    @else
                                        <tr class="border-bottom">
                                            <th scope="row">{{ __('User Register') }}:</th>


                                            <td>{{$user_register->name}}</td>

                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                    </x-table-card>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="remover-user" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
         data-keyboard="false">
        <div class="modal-sm modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Status usuário</h5>
                    <button type="button" class="close"
                            onclick="$('#customSwitch1').change()[0].checked = !$('#customSwitch1').change()[0].checked"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="h5 text-secondary">Tem certeza que deseja alterar o status este usuário?</p>
                </div>
                <div class="modal-footer">
                    <a id="desabilitar-user" href="" type="button" class="btn btn-danger">Alterar</a>
                    <button type="button" class="btn btn-secondary"
                            onclick="$('#customSwitch1').change()[0].checked = !$('#customSwitch1').change()[0].checked"
                            data-dismiss="modal">Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="set-password" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
         data-keyboard="false">
        <div class="modal-sm modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar senha</h5>
                    <button type="button" class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>

                @if($errors->any())
                    <script>
                        $('#set-password').modal().toggle();
                    </script>
                @endif
                <form name="register" method="POST" action="{{ route('user.password',['id'=>$user->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Password -->
                        <div class="mt-4">
                            <x-label for="password" :value="__('Password')"/>

                            <x-input id="password" class="block mt-1 w-full"
                                     type="password"
                                     name="password"
                                     required autocomplete="new-password"/>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')"/>

                            <x-input id="password_confirmation" class="block mt-1 w-full"
                                     type="password"
                                     name="password_confirmation" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="h5 text-secondary">Tem certeza que deseja alterar o status este usuário?</p>
                        <button type="submit" class="btn btn-danger">Alterar</button>
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#customSwitch1').click(function () {
                $('#remover-user').modal().toggle();
                let link = "{{route('user.active',['id'=> $user->id,'key'=> ' '])}}";
                $('#desabilitar-user').attr('href', link.replace('%20', $('#customSwitch1').change()[0].checked ? 1 : 0));
                $('#desabilitar-user').text($('#customSwitch1').change()[0].checked ? 'Ativar' : 'Desativar')
            });
        });
    </script>
</x-app-layout>
