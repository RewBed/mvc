# MVC
mvc

# MIGRATIONS
`php `

# MIDDLEWARES

Все мидлевейры должны быть наследованы от интерфейса `MiddleWare`

Инициализация в файле `Routes`

`Route::route('/', HomeController::class)->middleWare(TestMiddle::class);`
