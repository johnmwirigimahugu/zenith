
<?php

/*
|--------------------------------------------------------------------------
| ZEN FRAMEWORK - USAGE INSTRUCTIONS
|--------------------------------------------------------------------------
|
| This file contains usage instructions for the Zen Framework.
| Follow these examples to get started with building web applications.
|
*/

/*
|--------------------------------------------------------------------------
| BASIC SETUP
|--------------------------------------------------------------------------
|
| 1. Save the code above as zen.php in your project root
| 2. Create a .env file with your configuration:
|
| APP_ENV=development
| APP_DEBUG=true
| APP_KEY=your-secret-key-here
|
| DB_DRIVER=mysql
| DB_HOST=localhost
| DB_PORT=3306
| DB_DATABASE=zen
| DB_USERNAME=root
| DB_PASSWORD=password
|
| CACHE_DRIVER=file
| CACHE_PATH=/tmp/zen_cache
|
| SESSION_DRIVER=file
| SESSION_PATH=/tmp/zen_sessions
|
| LOG_DRIVER=file
| LOG_PATH=/tmp/zen_logs
|
*/

/*
|--------------------------------------------------------------------------
| CREATING ROUTES
|--------------------------------------------------------------------------
|
| In your index.php or routes.php file:
|
| require_once 'zen.php';
|
| $app = new ZenApp();
|
| // Define routes
| $app->getRouter()->get('/', function() {
|     return view('welcome');
| });
|
| $app->getRouter()->get('/users', function() {
|     $users = User::all();
|     return view('users', ['users' => $users]);
| });
|
| $app->getRouter()->get('/users/{id}', function($id) {
|     $user = User::find($id);
|     return view('user', ['user' => $user]);
| });
|
| $app->run();
|
*/

/*
|--------------------------------------------------------------------------
| CREATING MODELS
|--------------------------------------------------------------------------
|
| class User extends ZenModel
| {
|     protected string $table = 'users';
|     protected array $fillable = ['name', 'email', 'password'];
|     protected array $hidden = ['password'];
| }
|
*/

/*
|--------------------------------------------------------------------------
| USING THE ORM
|--------------------------------------------------------------------------
|
| // Find a user
| $user = User::find(1);
|
| // Create a new user
| $user = new User();
| $user->name = 'John Doe';
| $user->email = 'john@example.com';
| $user->password = password_hash('password', PASSWORD_DEFAULT);
| $user->save();
|
| // Query users
| $users = User::query()->where('active', 1)->orderBy('name')->get();
|
*/

/*
|--------------------------------------------------------------------------
| USING THE VIEW SYSTEM
|--------------------------------------------------------------------------
|
| // In a route
| return ZenView::make('welcome', ['name' => 'Zen Framework']);
|
| // In a view file (views/welcome.php)
| <h1>Welcome, <?= $name ?>!</h1>
|
*/

/*
|--------------------------------------------------------------------------
| USING VALIDATION
|--------------------------------------------------------------------------
|
| // In a controller
| $validator = ZenValidator::make($request->all(), [
|     'name' => 'required|string|max:255',
|     'email' => 'required|email|unique:users',
|     'password' => 'required|min:6|confirmed',
| ]);
|
| if ($validator->fails()) {
|     return redirect('/register')->with('errors', $validator->errors());
| }
|
*/

/*
|--------------------------------------------------------------------------
| USING AUTHENTICATION
|--------------------------------------------------------------------------
|
| // Login
| if ($auth->attempt($credentials)) {
|     return redirect('/dashboard');
| }
|
| // Get current user
| $user = $auth->user();
|
| // Check if user is authenticated
| if ($auth->check()) {
|     // User is logged in
| }
|
*/

/*
|--------------------------------------------------------------------------
| USING THE QUEUE
|--------------------------------------------------------------------------
|
| // Push a job
| $queue->push(SendEmailJob::class, ['user_id' => $user->id]);
|
| // Process jobs
| $queue->process(10);
|
*/

/*
|--------------------------------------------------------------------------
| USING EVENTS
|--------------------------------------------------------------------------
|
| // Register a listener
| ZenEvent::listen('user.registered', SendWelcomeEmailListener::class);
|
| // Dispatch an event
| ZenEvent::dispatch('user.registered', ['user' => $user]);
|
*/

/*
|--------------------------------------------------------------------------
| CLI COMMANDS
|--------------------------------------------------------------------------
|
| # Start development server
| php zen.php serve localhost 8000
|
| # Run migrations
| php zen.php migrate
|
| # Process queue jobs
| php zen.php queue:work 10
|
| # Health check
| php zen.php health
|
*/

/*
|--------------------------------------------------------------------------
| FEATURES INCLUDED
|--------------------------------------------------------------------------
|
| • Configuration Management - Environment-based configuration
| • Dependency Injection - Simple DI container
| • Routing - HTTP method routing with parameters
| • Middleware - Pipeline middleware system
| • Templating - PHP-based templating engine
| • ORM - ActiveRecord-style ORM with query builder
| • Authentication - Session-based authentication
| • Authorization - Role-based access control
| • Validation - Input validation engine
| • Caching - File-based cache system
| • Queue - Simple job queue system
| • Events - Event dispatcher and listeners
| • Logging - Structured logging
| • CLI - Built-in CLI commands
|
*/

/*
|--------------------------------------------------------------------------
| FRAMEWORK OVERVIEW
|--------------------------------------------------------------------------
|
| This framework provides a solid foundation for building web applications
| while maintaining simplicity and clarity. It's designed to be production-safe
| while remaining easy to understand and extend.
|
*/
?>
<?php

/*
|--------------------------------------------------------------------------
| ZEN FRAMEWARE 3.0 - COMPREHENSIVE DOCUMENTATION
|--------------------------------------------------------------------------
|
| Table of Contents:
| 1. Introduction
| 2. Installation & Setup
| 3. Architecture Overview
| 4. Core Components
| 5. API Reference
| 6. Examples & Tutorials
| 7. Best Practices
| 8. Security Considerations
| 9. Performance Optimization
| 10. Testing
| 11. Deployment
| 12. Contributing
| 13. FAQ
| 14. Changelog
|
*/

/*
|--------------------------------------------------------------------------
| 1. INTRODUCTION
|--------------------------------------------------------------------------
|
| Zen Framework is a single-file, enterprise-grade PHP framework that compresses
| the core capabilities of mature web frameworks such as Django, Ruby on Rails,
| Flask, and CodeIgniter into one cohesive system.
|
| Key Principles:
| • Simplicity without sacrificing power
| • Production-ready security
| • Explicit over implicit behavior
| • Single-file architecture for easy distribution
| • Modern PHP 8+ features
|
| Design Philosophy:
| Zen Framework follows the "pragmatic minimalism" approach - providing
| just enough abstraction to be productive while maintaining full control
| and understanding of the underlying mechanisms.
|
| What Zen Framework Provides:
| • Full-stack MVC architecture
| • ActiveRecord ORM with query builder
| • Template engine with layouts and components
| • Authentication and authorization system
| • Validation engine
| • Caching layer
| • Queue system for background jobs
| • Event system
| • CLI tools
| • Comprehensive logging
|
| What Zen Framework Is NOT:
| • A framework with magical auto-discovery
| • A replacement for specialized tools
| • A micro-framework with minimal features
| • A framework that hides complexity
|
*/

/*
|--------------------------------------------------------------------------
| 2. INSTALLATION & SETUP
|--------------------------------------------------------------------------
|
| Requirements:
| • PHP 8.0 or higher
| • PDO extension
| • Required database drivers (mysql, pgsql, sqlite)
| • Web server (Apache, Nginx, or PHP built-in server)
|
| Installation Steps:
|
| 1. Download the Framework
|    Save the zen.php file in your project root
|
| 2. Create Project Structure
|    /project-root
|    ├── zen.php                 # Framework file
|    ├── .env                    # Environment configuration
|    ├── index.php               # Entry point
|    ├── public/                 # Public assets
|    ├── views/                  # View templates
|    ├── storage/                # Cache, logs, sessions
|    └── config/                 # Additional configuration
|
| 3. Configure Environment
|    Create a .env file:
|
|    APP_ENV=development
|    APP_DEBUG=true
|    APP_KEY=base64:your-32-byte-key
|
|    DB_DRIVER=mysql
|    DB_HOST=localhost
|    DB_PORT=3306
|    DB_DATABASE=zen_app
|    DB_USERNAME=username
|    DB_PASSWORD=password
|
|    CACHE_DRIVER=file
|    CACHE_PATH=./storage/cache
|
|    SESSION_DRIVER=file
|    SESSION_PATH=./storage/sessions
|    SESSION_LIFETIME=120
|
|    LOG_DRIVER=file
|    LOG_PATH=./storage/logs
|
| 4. Create Entry Point
|    Create index.php:
|
|    <?php
|    require_once 'zen.php';
|
|    $app = new ZenApp();
|
|    // Define routes
|    $app->getRouter()->get('/', function() {
|        return 'Hello, Zen Framework!';
|    });
|
|    $app->run();
|
| 5. Set Up Web Server
|
|    Apache (.htaccess):
|    RewriteEngine On
|    RewriteCond %{REQUEST_FILENAME} !-f
|    RewriteCond %{REQUEST_FILENAME} !-d
|    RewriteRule ^(.*)$ index.php [QSA,L]
|
|    Nginx:
|    location / {
|        try_files $uri $uri/ /index.php?$query_string;
|    }
|
| 6. Generate Application Key
|    php -r "echo base64_encode(random_bytes(32));"
|    Copy the output to APP_KEY in .env
|
*/

/*
|--------------------------------------------------------------------------
| 3. ARCHITECTURE OVERVIEW
|--------------------------------------------------------------------------
|
| Request Lifecycle:
| 1. Request → Router → Middleware → Controller
| 2. Controller → Model → Database
| 3. Controller → View → Response
| 4. Response → Client
|
| Core Components:
|
| ┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
| │   ZenApp        │    │   ZenContainer   │    │   ZenRouter     │
| │                 │    │                  │    │                 │
| │ • Application   │◄──►│ • DI Container   │◄──►│ • Route Matching │
| │   Kernel        │    │ • Service Binding│    │ • Parameters    │
| │ • Bootstrapping │    │ • Auto-resolution│    │ • Middleware    │
| └─────────────────┘    └──────────────────┘    └─────────────────┘
|
| ┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
| │   ZenRequest    │    │   ZenResponse    │    │   ZenView       │
| │                 │    │                  │    │                 │
| │ • Input Handling│◄──►│ • Output         │◄──►│ • Templates     │
| │ • Validation    │    │ • Headers        │    │ • Layouts       │
| │ • Files         │    │ • Status Codes   │    │ • Components    │
| └─────────────────┘    └──────────────────┘    └─────────────────┘
|
| ┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
| │   ZenModel      │    │   ZenDatabase    │    │   ZenCache      │
| │                 │    │                  │    │                 │
| │ • ActiveRecord  │◄──►│ • PDO Wrapper    │◄──►│ • File Cache    │
| │ • Relations     │    │ • Query Builder  │    │ • TTL Support   │
| │ • Events        │    │ • Transactions   │    │ • Namespacing   │
| └─────────────────┘    └──────────────────┘    └─────────────────┘
|
| Design Patterns Used:
| • Dependency Injection Container
| • Active Record Pattern
| • Front Controller Pattern
| • Model-View-Controller (MVC)
| • Pipeline Pattern (Middleware)
| • Observer Pattern (Events)
| • Repository Pattern (Query Builder)
|
*/

