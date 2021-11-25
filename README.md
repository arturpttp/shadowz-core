**Shadowz initializing example**:

```php
include_once __DIR__ . '/../vendor/autoload.php';

use Core\Application;
use Core\System\Config;
use Core\System\Request;
use Core\System\Router;
use Core\Utils\Session;

include_once __DIR__."/functions.php";
include_once __DIR__."/config.php";

#Starting Configs
$config = Config::load();
#Starting Sessions
$session = Session::start();
#Starting Request
$request = Request::start();

#Starting Application
$application = new Application();
$application->init();

#Routing
$routes = require_once __DIR__ . '/../app/routes.php'; #Your app folder app/routes.php 
$router = new Router($routes, $application);
$router->load();
```

**Creating a config**

In your app folder create a folder called `configs` inside it, you should create a `.php` file witch should return an array

```php
return [
    'driver' => 'mysql',
    'options' => [
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        \PDO::ATTR_CASE => PDO::CASE_NATURAL,
        \PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        \PDO::ATTR_STRINGIFY_FETCHES => false,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ],
    'mysql' => [
        'host' => 'localhost',
        'database' => 'framework',
        'user' => 'root',
        'password' => ''
    ],

    'sqlite' => [
        'host' => 'database.db',
        'database' => 'storage/database/database.db'
    ]
];
```

**Routing**

This is how your `app/routes.php` should look like

```php
# ['Controller@Method', 'url', ['auth' => true, 'level' => 1]]

$routes[] = ['HomeController@index', '/'];
$routes[] = ['HomeController@contact', '/contact'];
$routes[] = ['UsersController@register', '/register'];
$routes[] = ['UsersController@login', '/login'];
$routes[] = ['UsersController@profile', '/profile', ['auth' => true, 'level' => 1]];
$routes[] = ['BlogController@show', '/posts/{id}/show'];

return $routes;
```

**Controllers**

This is how your controllers should be

```php
use Core\Application;
use Core\Bases\Controller;
use Core\Bases\View;
use Core\Utils\Html;

class HomeController extends Controller
{

    public function index($request, $response)
    {
        $this->view->name = "Your name";
        $this->view->header = false;
        $this->view->footer = false;
        $this->setTitle("Home");
        $this->renderView('home/index');
    }

    public function contact()
    {
        $this->view->header = false;
        $this->view->footer = false;
        $this->setTitle("Contact us");
        $this->renderView('home/contact');
    }

    public function test($name, $request, $response) {
        echo $name;
    }

}
```

**Models**

This is how your models should be

```php
use Core\Bases\Model;
use Core\System\User\User;

class UsersModel extends Model
{

    protected false|string $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }


    public function getUser($where): User
    {
        return $this->findToObject($where, User::class);
    }

}
```