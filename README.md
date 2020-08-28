# Laravel roles y permisos
Implementación de roles y permisos utilizando Laravel 7.25.0:
1. Utilizamos Auth de laravel
2. El paquete **laravel-permission** de la empresa **Spatie**

## Implementación:
1. Partimos de una instalación limpia de Laravel:
    ```
    mkdir laravel-custom-login
    composer create-project --prefer-dist laravel/laravel laravel-roles-permisos
    ```
2. Crear la base de datos
    ```
    mysql> CREATE SCHEMA IF NOT EXISTS `roles_permisos_db` DEFAULT CHARACTER SET utf8;
    ```
    > Recordemos configurar los datos de la base de datos en el archivo .env
3. Instalar laravel/ui el cual nos permitirá instalar Auth.
    ```
    composer require laravel/ui    
    ```
4. Instalar Auth con todas los recursos para trabajar solo con bootstrap:
    ```
    php artisan ui bootstrap --auth
    ```
5. Instalar dependencias front
    ```
    npm install && npm run dev
    ```
    >A ésta altura tenemos instalado el sistema de autenticación con el login y registro, pero no tenemos las tablas necesarias para el trabajo.
6. Configurar el **Service provider** en *config/app.php* agregando la siguiente línea:
    ```
    <?php
        return [

            'providers' => [
                // ...
                Spatie\Permission\PermissionServiceProvider::class,
            ],

        ];
    ```
7. Configurar el **middleware** en *app/Http/Kernel.php*:
    ```
    <?php
    namespace App\Http;

    use Illuminate\Foundation\Http\Kernel as HttpKernel;

    class Kernel extends HttpKernel
    {
        protected $routeMiddleware = [
            // ...
            'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        ];

    }
    ```
8. Instalar el componente **spatie/laravel-permission**
    ```
    composer require spatie/laravel-permission
    ```