/*
|--------------------------------------------------------------------------
| 4. CORE COMPONENTS
|--------------------------------------------------------------------------
|
| 4.1 ZenApp (Application Kernel)
| --------------------------------
| The central kernel that orchestrates all framework components.
|
| Methods:
| • boot() - Initialize the application
| • handle() - Process incoming request
| • terminate() - Clean up and shutdown
| • run() - Execute the full request lifecycle
|
| Usage:
| $app = new ZenApp();
| $app->run();
|
| 4.2 ZenContainer (Dependency Injection)
| ---------------------------------------
| Simple but powerful DI container for service management.
|
| Methods:
| • bind($abstract, $concrete, $shared) - Register a binding
| • singleton($abstract, $concrete) - Register a singleton
| • instance($abstract, $instance) - Register an instance
| • make($abstract) - Resolve a dependency
| • call($callback, $parameters) - Call with injection
|
| Example:
| $container->singleton('db', function() {
|     return new PDO('mysql:host=localhost', 'user', 'pass');
| });
|
| $db = $container->make('db');
|
| 4.3 ZenRouter (Routing System)
| -------------------------------
| HTTP method routing with parameters and middleware support.
|
| Methods:
| • get($uri, $action) - Register GET route
| • post($uri, $action) - Register POST route
| • put($uri, $action) - Register PUT route
| • delete($uri, $action) - Register DELETE route
| • group($attributes, $callback) - Route group
| • middleware($name, $handler) - Register middleware
| • dispatch($request) - Route the request
|
| Route Parameters:
| $app->getRouter()->get('/users/{id}', function($id) {
|     return "User ID: $id";
| });
|
| Route Groups:
| $app->getRouter()->group(['prefix' => 'api', 'middleware' => 'auth'], function($router) {
|     $router->get('/users', 'UserController@index');
| });
|
| 4.4 ZenRequest & ZenResponse (HTTP Layer)
| -----------------------------------------
| Request input handling and response generation.
|
| ZenRequest Methods:
| • all() - All input
| • input($key, $default) - Get input value
| • query($key, $default) - Get query parameter
| • post($key, $default) - Get POST data
| • file($key) - Get uploaded file
| • header($key, $default) - Get header
| • method() - HTTP method
| • path() - Request path
| • ip() - Client IP
| • ajax() - Is AJAX request
| • json() - JSON payload
|
| ZenResponse Methods:
| • make($content, $status, $headers) - Create response
| • json($data, $status) - JSON response
| • redirect($url, $status) - Redirect response
| • download($path, $name) - File download
| • header($key, $value) - Set header
| • send() - Send response
|
| 4.5 ZenModel (ActiveRecord ORM)
| -------------------------------
| Database abstraction with ActiveRecord pattern.
|
| Properties:
| • $table - Table name
| • $primaryKey - Primary key field
| • $fillable - Mass-assignable fields
| • $hidden - Hidden fields
| • $casts - Type casting
| • $timestamps - Auto timestamps
|
| Methods:
| • find($id) - Find by primary key
| • all() - Get all records
| • where($column, $operator, $value) - Query builder
| • save() - Save model
| • delete() - Delete model
| • create($attributes) - Create and save
| • update($attributes) - Update record
|
| Example:
| class User extends ZenModel {
|     protected $table = 'users';
|     protected $fillable = ['name', 'email'];
| }
|
| $user = User::find(1);
| $users = User::where('active', 1)->get();
|
| 4.6 ZenView (Templating Engine)
| -------------------------------
| PHP-based templating with layouts and components.
|
| Methods:
| • make($path, $data) - Create view
| • layout($name) - Set layout
| • section($name) - Start section
| • endSection() - End section
| • yield($name, $default) - Yield section
| • include($path, $data) - Include partial
| • escape($value) - Escape output
|
| Example Template:
| <?php $__view->extend('layouts.app') ?>
|
| <?php $__view->section('content') ?>
| <h1><?= $title ?></h1>
| <?php $__view->endSection() ?>
|
| 4.7 ZenValidator (Validation Engine)
| ------------------------------------
| Input validation with rule-based system.
|
| Rules:
| • required - Field is required
| • email - Valid email
| • min:value - Minimum length/value
| • max:value - Maximum length/value
| • unique:table,column - Unique in database
| • exists:table,column - Exists in database
| • confirmed - Must have confirmation field
| • regex:pattern - Match regex pattern
|
| Methods:
| • make($data, $rules, $messages) - Create validator
| • validate() - Validate and throw on failure
| • fails() - Check if validation fails
| • errors() - Get error messages
|
| Example:
| $validator = ZenValidator::make($request->all(), [
|     'name' => 'required|max:255',
|     'email' => 'required|email|unique:users',
| ]);
|
| if ($validator->fails()) {
|     return redirect()->back()->with('errors', $validator->errors());
| }
|
| 4.8 ZenAuth (Authentication)
| -----------------------------
| Session-based authentication system.
|
| Methods:
| • user() - Get current user
| • check() - Check if authenticated
| • guest() - Check if guest
| • attempt($credentials) - Attempt login
| • login($user) - Log in user
| • logout() - Log out user
| • validate($credentials) - Validate credentials
|
| Example:
| if ($auth->attempt(['email' => $email, 'password' => $password])) {
|     return redirect('/dashboard');
| }
|
| 4.9 ZenCache (Caching Layer)
| ----------------------------
| File-based caching with TTL support.
|
| Methods:
| • get($key, $default) - Get cached value
| • put($key, $value, $ttl) - Store value
| • has($key) - Check if key exists
| • forget($key) - Remove key
| • clear() - Clear all cache
| • remember($key, $callback, $ttl) - Get or store
|
| Example:
| $users = $cache->remember('users.all', function() {
|     return User::all();
| }, 3600);
|
| 4.10 ZenQueue (Job Queue)
| -------------------------
| Simple job queue for background processing.
|
| Methods:
| • push($job, $data) - Push job to queue
| • later($delay, $job, $data) - Delayed job
| • process($maxJobs) - Process jobs
|
| Job Interface:
| interface ZenJobInterface {
|     public function handle(array $data): void;
| }
|
| Example:
| class SendEmailJob implements ZenJobInterface {
|     public function handle(array $data): void {
|         mail($data['to'], $data['subject'], $data['body']);
|     }
| }
|
| $queue->push(SendEmailJob::class, [
|     'to' => 'user@example.com',
|     'subject' => 'Welcome',
|     'body' => 'Welcome to our app!'
| ]);
|
| 4.11 ZenEvent (Event System)
| -----------------------------
| Event dispatcher for loose coupling.
|
| Methods:
| • listen($event, $listener) - Register listener
| • dispatch($event, $data) - Fire event
|
| Example:
| ZenEvent::listen('user.registered', function($data) {
|     // Send welcome email
| });
|
| ZenEvent::dispatch('user.registered', ['user' => $user]);
|
*/

/*
|--------------------------------------------------------------------------
| 5. API REFERENCE
|--------------------------------------------------------------------------
|
| 5.1 Configuration API
| ----------------------
| ZenConfig::get($key, $default) - Get config value
| ZenConfig::set($key, $value) - Set config value
| ZenConfig::env() - Get environment
| ZenConfig::isProduction() - Check production
| ZenConfig::isDevelopment() - Check development
|
| 5.2 Database API
| ----------------
| ZenDatabase::getConnection() - Get PDO instance
| ZenDatabase::beginTransaction() - Start transaction
| ZenDatabase::commit() - Commit transaction
| ZenDatabase::rollback() - Rollback transaction
| ZenDatabase::statement($query, $bindings) - Execute statement
| ZenDatabase::select($query, $bindings) - Select query
| ZenDatabase::selectOne($query, $bindings) - Select one
| ZenDatabase::insert($query, $bindings) - Insert record
| ZenDatabase::affectingStatement($query, $bindings) - Update/Delete
|
| 5.3 Query Builder API
| ---------------------
| ZenQueryBuilder::select($columns) - Select columns
| ZenQueryBuilder::where($column, $operator, $value) - Where clause
| ZenQueryBuilder::orWhere($column, $operator, $value) - Or where
| ZenQueryBuilder::whereIn($column, $values) - Where in
| ZenQueryBuilder::orderBy($column, $direction) - Order by
| ZenQueryBuilder::limit($limit) - Limit results
| ZenQueryBuilder::offset($offset) - Offset results
| ZenQueryBuilder::get() - Get results
| ZenQueryBuilder::first() - Get first result
| ZenQueryBuilder::count() - Count results
| ZenQueryBuilder::insert($values) - Insert
| ZenQueryBuilder::update($values) - Update
| ZenQueryBuilder::delete() - Delete
|
| 5.4 Session API
| ---------------
| ZenSession::start() - Start session
| ZenSession::get($key, $default) - Get session value
| ZenSession::put($key, $value) - Put session value
| ZenSession::has($key) - Check if key exists
| ZenSession::forget($key) - Remove key
| ZenSession::flash($key, $value) - Flash data
| ZenSession::getFlash($key, $default) - Get flash data
| ZenSession::regenerate() - Regenerate ID
| ZenSession::destroy() - Destroy session
|
| 5.5 Mail API
| ------------
| ZenMail::to($address) - Create mail
| ZenMail::subject($subject) - Set subject
| ZenMail::body($body) - Set body
| ZenMail::html($html) - Set HTML body
| ZenMail::attach($path, $name) - Attach file
| ZenMail::send() - Send email
|
| 5.6 CLI API
| -----------
| ZenCLI::register($name, $handler) - Register command
| ZenCLI::run() - Run CLI
|
| Built-in Commands:
| • serve - Start development server
| • migrate - Run migrations
| • queue:work - Process queue jobs
| • health - Health check
|
*/

/*
|--------------------------------------------------------------------------
| 6. EXAMPLES & TUTORIALS
|--------------------------------------------------------------------------
|
| 6.1 Creating a Blog Application
| -------------------------------
|
| Step 1: Database Migration
| CREATE TABLE posts (
|     id INT AUTO_INCREMENT PRIMARY KEY,
|     title VARCHAR(255) NOT NULL,
|     content TEXT NOT NULL,
|     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
|     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
| );
|
| Step 2: Create Model
| class Post extends ZenModel {
|     protected $table = 'posts';
|     protected $fillable = ['title', 'content'];
| }
|
| Step 3: Create Routes
| $app->getRouter()->get('/posts', function() {
|     $posts = Post::orderBy('created_at', 'desc')->get();
|     return view('posts.index', ['posts' => $posts]);
| });
|
| $app->getRouter()->get('/posts/{id}', function($id) {
|     $post = Post::find($id);
|     if (!$post) {
|         return response('Not Found', 404);
|     }
|     return view('posts.show', ['post' => $post]);
| });
|
| $app->getRouter()->get('/posts/create', function() {
|     return view('posts.create');
| });
|
| $app->getRouter()->post('/posts', function() {
|     $validator = ZenValidator::make(request()->all(), [
|         'title' => 'required|max:255',
|         'content' => 'required',
|     ]);
|
|     if ($validator->fails()) {
|         return redirect('/posts/create')->with('errors', $validator->errors());
|     }
|
|     $post = Post::create(request()->all());
|     return redirect("/posts/{$post->id}");
| });
|
| Step 4: Create Views
| views/posts/index.php:
| <h1>Blog Posts</h1>
| <?php foreach ($posts as $post): ?>
|     <article>
|         <h2><?= $post->title ?></h2>
|         <p><?= substr($post->content, 0, 200) ?>...</p>
|         <a href="/posts/<?= $post->id ?>">Read More</a>
|     </article>
| <?php endforeach ?>
|
| views/posts/show.php:
| <article>
|     <h1><?= $post->title ?></h1>
|     <p><?= $post->content ?></p>
|     <small>Posted: <?= $post->created_at ?></small>
| </article>
|
| views/posts/create.php:
| <h1>Create Post</h1>
| <?php if (session()->getFlash('errors')): ?>
|     <div class="errors">
|         <?php foreach (session()->getFlash('errors') as $errors): ?>
|             <p><?= $error ?></p>
|         <?php endforeach ?>
|     </div>
| <?php endif ?>
| <form method="POST" action="/posts">
|     <div>
|         <label>Title:</label>
|         <input type="text" name="title" required>
|     </div>
|     <div>
|         <label>Content:</label>
|         <textarea name="content" required></textarea>
|     </div>
|     <button type="submit">Create Post</button>
| </form>
|
| 6.2 Building an API
| -------------------
|
| API Routes:
| $app->getRouter()->group(['prefix' => 'api'], function($router) {
|     $router->get('/users', function() {
|         $users = User::all();
|         return response()->json($users);
|     });
|
|     $router->get('/users/{id}', function($id) {
|         $user = User::find($id);
|         if (!$user) {
|             return response()->json(['error' => 'User not found'], 404);
|         }
|         return response()->json($user);
|     });
|
|     $router->post('/users', function() {
|         $validator = ZenValidator::make(request()->all(), [
|             'name' => 'required|max:255',
|             'email' => 'required|email|unique:users',
|         ]);
|
|         if ($validator->fails()) {
|             return response()->json([
|                 'error' => 'Validation failed',
|                 'errors' => $validator->errors()
|             ], 422);
|         }
|
|         $user = User::create(request()->all());
|         return response()->json($user, 201);
|     });
| });
|
| API Middleware:
| class ApiAuthMiddleware implements ZenMiddlewareInterface {
|     public function handle($request, Closure $next) {
|         $token = $request->header('Authorization');
|         if (!$token || !$this->validateToken($token)) {
|             return response()->json(['error' => 'Unauthorized'], 401);
|         }
|         return $next($request);
|     }
| }
|
| $app->getRouter()->middleware('api.auth', ApiAuthMiddleware::class);
|
| 6.3 Authentication Example
| -------------------------
|
| Registration:
| $app->getRouter()->get('/register', function() {
|     return view('auth.register');
| });
|
| $app->getRouter()->post('/register', function() {
|     $validator = ZenValidator::make(request()->all(), [
|         'name' => 'required|max:255',
|         'email' => 'required|email|unique:users',
|         'password' => 'required|min:6|confirmed',
|     ]);
|
|     if ($validator->fails()) {
|         return redirect('/register')->with('errors', $validator->errors());
|     }
|
|     $user = User::create([
|         'name' => request()->input('name'),
|         'email' => request()->input('email'),
|         'password' => password_hash(request()->input('password'), PASSWORD_DEFAULT),
|     ]);
|
|     auth()->login($user);
|     return redirect('/dashboard');
| });
|
| Login:
| $app->getRouter()->get('/login', function() {
|     return view('auth.login');
| });
|
| $app->getRouter()->post('/login', function() {
|     if (auth()->attempt([
|         'email' => request()->input('email'),
|         'password' => request()->input('password')
|     ])) {
|         return redirect('/dashboard');
|     }
|
|     return redirect('/login')->with('error', 'Invalid credentials');
| });
|
| Protected Routes:
| $app->getRouter()->middleware('auth', function($request, $next) {
|     if (!auth()->check()) {
|         return redirect('/login');
|     }
|     return $next($request);
| });
|
| $app->getRouter()->get('/dashboard', function() {
|     $user = auth()->user();
|     return view('dashboard', ['user' => $user]);
| });
|
*/

