<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Gerente']);
        $role2 = Role::create(['name' => 'Cajero']);

        Permission::create([
            'name' => 'users.index',
            'description' => 'Ver lista de usuarios'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'users.create',
            'description' => 'Crear un usuario'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'users.edit',
            'description' => 'Editar usuario'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'users.destroy',
            'description' => 'Eliminar usuario'
        ])->syncRoles([$role1]);        

        Permission::create([
            'name' => 'sucursales.index',
            'description' => 'Ver lista de sucursales'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'sucursales.create',
            'description' => 'Crear sucursales'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'sucursales.edit',
            'description' => 'Editar sucursales'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'sucursales.destroy',
            'description' => 'Eliminar sucursales'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'productos.index',
            'description' => 'Ver lista de productos'
        ])->syncRoles([$role1,$role2]);
        Permission::create([
            'name' => 'productos.create',
            'description' => 'Crear productos'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'productos.edit',
            'description' => 'Editar productos'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'productos.destroy',
            'description' => 'Eliminar productos'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'proveedores.index',
            'description' => 'Ver lista de proveedores'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'proveedores.create',
            'description' => 'Crear proveedores'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'proveedores.edit',
            'description' => 'Editar proveedores'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'proveedores.destroy',
            'description' => 'Eliminar proveedores'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'tipogastos.index',
            'description' => 'Ver lista de tipogastos'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'tipogastos.create',
            'description' => 'Crear tipogastos'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'tipogastos.edit',
            'description' => 'Editar tipogastos'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'tipogastos.destroy',
            'description' => 'Eliminar tipogastos'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'compras.index',
            'description' => 'Ver lista de compras'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'compras.create',
            'description' => 'Crear compras'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'compras.edit',
            'description' => 'Editar compras'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'compras.destroy',
            'description' => 'Eliminar compras'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ventas.index',
            'description' => 'Ver lista de ventas'
        ])->syncRoles([$role1, $role2]);
        Permission::create([
            'name' => 'ventas.create',
            'description' => 'Crear ventas'
        ])->syncRoles([$role1, $role2]);
        Permission::create([
            'name' => 'ventas.edit',
            'description' => 'Editar ventas'
        ])->syncRoles([$role1, $role2]);
        Permission::create([
            'name' => 'ventas.destroy',
            'description' => 'Eliminar ventas'
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'gastos.create',
            'description' => 'Adicionar Gastos'
        ])->syncRoles([$role1]);        

        //recepciones
        Permission::create([
            'name' => 'recepciones.index',
            'description' => 'Listar Recepciones'
        ])->syncRoles([$role1,$role2]);
        Permission::create([
            'name' => 'recepciones.create',
            'description' => 'Registrar Recepciones'
        ])->syncRoles([$role1,$role2]);        

        Permission::create([
            'name' => 'graficas.metas',
            'description' => 'Ver metas de vendedores'
        ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'reportes.ventas_ganancias_productos',
            'description' => 'Ver grafica ventas ganancias por productos'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'reportes.ventas_ganancias_sucursal',
            'description' => 'Ver grafica ventas ganancias por sucursales'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'reportes.formdemandas',
            'description' => 'Ver formulario de demandas'
        ])->syncRoles([$role1]);
    }
}
