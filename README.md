# MVC 1.0

# Роутинг

Роуты указываются в файле `\Routes\Routes.php`  

### Пример роута:  
`Route::route('/example', HomeController::class, 'example')`  
`/example` - URL  
`HomeController` - имя класса контроллера (путь: `\App\Controllers`)  
`example` - метод в контроллере  

### Параметры:
#### строка
url: `/example/(\w)`  
  
`public function exapmleString(string $str) {} `

#### число
url: `/example/(\d)`

`public function exapmleString(int $number) {} `

#### комбинированные
url: `/example/(\d)/params/(\w)`

`public function exapmleString(int $number, string $str) {} `

# MIDDLEWARES

Все мидлевейры должны быть наследованы от интерфейса `MiddleWare`  
Папка с классами `\App\MiddleWares`

Пример в файле `Routes`

`Route::route('/', HomeController::class)->middleWare(TestMiddle::class);`

# MIGRATIONS
`php `