/*
|--------------------------------------------------------------------------
| 7. BEST PRACTICES
|--------------------------------------------------------------------------
|
| 7.1 Code Organization
| ---------------------
| • Keep controllers thin - move business logic to services
| • Use repositories for complex queries
| • Implement proper error handling
| • Follow PSR-4 autoloading standards
| • Use dependency injection for testability
|
| 7.2 Security
| ------------
| • Always validate user input
| • Use prepared statements (handled by ORM)
| • Implement CSRF protection
| • Hash passwords properly
| • Use HTTPS in production
| • Implement rate limiting
| • Sanitize output (automatic in views)
|
| 7.3 Performance
| ---------------
| • Use caching for expensive operations
| • Optimize database queries
| • Implement lazy loading where appropriate
| • Use file uploads with size limits
| • Implement pagination for large datasets
| • Use compression for responses
|
| 7.4 Database Design
| -------------------
| • Use appropriate indexes
| • Normalize data properly
| • Use foreign key constraints
| • Implement soft deletes where needed
| • Use transactions for multi-table operations
| • Consider read replicas for scaling
|
| 7.5 API Design
| -------------
| • Use proper HTTP methods
| • Return appropriate status codes
| • Version your APIs
| • Implement rate limiting
| • Use consistent response formats
| • Document your endpoints
|
*/

/*
|--------------------------------------------------------------------------
| 8. SECURITY CONSIDERATIONS
|--------------------------------------------------------------------------
|
| 8.1 Built-in Security Features
| ------------------------------
| • CSRF Protection - Automatic token generation and validation
| • XSS Prevention - Output escaping in views
| • SQL Injection Prevention - Prepared statements via PDO
| • Secure Headers - CSP, HSTS, X-Frame-Options
| • Rate Limiting - Configurable request limits
| • Password Hashing - Bcrypt algorithm
| • Input Validation - Comprehensive validation rules
|
| 8.2 Security Checklist
| -----------------------
| □ Set APP_KEY to a random 32-byte string
| □ Use HTTPS in production
| □ Implement proper authentication
| □ Validate all user input
| □ Sanitize all output
| □ Use parameterized queries
| □ Implement rate limiting
| □ Set secure cookie flags
| □ Configure CSP headers
| □ Regularly update dependencies
| □ Implement proper error handling
| □ Use file upload restrictions
|
| 8.3 Common Vulnerabilities
| --------------------------
| • SQL Injection - Prevented by ORM
| • XSS - Prevented by view escaping
| • CSRF - Prevented by middleware
| • Directory Traversal - Validate file paths
| • Insecure Deserialization - Avoid unserialize()
| • Broken Authentication - Use built-in auth
| • Sensitive Data Exposure - Use HTTPS
| • Security Misconfiguration - Follow checklist
|
*/

/*
|--------------------------------------------------------------------------
| 9. PERFORMANCE OPTIMIZATION
|--------------------------------------------------------------------------
|
| 9.1 Caching Strategies
| ---------------------
| • Query Result Caching:
|   $users = $cache->remember('users.active', function() {
|       return User::where('active', 1)->get();
|   }, 3600);
|
| • View Caching:
|   $html = $cache->remember("view.{$viewKey}", function() use ($view) {
|       return $view->render();
|   }, 1800);
|
| • Configuration Caching:
|   $config = $cache->remember('app.config', function() {
|       return $this->loadConfiguration();
|   }, 86400);
|
| 9.2 Database Optimization
| ------------------------
| • Use indexes:
|   CREATE INDEX idx_users_email ON users(email);
|
| • Optimize queries:
|   // Bad: N+1 problem
|   $posts = Post::all();
|   foreach ($posts as $post) {
|       echo $post->user->name; // N+1 queries
|   }
|
|   // Good: Eager loading
|   $posts = Post::with('user')->get();
|
| • Use query limits:
|   $users = User::orderBy('created_at')->limit(10)->get();
|
| 9.3 Memory Management
| ----------------------
| • Process large datasets in chunks:
|   User::chunk(100, function($users) {
|       foreach ($users as $user) {
|           // Process user
|       }
|   });
|
| • Clear memory when needed:
|   unset($largeVariable);
|   gc_collect_cycles();
|
| 9.4 Response Optimization
| -------------------------
| • Enable gzip compression:
|   ob_start('ob_gzhandler');
|
| • Set cache headers:
|   $response->header('Cache-Control', 'public, max-age=3600');
|
| • Use ETags:
|   $etag = md5($content);
|   $response->header('ETag', $etag);
|
| 9.5 Profiling
| -------------
| • Measure execution time:
|   $start = microtime(true);
|   // Code to measure
|   $time = microtime(true) - $start;
|
| • Memory usage:
|   $memory = memory_get_usage(true);
|
| • Database query log:
|   ZenDatabase::enableQueryLog();
|   // Run queries
|   $queries = ZenDatabase::getQueryLog();
|
*/

/*
|--------------------------------------------------------------------------
| 10. TESTING
|--------------------------------------------------------------------------
|
| 10.1 Unit Testing
| ----------------
| Example test structure:
|
| class UserModelTest extends PHPUnit\Framework\TestCase {
|     private $db;
|
|     protected function setUp(): void {
|         $this->db = new PDO('sqlite::memory:');
|         $this->db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, email TEXT)");
|     }
|
|     public function testCreateUser() {
|         $user = new User(['name' => 'John', 'email' => 'john@example.com']);
|         $user->save();
|
|         $this->assertNotNull($user->id);
|         $this->assertEquals('John', $user->name);
|     }
|
|     public function testFindUser() {
|         $user = User::create(['name' => 'Jane', 'email' => 'jane@example.com']);
|         $found = User::find($user->id);
|
|         $this->assertNotNull($found);
|         $this->assertEquals('Jane', $found->name);
|     }
| }
|
| 10.2 Integration Testing
| -----------------------
| Example API test:
|
| class ApiTest extends PHPUnit\Framework\TestCase {
|     public function testGetUsers() {
|         $response = $this->get('/api/users');
|
|         $this->assertEquals(200, $response->getStatusCode());
|         $data = json_decode($response->getContent(), true);
|         $this->assertIsArray($data);
|     }
|
|     public function testCreateUser() {
|         $response = $this->post('/api/users', [
|             'name' => 'Test User',
|             'email' => 'test@example.com'
|         ]);
|
|         $this->assertEquals(201, $response->getStatusCode());
|         $data = json_decode($response->getContent(), true);
|         $this->assertEquals('Test User', $data['name']);
|     }
| }
|
| 10.3 Test Database
| ------------------
| Use SQLite in-memory for fast tests:
|
| $testConfig = [
|     'driver' => 'sqlite',
|     'database' => ':memory:'
| ];
|
| 10.4 Mocking
| ------------
| Mock dependencies:
|
| $mockCache = $this->createMock(ZenCache::class);
| $mockCache->expects($this->once())
|           ->method('get')
|           ->with('users')
|           ->willReturn(null);
|
| $container->instance(ZenCache::class, $mockCache);
|
*/

/*
|--------------------------------------------------------------------------
| 11. DEPLOYMENT
|--------------------------------------------------------------------------
|
| 11.1 Production Setup
| ----------------------
| 1. Server Requirements:
|    • PHP 8.0+ with required extensions
|    • Web server (Nginx recommended)
|    • Database server
|    • SSL certificate
|
| 2. Environment Configuration:
|    APP_ENV=production
|    APP_DEBUG=false
|    APP_KEY=your-production-key
|
| 3. File Permissions:
|    chmod -R 755 storage/
|    chmod -R 755 public/
|
| 4. Web Server Configuration:
|
|    Nginx:
|    server {
|        listen 443 ssl http2;
|        server_name example.com;
|        root /var/www/html/public;
|        index index.php;
|
|        ssl_certificate /path/to/cert.pem;
|        ssl_certificate_key /path/to/key.pem;
|
|        location / {
|            try_files $uri $uri/ /index.php?$query_string;
|        }
|
|        location ~ \.php$ {
|            fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
|            fastcgi_index index.php;
|            include fastcgi_params;
|            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
|        }
|    }
|
| 11.2 Optimization
| -----------------
| • Enable OPcache:
|   opcache.enable=1
|   opcache.memory_consumption=128
|   opcache.max_accelerated_files=4000
|
| • Configure PHP-FPM:
|   pm = dynamic
|   pm.max_children = 50
|   pm.start_servers = 5
|   pm.min_spare_servers = 5
|   pm.max_spare_servers = 35
|
| • Use Redis for cache/sessions in production
|
| 11.3 Monitoring
| --------------
| • Monitor error logs
| • Track performance metrics
| • Set up alerts for downtime
| • Monitor queue processing
| • Check disk space usage
|
| 11.4 Backup Strategy
| --------------------
| • Daily database backups
| • Weekly file system backups
| • Off-site storage
| • Test restore procedures
|
*/

/*
|--------------------------------------------------------------------------
| 12. CONTRIBUTING
|--------------------------------------------------------------------------
|
| 12.1 Development Setup
| ---------------------
| 1. Fork the repository
| 2. Clone your fork
| 3. Create a feature branch
| 4. Make your changes
| 5. Add tests
| 6. Run the test suite
| 7. Submit a pull request
|
| 12.2 Coding Standards
| ----------------------
| • Follow PSR-12 coding style
| • Use 4 spaces for indentation
| • Class names in PascalCase
| • Method names in camelCase
| • Constants in UPPER_SNAKE_CASE
| • Add PHPDoc blocks
|
| 12.3 Pull Request Process
| -------------------------
| 1. Update documentation
| 2. Add tests for new features
| 3. Ensure all tests pass
| 4. Update CHANGELOG.md
| 5. Submit PR with clear description
|
| 12.4 Bug Reports
| ----------------
| • Use the issue tracker
| • Provide reproduction steps
| • Include environment details
| • Add error logs if applicable
|
*/

/*
|--------------------------------------------------------------------------
| 13. FAQ
|--------------------------------------------------------------------------
|
| Q: Can I use Zen Framework for large applications?
| A: Yes, Zen Framework is designed to scale. Use proper architecture patterns
|    and consider splitting into multiple services for very large applications.
|
| Q: How do I handle database migrations?
| A: Create migration files and use the built-in CLI command:
|    php zen.php migrate
|
| Q: Can I use Composer packages with Zen Framework?
| A: Yes, you can include any Composer package. Use autoloading to integrate.
|
| Q: How do I implement WebSocket support?
| A: Zen Framework doesn't include WebSocket support out of the box,
|    but you can integrate libraries like Ratchet or use a separate service.
|
| Q: Is Zen Framework suitable for microservices?
| A: Absolutely! Its single-file nature makes it perfect for microservices.
|
| Q: How do I handle file uploads?
| A: Use the ZenUploadedFile class:
|    $file = $request->file('avatar');
|    $file->move('uploads', $filename);
|
| Q: Can I use Vue.js/React with Zen Framework?
| A: Yes, Zen Framework works great as an API backend for SPAs.
|
| Q: How do I implement OAuth?
| A: Use the League OAuth2 Client package or implement custom middleware.
|
| Q: Is there a GUI admin panel?
| A: Not built-in, but you can create one using the framework components.
|
| Q: How do I handle cron jobs?
| A: Create CLI commands and schedule them with system cron.
|
*/

