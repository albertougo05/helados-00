<x-app-layout>

    <x-slot name="title">Heladerías - Usuario</x-slot>

    <div class="flex mt-12">
            <div class="mx-auto w-full md:w-4/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
                <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                    Editar usuario
                </div>
                <div>
                    <hr class="my-6 mx-4 border-gray-400">
                </div>

                <form method="POST" action="{{ route('usuario.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-rows-1 grid-cols-1 sm:grid-cols-2 gap-4 mt-4 px-6">
                        <div> <!-- row 1 - col 1 -->
                            <x-label class="text-gray-700" for="name" :value="__('Usuario (Nombre y apellido)')" />
                            <x-input name="name" 
                                    class="form-input w-full mt-1" 
                                    type="text"
                                    value="{{ $usuario->name }}" />
                                    @error('name')
                                        <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('name') }}</p>
                                    @enderror
                        </div>
                        <div> <!-- row 1 - col 2 -->
                            <x-label class="text-gray-700" for="email" :value="__('Email')" />
                            <x-input name="email" 
                                    class="form-input w-full mt-1" 
                                    type="email"
                                    value="{{ $usuario->email }}" />
                                    @error('email')
                                        <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('email') }}</p>
                                    @enderror
                        </div>
                    </div>
                    <div class="grid grid-rows-2 grid-cols-1 sm:grid-cols-3 gap-4 mt-4 px-6">
                        <div>   <!-- Row 1 Col 1 -->
                            <x-label for="empresa_id" :value="__('Empresa')" />
                            <x-select id="empresa_id" name="empresa_id" class="w-full h-9 mt-0">
                                <x-slot name="options">
                                    <option value=0>Seleccione empresa...</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" 
                                            {{ $usuario->empresa_id == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->razon_social }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div>   <!-- Row 1 Col 2 -->
                            <x-label for="sucursal_id" :value="__('Sucursal')" />
                            <x-select id="sucursal_id" name="sucursal_id" class="w-full h-9 mt-0">
                                <x-slot name="options">
                                    <option value=0 selected>Seleccione sucursal...</option>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}"
                                            {{ $usuario->sucursal_id == $sucursal->id ? 'selected' : '' }}>
                                            {{ $sucursal->nombre }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div>   <!-- Row 1 Col 3 -->
                            <x-label for="perfil_id" :value="__('Perfil')" />
                            <x-select id="perfil_id" name="perfil_id" class="w-full h-9 mt-0">
                                <x-slot name="options">
                                    <option value=0 selected>Seleccione perfil...</option>
                                    @foreach ($perfiles as $perfil)
                                        <option value="{{ $perfil->id }}"
                                            {{ $usuario->perfil_id == $perfil->id ? 'selected' : '' }}>
                                            {{ $perfil->descripcion }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <div class="col-span-2">   <!-- Row 2 Col 1/2 -->
                            <x-label for="nombre_usuario" :value="__('Alias')" />
                            <x-input type="text"
                                     value="{{ $usuario->nombre_usuario }}"
                                     name="nombre_usuario"
                                     id="nombre_usuario"
                                     autocomplete="off"
                                     class="block mt-0 w-full" />
                        </div>
                        <div>   <!-- Row 2 Col 3 -->
                            <x-label for="estado" :value="__('Estado')" />
                            <x-select id="estado" name="estado" class="w-full h-9 mt-0">
                                <x-slot name="options">
                                    <option value=1 {{ $usuario->estado == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value=0 {{ $usuario->estado == 0 ? 'selected' : '' }}>Baja</option>
                                </x-slot>
                            </x-select>
                        </div>
                    </div>
                    <div class="grid grid-rows-1 grid-cols-1 sm:grid-cols-2 gap-4 mt-4 px-6">
                        <div> <!-- row 2 - col 1 -->
                            <x-label class="text-gray-700" for="password" :value="__('Contraseña')"/>
                            <x-input name="password" 
                                class="form-input w-full mt-1" 
                                type="password"
                                value="{{ $usuario->password }}" />
                            @error('password')
                                <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('password') }}</p>
                            @enderror
                        </div>
                        <div> <!-- row 2 - col 2 -->
                            <x-label class="text-gray-700" for="password" :value="__('Confirmar contraseña')" />
                            <x-input name="password_confirmation" 
                                class="form-input w-full mt-1" 
                                type="password"
                                value="{{ $usuario->password }}" />
                            @error('password_confirmation')
                                <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('password_confirmation') }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <hr class="my-6 mx-4 border-gray-400">
                    </div>

                    <div class="flex justify-end mt-4 mb-6 mr-6">
                        <x-button class="mr-4">
                            Confirmar
                        </x-button>
                        <x-link-cancel href="/usuarios">
                            Salir                    
                        </x-link-cancel>
                    </div>
                </form>
            </div>
    </div>

</x-app-layout>
