<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <x-auth-card>
                        <x-slot name="logo">
                            <h3>
                                {{$user->name}}
                            </h3>
                        </x-slot>

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                        <button class="btn btn-lightr float-right" onclick="history.back()">
                            X
                        </button>
                        <form name="register" method="POST" action="{{ route('user.set_user',['id'=>$user->id]) }}"
                              enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                            <div class="mt-4">
                                <x-label for="name" :value="__('Name')"/>

                                <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                         value="{{$user->name}}" required autofocus/>
                            </div>

                            <!-- Picture -->
                            <div class="mt-4">
                                <x-label for="picture" :value="__('Picture')"/>

                                <x-input id="picture" class="block mt-1 w-full" type="file" name="picture"/>
                            </div>

                            <!-- CEP -->
                            <div class="mt-4">
                                <x-label for="CEP" :value="__('CEP')"/>

                                <x-input id="CEP" min="8" min="8" class="block mt-1 w-full" type="text" name="CEP"
                                         value="{{$address->CEP}}" placeholder="Apenas n??meros" pattern="[0-9]{8}"
                                         required/>
                            </div>

                            <!-- Address -->
                            <div class="mt-4">
                                <x-label for="address" :value="__('Address')"/>

                                <x-input style="color:white" id="address" class="block mt-1 w-full bg-secondary"
                                         type="text" name="address"
                                         value="{{$address->address}}" required/>
                            </div>

                            <!-- district -->
                            <div class="mt-4">
                                <x-label for="district" :value="__('District')"/>

                                <x-input style="color:white" id="district" class="block mt-1 w-full bg-secondary"
                                         type="text"
                                         name="district" value="{{$address->district}}" required/>
                            </div>

                            <!-- city -->
                            <div class="mt-4">
                                <x-label for="city" :value="__('City')"/>

                                <x-input style="color:white" id="city" class="block mt-1 w-full bg-secondary"
                                         type="text" name="city" value="{{$address->city}}" required/>
                            </div>
                            <!-- state -->
                            <div class="mt-4">
                                <x-label for="state" :value="__('State')"/>

                                <x-input style="color:white" id="state" class="block mt-1 w-full bg-secondary"
                                         type="text" name="state" value="{{$address->state}}" required/>
                            </div>

                            <!-- complement -->
                            <div class="mt-4">
                                <x-label for="complement" :value="__('Complement')"/>

                                <x-input id="complement" style="color:white" class="block mt-1 w-full bg-secondary"
                                         type="text"
                                         value="{{$address->complement}}" name="complement"/>
                            </div>

                            <!-- Email Address -->
                            <div class="mt-4">
                                <x-label for="email" :value="__('Email')"/>

                                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                         value="{{$user->email}}" required/>
                            </div>

                            <!-- Username -->
                            <div class="mt-4">
                                <x-label for="username" :value="__('User')"/>

                                <x-input id="username" class="block mt-1 w-full" type="text" name="username"
                                         value="{{$user->username}}" required/>
                            </div>

                            <!-- tel -->
                            <div class="mt-4">
                                <x-label for="tel" :value="__('Telefone')"/>

                                <x-input id="tel" class="block mt-1 w-full" type="tel" name="tel"
                                         placeholder="Apenas n??meros" pattern="[0-9]{11}"
                                         value="{{$user->tel}}" required/>
                            </div>

                            <!-- CPF -->
                            <div class="mt-4">
                                <x-label for="CPF" :value="__('CPF')"/>

                                <x-input placeholder="Apenas n??meros" id="CPF" class="block mt-1 w-full" type="text"
                                         name="CPF" pattern="[0-9]{11}"
                                         value="{{$user->CPF}}" required/>
                            </div>

                            <!-- Birth date -->
                            <div class="mt-4">
                                <x-label for="birth_date" :value="__('Birth')"/>

                                <x-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date"
                                         value="{{$user->birth_date}}" required max="{{date('Y-m-d')}}"/>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-4" id="submit">
                                    {{ __('Update') }}
                                </x-button>

                            </div>

                        </form>
                    </x-auth-card>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/validador_cpf.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#CPF').change(function (event) {
                let cpf = $(this).val();
                if (validar(cpf)) {
                    $(this).removeClass('is-invalid');
                    $('#submit').prop('disabled', false);
                } else {
                    $(this).addClass('is-invalid');
                    $('#submit').prop('disabled', true);
                }
            });
            $('#CEP').change(function () {
                $.ajax({
                    url: "http://127.0.0.1:8000/user_cep/" + $('#CEP').val(),
                    data: $('form[name="register"]').serialize(),
                    type: "get",
                    dataType: 'json',
                    async: true,
                    success: function (response) {
                        $('#address').val(response.logradouro);
                        $('#district').val(response.bairro);
                        $('#city').val(response.localidade);
                        $('#state').val(response.uf);
                        $('#complement').val(response.complemento);
                    }
                });
            });
        });
    </script>
</x-app-layout>