/*
|--------------------------------------------------------------------------
| 14. CHANGELOG
|--------------------------------------------------------------------------
|
| Version 3.0.0 (Current)
| • Initial release
| • Single-file architecture
| • Complete MVC implementation
| • ActiveRecord ORM
| • Authentication system
| • Validation engine
| • Caching layer
| • Queue system
| • Event system
| • CLI tools
| • Comprehensive documentation
|
| Planned Features (4.0.0):
| • WebSocket support
| • GraphQL integration
| • Redis cache/session drivers
| • Advanced authentication (OAuth2, JWT)
| • API documentation generator
| • Admin panel generator
| • Advanced query builder features
| • Database seeding
| • Form builder
| • More middleware
|
*/

/*
|--------------------------------------------------------------------------
| LICENSE
|--------------------------------------------------------------------------
|
| MIT License
|
| Copyright (c) 2025 Zen Framework Team(Seth Ng'ang'a, Jean Luc Kajuga, Prof.Anthony Wanjohi, John Mahugu)
|
| Permission is hereby granted, free of charge, to any person obtaining a copy
| of this software and associated documentation files (the "Software"), to deal
| in the Software without restriction, including without limitation the rights
| to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
| copies of the Software, and to permit persons to whom the Software is
| furnished to do so, subject to the following conditions:
|
| The above copyright notice and this permission notice shall be included in all
| copies or substantial portions of the Software.
|
| THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
| IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
| FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
| AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
| LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
| OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
| SOFTWARE.
|
*/

/*
|--------------------------------------------------------------------------
| SUPPORT & COMMUNITY
|--------------------------------------------------------------------------
|
| • GitHub: https://github.com/zen-framework/zen
| • Documentation: https://zen-framework.dev/docs
| • Issues: https://github.com/zen-framework/zen/issues
| • Discussions: https://github.com/zen-framework/zen/discussions
|
| Contributing:
| We welcome contributions! Please see the Contributing section above for
| guidelines on how to contribute to the Zen Framework.
|
| Acknowledgments:
| • Inspired by Laravel, Symfony, and Ruby on Rails
| • Built with the PHP community in mind
| • Thanks to all contributors
|
*/
?>
<?php

/*
|--------------------------------------------------------------------------
| ZEN FRAMEWORK - COMPREHENSIVE UNIVERSITY COURSE
|--------------------------------------------------------------------------
|
| Table of Contents:
| 1. Course Overview & Learning Objectives
| 2. Module 1: Introduction to Web Development & PHP
| 3. Module 2: Framework Architecture & Design Patterns
| 4. Module 3: Building with Zen Framework - Core Concepts
| 5. Module 4: Database Design & ORM
| 6. Module 5: Frontend Integration & User Experience
| 7. Module 6: Security & Performance
| 8. Module 7: System Design & Architecture
| 9. Module 8: Advanced Topics & Specialization
| 10. Module 9: Real-World Project
| 11. Module 10: Deployment & Operations
| 12. Course Project & Assessment
|
*/

