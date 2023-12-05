<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use App\Models\Role;

class RoleAbilityController extends Controller
{
        // Para asignar el rol al usuario...
            # Busco el usuario...
            # $user = User::find($id);
        // Asigno el rol... 
            # ya definido en el modelo User
            # $user->assignRole($role);
        // Para ver todos los roles del usuario...
            # devuelve array con roles asignados
            # $user->roles;
        // Para ver abilities (permisos) de ese rol...
            # $user->roles[0]->abilities;
        // Para ver los permisos (ability) TODOS de un rol (role)
            # $user->abilities();

    /**
     * Crea un rol (Role)
     * 
     * @param  string $role
     * @param  string $label
     * @return class Role
     */
    public function createRole($role, $label)
    {
        $roleClass = Role::firstOrCreate([
            'name' => $role,
            'label' => $label
        ]);

        return $roleClass;
    }

    /** 
     * Para asignar un permiso (ability) a un rol (role)
     * -> $role = Role::find(1);
     * -> $ability = Ability::where('name', 'crear_usuario')->get();
     * -> $role->allowTo($ability);
     *    -> or
     *       -> $role->allowTo('crear_usuario');
     */

     
    /**
     * Crea permiso (Ability)
     * 
     * @param  string $ability
     * @return class Ability
     */
    public function createAbility($ability, $label)
    {
        $abilityClass = Ability::firstOrCreate([
            'name' => $ability,
            'label' => $label
        ]);

        return $abilityClass;
    }

}
