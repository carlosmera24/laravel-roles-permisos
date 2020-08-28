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
9. Generar las migraciones que utilizará **laravel-permission**:
    ```
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    ```
    Esto nos generará la migración **XXXX_XX_XX_XXXXXX_create_permission_tables.php**
10. Ejecutar migraciones:
    ```
    php artisan migrate
    ```
    En este punto se geraran las migraciones para el manejo de usuarios y permisos
11. Tablas creadas:
    - failed_jobs
    - migrations
    - nidel_has_permissions
    - model_has_roles
    - password_sets
    - permissions
    - role_has_permissions
    - roles
    - users
12. **Seeders:** Para el ejemplo vamos a utilizar una tabla de productos para simular los permisos:
    - Crear modelo, factory, migración, seeder y controller:
        ```
        php artisan make:model Product -a
        ```
    - Modificar migración ***xxxxxxx_create_products_table.php*
        ```
        public function up()
        {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->timestamps();
            });
        }
        ```
    - Crear archivos seeders:
        ```
        php artisan make:seeder UsersTableSeeder
        php artisan make:seeder ProductsTableSeeder
        php artisan make:seeder PermissionsTableSeeder
        ```
    - Configurar **DatabaseSeeder** en *database/seeds/DatabaseSeeder.php*:
        ```
        public function run()
        {
            $this->call(UsersTableSeeder::class);
            $this->call(ProductsTableSeeder::class);
            $this->call(PermissionsTableSeeder::class);
        }
        ```
    - Configurar **UserTableSeeder** en *database/seeds/UsersTableSeeder.php*:
        ```
        public function run()
        {
            App\User::create([
                                'name'      => 'Usuario Demo',
                                'email'     => 'user@demo.com',
                                'password'     => bcrypt('1234abcd'),

                            ]);

            factory(App\User::class, 7)->create();
        }
        ```
    - Configurar **ProductsTableSeeder** en *database/seeds/ProductsTableSeeder.php*:
        ```
        public function run()
        {
            factory(App\Product::class, 20)->create();
        }
        ```
    - Configurar **PermissionsTableSeeder** en *database/seeds/PermissionsTableSeeder.php*:
        ```
        use Spatie\Permission\Models\Role;
        use Spatie\Permission\Models\Permission;
        use App\User;

        public function run()
        {
            //Permission list
            Permission::create(['name' => 'products.index']);
            Permission::create(['name' => 'products.edit']);
            Permission::create(['name' => 'products.show']);
            Permission::create(['name' => 'products.create']);
            Permission::create(['name' => 'products.destroy']);

            //Admin
            $admin = Role::create(['name' => 'Admin']);

            $admin->givePermissionTo([
                'products.index',
                'products.edit',
                'products.show',
                'products.create',
                'products.destroy'
            ]);
            //$admin->givePermissionTo('products.index');
            //$admin->givePermissionTo(Permission::all());
        
            //Guest
            $guest = Role::create(['name' => 'Guest']);

            $guest->givePermissionTo([
                'products.index',
                'products.show'
            ]);

            //User Admin
            $user = User::find(1); //Usuario Demo
            $user->assignRole('Admin');
        }
        ```
    - Configurar **UserFactory** en *database/factories/UserFactory.php*:
        Por defecto ya está configurado ya que se generó con Auth
    - Configurar **ProductFactory** en *database/factories/ProductFactory.php*:
        ```
        $factory->define(App\Product::class, function (Faker $faker) {
            return [
                'name'             => $faker->sentence,
                'description'     => $faker->text(500),
            ];
        })
        ```
    - Modelo **User** *en app/User.php* agregar:
        ```
        //....
        use Spatie\Permission\Traits\HasRoles;
        //....
        use Notifiable;
        use HasRoles;
        ```        
13. Refrescar nuestra base de datos para llenar nuestra:
    ```
    php artisan migrate:refresh --seed
    ```
14. Archivo de rutas *routes/web.php*:
    ```
    <?php

    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::middleware(['auth'])->group(function () {
    
        Route::post('products/store', 'ProductController@store')->name('products.store')
                                                            ->middleware('permission:products.create');
        Route::get('products', 'ProductController@index')->name('products.index')
                                                            ->middleware('permission:products.index');
        Route::get('products/create', 'ProductController@create')->name('products.create')
                                                            ->middleware('permission:products.create');
        Route::put('products/{role}', 'ProductController@update')->name('products.update')
                                                            ->middleware('permission:products.edit');
        Route::get('products/{role}', 'ProductController@show')->name('products.show')
                                                            ->middleware('permission:products.show');
        Route::delete('products/{role}', 'ProductController@destroy')->name('products.destroy')
                                                            ->middleware('permission:products.destroy');
        Route::get('products/{role}/edit', 'ProductController@edit')->name('products.edit')
                                                            ->middleware('permission:products.edit');
    });
    ```
15. Controlador para el proudcto *app/Http/Controllers/ProductController.php*:
    ```
    public function index()
    {
        $products = Product::get();

        return view('product', compact('products'));
    }

    public function create()
    {
        return 'Tiene permiso de crear';
    }

    public function show(Product $product)
    {
        return 'Tiene permiso de ver';
    }

    public function edit(Product $product)
    {
        return 'Tiene permiso de editar';
    }

    public function destroy(Product $product)
    {
        return 'Tiene permiso de eliminar';
    }
    ```
16. Vista del producto *resources/views/product.blade.php*