/*
|--------------------------------------------------------------------------
| 1. COURSE OVERVIEW & LEARNING OBJECTIVES
|--------------------------------------------------------------------------
|
| Course Title: Modern Web Development with Zen Framework
| Duration: 12 weeks (3 hours lecture + 2 hours lab per week)
| Credits: 4
| Prerequisites: Basic programming knowledge, HTML/CSS fundamentals
|
| Course Description:
| This comprehensive course covers modern web development using the Zen Framework,
| a single-file PHP framework that compresses the capabilities of major frameworks
| into one cohesive system. Students will learn full-stack development, from
| backend architecture to frontend integration, with emphasis on system design,
| security, and performance optimization.
|
| Learning Objectives:
| • Master full-stack web development using PHP and Zen Framework
| • Understand and implement system architecture patterns
| • Design and develop scalable web applications
| • Implement secure authentication and authorization systems
| • Build RESTful APIs and integrate with frontend frameworks
| • Apply performance optimization techniques
| • Deploy and maintain production applications
| • Develop problem-solving skills through real-world projects
|
| Assessment Methods:
| • Weekly coding assignments (30%)
| • Mid-term project (25%)
| • Final project (35%)
| • Class participation and quizzes (10%)
|
| Required Resources:
| • Zen Framework (provided)
| • PHP 8.0+ development environment
| • Git version control
| • Database management system (MySQL/PostgreSQL)
| • Code editor (VS Code recommended)
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 1: INTRODUCTION TO WEB DEVELOPMENT & PHP
|--------------------------------------------------------------------------
|
| Week 1-2: Foundations of Web Development
|
| Lesson 1.1: Web Architecture Overview
| ------------------------------------
| Learning Objectives:
| • Understand client-server architecture
| • Identify components of web applications
| • Explain HTTP protocol and request-response cycle
|
| Content:
| The web operates on a client-server model where:
| 1. Clients (browsers) send HTTP requests to servers
| 2. Servers process requests and return HTTP responses
| 3. Clients render responses for users
|
| HTTP Request Components:
| • Method: GET, POST, PUT, DELETE, etc.
| • URL: Resource identifier
| • Headers: Metadata (content-type, authorization, etc.)
| • Body: Data for POST/PUT requests
|
| HTTP Response Components:
| • Status Code: 200 OK, 404 Not Found, etc.
| • Headers: Metadata (content-type, cache-control, etc.)
| • Body: Response content (HTML, JSON, etc.)
|
| Diagram: [Client] ↔ [Internet] ↔ [Web Server] ↔ [Application] ↔ [Database]
|
| Lesson 1.2: Introduction to PHP
| -------------------------------
| Learning Objectives:
| • Set up a PHP development environment
| • Write basic PHP scripts
| • Understand PHP syntax and data types
|
| Content:
| PHP (Hypertext Preprocessor) is a server-side scripting language designed
| for web development. It's embedded in HTML and executed on the server.
|
| Basic PHP Syntax:
| <?php
| // Variable declaration
| $name = "Student";
| $age = 20;
| $isEnrolled = true;
|
| // Output
| echo "Hello, $name!";
|
| // Arrays
| $courses = ["Web Dev", "Database", "Security"];
|
| // Functions
| function greet($name) {
|     return "Hello, $name!";
| }
|
| // Control structures
| if ($age >= 18) {
|     echo "Adult";
| } else {
|     echo "Minor";
| }
| ?>
|
| Lesson 1.3: PHP for Web Development
| ------------------------------------
| Learning Objectives:
| • Process form data with PHP
| • Manage sessions and cookies
| • Connect to databases
|
| Content:
| Processing Form Data:
| <?php
| // Check if form was submitted
| if ($_SERVER["REQUEST_METHOD"] == "POST") {
|     // Get form data
|     $name = $_POST["name"];
|     $email = $_POST["email"];
|     
|     // Validate data
|     if (empty($name) || empty($email)) {
|         echo "Please fill all fields";
|     } else {
|         // Process data
|         echo "Thank you, $name!";
|     }
| }
| ?>
|
| Session Management:
| <?php
| // Start session
| session_start();
|
| // Set session data
| $_SESSION["user_id"] = 123;
| $_SESSION["username"] = "student";
|
| // Get session data
| $userId = $_SESSION["user_id"];
| ?>
|
| Database Connection:
| <?php
| $host = "localhost";
| $dbname = "university";
| $username = "user";
| $password = "pass";
|
| try {
|     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
|     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
|     
|     // Query database
|     $stmt = $pdo->query("SELECT * FROM students");
|     $students = $stmt->fetchAll();
| } catch (PDOException $e) {
|     echo "Connection failed: " . $e->getMessage();
| }
| ?>
|
| Lab Exercise 1: Personal Portfolio Website
| ---------------------------------------
| Create a simple personal portfolio website with:
| • Homepage with personal information
| • Projects page showcasing your work
| • Contact form with PHP processing
| • Session-based message display
|
| Submission: GitHub repository with source code
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 2: FRAMEWORK ARCHITECTURE & DESIGN PATTERNS
|--------------------------------------------------------------------------
|
| Week 3-4: Understanding Frameworks and Software Architecture
|
| Lesson 2.1: Introduction to Web Frameworks
| -----------------------------------------
| Learning Objectives:
| • Explain the purpose of web frameworks
| • Compare different framework architectures
| • Understand the MVC pattern
|
| Content:
| Web frameworks provide structure and tools for web development:
| • Code organization and structure
| • Common functionality (routing, database access, etc.)
| • Security features
| • Performance optimization
|
| Framework Architectures:
| 1. Monolithic (single file): Zen Framework
| 2. Component-based: Laravel, Symfony
| 3. Micro-framework: Express.js, Flask
|
| Model-View-Controller (MVC) Pattern:
| • Model: Data and business logic
| • View: Presentation layer
| • Controller: Handles user input and coordinates
|
| Diagram: [User] → [Controller] ↔ [Model] ↔ [Database]
|                      ↓
|                  [View] → [User]
|
| Lesson 2.2: Zen Framework Architecture
| -------------------------------------
| Learning Objectives:
| • Understand Zen Framework's single-file architecture
| • Explore core components
| • Analyze request lifecycle
|
| Content:
| Zen Framework Architecture:
| ┌─────────────────────────────────────────────────────────────┐
| │                    Zen Framework                            │
| └─────────────────────────────────────────────────────────────┘
| ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
| │   Kernel     │  │   Router     │  │   Request    │  │   Response   │
| │              │  │              │  │              │  │              │
| │ • Bootstrap  │  │ • Routing    │  │ • Input      │  │ • Output     │
| │ • Lifecycle  │  │ • Parameters │  │ • Validation │  │ • Headers    │
| └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
| ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
| │   Container  │  │   Database   │  │    View      │  │    Auth      │
| │              │  │              │  │              │  │              │
| │ • DI         │  │ • ORM        │  │ • Templates  │  │ • Sessions   │
| │ • Services   │  │ • Queries    │  │ • Layouts    │  │ • Users      │
| └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
|
| Request Lifecycle:
| 1. Request received by server
| 2. Zen Framework boots
| 3. Router matches route
| 4. Middleware pipeline executes
| 5. Controller action called
| 6. Model interacts with database
| 7. View renders response
| 8. Response sent to client
| 9. Framework terminates
|
| Lesson 2.3: Design Patterns in Web Development
| ---------------------------------------------
| Learning Objectives:
| • Identify common design patterns
| • Apply patterns to solve specific problems
| • Understand pattern implementation in Zen Framework
|
| Content:
| Singleton Pattern:
| Ensures a class has only one instance:
| class Database {
|     private static $instance = null;
|     
|     private function __construct() {}
|     
|     public static function getInstance() {
|         if (self::$instance === null) {
|             self::$instance = new self();
|         }
|         return self::$instance;
|     }
| }
|
| Factory Pattern:
| Creates objects without specifying exact class:
| class ModelFactory {
|     public static function create($model) {
|         $className = $model . 'Model';
|         return new $className();
|     }
| }
|
| Observer Pattern:
| Notifies multiple objects about state changes:
| class EventDispatcher {
|     private $listeners = [];
|     
|     public function addListener($event, $listener) {
|         $this->listeners[$event][] = $listener;
|     }
|     
|     public function dispatch($event, $data = []) {
|         if (isset($this->listeners[$event])) {
|             foreach ($this->listeners[$event] as $listener) {
|                 $listener->handle($data);
|             }
|         }
|     }
| }
|
| Dependency Injection:
| Provides dependencies to objects rather than creating them internally:
| class UserController {
|     private $userService;
|     
|     public function __construct(UserService $userService) {
|         $this->userService = $userService;
|     }
| }
|
| Lab Exercise 2: Framework Analysis
| ---------------------------------
| Analyze and compare Zen Framework with another framework of your choice:
| • Identify architectural differences
| • Compare code organization
| • Evaluate strengths and weaknesses
| • Present findings in class
|
| Submission: 5-page analysis report
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 3: BUILDING WITH ZEN FRAMEWORK - CORE CONCEPTS
|--------------------------------------------------------------------------
|
| Week 5-6: Framework Fundamentals
|
| Lesson 3.1: Routing and Controllers
| -----------------------------------
| Learning Objectives:
| • Define routes for different HTTP methods
| • Create controllers to handle requests
| • Implement route parameters and groups
|
| Content:
| Routing in Zen Framework:
| <?php
| // Basic routes
| $app->getRouter()->get('/', function() {
|     return view('welcome');
| });
|
| $app->getRouter()->post('/submit', function() {
|     // Handle form submission
| });
|
| // Route with parameters
| $app->getRouter()->get('/users/{id}', function($id) {
|     $user = User::find($id);
|     return view('users.profile', ['user' => $user]);
| });
|
| // Route groups
| $app->getRouter()->group(['prefix' => 'admin'], function($router) {
|     $router->get('/dashboard', 'AdminController@dashboard');
|     $router->get('/users', 'AdminController@users');
| });
|
| // API routes
| $app->getRouter()->group(['prefix' => 'api'], function($router) {
|     $router->get('/users', function() {
|         $users = User::all();
|         return response()->json($users);
|     });
| });
| ?>
|
| Controllers:
| <?php
| class UserController {
|     public function index() {
|         $users = User::all();
|         return view('users.index', ['users' => $users]);
|     }
|     
|     public function show($id) {
|         $user = User::find($id);
|         return view('users.show', ['user' => $user]);
|     }
|     
|     public function create() {
|         return view('users.create');
|     }
|     
|     public function store() {
|         $user = User::create(request()->all());
|         return redirect('/users/' . $user->id);
|     }
| }
| ?>
|
| Lesson 3.2: Views and Templating
| --------------------------------
| Learning Objectives:
| • Create view templates
| • Implement layouts and sections
| • Pass data to views
|
| Content:
| Basic View:
| <!-- views/welcome.php -->
| <!DOCTYPE html>
| <html>
| <head>
|     <title>Welcome</title>
| </head>
| <body>
|     <h1>Welcome, <?= $name ?>!</h1>
| </body>
| </html>
|
| Layouts and Sections:
| <!-- views/layouts/app.php -->
| <!DOCTYPE html>
| <html>
| <head>
|     <title><?= $title ?? 'My App' ?></title>
|     <link rel="stylesheet" href="/css/app.css">
| </head>
| <body>
|     <header>
|         <nav>
|             <a href="/">Home</a>
|             <a href="/about">About</a>
|         </nav>
|     </header>
|     
|     <main>
|         <?= $content ?>
|     </main>
|     
|     <footer>
|         &copy; <?= date('Y') ?> My App
|     </footer>
| </body>
| </html>
|
| <!-- views/home.php -->
| <?php $__view->layout('layouts.app') ?>
|
| <?php $__view->section('content') ?>
| <h1>Home Page</h1>
| <p>Welcome to our website!</p>
| <?php $__view->endSection() ?>
|
| Lesson 3.3: Middleware
| ----------------------
| Learning Objectives:
| • Understand middleware concept
| • Create custom middleware
| • Apply middleware to routes
|
| Content:
| Middleware Concept:
| Middleware are layers that process requests before they reach controllers:
| [Request] → [Middleware 1] → [Middleware 2] → [Controller]
|
| Creating Middleware:
| <?php
| class AuthMiddleware implements ZenMiddlewareInterface {
|     public function handle($request, Closure $next) {
|         if (!auth()->check()) {
|             return redirect('/login');
|         }
|         return $next($request);
|     }
| }
|
| class LoggingMiddleware implements ZenMiddlewareInterface {
|     public function handle($request, Closure $next) {
|         $start = microtime(true);
|         $response = $next($request);
|         $duration = microtime(true) - $start;
|         
|         logger()->info("Request processed", [
|             'path' => $request->path(),
|             'method' => $request->method(),
|             'duration' => $duration
|         ]);
|         
|         return $response;
|     }
| }
| ?>
|
| Applying Middleware:
| <?php
| // Global middleware
| $app->getRouter()->middleware('auth', AuthMiddleware::class);
| $app->getRouter()->middleware('logging', LoggingMiddleware::class);
|
| // Route-specific middleware
| $app->getRouter()->get('/dashboard', function() {
|     return view('dashboard');
| })->middleware('auth');
|
| // Group middleware
| $app->getRouter()->group(['middleware' => 'auth'], function($router) {
|     $router->get('/profile', 'UserController@profile');
|     $router->get('/settings', 'UserController@settings');
| });
| ?>
|
| Lab Exercise 3: Blog Application
| -------------------------------
| Create a blog application with:
| • Homepage listing all posts
| • Individual post pages
| • Admin area for creating/editing posts
| • Authentication for admin access
| • Middleware for protecting admin routes
|
| Submission: GitHub repository with source code
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 4: DATABASE DESIGN & ORM
|--------------------------------------------------------------------------
|
| Week 7-8: Data Management and Persistence
|
| Lesson 4.1: Database Design Principles
| --------------------------------------
| Learning Objectives:
| • Understand database normalization
| • Design efficient database schemas
| • Create relationships between tables
|
| Content:
| Database Normalization:
| 1. First Normal Form (1NF): Eliminate repeating groups
| 2. Second Normal Form (2NF): Remove partial dependencies
| 3. Third Normal Form (3NF): Remove transitive dependencies
|
| Example Schema:
| -- Users table
| CREATE TABLE users (
|     id INT AUTO_INCREMENT PRIMARY KEY,
|     name VARCHAR(255) NOT NULL,
|     email VARCHAR(255) UNIQUE NOT NULL,
|     password VARCHAR(255) NOT NULL,
|     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
|     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
| );
|
| -- Posts table
| CREATE TABLE posts (
|     id INT AUTO_INCREMENT PRIMARY KEY,
|     title VARCHAR(255) NOT NULL,
|     content TEXT NOT NULL,
|     user_id INT NOT NULL,
|     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
|     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
|     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
| );
|
| -- Comments table
| CREATE TABLE comments (
|     id INT AUTO_INCREMENT PRIMARY KEY,
|     content TEXT NOT NULL,
|     post_id INT NOT NULL,
|     user_id INT NOT NULL,
|     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
|     FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
|     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
| );
|
| Database Relationships:
| • One-to-One: User ↔ Profile
| • One-to-Many: User → Posts
| • Many-to-Many: Posts ↔ Tags (through pivot table)
|
| Lesson 4.2: Zen Framework ORM
| -----------------------------
| Learning Objectives:
| • Use Zen Framework's ORM for database operations
| • Implement CRUD operations
| • Define model relationships
|
| Content:
| Basic Model Usage:
| <?php
| class User extends ZenModel {
|     protected $table = 'users';
|     protected $fillable = ['name', 'email', 'password'];
|     protected $hidden = ['password'];
| }
|
| // Create user
| $user = new User();
| $user->name = 'John Doe';
| $user->email = 'john@example.com';
| $user->password = password_hash('password', PASSWORD_DEFAULT);
| $user->save();
|
| // Find user
| $user = User::find(1);
|
| // Update user
| $user->name = 'Jane Doe';
| $user->save();
|
| // Delete user
| $user->delete();
| ?>
|
| Query Builder:
| <?php
| // Get all users
| $users = User::all();
|
| // Find users by condition
| $activeUsers = User::where('active', 1)->get();
|
| // Complex query
| $recentPosts = Post::where('created_at', '>', date('Y-m-d', strtotime('-30 days')))
|     ->orderBy('created_at', 'desc')
|     ->limit(10)
|     ->get();
|
| // Aggregation
| $postCount = User::find(1)->posts()->count();
| ?>
|
| Model Relationships:
| <?php
| class User extends ZenModel {
|     protected $table = 'users';
|     
|     // One-to-many relationship
|     public function posts() {
|         return $this->hasMany('Post');
|     }
| }
|
| class Post extends ZenModel {
|     protected $table = 'posts';
|     
|     // Many-to-one relationship
|     public function user() {
|         return $this->belongsTo('User');
|     }
|     
|     // Many-to-many relationship
|     public function tags() {
|         return $this->belongsToMany('Tag');
|     }
| }
|
| // Using relationships
| $user = User::find(1);
| $posts = $user->posts; // Get all posts by this user
|
| $post = Post::find(1);
| $author = $post->user; // Get the author of this post
| ?>
|
| Lesson 4.3: Database Migrations
| --------------------------------
| Learning Objectives:
| • Create database migrations
| • Version control database schema
| • Roll back migrations when needed
|
| Content:
| Migration Concept:
| Migrations are version control for your database, allowing you to:
| • Define schema in code
| • Share database structure with team
| • Roll back changes if needed
| • Deploy schema changes consistently
|
| Creating Migrations:
| <?php
| class CreateUsersTable {
|     public function up() {
|         ZenDatabase::statement("
|             CREATE TABLE users (
|                 id INT AUTO_INCREMENT PRIMARY KEY,
|                 name VARCHAR(255) NOT NULL,
|                 email VARCHAR(255) UNIQUE NOT NULL,
|                 password VARCHAR(255) NOT NULL,
|                 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
|                 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
|             )
|         ");
|     }
|     
|     public function down() {
|         ZenDatabase::statement("DROP TABLE users");
|     }
| }
|
| class AddRoleToUsersTable {
|     public function up() {
|         ZenDatabase::statement("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'user'");
|     }
|     
|     public function down() {
|         ZenDatabase::statement("ALTER TABLE users DROP COLUMN role");
|     }
| }
| ?>
|
| Running Migrations:
| php zen.php migrate
|
| Lab Exercise 4: E-commerce Database
| ----------------------------------
| Design and implement a database for an e-commerce platform:
| • Create normalized schema
| • Implement models with relationships
| • Create migrations
| • Write queries for common operations
|
| Submission: Database schema diagram, migration files, and model definitions
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 5: FRONTEND INTEGRATION & USER EXPERIENCE
|--------------------------------------------------------------------------
|
| Week 9: Building Modern User Interfaces
|
| Lesson 5.1: Frontend Frameworks Integration
| -------------------------------------------
| Learning Objectives:
| • Integrate React with Zen Framework
| • Create SPA (Single Page Application) architecture
| • Implement API endpoints for frontend consumption
|
| Content:
| SPA Architecture:
| [Browser] → [React App] → [API Calls] → [Zen Framework Backend]
|
| Creating API Endpoints:
| <?php
| // API routes
| $app->getRouter()->group(['prefix' => 'api'], function($router) {
|     // Get all products
|     $router->get('/products', function() {
|         $products = Product::all();
|         return response()->json($products);
|     });
|     
|     // Get single product
|     $router->get('/products/{id}', function($id) {
|         $product = Product::find($id);
|         if (!$product) {
|             return response()->json(['error' => 'Product not found'], 404);
|         }
|         return response()->json($product);
|     });
|     
|     // Create product
|     $router->post('/products', function() {
|         $product = Product::create(request()->all());
|         return response()->json($product, 201);
|     });
| });
| ?>
|
| React Component Example:
| // ProductList.js
| import React, { useState, useEffect } from 'react';
| 
| function ProductList() {
|   const [products, setProducts] = useState([]);
| 
|   useEffect(() => {
|     fetch('/api/products')
|       .then(response => response.json())
|       .then(data => setProducts(data));
|   }, []);
| 
|   return (
|     <div>
|       <h1>Products</h1>
|       <ul>
|         {products.map(product => (
|           <li key={product.id}>{product.name}</li>
|         ))}
|       </ul>
|     </div>
|   );
| }
| 
| export default ProductList;
|
| Lesson 5.2: User Experience Design
| ---------------------------------
| Learning Objectives:
| • Apply UX principles to web applications
| • Design responsive interfaces
| • Implement accessibility features
|
| Content:
| UX Principles:
| 1. Consistency: Maintain consistent design patterns
| 2. Feedback: Provide clear feedback for user actions
| 3. Simplicity: Keep interfaces simple and intuitive
| 4. Accessibility: Ensure use by people with disabilities
|
| Responsive Design:
| <!-- Mobile-first approach -->
| <div class="container">
|   <header class="header">...</header>
|   <main class="main-content">...</main>
|   <footer class="footer">...</footer>
| </div>
|
| //CSS 
| .container {
|   width: 100%;
|   max-width: 1200px;
|   margin: 0 auto;
|   padding: 0 15px;
| }
| 
| @media (min-width: 768px) {
|   .container {
|     padding: 0 30px;
|   }
| }
| 
| @media (min-width: 1024px) {
|   .container {
|     padding: 0 60px;
|   }
| }
|
| Accessibility Features:
| <!-- Semantic HTML -->
| <nav aria-label="Main navigation">
|   <ul>
|     <li><a href="/">Home</a></li>
|     <li><a href="/about">About</a></li>
|   </ul>
| </nav>
| 
| <!-- Form with proper labels -->
| <form>
|   <label for="email">Email:</label>
|   <input type="email" id="email" name="email" required>
|   
|   <button type="submit">Submit</button>
| </form>
|
| Lesson 5.3: Progressive Web Applications
| ----------------------------------------
| Learning Objectives:
| • Create PWA with Zen Framework
| • Implement service workers
| • Add offline functionality
|
| Content:
| PWA Components:
| 1. Web App Manifest
| 2. Service Worker
| 3. HTTPS
| 4. Responsive Design
|
| Web App Manifest:
| {
|   "name": "My App",
|   "short_name": "App",
|   "description": "A progressive web application",
|   "start_url": "/",
|   "display": "standalone",
|   "background_color": "#ffffff",
|   "theme_color": "#3367D6",
|   "icons": [
|     {
|       "src": "/icons/icon-192x192.png",
|       "sizes": "192x192",
|       "type": "image/png"
|     }
|   ]
| }
|
| Service Worker:
| // public/sw.js
| const CACHE_NAME = 'my-app-v1';
| const urlsToCache = [
|   '/',
|   '/css/main.css',
|   '/js/main.js',
|   '/offline.html'
| ];
| 
| // Install event
| self.addEventListener('install', event => {
|   event.waitUntil(
|     caches.open(CACHE_NAME)
|       .then(cache => cache.addAll(urlsToCache))
|   );
| });
| 
| // Fetch event
| self.addEventListener('fetch', event => {
|   event.respondWith(
|     caches.match(event.request)
|       .then(response => {
|         // Return cached version or fetch from network
|         return response || fetch(event.request);
|       })
|   );
| });
|
| Lab Exercise 5: Frontend Integration
| -----------------------------------
| Convert a traditional web app to a SPA:
| • Create API endpoints for existing functionality
| • Build React components to consume APIs
| • Implement routing on the frontend
| • Add PWA features
|
| Submission: GitHub repository with before and after versions
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 6: SECURITY & PERFORMANCE
|--------------------------------------------------------------------------
|
| Week 10: Building Robust Applications
|
| Lesson 6.1: Web Security Fundamentals
| ------------------------------------
| Learning Objectives:
| • Identify common web vulnerabilities
| • Implement security best practices
| • Use Zen Framework's security features
|
| Content:
| Common Web Vulnerabilities:
| 1. SQL Injection: Prevented by parameterized queries
| 2. XSS (Cross-Site Scripting): Prevented by output escaping
| 3. CSRF (Cross-Site Request Forgery): Prevented by tokens
| 4. Authentication Bypass: Prevented by proper authentication
| 5. Authorization Issues: Prevented by proper access control
|
| Zen Framework Security Features:
| <?php
| // CSRF Protection
| $app->getRouter()->middleware('csrf', ZenCsrfMiddleware::class);
|
| // Input Validation
| $validator = ZenValidator::make($request->all(), [
|     'email' => 'required|email',
|     'password' => 'required|min:8',
| ]);
|
| // Password Hashing
| $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
|
| // Secure Headers
| $response->header('X-Content-Type-Options', 'nosniff');
| $response->header('X-Frame-Options', 'SAMEORIGIN');
| $response->header('X-XSS-Protection', '1; mode=block');
| ?>
|
| Security Best Practices:
| 1. Validate all input
| 2. Escape all output
| 3. Use HTTPS
| 4. Implement proper authentication
| 5. Follow principle of least privilege
| 6. Keep dependencies updated
| 7. Log security events
| 8. Regularly test for vulnerabilities
|
| Lesson 6.2: Authentication and Authorization
| -------------------------------------------
| Learning Objectives:
| • Implement user authentication
| • Create role-based access control
| • Use JWT for API authentication
|
| Content:
| Authentication Implementation:
| <?php
| // Registration
| $app->getRouter()->post('/register', function() {
|     $user = User::create([
|         'name' => request()->input('name'),
|         'email' => request()->input('email'),
|         'password' => password_hash(request()->input('password'), PASSWORD_DEFAULT),
|     ]);
|     
|     auth()->login($user);
|     return redirect('/dashboard');
| });
|
| // Login
| $app->getRouter()->post('/login', function() {
|     if (auth()->attempt([
|         'email' => request()->input('email'),
|         'password' => request()->input('password')
|     ])) {
|         return redirect('/dashboard');
|     }
|     
|     return redirect('/login')->with('error', 'Invalid credentials');
| });
| ?>
|
| Role-Based Access Control:
| <?php
| class User extends ZenModel {
|     protected $table = 'users';
|     protected $fillable = ['name', 'email', 'password', 'role'];
|     
|     public function hasRole($role) {
|         return $this->role === $role;
|     }
| }
|
| class RoleMiddleware implements ZenMiddlewareInterface {
|     public function handle($request, Closure $next, $role) {
|         if (!auth()->check() || !auth()->user()->hasRole($role)) {
|             return redirect('/login');
|         }
|         return $next($request);
|     }
| }
|
| // Apply role middleware
| $app->getRouter()->get('/admin', function() {
|     return view('admin.dashboard');
| })->middleware('role:admin');
| ?>
|
| JWT for API Authentication:
| <?php
| class AuthController {
|     public function login() {
|         $credentials = request()->only(['email', 'password']);
|         
|         if (auth()->attempt($credentials)) {
|             $user = auth()->user();
|             $token = $this->generateJWT($user);
|             
|             return response()->json([
|                 'token' => $token,
|                 'user' => $user
|             ]);
|         }
|         
|         return response()->json(['error' => 'Invalid credentials'], 401);
|     }
|     
|     private function generateJWT($user) {
|         $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
|         $payload = json_encode([
|             'user_id' => $user->id,
|             'exp' => time() + 60 * 60 // 1 hour
|         ]);
|         
|         $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
|         $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
|         
|         $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secret', true);
|         $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
|         
|         return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
|     }
| }
| ?>
|
| Lesson 6.3: Performance Optimization
| -----------------------------------
| Learning Objectives:
| • Identify performance bottlenecks
| • Implement caching strategies
| • Optimize database queries
|
| Content:
| Performance Optimization Techniques:
| 1. Caching: Store frequently accessed data
| 2. Database Optimization: Efficient queries and indexing
| 3. Code Optimization: Efficient algorithms and data structures
| 4. Asset Optimization: Minify and compress files
| 5. Server Optimization: Configure server for performance
|
| Caching Implementation:
| <?php
| // Route caching
| $app->getRouter()->get('/popular-posts', function() {
|     $posts = cache()->remember('popular_posts', function() {
|         return Post::orderBy('views', 'desc')->limit(10)->get();
|     }, 3600); // Cache for 1 hour
|     
|     return view('posts.popular', ['posts' => $posts]);
| });
|
| // Query result caching
| $users = cache()->remember('active_users', function() {
|     return User::where('active', 1)->get();
| }, 1800); // Cache for 30 minutes
| ?>
|
| Database Optimization:
| <?php
| // N+1 problem
| // Bad: Makes 1 query for posts + N queries for comments
| $posts = Post::all();
| foreach ($posts as $post) {
|     echo $post->comments->count(); // Additional query for each post
| }
|
| // Good: Makes 2 queries total
| $posts = Post::with('comments')->get();
| foreach ($posts as $post) {
|     echo $post->comments->count(); // No additional queries
| }
| ?>
|
| Asset Optimization:
| <!-- Minified CSS -->
| <link rel="stylesheet" href="/css/app.min.css">
| 
| <!-- Minified JS -->
| <script src="/js/app.min.js"></script>
| 
| <!-- Optimized images -->
| <img src="/images/photo.webp" alt="Photo">
|
| Lab Exercise 6: Security Audit & Performance Boost
| ------------------------------------------------
| Audit and improve an existing application:
| • Identify and fix security vulnerabilities
| • Implement authentication and authorization
| • Add caching to improve performance
| • Optimize database queries
|
| Submission: Before and after code with performance metrics
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 7: SYSTEM DESIGN & ARCHITECTURE
|--------------------------------------------------------------------------
|
| Week 11: Building Scalable Systems
|
| Lesson 7.1: System Design Principles
| -----------------------------------
| Learning Objectives:
| • Understand system design fundamentals
| • Apply scalability principles
| • Design for reliability and maintainability
|
| Content:
| System Design Fundamentals:
| 1. Requirements: Functional and non-functional requirements
| 2. Constraints: Technical, business, and resource constraints
| 3. Trade-offs: Balance between competing factors
| 4. Evolution: Systems evolve over time
|
| Scalability Principles:
| 1. Horizontal Scaling: Add more machines
| 2. Vertical Scaling: Increase machine capacity
| 3. Load Balancing: Distribute load across servers
| 4. Caching: Reduce database load
| 5. Database Partitioning: Split data across servers
|
| System Architecture Diagram:
| ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
| │   Client        │    │   Load Balancer │    │   Web Server 1  │
| │                 │◄──►│                 │◄──►│                 │
| │   Browser       │    │   Nginx/HAProxy │    │   Zen Framework │
| └─────────────────┘    └─────────────────┘    └─────────────────┘
|                                               │
| ┌─────────────────┐    ┌─────────────────┐    │
| │   CDN           │    │   Cache Layer   │    │
| │                 │◄──►│                 │◄──►│
| │   CloudFlare    │    │  Redis/Memcached│    │
| └─────────────────┘    └─────────────────┘    │
|                                               │
| ┌─────────────────┐    ┌─────────────────┐    │
| │   Static Assets │    │   Database      │◄──►│
| │                 │◄──►│                 │    │
| │   S3/MinIO      │    │ MySQL/PostgreSQL│    │
| └─────────────────┘    └─────────────────┘    │
|                                               │
| ┌─────────────────┐    ┌─────────────────┐    │
| │   Message Queue │    │   Background    │◄──►│
| │                 │◄──►│                 │    │
| │   RabbitMQ      │    │   Workers       │    │
| └─────────────────┘    └─────────────────┘    └─────────────────┘
|
| Lesson 7.2: Microservices Architecture
| --------------------------------------
| Learning Objectives:
| • Understand microservices concepts
| • Design service boundaries
| • Implement inter-service communication
|
| Content:
| Microservices vs Monolith:
| Monolith:
| • Single application with all functionality
| • Easier to develop initially
| • Harder to scale and maintain
|
| Microservices:
| • Multiple small services
| • Each service has a specific responsibility
| • More complex to manage but more scalable
|
| Service Boundaries:
| 1. Business Capability: Services aligned with business functions
| 2. Data Ownership: Each service owns its data
| 3. Independent Deployment: Services can be deployed independently
| 4. Fault Isolation: Failure in one service doesn't affect others
|
| Inter-Service Communication:
| 1. Synchronous: REST APIs, gRPC
| 2. Asynchronous: Message queues, event streaming
|
| Example Microservices:
| ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
| │   User Service  │    │   Product       │    │   Order Service │
| │                 │    │   Service       │    │                 │
| │ • Registration  │◄──►│ • Catalog       │◄──►│ • Processing    │
| │ • Authentication│    │ • Inventory     │    │ • History       │
| │ • Profile       │    │ • Pricing       │    │ • Status        │
| └─────────────────┘    └─────────────────┘    └─────────────────┘
|         │                       │                       │
|         └───────────────────────┼───────────────────────┘
|                                 │
|                     ┌─────────────────┐
|                     │   API Gateway   │
|                     │                 │
|                     │ • Routing       │
|                     │ • Authentication│
|                     │ • Rate Limiting │
|                     └─────────────────┘
|
| Lesson 7.3: Distributed Systems
| --------------------------------
| Learning Objectives:
| • Understand distributed systems challenges
| • Implement data consistency strategies
| • Design for fault tolerance
|
| Content:
| Distributed Systems Challenges:
| 1. Network Latency: Communication delays
| 2. Partial Failures: Some components may fail
| 3. Data Consistency: Keeping data synchronized
| 4. Concurrency: Handling simultaneous operations
|
| Consistency Models:
| 1. Strong Consistency: All nodes see the same data simultaneously
| 2. Eventual Consistency: Data becomes consistent over time
| 3. Weak Consistency: No guarantee of consistency
|
| CAP Theorem:
| • Consistency: All nodes see the same data
| • Availability: System remains operational
| • Partition Tolerance: System continues despite network partitions
|
| You can only have two out of three:
| 1. CP: Consistency and Partition Tolerance
| 2. AP: Availability and Partition Tolerance
| 3. CA: Consistency and Availability (not realistic in distributed systems)
|
| Fault Tolerance Patterns:
| 1. Redundancy: Multiple instances of critical components
| 2. Circuit Breaker: Stop making requests to failing services
| 3. Retry: Retry failed operations with exponential backoff
| 4. Timeouts: Set appropriate timeouts for operations
|
| Lab Exercise 7: System Design
| -----------------------------
| Design a system for a social media platform:
| • Create system architecture diagram
| • Identify components and their responsibilities
| • Design data flow between components
| • Consider scalability and reliability
|
| Submission: System design document with diagrams and explanations
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 8: ADVANCED TOPICS & SPECIALIZATION
|--------------------------------------------------------------------------
|
| Week 12: Exploring Specialized Areas
|
| Lesson 8.1: Real-time Applications
| ----------------------------------
| Learning Objectives:
| • Implement WebSockets for real-time communication
| • Create event-driven applications
| • Build collaborative features
|
| Content:
| WebSockets vs HTTP:
| HTTP:
| • Request-Response model
| • Stateless
| • One-way communication
|
| WebSockets:
| • Persistent connection
| • Full-duplex communication
| • Stateful
|
| WebSocket Implementation:
| <?php
| // WebSocket server (using Ratchet library)
| use Ratchet\MessageComponentInterface;
| use Ratchet\ConnectionInterface;
| 
| class Chat implements MessageComponentInterface {
|     protected $clients;
| 
|     public function __construct() {
|         $this->clients = new \SplObjectStorage;
|     }
| 
|     public function onOpen(ConnectionInterface $conn) {
|         $this->clients->attach($conn);
|     }
| 
|     public function onMessage(ConnectionInterface $from, $msg) {
|         foreach ($this->clients as $client) {
|             if ($from !== $client) {
|                 $client->send($msg);
|             }
|         }
|     }
| 
|     public function onClose(ConnectionInterface $conn) {
|         $this->clients->detach($conn);
|     }
| 
|     public function onError(ConnectionInterface $conn, \Exception $e) {
|         $conn->close();
|     }
| }
| ?>
|
| Client-side WebSocket:
| // JavaScript
| const socket = new WebSocket('ws://localhost:8080');
| 
| socket.onopen = function(e) {
|     console.log("Connection established");
| };
| 
| socket.onmessage = function(event) {
|     const chatMessage = document.createElement('div');
|     chatMessage.textContent = event.data;
|     document.getElementById('chat-messages').appendChild(chatMessage);
| };
| 
| document.getElementById('send-button').addEventListener('click', function() {
|     const messageInput = document.getElementById('message-input');
|     socket.send(messageInput.value);
|     messageInput.value = '';
| });
|
| Lesson 8.2: Cloud-Native Development
| -------------------------------------
| Learning Objectives:
| • Deploy applications to cloud platforms
| • Use containerization with Docker
| • Implement CI/CD pipelines
|
| Content:
| Cloud Deployment Options:
| 1. IaaS (Infrastructure as a Service): AWS EC2, Google Compute Engine
| 2. PaaS (Platform as a Service): Heroku, AWS Elastic Beanstalk
| 3. CaaS (Container as a Service): AWS ECS, Google Kubernetes Engine
| 4. Serverless: AWS Lambda, Google Cloud Functions
|
| Docker Containerization:
| # Dockerfile
| FROM php:8.0-apache
| 
| # Install dependencies
| RUN apt-get update && apt-get install -y \
|     libpng-dev \
|     libonig-dev \
|     libxml2-dev \
|     zip \
|     unzip
| 
| # Install PHP extensions
| RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
| 
| # Set working directory
| WORKDIR /var/www/html
| 
| # Copy application files
| COPY . .
| 
| # Set permissions
| RUN chown -R www-data:www-data /var/www/html/storage
| 
| # Expose port
| EXPOSE 80
|
| Docker Compose:
| # docker-compose.yml
| version: '3'
| 
| services:
|   app:
|     build: .
|     ports:
|       - "8080:80"
|     volumes:
|       - .:/var/www/html
|     environment:
|       - DB_HOST=db
|       - DB_DATABASE=myapp
|       - DB_USERNAME=user
|       - DB_PASSWORD=password
|     depends_on:
|       - db
| 
|   db:
|     image: mysql:5.7
|     environment:
|       - MYSQL_DATABASE=myapp
|       - MYSQL_USER=user
|       - MYSQL_PASSWORD=password
|       - MYSQL_ROOT_PASSWORD=root
|     volumes:
|       - dbdata:/var/lib/mysql
| 
| volumes:
|   dbdata:
|
| CI/CD Pipeline:
| # .github/workflows/deploy.yml
| name: Deploy to Production
| 
| on:
|   push:
|     branches: [ main ]
| 
| jobs:
|   test:
|     runs-on: ubuntu-latest
|     steps:
|     - uses: actions/checkout@v2
|     - name: Setup PHP
|       uses: shivammathur/setup-php@v2
|       with:
|         php-version: '8.0'
|     - name: Install dependencies
|       run: composer install
|     - name: Run tests
|       run: vendor/bin/phpunit
| 
|   deploy:
|     needs: test
|     runs-on: ubuntu-latest
|     steps:
|     - uses: actions/checkout@v2
|     - name: Deploy to server
|       uses: appleboy/ssh-action@master
|       with:
|         host: ${{ secrets.HOST }}
|         username: ${{ secrets.USERNAME }}
|         key: ${{ secrets.SSH_KEY }}
|         script: |
|           cd /var/www/html
|           git pull origin main
|           composer install --no-dev --optimize-autoloader
|           php artisan migrate --force
|
| Lesson 8.3: GraphQL Integration
| -------------------------------
| Learning Objectives:
| • Understand GraphQL concepts
| • Implement GraphQL API with Zen Framework
| • Build flexible frontend queries
|
| Content:
| GraphQL vs REST:
| REST:
| • Multiple endpoints for different resources
| • Fixed data structure
| • Over-fetching or under-fetching of data
|
| GraphQL:
| • Single endpoint
| • Flexible data structure
| • Fetch exactly what you need
|
| GraphQL Schema:
| <?php
| // Define types
| class UserType {
|     public function fields() {
|         return [
|             'id' => ['type' => Type::nonNull(Type::id())],
|             'name' => ['type' => Type::nonNull(Type::string())],
|             'email' => ['type' => Type::nonNull(Type::string())],
|             'posts' => [
|                 'type' => Type::listOf(Type::nonNull(PostType::class)),
|                 'resolve' => function($user) {
|                     return Post::where('user_id', $user['id'])->get();
|                 }
|             ]
|         ];
|     }
| }
| 
| class PostType {
|     public function fields() {
|         return [
|             'id' => ['type' => Type::nonNull(Type::id())],
|             'title' => ['type' => Type::nonNull(Type::string())],
|             'content' => ['type' => Type::nonNull(Type::string())],
|             'user' => [
|                 'type' => Type::nonNull(UserType::class),
|                 'resolve' => function($post) {
|                     return User::find($post['user_id']);
|                 }
|             ]
|         ];
|     }
| }
| 
| // Define queries
| class QueryType {
|     public function fields() {
|         return [
|             'user' => [
|                 'type' => UserType::class,
|                 'args' => [
|                     'id' => ['type' => Type::nonNull(Type::id())]
|                 ],
|                 'resolve' => function($root, $args) {
|                     return User::find($args['id']);
|                 }
|             ],
|             'posts' => [
|                 'type' => Type::listOf(Type::nonNull(PostType::class)),
|                 'args' => [
|                     'limit' => ['type' => Type::int()]
|                 ],
|                 'resolve' => function($root, $args) {
|                     $query = Post::query();
|                     if (isset($args['limit'])) {
|                         $query->limit($args['limit']);
|                     }
|                     return $query->get();
|                 }
|             ]
|         ];
|     }
| }
| ?>
|
| GraphQL Endpoint:
| <?php
| $app->getRouter()->post('/graphql', function() {
|     $schema = new Schema([
|         'query' => new QueryType()
|     ]);
|     
|     $input = json_decode(file_get_contents('php://input'), true);
|     $query = $input['query'];
|     $variables = $input['variables'] ?? null;
|     
|     $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
|     
|     return response()->json($result->toArray());
| });
| ?>
|
| Lab Exercise 8: Specialization Project
| ------------------------------------
| Choose one specialization area:
| • Real-time chat application
| • Cloud deployment with CI/CD
| • GraphQL API implementation
| • Microservices architecture
|
| Submission: Project with documentation and demonstration
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 9: REAL-WORLD PROJECT
|--------------------------------------------------------------------------
|
| Week 13-14: Applying Knowledge to Build a Complete Application
|
| Project Overview:
| Students will work in teams to build a complete web application using
| Zen Framework, applying all concepts learned throughout the course.
|
| Project Options:
| 1. E-commerce Platform
|    • Product catalog
|    • Shopping cart
|    • Order processing
|    • Payment integration
|    • Admin dashboard
|
| 2. Social Media Platform
|    • User profiles
|    • Posts and comments
|    • Follow system
|    • Real-time notifications
|    • Analytics dashboard
|
| 3. Learning Management System
|    • Course catalog
|    • Enrollment system
|    • Progress tracking
|    • Quizzes and assessments
|    • Instructor dashboard
|
| 4. Project Management Tool
|    • Project creation and management
|    • Task assignment and tracking
|    • Team collaboration
|    • File sharing
|    • Reporting and analytics
|
| Project Requirements:
| 1. Use Zen Framework for backend
| 2. Implement user authentication and authorization
| 3. Design and implement a normalized database schema
| 4. Create a responsive frontend
| 5. Implement at least one advanced feature (real-time, API, etc.)
| 6. Include comprehensive testing
| 7. Deploy to a cloud platform
| 8. Create documentation
|
| Project Deliverables:
| 1. Source Code
|    • Well-structured and commented code
|    • Git repository with proper commit history
|    • Environment setup instructions
|
| 2. Database Schema
|    • ER diagram
|    • Migration files
|    • Seed data
|
| 3. API Documentation
|    • Endpoint documentation
|    • Request/response examples
|    • Authentication requirements
|
| 4. User Guide
|    • Feature overview
|    • User instructions
|    • Screenshots or demo video
|
| 5. Technical Documentation
|    • System architecture
|    • Design decisions
|    • Deployment guide
|    • Future improvements
|
| 6. Testing Report
|    • Unit tests
|    • Integration tests
|    • Test coverage report
|    • Performance testing
|
| 7. Presentation
|    • Project overview
|    • Demonstration
|    • Technical challenges
|    • Lessons learned
|
| Project Timeline:
| Week 13:
| • Team formation and project selection
| • Requirements gathering and planning
| • System design and architecture
|
| Week 14:
| • Development and implementation
| • Testing and debugging
| • Documentation and presentation preparation
|
| Evaluation Criteria:
| 1. Functionality (30%)
|    • All required features implemented
|    • Features work correctly
|    • User-friendly interface
|
| 2. Technical Implementation (25%)
|    • Proper use of Zen Framework
|    • Clean and maintainable code
|    • Effective database design
|
| 3. Advanced Features (20%)
|    • Implementation of advanced concepts
|    • Innovation and creativity
|    • Technical complexity
|
| 4. Testing (10%)
|    • Comprehensive test coverage
|    • Effective test cases
|    • Bug-free implementation
|
| 5. Documentation (10%)
|    • Complete and clear documentation
|    • Well-structured API docs
|    • Helpful user guide
|
| 6. Presentation (5%)
|    • Clear and engaging presentation
|    • Effective demonstration
|    • Professional delivery
|
*/

/*
|--------------------------------------------------------------------------
| MODULE 10: DEPLOYMENT & OPERATIONS
|--------------------------------------------------------------------------
|
| Week 15: Taking Applications to Production
|
| Lesson 10.1: Production Deployment
| ----------------------------------
| Learning Objectives:
| • Prepare applications for production
| • Configure production servers
| • Implement monitoring and logging
|
| Content:
| Production Preparation:
| 1. Environment Configuration
|    • Set production environment variables
|    • Configure database connections
|    • Set up caching systems
|
| 2. Security Hardening
|    • Generate secure application key
|    • Configure HTTPS
|    • Set up firewalls
|    • Implement security headers
|
| 3. Performance Optimization
|    • Enable OPcache
|    • Configure web server
|    • Implement caching strategies
|    • Optimize database
|
| Server Configuration:
| # Nginx configuration
| server {
|     listen 443 ssl http2;
|     server_name example.com;
|     root /var/www/html/public;
|     index index.php;
| 
|     ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem;
|     ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
|     ssl_protocols TLSv1.2 TLSv1.3;
|     ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
|     ssl_prefer_server_ciphers off;
| 
|     location / {
|         try_files $uri $uri/ /index.php?$query_string;
|     }
| 
|     location ~ \.php$ {
|         fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
|         fastcgi_index index.php;
|         include fastcgi_params;
|         fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
|         fastcgi_param HTTPS on;
|     }
| 
|     location ~ /\.ht {
|         deny all;
|     }
| }
| 
| server {
|     listen 80;
|     server_name example.com;
|     return 301 https://$server_name$request_uri;
| }
|
| PHP Configuration:
| # /etc/php/8.0/fpm/php.ini
| 
| ; Performance settings
| max_execution_time = 30
| memory_limit = 256M
| 
| ; Error handling
| display_errors = Off
| log_errors = On
| error_log = /var/log/php_errors.log
| 
| ; File uploads
| upload_max_filesize = 10M
| post_max_size = 12M
| 
| ; OPcache settings
| opcache.enable=1
| opcache.memory_consumption=128
| opcache.max_accelerated_files=4000
| opcache.revalidate_freq=60
|
| Lesson 10.2: Monitoring and Logging
| -----------------------------------
| Learning Objectives:
| • Implement application monitoring
| • Set up comprehensive logging
| • Create alerting for critical issues
|
| Content:
| Application Monitoring:
| 1. Performance Metrics
|    • Response time
|    • Throughput
|    • Error rate
|    • Resource usage
|
| 2. Health Checks
|    • Application health
|    • Database connectivity
|    • External service availability
|
| 3. Business Metrics
|    • User registrations
|    • Feature usage
|    • Conversion rates
|
| Monitoring Implementation:
| <?php
| // Health check endpoint
| $app->getRouter()->get('/health', function() {
|     $status = [
|         'status' => 'ok',
|         'timestamp' => time(),
|         'version' => '1.0.0'
|     ];
|     
|     // Check database
|     try {
|         ZenDatabase::select('SELECT 1');
|         $status['database'] = 'ok';
|     } catch (Exception $e) {
|         $status['database'] = 'error';
|         $status['status'] = 'error';
|     }
|     
|     // Check cache
|     try {
|         cache()->put('health_check', 'ok', 60);
|         $value = cache()->get('health_check');
|         $status['cache'] = ($value === 'ok') ? 'ok' : 'error';
|     } catch (Exception $e) {
|         $status['cache'] = 'error';
|         $status['status'] = 'error';
|     }
|     
|     $httpStatus = ($status['status'] === 'ok') ? 200 : 503;
|     return response()->json($status, $httpStatus);
| });
| ?>
|
| Logging Strategy:
| <?php
| // Custom logger
| class AppLogger {
|     public static function log($level, $message, $context = []) {
|         $logEntry = [
|             'timestamp' => date('Y-m-d H:i:s'),
|             'level' => $level,
|             'message' => $message,
|             'context' => $context,
|             'request_id' => request()->header('X-Request-ID', uniqid()),
|             'user_id' => auth()->check() ? auth()->id() : null,
|             'ip' => request()->ip(),
|             'user_agent' => request()->header('User-Agent'),
|         ];
|         
|         file_put_contents(
|             storage_path('logs/app.log'),
|             json_encode($logEntry) . "\n",
|             FILE_APPEND | LOCK_EX
|         );
|     }
|     
|     public static function info($message, $context = []) {
|         self::log('info', $message, $context);
|     }
|     
|     public static function error($message, $context = []) {
|         self::log('error', $message, $context);
|     }
| }
| 
| // Usage in controllers
| class UserController {
|     public function login() {
|         AppLogger::info('User login attempt', [
|             'email' => request()->input('email')
|         ]);
|         
|         if (auth()->attempt(request()->only(['email', 'password']))) {
|             AppLogger::info('User logged in successfully', [
|                 'user_id' => auth()->id()
|             ]);
|             return redirect('/dashboard');
|         }
|         
|         AppLogger::error('User login failed', [
|             'email' => request()->input('email'),
|             'reason' => 'invalid_credentials'
|         ]);
|         
|         return redirect('/login')->with('error', 'Invalid credentials');
|     }
| }
| ?>
|
| Alerting Implementation:
| <?php
| class AlertManager {
|     public static function sendAlert($level, $message, $context = []) {
|         $webhookUrl = config('alert.webhook_url');
|         
|         $payload = [
|             'text' => "[{$level}] {$message}",
|             'attachments' => [
|                 [
|                     'fields' => array_map(function($key, $value) {
|                         return [
|                             'title' => $key,
|                             'value' => is_array($value) ? json_encode($value) : $value,
|                             'short' => true
|                         ];
|                     }, array_keys($context), $context)
|                 ]
|             ]
|         ];
|         
|         file_get_contents($webhookUrl, false, stream_context_create([
|             'http' => [
|                 'method' => 'POST',
|                 'header' => 'Content-Type: application/json',
|                 'content' => json_encode($payload)
|             ]
|         ]));
|     }
| }
| ?>
|
| Lesson 10.3: Maintenance and Updates
| ------------------------------------
| Learning Objectives:
| • Plan and execute maintenance windows
| • Implement zero-downtime deployments
| • Handle application updates
|
| Content:
| Maintenance Strategies:
| 1. Scheduled Maintenance
|    • Notify users in advance
|    • Implement maintenance mode
|    • Plan rollback procedures
|
| 2. Zero-Downtime Deployment
|    • Blue-green deployment
|    • Canary releases
|    • Feature flags
|
| Maintenance Mode Implementation:
| <?php
| class MaintenanceMiddleware implements ZenMiddlewareInterface {
|     public function handle($request, Closure $next) {
|         if (file_exists(storage_path('framework/down'))) {
|             $allowedIps = ['127.0.0.1', '::1']; // Admin IPs
|             
|             if (!in_array($request->ip(), $allowedIps)) {
|                 $data = json_decode(file_get_contents(storage_path('framework/down')), true);
|                 
|                 return response()->view('maintenance', [
|                     'message' => $data['message'] ?? 'Down for maintenance'
|                 ], 503);
|             }
|         }
|         
|         return $next($request);
|     }
| }
| ?>
|
| Zero-Downtime Deployment Script:
| #!/bin/bash
| 
| # Variables
| APP_DIR="/var/www/html"
| BACKUP_DIR="/var/www/backups"
| TIMESTAMP=$(date +%Y%m%d_%H%M%S)
| 
| # Create backup
| echo "Creating backup..."
| tar -czf "$BACKUP_DIR/app_$TIMESTAMP.tar.gz" -C "$APP_DIR" .
| 
| # Pull latest code
| echo "Pulling latest code..."
| cd "$APP_DIR"
| git pull origin main
| 
| # Install dependencies
| echo "Installing dependencies..."
| composer install --no-dev --optimize-autoloader
| 
| # Run migrations
| echo "Running migrations..."
| php zen.php migrate --force
| 
| # Clear cache
| echo "Clearing cache..."
| php zen.php cache:clear
| 
| # Restart services
| echo "Restarting services..."
| systemctl reload nginx
| systemctl restart php8.0-fpm
| 
| echo "Deployment completed successfully!"
|
| Lab Exercise 10: Production Deployment
| --------------------------------------
| Deploy your course project to a production environment:
| • Set up a production server
| • Configure domain and SSL
| • Implement monitoring and logging
| • Create deployment scripts
| • Document maintenance procedures
|
| Submission: Production URL, deployment scripts, and monitoring dashboard
|
*/

/*
|--------------------------------------------------------------------------
| COURSE PROJECT & ASSESSMENT
|--------------------------------------------------------------------------
|
| Final Project Requirements:
| Students will build a complete web application using Zen Framework that
| demonstrates mastery of all course concepts. The project should be a
| comprehensive solution to a real-world problem.
|
| Project Selection:
| Choose one of the following or propose your own:
| 1. E-commerce Platform
| 2. Social Media Application
| 3. Learning Management System
| 4. Project Management Tool
| 5. Custom proposal (must be approved)
|
| Project Requirements:
| 1. Backend Development (40%)
|    • Complete CRUD operations
|    • Authentication and authorization
|    • RESTful API endpoints
|    • Database design and optimization
|    • Background job processing
|
| 2. Frontend Development (30%)
|    • Responsive design
|    • Modern JavaScript framework integration
|    • Real-time features (WebSockets)
|    • Progressive Web App features
|
| 3. System Architecture (20%)
|    • Scalable design
|    • Microservices or modular architecture
|    • Caching strategies
|    • Performance optimization
|
| 4. Operations (10%)
|    • Deployment to production
|    • Monitoring and logging
|    • CI/CD pipeline
|    • Documentation
|
| Assessment Rubric:
| 
| 1. Technical Implementation (40%)
|    • Code quality and organization
|    • Proper use of Zen Framework
|    • Effective database design
|    • Security implementation
|    • Performance optimization
|
| 2. Functionality (30%)
|    • All required features implemented
|    • Features work correctly
|    • User-friendly interface
|    • Error handling
|
| 3. Innovation (15%)
|    • Creative solutions
|    • Advanced features
|    • Unique approach to problems
|
| 4. Documentation (10%)
|    • Complete technical documentation
|    • User guide
|    • API documentation
|    • Deployment instructions
|
| 5. Presentation (5%)
|    • Clear explanation of project
|    • Demonstration of features
|    • Discussion of challenges
|
| Submission Requirements:
| 1. Source Code
|    • Git repository with proper commit history
|    • Well-structured and commented code
|    • Environment setup instructions
|
| 2. Documentation
|    • System architecture diagram
|    • Database schema
|    • API documentation
|    • User guide
|    • Deployment guide
|
| 3. Live Demo
|    • Deployed application
|    • Admin access for evaluation
|    • Test accounts if applicable
|
| 4. Presentation
|    • 15-minute presentation
|    • Demonstration of key features
|    • Technical challenges and solutions
|    • Future improvements
|
| Timeline:
| Week 13: Project proposal and team formation
| Week 14: Development and implementation
| Week 15: Testing, documentation, and deployment
| Finals Week: Presentations and submission
|
| Academic Integrity:
| All work must be original. Plagiarism will result in a failing grade.
| You may use third-party libraries with proper attribution, but the core
| implementation must be your own work.
|
| Late Policy:
| 10% deduction per day for late submissions. No submissions accepted
| after 3 days past the deadline without prior arrangement.
|
| Grading Scale:
| A: 90-100% - Outstanding work with exceptional innovation
| B: 80-89% - Good work with all requirements met
| C: 70-79% - Satisfactory work with some requirements met
| D: 60-69% - Marginal work with basic requirements met
| F: Below 60% - Unsatisfactory work
|
*/

/*
|--------------------------------------------------------------------------
| CONCLUSION
|--------------------------------------------------------------------------
|
| This comprehensive course covers modern web development using Zen Framework,
| from basic concepts to advanced system design. Students will gain hands-on
| experience building real-world applications and deploying them to production.
|
| Learning Outcomes:
| Upon completion of this course, students will be able to:
| • Design and build scalable web applications
| • Implement secure authentication and authorization
| • Create RESTful APIs and integrate with frontend frameworks
| • Apply performance optimization techniques
| • Deploy and maintain production applications
| • Solve complex web development problems
|
| Career Opportunities:
| This course prepares students for roles such as:
| • Full-Stack Developer
| • Backend Developer
| • Web Application Architect
| • DevOps Engineer
| • Technical Lead
|
| Continuous Learning:
| Web development is a rapidly evolving field. Students are encouraged to:
| • Follow industry trends
| • Contribute to open-source projects
| • Build a portfolio of work
| • Pursue certifications
| • Join professional communities
|
| Thank you for your participation in this course. I hope you find it
| valuable for your career in web development!
|
*/

/*
|--------------------------------------------------------------------------
| RESOURCES
|--------------------------------------------------------------------------
|
| Recommended Books:
| 1. "Clean Code" by Robert C. Martin
| 2. "Design Patterns: Elements of Reusable Object-Oriented Software" by Gang of Four
| 3. "Building Microservices" by Sam Newman
| 4. "High Performance Browser Networking" by Ilya Grigorik
| 5. "The Art of Web Development" by James Williamson
|
| Online Resources:
| 1. Zen Framework Documentation: https://thezen.ct.ws
| 2. PHP Documentation: https://www.php.net/docs.php
| 3. MDN Web Docs: https://developer.mozilla.org
| 4. Stack Overflow: https://stackoverflow.com
| 5. GitHub: https://github.com/thezen
|
| Tools and Services:
| 1. Development Environment: Docker, XAMPP, MAMP
| 2. Version Control: Git, GitHub, GitLab
| 3. IDE: VS Code, PhpStorm
| 4. Database: MySQL, PostgreSQL, SQLite
| 5. Deployment: AWS, Google Cloud, Heroku
| 6. Monitoring: New Relic, DataDog, Sentry
|
| Communities:
| 1. Reddit: r/PHP, r/webdev
| 2. Discord: PHP Discord Server
| 3. Meetup: Local PHP and web development groups
| 4. Conferences: PHP Conference, Laracon, SymfonyCon
|
*/

?>
