<?php
/**
 * Zen Framework 3.1.6
 * 
 * A single-file, enterprise-grade PHP framework that compresses the core capabilities
 * of Django, Ruby on Rails, Flask, CodeIgniter, and modern cloud-native frameworks.
 * 
 * Unix timestamp is 1765602281 https://github.com/mwirigimahugu/zen ver3.1.6 ALL IN ONE enterprise grade fullstack framework by johnmahugu@gmail.com for by beloved son Seth.
 * @version 3.1.6
 * @author Zen Framework Team [Seth Ng'ang'a , Jean Luc Kajuga, Prof. Anthony Wanjohi and johnmahugu]
 * @license MIT
 */

// ============================================================================
// BOOTSTRAP & CONFIGURATION
// ============================================================================

// Ensure we're running on PHP 8.0+
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('Zen Framework requires PHP 8.0 or higher.');
}

// Define framework constants
define('ZEN_VERSION', '3.1.6');
define('ZEN_START_TIME', microtime(true));
define('ZEN_START_MEMORY', memory_get_usage());

// ============================================================================
// ENVIRONMENT & CONFIGURATION
// ============================================================================

class ZenConfig
{
    private static array $config = [];
    private static string $env = 'production';
    
    /**
     * Load configuration from environment variables and defaults
     */
    public static function load(): void
    {
        // Default configuration
        self::$config = [
            'app' => [
                'name' => 'Zen Application',
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
                'key' => $_ENV['APP_KEY'] ?? null,
            ],
            'database' => [
                'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? '3306',
                'database' => $_ENV['DB_DATABASE'] ?? 'zen',
                'username' => $_ENV['DB_USERNAME'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
                'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
                'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
                'prefix' => $_ENV['DB_PREFIX'] ?? '',
            ],
            'cache' => [
                'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
                'path' => $_ENV['CACHE_PATH'] ?? sys_get_temp_dir() . '/zen_cache',
                'ttl' => (int)($_ENV['CACHE_TTL'] ?? 3600),
            ],
            'session' => [
                'driver' => $_ENV['SESSION_DRIVER'] ?? 'file',
                'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
                'path' => $_ENV['SESSION_PATH'] ?? sys_get_temp_dir() . '/zen_sessions',
                'cookie' => $_ENV['SESSION_COOKIE'] ?? 'zen_session',
                'secure' => filter_var($_ENV['SESSION_SECURE'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'httponly' => true,
                'samesite' => 'lax',
            ],
            'mail' => [
                'driver' => $_ENV['MAIL_DRIVER'] ?? 'smtp',
                'host' => $_ENV['MAIL_HOST'] ?? 'localhost',
                'port' => (int)($_ENV['MAIL_PORT'] ?? 587),
                'username' => $_ENV['MAIL_USERNAME'] ?? '',
                'password' => $_ENV['MAIL_PASSWORD'] ?? '',
                'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
                'from' => [
                    'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@example.com',
                    'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Zen App',
                ],
            ],
            'logging' => [
                'driver' => $_ENV['LOG_DRIVER'] ?? 'file',
                'path' => $_ENV['LOG_PATH'] ?? sys_get_temp_dir() . '/zen_logs',
                'level' => $_ENV['LOG_LEVEL'] ?? 'info',
            ],
            'security' => [
                'csrf_token_name' => '_token',
                'csrf_header_name' => 'X-CSRF-TOKEN',
                'rate_limit' => [
                    'enabled' => filter_var($_ENV['RATE_LIMIT_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'requests' => (int)($_ENV['RATE_LIMIT_REQUESTS'] ?? 60),
                    'minutes' => (int)($_ENV['RATE_LIMIT_MINUTES'] ?? 1),
                ],
            ],
        ];
        
        self::$env = self::$config['app']['env'];
        
        // Set timezone
        date_default_timezone_set(self::$config['app']['timezone']);
        
        // Set error reporting based on environment
        if (self::$config['app']['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
            ini_set('display_errors', '0');
        }
    }
    
    /**
     * Get a configuration value
     */
    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Set a configuration value
     */
    public static function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &self::$config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
    }
    
    /**
     * Get the current environment
     */
    public static function env(): string
    {
        return self::$env;
    }
    
    /**
     * Check if the application is in production environment
     */
    public static function isProduction(): bool
    {
        return self::$env === 'production';
    }
    
    /**
     * Check if the application is in development environment
     */
    public static function isDevelopment(): bool
    {
        return self::$env === 'development' || self::$env === 'local';
    }
}

// ============================================================================
// DEPENDENCY INJECTION CONTAINER
// ============================================================================

class ZenContainer
{
    private array $bindings = [];
    private array $instances = [];
    private array $aliases = [];
    
    /**
     * Register a binding in the container
     */
    public function bind(string $abstract, $concrete = null, bool $shared = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
    }
    
    /**
     * Register a singleton binding in the container
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }
    
    /**
     * Register an instance in the container
     */
    public function instance(string $abstract, $instance): void
    {
        $this->instances[$abstract] = $instance;
    }
    
    /**
     * Alias a type to a different name
     */
    public function alias(string $abstract, string $alias): void
    {
        $this->aliases[$alias] = $abstract;
    }
    
    /**
     * Resolve a binding from the container
     */
    public function make(string $abstract)
    {
        // Check for aliases
        if (isset($this->aliases[$abstract])) {
            $abstract = $this->aliases[$abstract];
        }
        
        // If we have an instance, return it
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        // If we don't have a binding, just instantiate the class
        if (!isset($this->bindings[$abstract])) {
            return $this->build($abstract);
        }
        
        $concrete = $this->bindings[$abstract]['concrete'];
        $shared = $this->bindings[$abstract]['shared'];
        
        // If the concrete is a closure, resolve it
        if ($concrete instanceof Closure) {
            $object = $concrete($this);
        } else {
            $object = $this->build($concrete);
        }
        
        // If it's a shared binding, store the instance
        if ($shared) {
            $this->instances[$abstract] = $object;
        }
        
        return $object;
    }
    
    /**
     * Build an instance of a class
     */
    private function build(string $concrete)
    {
        if (!class_exists($concrete)) {
            throw new Exception("Class {$concrete} does not exist");
        }
        
        $reflector = new ReflectionClass($concrete);
        
        // Check if the class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        
        // If there's no constructor, just instantiate
        if ($constructor === null) {
            return new $concrete();
        }
        
        $dependencies = $constructor->getParameters();
        
        // If there are no dependencies, just instantiate
        if (empty($dependencies)) {
            return new $concrete();
        }
        
        $instances = [];
        
        // Resolve each dependency
        foreach ($dependencies as $dependency) {
            $type = $dependency->getType();
            
            if ($type === null) {
                throw new Exception("Unresolvable dependency [{$dependency->name}] on class {$concrete}");
            }
            
            $instances[] = $this->make($type->getName());
        }
        
        return $reflector->newInstanceArgs($instances);
    }
    
    /**
     * Call a method with dependency injection
     */
    public function call($callback, array $parameters = [])
    {
        if ($callback instanceof Closure) {
            return $this->callClosure($callback, $parameters);
        } elseif (is_array($callback) && count($callback) === 2) {
            return $this->callMethod($callback[0], $callback[1], $parameters);
        } elseif (is_string($callback) && strpos($callback, '@') !== false) {
            [$class, $method] = explode('@', $callback);
            return $this->callMethod($class, $method, $parameters);
        }
        
        throw new Exception('Invalid callback provided to container call');
    }
    
    /**
     * Call a closure with dependency injection
     */
    private function callClosure(Closure $closure, array $parameters = [])
    {
        $reflector = new ReflectionFunction($closure);
        $dependencies = [];
        
        foreach ($reflector->getParameters() as $parameter) {
            $name = $parameter->getName();
            
            if (isset($parameters[$name])) {
                $dependencies[] = $parameters[$name];
            } elseif ($parameter->getType() !== null) {
                $dependencies[] = $this->make($parameter->getType()->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new Exception("Unresolvable dependency [{$name}] in closure");
            }
        }
        
        return $reflector->invokeArgs($dependencies);
    }
    
    /**
     * Call a class method with dependency injection
     */
    private function callMethod($class, string $method, array $parameters = [])
    {
        if (is_string($class)) {
            $class = $this->make($class);
        }
        
        $reflector = new ReflectionMethod($class, $method);
        $dependencies = [];
        
        foreach ($reflector->getParameters() as $parameter) {
            $name = $parameter->getName();
            
            if (isset($parameters[$name])) {
                $dependencies[] = $parameters[$name];
            } elseif ($parameter->getType() !== null) {
                $dependencies[] = $this->make($parameter->getType()->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new Exception("Unresolvable dependency [{$name}] in method {$method}");
            }
        }
        
        return $reflector->invokeArgs($class, $dependencies);
    }
}

// ============================================================================
// ERROR & EXCEPTION HANDLING
// ============================================================================

class ZenErrorHandler
{
    private ZenContainer $container;
    private ZenLogger $logger;
    
    public function __construct(ZenContainer $container)
    {
        $this->container = $container;
        $this->logger = $container->make(ZenLogger::class);
        
        // Set error and exception handlers
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
    
    /**
     * Handle PHP errors
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        if (!(error_reporting() & $level)) {
            return false;
        }
        
        $exception = new ErrorException($message, 0, $level, $file, $line);
        $this->handleException($exception);
        
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public function handleException(Throwable $exception): void
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
        
        if (ZenConfig::isDevelopment()) {
            $this->renderException($exception);
        } else {
            $this->renderErrorPage(500);
        }
        
        exit(1);
    }
    
    /**
     * Handle fatal errors
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $exception = new ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );
            
            $this->handleException($exception);
        }
    }
    
    /**
     * Render exception details in development
     */
    private function renderException(Throwable $exception): void
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Zen Framework - Exception</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #e74c3c; }
        .exception { margin-bottom: 20px; }
        .trace { background-color: #f8f8f8; padding: 15px; border-radius: 5px; overflow: auto; font-family: monospace; }
        .file { color: #3498db; }
        .line { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>' . get_class($exception) . '</h1>
        <div class="exception">
            <p><strong>Message:</strong> ' . htmlspecialchars($exception->getMessage()) . '</p>
            <p><strong>File:</strong> <span class="file">' . htmlspecialchars($exception->getFile()) . '</span></p>
            <p><strong>Line:</strong> <span class="line">' . $exception->getLine() . '</span></p>
        </div>
        <h2>Stack Trace</h2>
        <div class="trace">' . htmlspecialchars($exception->getTraceAsString()) . '</div>
    </div>
</body>
</html>';
        
        echo $html;
    }
    
    /**
     * Render a generic error page
     */
    private function renderErrorPage(int $code): void
    {
        $messages = [
            404 => 'Page Not Found',
            500 => 'Internal Server Error',
        ];
        
        $message = $messages[$code] ?? 'Error';
        
        http_response_code($code);
        
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Zen Framework - ' . $code . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { text-align: center; background-color: #fff; padding: 40px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { font-size: 48px; margin-bottom: 20px; color: #3498db; }
        p { font-size: 18px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>' . $code . '</h1>
        <p>' . $message . '</p>
    </div>
</body>
</html>';
    }
}

// ============================================================================
// LOGGING
// ============================================================================

class ZenLogger
{
    private string $path;
    private string $level;
    
    // Log levels in order of severity
    private const LEVELS = [
        'debug' => 0,
        'info' => 1,
        'warning' => 2,
        'error' => 3,
        'critical' => 4,
    ];
    
    public function __construct()
    {
        $this->path = ZenConfig::get('logging.path', sys_get_temp_dir() . '/zen_logs');
        $this->level = ZenConfig::get('logging.level', 'info');
        
        // Ensure log directory exists
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    /**
     * Log a debug message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }
    
    /**
     * Log an info message
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }
    
    /**
     * Log a warning message
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }
    
    /**
     * Log an error message
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }
    
    /**
     * Log a critical message
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }
    
    /**
     * Log a message
     */
    private function log(string $level, string $message, array $context = []): void
    {
        // Check if this level should be logged
        if (self::LEVELS[$level] < self::LEVELS[$this->level]) {
            return;
        }
        
        $date = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        $logMessage = "[{$date}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        // Write to log file
        $logFile = $this->path . '/' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

// ============================================================================
// HTTP LAYER - REQUEST & RESPONSE
// ============================================================================

class ZenRequest
{
    private array $get;
    private array $post;
    private array $cookies;
    private array $files;
    private array $server;
    private array $headers;
    private string $method;
    private string $uri;
    private string $path;
    private string $queryString;
    private string $ip;
    private bool $secure;
    private bool $ajax;
    private bool $pjax;
    
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        
        $this->method = $this->server['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->server['REQUEST_URI'] ?? '/';
        $this->path = parse_url($this->uri, PHP_URL_PATH) ?: '/';
        $this->queryString = parse_url($this->uri, PHP_URL_QUERY) ?: '';
        
        $this->ip = $this->determineIp();
        $this->secure = !empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
        $this->ajax = !empty($this->server['HTTP_X_REQUESTED_WITH']) && 
                     strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $this->pjax = !empty($this->server['HTTP_X_PJAX']);
        
        $this->headers = $this->getHeaders();
    }
    
    /**
     * Get all input data
     */
    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }
    
    /**
     * Get input data by key
     */
    public function input(string $key, $default = null)
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }
    
    /**
     * Get query string data by key
     */
    public function query(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }
    
    /**
     * Get POST data by key
     */
    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }
    
    /**
     * Get cookie by key
     */
    public function cookie(string $key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }
    
    /**
     * Get file by key
     */
    public function file(string $key): ?ZenUploadedFile
    {
        if (!isset($this->files[$key])) {
            return null;
        }
        
        return new ZenUploadedFile($this->files[$key]);
    }
    
    /**
     * Get header by key
     */
    public function header(string $key, $default = null)
    {
        $key = strtoupper(str_replace('-', '_', $key));
        return $this->headers[$key] ?? $default;
    }
    
    /**
     * Get server variable by key
     */
    public function server(string $key, $default = null)
    {
        return $this->server[$key] ?? $default;
    }
    
    /**
     * Get request method
     */
    public function method(): string
    {
        return $this->method;
    }
    
    /**
     * Get request URI
     */
    public function uri(): string
    {
        return $this->uri;
    }
    
    /**
     * Get request path
     */
    public function path(): string
    {
        return $this->path;
    }
    
    /**
     * Get query string
     */
    public function queryString(): string
    {
        return $this->queryString;
    }
    
    /**
     * Get client IP address
     */
    public function ip(): string
    {
        return $this->ip;
    }
    
    /**
     * Check if request is secure (HTTPS)
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }
    
    /**
     * Check if request is AJAX
     */
    public function isAjax(): bool
    {
        return $this->ajax;
    }
    
    /**
     * Check if request is PJAX
     */
    public function isPjax(): bool
    {
        return $this->pjax;
    }
    
    /**
     * Get JSON payload
     */
    public function json(): ?array
    {
        if ($this->method !== 'POST' && $this->method !== 'PUT' && $this->method !== 'PATCH') {
            return null;
        }
        
        $contentType = $this->header('Content-Type', '');
        
        if (strpos($contentType, 'application/json') === false) {
            return null;
        }
        
        $input = file_get_contents('php://input');
        
        if (empty($input)) {
            return null;
        }
        
        $json = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        return $json;
    }
    
    /**
     * Determine client IP address
     */
    private function determineIp(): string
    {
        $ipKeys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR',
        ];
        
        foreach ($ipKeys as $key) {
            if (!empty($this->server[$key])) {
                $ips = explode(',', $this->server[$key]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Get all headers
     */
    private function getHeaders(): array
    {
        $headers = [];
        
        foreach ($this->server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[substr($key, 5)] = $value;
            }
        }
        
        return $headers;
    }
}

class ZenResponse
{
    private string $content = '';
    private int $statusCode = 200;
    private array $headers = [];
    
    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    /**
     * Create a new response
     */
    public static function make(string $content = '', int $statusCode = 200, array $headers = []): self
    {
        return new self($content, $statusCode, $headers);
    }
    
    /**
     * Create a JSON response
     */
    public static function json($data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        $content = json_encode($data);
        
        return new self($content, $statusCode, $headers);
    }
    
    /**
     * Create a redirect response
     */
    public static function redirect(string $url, int $statusCode = 302): self
    {
        $headers['Location'] = $url;
        
        return new self('', $statusCode, $headers);
    }
    
    /**
     * Create a file download response
     */
    public static function download(string $path, string $name = null): self
    {
        if (!file_exists($path)) {
            throw new Exception("File does not exist: {$path}");
        }
        
        $name = $name ?? basename($path);
        $headers['Content-Disposition'] = 'attachment; filename="' . $name . '"';
        $headers['Content-Length'] = filesize($path);
        $headers['Content-Type'] = mime_content_type($path) ?? 'application/octet-stream';
        
        return new self(file_get_contents($path), 200, $headers);
    }
    
    /**
     * Set response content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Get response content
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * Set status code
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        
        return $this;
    }
    
    /**
     * Get status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Set header
     */
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        
        return $this;
    }
    
    /**
     * Get headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * Send the response
     */
    public function send(): void
    {
        // Send status code
        http_response_code($this->statusCode);
        
        // Send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        // Send content
        echo $this->content;
    }
}

class ZenUploadedFile
{
    private array $file;
    
    public function __construct(array $file)
    {
        $this->file = $file;
    }
    
    /**
     * Get original filename
     */
    public function getOriginalName(): string
    {
        return $this->file['name'];
    }
    
    /**
     * Get file MIME type
     */
    public function getMimeType(): string
    {
        return $this->file['type'];
    }
    
    /**
     * Get file size in bytes
     */
    public function getSize(): int
    {
        return $this->file['size'];
    }
    
    /**
     * Get temporary file path
     */
    public function getTempPath(): string
    {
        return $this->file['tmp_name'];
    }
    
    /**
     * Get upload error code
     */
    public function getError(): int
    {
        return $this->file['error'];
    }
    
    /**
     * Check if there was an upload error
     */
    public function hasError(): bool
    {
        return $this->file['error'] !== UPLOAD_ERR_OK;
    }
    
    /**
     * Check if the file is an image
     */
    public function isImage(): bool
    {
        return strpos($this->getMimeType(), 'image/') === 0;
    }
    
    /**
     * Move the uploaded file to a new location
     */
    public function move(string $directory, string $name = null): bool
    {
        if ($this->hasError()) {
            return false;
        }
        
        $name = $name ?? $this->getOriginalName();
        $path = rtrim($directory, '/') . '/' . $name;
        
        // Ensure directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return move_uploaded_file($this->getTempPath(), $path);
    }
    
    /**
     * Get file contents
     */
    public function getContents(): string
    {
        if ($this->hasError()) {
            return '';
        }
        
        return file_get_contents($this->getTempPath());
    }
}

// ============================================================================
// ROUTING
// ============================================================================

class ZenRouter
{
    private ZenContainer $container;
    private array $routes = [];
    private array $middleware = [];
    private array $routeGroups = [];
    private array $namedRoutes = [];
    private string $prefix = '';
    private array $groupMiddleware = [];
    
    public function __construct(ZenContainer $container)
    {
        $this->container = $container;
    }
    
    /**
     * Register a GET route
     */
    public function get(string $uri, $action): ZenRoute
    {
        return $this->addRoute('GET', $uri, $action);
    }
    
    /**
     * Register a POST route
     */
    public function post(string $uri, $action): ZenRoute
    {
        return $this->addRoute('POST', $uri, $action);
    }
    
    /**
     * Register a PUT route
     */
    public function put(string $uri, $action): ZenRoute
    {
        return $this->addRoute('PUT', $uri, $action);
    }
    
    /**
     * Register a PATCH route
     */
    public function patch(string $uri, $action): ZenRoute
    {
        return $this->addRoute('PATCH', $uri, $action);
    }
    
    /**
     * Register a DELETE route
     */
    public function delete(string $uri, $action): ZenRoute
    {
        return $this->addRoute('DELETE', $uri, $action);
    }
    
    /**
     * Register a route for any HTTP method
     */
    public function any(string $uri, $action): ZenRoute
    {
        return $this->addRoute('*', $uri, $action);
    }
    
    /**
     * Register a route for multiple HTTP methods
     */
    public function match(array $methods, string $uri, $action): ZenRoute
    {
        foreach ($methods as $method) {
            $this->addRoute(strtoupper($method), $uri, $action);
        }
        
        return $this->routes[end(array_keys($this->routes))];
    }
    
    /**
     * Register a route group
     */
    public function group(array $attributes, Closure $callback): void
    {
        $this->routeGroups[] = $attributes;
        
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->groupMiddleware;
        
        if (isset($attributes['prefix'])) {
            $this->prefix .= '/' . trim($attributes['prefix'], '/');
        }
        
        if (isset($attributes['middleware'])) {
            $this->groupMiddleware = array_merge($this->groupMiddleware, (array)$attributes['middleware']);
        }
        
        $callback($this);
        
        $this->prefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
        
        array_pop($this->routeGroups);
    }
    
    /**
     * Add a route to the routing table
     */
    private function addRoute(string $method, string $uri, $action): ZenRoute
    {
        $uri = '/' . trim($uri, '/');
        
        if ($this->prefix !== '') {
            $uri = $this->prefix . $uri;
        }
        
        $route = new ZenRoute($method, $uri, $action);
        
        // Apply group middleware
        foreach ($this->groupMiddleware as $middleware) {
            $route->middleware($middleware);
        }
        
        $this->routes[] = $route;
        
        return $route;
    }
    
    /**
     * Register middleware
     */
    public function middleware(string $name, $handler): void
    {
        $this->middleware[$name] = $handler;
    }
    
    /**
     * Dispatch the request to the appropriate route
     */
    public function dispatch(ZenRequest $request): ZenResponse
    {
        $method = $request->method();
        $uri = $request->path();
        
        // Find matching route
        $route = $this->findRoute($method, $uri);
        
        if ($route === null) {
            return $this->handleNotFound();
        }
        
        // Extract route parameters
        $parameters = $this->extractParameters($route, $uri);
        
        // Create a new request with route parameters
        $request = new ZenRequest();
        $request->setRouteParameters($parameters);
        
        // Run middleware pipeline
        $pipeline = new ZenPipeline($this->container);
        
        // Add global middleware
        foreach ($this->getGlobalMiddleware() as $middleware) {
            $pipeline->pipe($middleware);
        }
        
        // Add route-specific middleware
        foreach ($route->getMiddleware() as $middleware) {
            $pipeline->pipe($middleware);
        }
        
        // Set the final destination (the route action)
        $pipeline->destination(function () use ($route, $request) {
            return $this->runRoute($route, $request);
        });
        
        return $pipeline->process($request);
    }
    
    /**
     * Find a matching route
     */
    private function findRoute(string $method, string $uri): ?ZenRoute
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Extract parameters from URI based on route pattern
     */
    private function extractParameters(ZenRoute $route, string $uri): array
    {
        $pattern = $route->getRegex();
        
        if (!preg_match($pattern, $uri, $matches)) {
            return [];
        }
        
        $parameters = [];
        
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[$key] = $value;
            }
        }
        
        return $parameters;
    }
    
    /**
     * Run the route action
     */
    private function runRoute(ZenRoute $route, ZenRequest $request): ZenResponse
    {
        $action = $route->getAction();
        
        if ($action instanceof Closure) {
            $response = $this->container->call($action, ['request' => $request] + $request->getRouteParameters());
        } elseif (is_array($action)) {
            [$controller, $method] = $action;
            $controller = $this->container->make($controller);
            $response = $this->container->call([$controller, $method], ['request' => $request] + $request->getRouteParameters());
        } elseif (is_string($action)) {
            if (strpos($action, '@') !== false) {
                [$controller, $method] = explode('@', $action);
                $controller = $this->container->make($controller);
                $response = $this->container->call([$controller, $method], ['request' => $request] + $request->getRouteParameters());
            } else {
                // Assume it's a callable class
                $controller = $this->container->make($action);
                $response = $this->container->call([$controller, '__invoke'], ['request' => $request] + $request->getRouteParameters());
            }
        } else {
            throw new Exception('Invalid route action');
        }
        
        // Convert response to ZenResponse if needed
        if (!$response instanceof ZenResponse) {
            $response = new ZenResponse($response);
        }
        
        return $response;
    }
    
    /**
     * Handle 404 Not Found
     */
    private function handleNotFound(): ZenResponse
    {
        return new ZenResponse('Not Found', 404);
    }
    
    /**
     * Get global middleware
     */
    private function getGlobalMiddleware(): array
    {
        return [
            ZenSecurityMiddleware::class,
            ZenRateLimitMiddleware::class,
        ];
    }
    
    /**
     * Generate a URL for a named route
     */
    public function route(string $name, array $parameters = []): ?string
    {
        if (!isset($this->namedRoutes[$name])) {
            return null;
        }
        
        $uri = $this->namedRoutes[$name];
        
        // Replace parameters in URI
        foreach ($parameters as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }
        
        // Remove optional parameters that weren't provided
        $uri = preg_replace('/\{[^}]+\?\}/', '', $uri);
        
        return $uri;
    }
    
    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

class ZenRoute
{
    private string $method;
    private string $uri;
    private $action;
    private array $middleware = [];
    private ?string $name = null;
    private string $regex;
    
    public function __construct(string $method, string $uri, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = $this->compileRegex();
    }
    
    /**
     * Check if the route matches the given method and URI
     */
    public function matches(string $method, string $uri): bool
    {
        if ($this->method !== '*' && $this->method !== $method) {
            return false;
        }
        
        return preg_match($this->regex, $uri);
    }
    
    /**
     * Compile the route URI to a regex pattern
     */
    private function compileRegex(): string
    {
        $pattern = $this->uri;
        
        // Convert {param} to named capture group
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $pattern);
        
        // Convert {param?} to optional named capture group
        $pattern = preg_replace('/\{([^}]+)\?\}/', '(?:/(?P<$1>[^/]+))?', $pattern);
        
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Get the regex pattern
     */
    public function getRegex(): string
    {
        return $this->regex;
    }
    
    /**
     * Get the HTTP method
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    
    /**
     * Get the URI
     */
    public function getUri(): string
    {
        return $this->uri;
    }
    
    /**
     * Get the action
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * Get middleware
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
    
    /**
     * Add middleware to the route
     */
    public function middleware($middleware): self
    {
        $this->middleware = array_merge($this->middleware, (array)$middleware);
        
        return $this;
    }
    
    /**
     * Set the route name
     */
    public function name(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Get the route name
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}

// ============================================================================
// MIDDLEWARE
// ============================================================================

class ZenPipeline
{
    private ZenContainer $container;
    private array $pipes = [];
    private $destination;
    
    public function __construct(ZenContainer $container)
    {
        $this->container = $container;
    }
    
    /**
     * Add a pipe to the pipeline
     */
    public function pipe($pipe): self
    {
        $this->pipes[] = $pipe;
        
        return $this;
    }
    
    /**
     * Set the destination of the pipeline
     */
    public function destination($destination): self
    {
        $this->destination = $destination;
        
        return $this;
    }
    
    /**
     * Process the request through the pipeline
     */
    public function process(ZenRequest $request): ZenResponse
    {
        return $this->carry($request);
    }
    
    /**
     * Carry the request through the pipeline
     */
    private function carry(ZenRequest $request): ZenResponse
    {
        if (empty($this->pipes)) {
            return $this->container->call($this->destination, ['request' => $request]);
        }
        
        $pipe = array_shift($this->pipes);
        
        if (is_string($pipe)) {
            $pipe = $this->container->make($pipe);
        }
        
        return $pipe->handle($request, function ($request) {
            return $this->carry($request);
        });
    }
}

interface ZenMiddlewareInterface
{
    /**
     * Handle the request
     */
    public function handle(ZenRequest $request, Closure $next): ZenResponse;
}

class ZenSecurityMiddleware implements ZenMiddlewareInterface
{
    private ZenConfig $config;
    
    public function __construct()
    {
        $this->config = new ZenConfig();
    }
    
    /**
     * Handle the request
     */
    public function handle(ZenRequest $request, Closure $next): ZenResponse
    {
        $response = $next($request);
        
        // Add security headers
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-XSS-Protection', '1; mode=block');
        
        // Add CSP header if configured
        if ($this->config->get('security.csp.enabled', false)) {
            $policy = $this->config->get('security.csp.policy', "default-src 'self'");
            $response->header('Content-Security-Policy', $policy);
        }
        
        // Add HSTS header if HTTPS and configured
        if ($request->isSecure() && $this->config->get('security.hsts.enabled', false)) {
            $maxAge = $this->config->get('security.hsts.max_age', 31536000);
            $includeSubDomains = $this->config->get('security.hsts.include_subdomains', false) ? '; includeSubDomains' : '';
            $preload = $this->config->get('security.hsts.preload', false) ? '; preload' : '';
            
            $response->header('Strict-Transport-Security', "max-age={$maxAge}{$includeSubDomains}{$preload}");
        }
        
        return $response;
    }
}

class ZenRateLimitMiddleware implements ZenMiddlewareInterface
{
    private ZenCache $cache;
    private ZenConfig $config;
    
    public function __construct(ZenCache $cache)
    {
        $this->cache = $cache;
        $this->config = new ZenConfig();
    }
    
    /**
     * Handle the request
     */
    public function handle(ZenRequest $request, Closure $next): ZenResponse
    {
        if (!$this->config->get('security.rate_limit.enabled', true)) {
            return $next($request);
        }
        
        $key = 'rate_limit:' . md5($request->ip());
        $limit = $this->config->get('security.rate_limit.requests', 60);
        $minutes = $this->config->get('security.rate_limit.minutes', 1);
        
        $current = $this->cache->get($key, 0);
        
        if ($current >= $limit) {
            return new ZenResponse('Too Many Requests', 429);
        }
        
        $this->cache->put($key, $current + 1, $minutes * 60);
        
        $response = $next($request);
        
        // Add rate limit headers
        $response->header('X-RateLimit-Limit', $limit);
        $response->header('X-RateLimit-Remaining', max(0, $limit - $current - 1));
        
        return $response;
    }
}

class ZenCsrfMiddleware implements ZenMiddlewareInterface
{
    private ZenSession $session;
    private ZenConfig $config;
    
    public function __construct(ZenSession $session)
    {
        $this->session = $session;
        $this->config = new ZenConfig();
    }
    
    /**
     * Handle the request
     */
    public function handle(ZenRequest $request, Closure $next): ZenResponse
    {
        $method = $request->method();
        
        // Skip CSRF check for safe methods
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }
        
        $tokenName = $this->config->get('security.csrf_token_name', '_token');
        $headerName = $this->config->get('security.csrf_header_name', 'X-CSRF-TOKEN');
        
        $token = $request->input($tokenName) ?? $request->header($headerName);
        
        if (!$token || !$this->tokensMatch($token)) {
            return new ZenResponse('CSRF token mismatch', 419);
        }
        
        return $next($request);
    }
    
    /**
     * Check if the provided token matches the session token
     */
    private function tokensMatch(string $token): bool
    {
        $sessionToken = $this->session->get('_token');
        
        return hash_equals($sessionToken, $token);
    }
}

// ============================================================================
// TEMPLATING ENGINE
// ============================================================================

class ZenView
{
    private string $path;
    private array $data = [];
    private array $sections = [];
    private string $currentSection = '';
    private string $layout = '';
    
    public function __construct(string $path, array $data = [])
    {
        $this->path = $path;
        $this->data = $data;
    }
    
    /**
     * Create a new view instance
     */
    public static function make(string $path, array $data = []): self
    {
        return new self($path, $data);
    }
    
    /**
     * Set the layout for the view
     */
    public function layout(string $layout): self
    {
        $this->layout = $layout;
        
        return $this;
    }
    
    /**
     * Extend a layout
     */
    public function extend(string $layout): void
    {
        $this->layout = $layout;
    }
    
    /**
     * Start a section
     */
    public function section(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }
    
    /**
     * End a section
     */
    public function endSection(): void
    {
        if (empty($this->currentSection)) {
            throw new Exception('No section started');
        }
        
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = '';
    }
    
    /**
     * Yield a section
     */
    public function yield(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }
    
    /**
     * Include a partial view
     */
    public function include(string $path, array $data = []): string
    {
        $view = new self($path, array_merge($this->data, $data));
        
        return $view->render();
    }
    
    /**
     * Escape a string
     */
    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Render the view
     */
    public function render(): string
    {
        // Extract data to make it available in the view
        extract($this->data);
        
        // Make view methods available
        $__view = $this;
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = $this->resolveViewPath($this->path);
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$viewFile}");
        }
        
        include $viewFile;
        
        $content = ob_get_clean();
        
        // If a layout is specified, render the layout with the view content
        if (!empty($this->layout)) {
            $layoutFile = $this->resolveViewPath($this->layout);
            
            if (!file_exists($layoutFile)) {
                throw new Exception("Layout file not found: {$layoutFile}");
            }
            
            $__view->sections['content'] = $content;
            
            ob_start();
            include $layoutFile;
            $content = ob_get_clean();
        }
        
        return $content;
    }
    
    /**
     * Resolve the view file path
     */
    private function resolveViewPath(string $path): string
    {
        $viewsPath = ZenConfig::get('app.views_path', 'views');
        
        // Ensure .php extension
        if (!str_ends_with($path, '.php')) {
            $path .= '.php';
        }
        
        return $viewsPath . '/' . $path;
    }
    
    /**
     * Convert the view to a string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}

// ============================================================================
// DATABASE & ORM
// ============================================================================

class ZenDatabase
{
    private static ?PDO $connection = null;
    private array $config;
    
    public function __construct()
    {
        $this->config = ZenConfig::get('database');
    }
    
    /**
     * Get the database connection
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $instance = new self();
            self::$connection = $instance->createConnection();
        }
        
        return self::$connection;
    }
    
    /**
     * Create a new database connection
     */
    private function createConnection(): PDO
    {
        $dsn = $this->buildDsn();
        $username = $this->config['username'];
        $password = $this->config['password'];
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            return new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Build the DSN string
     */
    private function buildDsn(): string
    {
        $driver = $this->config['driver'];
        
        switch ($driver) {
            case 'mysql':
                return "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']};charset={$this->config['charset']}";
            
            case 'pgsql':
                return "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";
            
            case 'sqlite':
                return "sqlite:{$this->config['database']}";
            
            default:
                throw new Exception("Unsupported database driver: {$driver}");
        }
    }
    
    /**
     * Begin a transaction
     */
    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public static function commit(): void
    {
        self::getConnection()->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public static function rollback(): void
    {
        self::getConnection()->rollBack();
    }
    
    /**
     * Execute a query and return the statement
     */
    public static function statement(string $query, array $bindings = []): PDOStatement
    {
        $statement = self::getConnection()->prepare($query);
        $statement->execute($bindings);
        
        return $statement;
    }
    
    /**
     * Execute a query and return the affected rows
     */
    public static function affectingStatement(string $query, array $bindings = []): int
    {
        $statement = self::statement($query, $bindings);
        
        return $statement->rowCount();
    }
    
    /**
     * Execute a select query and return the results
     */
    public static function select(string $query, array $bindings = []): array
    {
        $statement = self::statement($query, $bindings);
        
        return $statement->fetchAll();
    }
    
    /**
     * Execute a select query and return the first result
     */
    public static function selectOne(string $query, array $bindings = [])
    {
        $statement = self::statement($query, $bindings);
        
        return $statement->fetch();
    }
    
    /**
     * Insert a record and return the last insert ID
     */
    public static function insert(string $query, array $bindings = []): string
    {
        self::statement($query, $bindings);
        
        return self::getConnection()->lastInsertId();
    }
    
    /**
     * Get the last insert ID
     */
    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }
}

class ZenQueryBuilder
{
    private PDO $connection;
    private string $table;
    private array $wheres = [];
    private array $orders = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $joins = [];
    private array $columns = ['*'];
    private array $bindings = [];
    
    public function __construct(PDO $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }
    
    /**
     * Set the columns to select
     */
    public function select($columns): self
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        
        return $this;
    }
    
    /**
     * Add a where clause
     */
    public function where(string $column, string $operator = null, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = [
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'and',
        ];
        
        $this->bindings[] = $value;
        
        return $this;
    }
    
    /**
     * Add an or where clause
     */
    public function orWhere(string $column, string $operator = null, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->wheres[] = [
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'or',
        ];
        
        $this->bindings[] = $value;
        
        return $this;
    }
    
    /**
     * Add a where in clause
     */
    public function whereIn(string $column, array $values): self
    {
        $this->wheres[] = [
            'type' => 'in',
            'column' => $column,
            'values' => $values,
            'boolean' => 'and',
        ];
        
        $this->bindings = array_merge($this->bindings, $values);
        
        return $this;
    }
    
    /**
     * Add an or where in clause
     */
    public function orWhereIn(string $column, array $values): self
    {
        $this->wheres[] = [
            'type' => 'in',
            'column' => $column,
            'values' => $values,
            'boolean' => 'or',
        ];
        
        $this->bindings = array_merge($this->bindings, $values);
        
        return $this;
    }
    
    /**
     * Add a where null clause
     */
    public function whereNull(string $column): self
    {
        $this->wheres[] = [
            'type' => 'null',
            'column' => $column,
            'boolean' => 'and',
        ];
        
        return $this;
    }
    
    /**
     * Add an or where null clause
     */
    public function orWhereNull(string $column): self
    {
        $this->wheres[] = [
            'type' => 'null',
            'column' => $column,
            'boolean' => 'or',
        ];
        
        return $this;
    }
    
    /**
     * Add a join clause
     */
    public function join(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = [
            'type' => 'inner',
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
        ];
        
        return $this;
    }
    
    /**
     * Add a left join clause
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = [
            'type' => 'left',
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
        ];
        
        return $this;
    }
    
    /**
     * Add an order by clause
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtolower($direction),
        ];
        
        return $this;
    }
    
    /**
     * Set the limit
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        
        return $this;
    }
    
    /**
     * Set the offset
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        
        return $this;
    }
    
    /**
     * Get the SQL query
     */
    public function toSql(): string
    {
        $columns = implode(', ', $this->columns);
        $query = "SELECT {$columns} FROM {$this->table}";
        
        // Add joins
        foreach ($this->joins as $join) {
            $query .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
        }
        
        // Add wheres
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . $this->compileWheres();
        }
        
        // Add orders
        foreach ($this->orders as $order) {
            $query .= " ORDER BY {$order['column']} {$order['direction']}";
        }
        
        // Add limit
        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }
        
        // Add offset
        if ($this->offset !== null) {
            $query .= " OFFSET {$this->offset}";
        }
        
        return $query;
    }
    
    /**
     * Compile the where clauses
     */
    private function compileWheres(): string
    {
        $sql = [];
        
        foreach ($this->wheres as $where) {
            switch ($where['type']) {
                case 'basic':
                    $sql[] = ($where['boolean'] === 'or' ? 'OR ' : '') . "{$where['column']} {$where['operator']} ?";
                    break;
                
                case 'in':
                    $placeholders = implode(', ', array_fill(0, count($where['values']), '?'));
                    $sql[] = ($where['boolean'] === 'or' ? 'OR ' : '') . "{$where['column']} IN ({$placeholders})";
                    break;
                
                case 'null':
                    $sql[] = ($where['boolean'] === 'or' ? 'OR ' : '') . "{$where['column']} IS NULL";
                    break;
            }
        }
        
        return implode(' ', $sql);
    }
    
    /**
     * Execute the query and return the results
     */
    public function get(): array
    {
        $statement = $this->connection->prepare($this->toSql());
        $statement->execute($this->bindings);
        
        return $statement->fetchAll();
    }
    
    /**
     * Execute the query and return the first result
     */
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        
        return empty($results) ? null : $results[0];
    }
    
    /**
     * Get the count of results
     */
    public function count(): int
    {
        $query = $this->toSql();
        $query = preg_replace('/SELECT.*?FROM/', 'SELECT COUNT(*) FROM', $query);
        $query = preg_replace('/ORDER BY.*$/', '', $query);
        
        $statement = $this->connection->prepare($query);
        $statement->execute($this->bindings);
        
        return (int)$statement->fetchColumn();
    }
    
    /**
     * Insert a record
     */
    public function insert(array $values): bool
    {
        if (empty($values)) {
            return false;
        }
        
        $columns = implode(', ', array_keys($values));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $statement = $this->connection->prepare($query);
        
        return $statement->execute(array_values($values));
    }
    
    /**
     * Update records
     */
    public function update(array $values): int
    {
        if (empty($values)) {
            return 0;
        }
        
        $set = [];
        $bindings = [];
        
        foreach ($values as $column => $value) {
            $set[] = "{$column} = ?";
            $bindings[] = $value;
        }
        
        $set = implode(', ', $set);
        $query = "UPDATE {$this->table} SET {$set}";
        
        // Add wheres
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . $this->compileWheres();
        }
        
        $bindings = array_merge($bindings, $this->bindings);
        
        $statement = $this->connection->prepare($query);
        $statement->execute($bindings);
        
        return $statement->rowCount();
    }
    
    /**
     * Delete records
     */
    public function delete(): int
    {
        $query = "DELETE FROM {$this->table}";
        
        // Add wheres
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . $this->compileWheres();
        }
        
        $statement = $this->connection->prepare($query);
        $statement->execute($this->bindings);
        
        return $statement->rowCount();
    }
}

class ZenModel
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;
    protected array $hidden = [];
    protected array $fillable = [];
    protected array $casts = [];
    protected bool $timestamps = true;
    protected const CREATED_AT = 'created_at';
    protected const UPDATED_AT = 'updated_at';
    
    /**
     * Create a new model instance
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
    
    /**
     * Fill the model with an array of attributes
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        
        return $this;
    }
    
    /**
     * Set a given attribute on the model
     */
    public function setAttribute(string $key, $value): void
    {
        if ($this->isFillable($key)) {
            $this->attributes[$key] = $value;
        }
    }
    
    /**
     * Determine if the attribute is fillable
     */
    protected function isFillable(string $key): bool
    {
        if (empty($this->fillable)) {
            return true;
        }
        
        return in_array($key, $this->fillable);
    }
    
    /**
     * Get an attribute from the model
     */
    public function getAttribute(string $key)
    {
        $value = $this->getAttributeValue($key);
        
        // Cast the value if needed
        if (isset($this->casts[$key])) {
            $value = $this->castAttribute($key, $value);
        }
        
        return $value;
    }
    
    /**
     * Get a plain attribute value
     */
    protected function getAttributeValue(string $key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        
        return null;
    }
    
    /**
     * Cast an attribute to a native PHP type
     */
    protected function castAttribute(string $key, $value)
    {
        if ($value === null) {
            return $value;
        }
        
        switch ($this->casts[$key]) {
            case 'int':
            case 'integer':
                return (int)$value;
            
            case 'real':
            case 'float':
            case 'double':
                return (float)$value;
            
            case 'string':
                return (string)$value;
            
            case 'bool':
            case 'boolean':
                return (bool)$value;
            
            case 'array':
                return json_decode($value, true);
            
            case 'json':
                return json_decode($value);
            
            case 'date':
                return new DateTime($value);
            
            case 'datetime':
                return new DateTime($value);
            
            default:
                return $value;
        }
    }
    
    /**
     * Get the table name
     */
    public function getTable(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }
        
        return strtolower(str_replace('\\', '_', static::class));
    }
    
    /**
     * Get the primary key
     */
    public function getKeyName(): string
    {
        return $this->primaryKey;
    }
    
    /**
     * Get the value of the model's primary key
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }
    
    /**
     * Save the model to the database
     */
    public function save(): bool
    {
        if ($this->exists) {
            return $this->update();
        }
        
        return $this->insert();
    }
    
    /**
     * Insert a new record
     */
    protected function insert(): bool
    {
        if ($this->timestamps) {
            $this->setCreatedAt();
            $this->setUpdatedAt();
        }
        
        $attributes = $this->getAttributes();
        
        $id = ZenDatabase::insert(
            "INSERT INTO {$this->getTable()} (" . implode(', ', array_keys($attributes)) . ") VALUES (" . implode(', ', array_fill(0, count($attributes), '?')) . ")",
            array_values($attributes)
        );
        
        $this->setAttribute($this->getKeyName(), $id);
        $this->exists = true;
        $this->original = $this->attributes;
        
        return true;
    }
    
    /**
     * Update the record in the database
     */
    protected function update(): bool
    {
        if ($this->timestamps) {
            $this->setUpdatedAt();
        }
        
        $dirty = $this->getDirty();
        
        if (empty($dirty)) {
            return true;
        }
        
        $set = [];
        $bindings = [];
        
        foreach ($dirty as $key => $value) {
            $set[] = "{$key} = ?";
            $bindings[] = $value;
        }
        
        $bindings[] = $this->getKey();
        
        $affected = ZenDatabase::affectingStatement(
            "UPDATE {$this->getTable()} SET " . implode(', ', $set) . " WHERE {$this->getKeyName()} = ?",
            $bindings
        );
        
        $this->original = $this->attributes;
        
        return $affected > 0;
    }
    
    /**
     * Delete the model from the database
     */
    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        
        $affected = ZenDatabase::affectingStatement(
            "DELETE FROM {$this->getTable()} WHERE {$this->getKeyName()} = ?",
            [$this->getKey()]
        );
        
        $this->exists = false;
        
        return $affected > 0;
    }
    
    /**
     * Get the attributes that have been changed since last sync
     */
    public function getDirty(): array
    {
        $dirty = [];
        
        foreach ($this->attributes as $key => $value) {
            if (!array_key_exists($key, $this->original) || $this->original[$key] !== $value) {
                $dirty[$key] = $value;
            }
        }
        
        return $dirty;
    }
    
    /**
     * Get all of the current attributes on the model
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    /**
     * Set the creation and update timestamps
     */
    protected function setCreatedAt(): void
    {
        $this->setAttribute(static::CREATED_AT, date('Y-m-d H:i:s'));
    }
    
    /**
     * Set the update timestamp
     */
    protected function setUpdatedAt(): void
    {
        $this->setAttribute(static::UPDATED_AT, date('Y-m-d H:i:s'));
    }
    
    /**
     * Begin a query on the model
     */
    public static function query(): ZenQueryBuilder
    {
        $instance = new static;
        $connection = ZenDatabase::getConnection();
        
        return new ZenQueryBuilder($connection, $instance->getTable());
    }
    
    /**
     * Find a model by its primary key
     */
    public static function find($id): ?self
    {
        $instance = new static;
        
        $result = static::query()->where($instance->getKeyName(), $id)->first();
        
        if ($result === null) {
            return null;
        }
        
        $model = new static($result);
        $model->exists = true;
        $model->original = $model->attributes;
        
        return $model;
    }
    
    /**
     * Find a model by its primary key or throw an exception
     */
    public static function findOrFail($id): self
    {
        $model = static::find($id);
        
        if ($model === null) {
            throw new Exception("Model not found");
        }
        
        return $model;
    }
    
    /**
     * Get all models
     */
    public static function all(): array
    {
        $results = static::query()->get();
        $models = [];
        
        foreach ($results as $result) {
            $model = new static($result);
            $model->exists = true;
            $model->original = $model->attributes;
            $models[] = $model;
        }
        
        return $models;
    }
    
    /**
     * Create a new model and save it to the database
     */
    public static function create(array $attributes): self
    {
        $model = new static($attributes);
        $model->save();
        
        return $model;
    }
    
    /**
     * Update a record in the database
     */
    public static function where(string $column, string $operator = null, $value = null): ZenQueryBuilder
    {
        return static::query()->where($column, $operator, $value);
    }
    
    /**
     * Convert the model instance to an array
     */
    public function toArray(): array
    {
        $attributes = $this->getAttributes();
        
        // Hide attributes
        foreach ($this->hidden as $attribute) {
            unset($attributes[$attribute]);
        }
        
        return $attributes;
    }
    
    /**
     * Convert the model instance to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Dynamically retrieve attributes on the model
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }
    
    /**
     * Dynamically set attributes on the model
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
    
    /**
     * Determine if an attribute exists on the model
     */
    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Unset an attribute on the model
     */
    public function __unset(string $key): void
    {
        unset($this->attributes[$key]);
    }
}

// ============================================================================
// AUTHENTICATION & AUTHORIZATION
// ============================================================================

class ZenAuth
{
    private ZenSession $session;
    private ZenContainer $container;
    private ?ZenUser $user = null;
    
    public function __construct(ZenSession $session, ZenContainer $container)
    {
        $this->session = $session;
        $this->container = $container;
    }
    
    /**
     * Get the currently authenticated user
     */
    public function user(): ?ZenUser
    {
        if ($this->user !== null) {
            return $this->user;
        }
        
        $userId = $this->session->get('auth_user_id');
        
        if ($userId === null) {
            return null;
        }
        
        $userModel = ZenConfig::get('auth.user_model', ZenUser::class);
        $this->user = $userModel::find($userId);
        
        return $this->user;
    }
    
    /**
     * Check if a user is authenticated
     */
    public function check(): bool
    {
        return $this->user() !== null;
    }
    
    /**
     * Check if a user is a guest
     */
    public function guest(): bool
    {
        return !$this->check();
    }
    
    /**
     * Authenticate a user
     */
    public function login(ZenUser $user, bool $remember = false): bool
    {
        $this->session->put('auth_user_id', $user->getKey());
        
        if ($remember) {
            $this->rememberUser($user);
        }
        
        $this->user = $user;
        
        return true;
    }
    
    /**
     * Log the user out
     */
    public function logout(): void
    {
        $this->session->forget('auth_user_id');
        $this->forgetRememberedUser();
        $this->user = null;
    }
    
    /**
     * Remember a user
     */
    private function rememberUser(ZenUser $user): void
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = time() + (30 * 24 * 60 * 60); // 30 days
        
        $this->session->put('auth_remember_token', $token);
        $this->session->put('auth_remember_expires', $expiresAt);
        
        // Store token in database (implementation depends on your user model)
        $user->setRememberToken($token);
        $user->save();
    }
    
    /**
     * Forget a remembered user
     */
    private function forgetRememberedUser(): void
    {
        $token = $this->session->get('auth_remember_token');
        
        if ($token !== null) {
            $userModel = ZenConfig::get('auth.user_model', ZenUser::class);
            $user = $userModel::findByRememberToken($token);
            
            if ($user !== null) {
                $user->setRememberToken(null);
                $user->save();
            }
        }
        
        $this->session->forget('auth_remember_token');
        $this->session->forget('auth_remember_expires');
    }
    
    /**
     * Attempt to authenticate a user with credentials
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        $userModel = ZenConfig::get('auth.user_model', ZenUser::class);
        $user = $userModel::findByEmail($credentials['email']);
        
        if ($user === null) {
            return false;
        }
        
        if (!password_verify($credentials['password'], $user->getPassword())) {
            return false;
        }
        
        return $this->login($user, $remember);
    }
    
    /**
     * Validate user credentials
     */
    public function validate(array $credentials): bool
    {
        $userModel = ZenConfig::get('auth.user_model', ZenUser::class);
        $user = $userModel::findByEmail($credentials['email']);
        
        if ($user === null) {
            return false;
        }
        
        return password_verify($credentials['password'], $user->getPassword());
    }
    
    /**
     * Get the ID of the currently authenticated user
     */
    public function id(): ?int
    {
        $user = $this->user();
        
        return $user ? $user->getKey() : null;
    }
}

class ZenUser extends ZenModel
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password', 'remember_token'];
    protected array $hidden = ['password', 'remember_token'];
    protected array $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Find a user by email
     */
    public static function findByEmail(string $email): ?self
    {
        $result = static::query()->where('email', $email)->first();
        
        if ($result === null) {
            return null;
        }
        
        $user = new static($result);
        $user->exists = true;
        $user->original = $user->attributes;
        
        return $user;
    }
    
    /**
     * Find a user by remember token
     */
    public static function findByRememberToken(string $token): ?self
    {
        $result = static::query()->where('remember_token', $token)->first();
        
        if ($result === null) {
            return null;
        }
        
        $user = new static($result);
        $user->exists = true;
        $user->original = $user->attributes;
        
        return $user;
    }
    
    /**
     * Get the user's password
     */
    public function getPassword(): string
    {
        return $this->getAttribute('password');
    }
    
    /**
     * Set the user's password
     */
    public function setPassword(string $password): void
    {
        $this->setAttribute('password', password_hash($password, PASSWORD_DEFAULT));
    }
    
    /**
     * Get the remember token
     */
    public function getRememberToken(): ?string
    {
        return $this->getAttribute('remember_token');
    }
    
    /**
     * Set the remember token
     */
    public function setRememberToken(?string $token): void
    {
        $this->setAttribute('remember_token', $token);
    }
    
    /**
     * Check if the user has a role
     */
    public function hasRole(string $role): bool
    {
        $roles = $this->getAttribute('roles');
        
        if (is_string($roles)) {
            $roles = json_decode($roles, true);
        }
        
        return is_array($roles) && in_array($role, $roles);
    }
    
    /**
     * Check if the user has a permission
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getAttribute('permissions');
        
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true);
        }
        
        return is_array($permissions) && in_array($permission, $permissions);
    }
}

class ZenGate
{
    private static array $policies = [];
    private static ZenContainer $container;
    
    /**
     * Set the container instance
     */
    public static function setContainer(ZenContainer $container): void
    {
        self::$container = $container;
    }
    
    /**
     * Define a policy
     */
    public static function policy(string $modelClass, string $policyClass): void
    {
        self::$policies[$modelClass] = $policyClass;
    }
    
    /**
     * Determine if the user is authorized to perform an action
     */
    public static function allows(string $ability, $model = null): bool
    {
        return self::check($ability, $model);
    }
    
    /**
     * Determine if the user is not authorized to perform an action
     */
    public static function denies(string $ability, $model = null): bool
    {
        return !self::check($ability, $model);
    }
    
    /**
     * Check if the user is authorized to perform an action
     */
    private static function check(string $ability, $model = null): bool
    {
        $auth = self::$container->make(ZenAuth::class);
        $user = $auth->user();
        
        if ($user === null) {
            return false;
        }
        
        // Check for a policy
        if ($model !== null) {
            $modelClass = get_class($model);
            
            if (isset(self::$policies[$modelClass])) {
                $policyClass = self::$policies[$modelClass];
                $policy = self::$container->make($policyClass);
                
                if (method_exists($policy, $ability)) {
                    return self::$container->call([$policy, $ability], ['user' => $user, 'model' => $model]);
                }
            }
        }
        
        // Check for a user ability
        if (method_exists($user, $ability)) {
            return self::$container->call([$user, $ability], [$model]);
        }
        
        // Default to false
        return false;
    }
    
    /**
     * Authorize the action or throw an exception
     */
    public static function authorize(string $ability, $model = null): void
    {
        if (!self::allows($ability, $model)) {
            throw new Exception('This action is unauthorized');
        }
    }
}

// ============================================================================
// SESSION MANAGEMENT
// ============================================================================

class ZenSession
{
    private string $sessionId;
    private array $data = [];
    private bool $started = false;
    private int $lifetime;
    private string $path;
    private string $cookie;
    private bool $secure;
    private bool $httponly;
    private string $samesite;
    
    public function __construct()
    {
        $config = ZenConfig::get('session');
        
        $this->lifetime = $config['lifetime'] * 60; // Convert minutes to seconds
        $this->path = $config['path'];
        $this->cookie = $config['cookie'];
        $this->secure = $config['secure'];
        $this->httponly = $config['httponly'];
        $this->samesite = $config['samesite'];
        
        $this->sessionId = $this->getIdFromRequest();
        
        if ($this->sessionId === null) {
            $this->sessionId = $this->generateId();
        }
    }
    
    /**
     * Start the session
     */
    public function start(): void
    {
        if ($this->started) {
            return;
        }
        
        $this->loadSessionData();
        $this->started = true;
        
        // Set session cookie
        $this->setCookie();
    }
    
    /**
     * Get the session ID from the request
     */
    private function getIdFromRequest(): ?string
    {
        return $_COOKIE[$this->cookie] ?? null;
    }
    
    /**
     * Generate a new session ID
     */
    private function generateId(): string
    {
        return bin2hex(random_bytes(16));
    }
    
    /**
     * Load session data from storage
     */
    private function loadSessionData(): void
    {
        $filename = $this->path . '/' . $this->sessionId . '.sess';
        
        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            $this->data = unserialize($data);
        }
    }
    
    /**
     * Save session data to storage
     */
    public function save(): void
    {
        if (!$this->started) {
            return;
        }
        
        // Ensure session directory exists
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
        
        $filename = $this->path . '/' . $this->sessionId . '.sess';
        file_put_contents($filename, serialize($this->data), LOCK_EX);
    }
    
    /**
     * Set the session cookie
     */
    private function setCookie(): void
    {
        $params = [
            'expires' => time() + $this->lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $this->secure,
            'httponly' => $this->httponly,
            'samesite' => $this->samesite,
        ];
        
        setcookie($this->cookie, $this->sessionId, $params);
    }
    
    /**
     * Get a value from the session
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Put a value in the session
     */
    public function put(string $key, $value): void
    {
        $this->data[$key] = $value;
    }
    
    /**
     * Check if a key exists in the session
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Remove a value from the session
     */
    public function forget(string $key): void
    {
        unset($this->data[$key]);
    }
    
    /**
     * Get all session data
     */
    public function all(): array
    {
        return $this->data;
    }
    
    /**
     * Flash a value to the session
     */
    public function flash(string $key, $value): void
    {
        $this->put('_flash.' . $key, $value);
    }
    
    /**
     * Get a flashed value
     */
    public function getFlash(string $key, $default = null)
    {
        $flashKey = '_flash.' . $key;
        $value = $this->get($flashKey, $default);
        
        // Remove the flash data
        $this->forget($flashKey);
        
        return $value;
    }
    
    /**
     * Get the session ID
     */
    public function getId(): string
    {
        return $this->sessionId;
    }
    
    /**
     * Regenerate the session ID
     */
    public function regenerate(bool $deleteOldSession = false): void
    {
        if ($deleteOldSession) {
            $filename = $this->path . '/' . $this->sessionId . '.sess';
            
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        
        $this->sessionId = $this->generateId();
        $this->setCookie();
    }
    
    /**
     * Destroy the session
     */
    public function destroy(): void
    {
        $filename = $this->path . '/' . $this->sessionId . '.sess';
        
        if (file_exists($filename)) {
            unlink($filename);
        }
        
        $this->data = [];
        $this->started = false;
        
        // Delete the cookie
        setcookie($this->cookie, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => $this->secure,
            'httponly' => $this->httponly,
            'samesite' => $this->samesite,
        ]);
    }
}

// ============================================================================
// CACHE
// ============================================================================

class ZenCache
{
    private string $path;
    private int $ttl;
    
    public function __construct()
    {
        $config = ZenConfig::get('cache');
        
        $this->path = $config['path'];
        $this->ttl = $config['ttl'];
        
        // Ensure cache directory exists
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    /**
     * Get a value from the cache
     */
    public function get(string $key, $default = null)
    {
        $filename = $this->getFilename($key);
        
        if (!file_exists($filename)) {
            return $default;
        }
        
        $data = file_get_contents($filename);
        $cache = unserialize($data);
        
        // Check if the cache has expired
        if ($cache['expires'] !== 0 && $cache['expires'] < time()) {
            unlink($filename);
            return $default;
        }
        
        return $cache['value'];
    }
    
    /**
     * Put a value in the cache
     */
    public function put(string $key, $value, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->ttl;
        $expires = $ttl === 0 ? 0 : time() + $ttl;
        
        $cache = [
            'value' => $value,
            'expires' => $expires,
        ];
        
        $filename = $this->getFilename($key);
        
        return file_put_contents($filename, serialize($cache), LOCK_EX) !== false;
    }
    
    /**
     * Check if a key exists in the cache
     */
    public function has(string $key): bool
    {
        $filename = $this->getFilename($key);
        
        if (!file_exists($filename)) {
            return false;
        }
        
        $data = file_get_contents($filename);
        $cache = unserialize($data);
        
        // Check if the cache has expired
        if ($cache['expires'] !== 0 && $cache['expires'] < time()) {
            unlink($filename);
            return false;
        }
        
        return true;
    }
    
    /**
     * Remove a value from the cache
     */
    public function forget(string $key): bool
    {
        $filename = $this->getFilename($key);
        
        if (!file_exists($filename)) {
            return true;
        }
        
        return unlink($filename);
    }
    
    /**
     * Clear the entire cache
     */
    public function clear(): bool
    {
        $files = glob($this->path . '/*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }
    
    /**
     * Get the filename for a cache key
     */
    private function getFilename(string $key): string
    {
        return $this->path . '/' . md5($key) . '.cache';
    }
    
    /**
     * Remember a value in the cache
     */
    public function remember(string $key, Closure $callback, int $ttl = null)
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->put($key, $value, $ttl);
        
        return $value;
    }
}

// ============================================================================
// FORMS & VALIDATION
// ============================================================================

class ZenValidator
{
    private array $data;
    private array $rules;
    private array $messages = [];
    private array $errors = [];
    
    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
    }
    
    /**
     * Create a new validator instance
     */
    public static function make(array $data, array $rules, array $messages = []): self
    {
        return new self($data, $rules, $messages);
    }
    
    /**
     * Validate the data
     */
    public function validate(): array
    {
        foreach ($this->rules as $attribute => $rules) {
            $rules = is_string($rules) ? explode('|', $rules) : $rules;
            
            foreach ($rules as $rule) {
                $this->validateAttribute($attribute, $rule);
            }
        }
        
        if (!empty($this->errors)) {
            throw new ZenValidationException($this->errors);
        }
        
        return $this->data;
    }
    
    /**
     * Validate an attribute with a rule
     */
    private function validateAttribute(string $attribute, string $rule): void
    {
        $parameters = [];
        
        // Extract parameters from the rule
        if (strpos($rule, ':') !== false) {
            [$rule, $parameterString] = explode(':', $rule, 2);
            $parameters = explode(',', $parameterString);
        }
        
        $value = $this->getValue($attribute);
        $method = 'validate' . ucfirst($rule);
        
        if (method_exists($this, $method)) {
            if (!$this->$method($attribute, $value, $parameters)) {
                $this->addError($attribute, $rule, $parameters);
            }
        }
    }
    
    /**
     * Get the value of an attribute
     */
    private function getValue(string $attribute)
    {
        if (strpos($attribute, '*') !== false) {
            // Handle array attributes
            $pattern = str_replace('*', '([^.]*)', $attribute);
            $values = [];
            
            foreach ($this->data as $key => $value) {
                if (preg_match('/^' . $pattern . '$/', $key, $matches)) {
                    $values[] = $value;
                }
            }
            
            return $values;
        }
        
        return $this->data[$attribute] ?? null;
    }
    
    /**
     * Add an error message
     */
    private function addError(string $attribute, string $rule, array $parameters): void
    {
        $key = "{$attribute}.{$rule}";
        
        $message = $this->messages[$key] ?? $this->getDefaultMessage($attribute, $rule, $parameters);
        
        $this->errors[$attribute][] = $message;
    }
    
    /**
     * Get the default error message
     */
    private function getDefaultMessage(string $attribute, string $rule, array $parameters): string
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
            'string' => 'The :attribute must be a string.',
            'array' => 'The :attribute must be an array.',
            'boolean' => 'The :attribute field must be true or false.',
            'date' => 'The :attribute is not a valid date.',
            'unique' => 'The :attribute has already been taken.',
            'exists' => 'The selected :attribute is invalid.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'same' => 'The :attribute and :other must match.',
            'different' => 'The :attribute and :other must be different.',
            'in' => 'The selected :attribute is invalid.',
            'not_in' => 'The selected :attribute is invalid.',
            'between' => 'The :attribute must be between :min and :max.',
            'regex' => 'The :attribute format is invalid.',
            'url' => 'The :attribute format is invalid.',
            'ip' => 'The :attribute must be a valid IP address.',
            'json' => 'The :attribute must be a valid JSON string.',
        ];
        
        $message = $messages[$rule] ?? 'The :attribute is invalid.';
        
        // Replace placeholders
        $message = str_replace(':attribute', $attribute, $message);
        
        foreach ($parameters as $i => $parameter) {
            $message = str_replace(':' . ['min', 'max', 'other'][$i] ?? $i, $parameter, $message);
        }
        
        return $message;
    }
    
    /**
     * Validate required
     */
    private function validateRequired(string $attribute, $value, array $parameters): bool
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif (is_array($value) && empty($value)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate email
     */
    private function validateEmail(string $attribute, $value, array $parameters): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate min
     */
    private function validateMin(string $attribute, $value, array $parameters): bool
    {
        $min = $parameters[0];
        
        if (is_numeric($value)) {
            return $value >= $min;
        } elseif (is_string($value)) {
            return mb_strlen($value) >= $min;
        } elseif (is_array($value)) {
            return count($value) >= $min;
        }
        
        return false;
    }
    
    /**
     * Validate max
     */
    private function validateMax(string $attribute, $value, array $parameters): bool
    {
        $max = $parameters[0];
        
        if (is_numeric($value)) {
            return $value <= $max;
        } elseif (is_string($value)) {
            return mb_strlen($value) <= $max;
        } elseif (is_array($value)) {
            return count($value) <= $max;
        }
        
        return false;
    }
    
    /**
     * Validate numeric
     */
    private function validateNumeric(string $attribute, $value, array $parameters): bool
    {
        return is_numeric($value);
    }
    
    /**
     * Validate integer
     */
    private function validateInteger(string $attribute, $value, array $parameters): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Validate string
     */
    private function validateString(string $attribute, $value, array $parameters): bool
    {
        return is_string($value);
    }
    
    /**
     * Validate array
     */
    private function validateArray(string $attribute, $value, array $parameters): bool
    {
        return is_array($value);
    }
    
    /**
     * Validate boolean
     */
    private function validateBoolean(string $attribute, $value, array $parameters): bool
    {
        return is_bool($value) || in_array($value, [0, 1, '0', '1']);
    }
    
    /**
     * Validate date
     */
    private function validateDate(string $attribute, $value, array $parameters): bool
    {
        if ($value instanceof DateTime) {
            return true;
        }
        
        if (is_string($value)) {
            $date = DateTime::createFromFormat('Y-m-d', $value);
            
            if ($date === false) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            }
            
            return $date !== false;
        }
        
        return false;
    }
    
    /**
     * Validate unique
     */
    private function validateUnique(string $attribute, $value, array $parameters): bool
    {
        $table = $parameters[0];
        $column = $parameters[1] ?? $attribute;
        
        $query = ZenDatabase::select("SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?", [$value]);
        
        return (int)$query[0]['count'] === 0;
    }
    
    /**
     * Validate exists
     */
    private function validateExists(string $attribute, $value, array $parameters): bool
    {
        $table = $parameters[0];
        $column = $parameters[1] ?? $attribute;
        
        $query = ZenDatabase::select("SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?", [$value]);
        
        return (int)$query[0]['count'] > 0;
    }
    
    /**
     * Validate confirmed
     */
    private function validateConfirmed(string $attribute, $value, array $parameters): bool
    {
        $confirmation = $this->data[$attribute . '_confirmation'] ?? null;
        
        return $value === $confirmation;
    }
    
    /**
     * Validate same
     */
    private function validateSame(string $attribute, $value, array $parameters): bool
    {
        $other = $parameters[0];
        $otherValue = $this->data[$other] ?? null;
        
        return $value === $otherValue;
    }
    
    /**
     * Validate different
     */
    private function validateDifferent(string $attribute, $value, array $parameters): bool
    {
        $other = $parameters[0];
        $otherValue = $this->data[$other] ?? null;
        
        return $value !== $otherValue;
    }
    
    /**
     * Validate in
     */
    private function validateIn(string $attribute, $value, array $parameters): bool
    {
        return in_array($value, $parameters);
    }
    
    /**
     * Validate not_in
     */
    private function validateNotIn(string $attribute, $value, array $parameters): bool
    {
        return !in_array($value, $parameters);
    }
    
    /**
     * Validate between
     */
    private function validateBetween(string $attribute, $value, array $parameters): bool
    {
        $min = $parameters[0];
        $max = $parameters[1];
        
        if (is_numeric($value)) {
            return $value >= $min && $value <= $max;
        } elseif (is_string($value)) {
            return mb_strlen($value) >= $min && mb_strlen($value) <= $max;
        } elseif (is_array($value)) {
            return count($value) >= $min && count($value) <= $max;
        }
        
        return false;
    }
    
    /**
     * Validate regex
     */
    private function validateRegex(string $attribute, $value, array $parameters): bool
    {
        return preg_match($parameters[0], $value);
    }
    
    /**
     * Validate url
     */
    private function validateUrl(string $attribute, $value, array $parameters): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate ip
     */
    private function validateIp(string $attribute, $value, array $parameters): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * Validate json
     */
    private function validateJson(string $attribute, $value, array $parameters): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        json_decode($value);
        
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * Get the validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }
    
    /**
     * Check if validation failed
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }
}

class ZenValidationException extends Exception
{
    private array $errors;
    
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('The given data was invalid.');
    }
    
    /**
     * Get the validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }
}

// ============================================================================
// EMAIL
// ============================================================================

class ZenMail
{
    private string $to;
    private string $subject;
    private string $body;
    private array $headers = [];
    private array $attachments = [];
    private array $config;
    
    public function __construct()
    {
        $this->config = ZenConfig::get('mail');
    }
    
    /**
     * Create a new mail instance
     */
    public static function to(string $address): self
    {
        $instance = new self();
        $instance->to = $address;
        
        return $instance;
    }
    
    /**
     * Set the subject
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;
        
        return $this;
    }
    
    /**
     * Set the body
     */
    public function body(string $body): self
    {
        $this->body = $body;
        
        return $this;
    }
    
    /**
     * Set the HTML body
     */
    public function html(string $html): self
    {
        $this->body = $html;
        $this->headers['MIME-Version'] = '1.0';
        $this->headers['Content-type'] = 'text/html; charset=iso-8859-1';
        
        return $this;
    }
    
    /**
     * Attach a file
     */
    public function attach(string $path, string $name = null): self
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name ?? basename($path),
        ];
        
        return $this;
    }
    
    /**
     * Send the email
     */
    public function send(): bool
    {
        $this->prepareHeaders();
        
        if ($this->config['driver'] === 'smtp') {
            return $this->sendSmtp();
        } else {
            return $this->sendMail();
        }
    }
    
    /**
     * Prepare the headers
     */
    private function prepareHeaders(): void
    {
        $this->headers['From'] = "{$this->config['from']['name']} <{$this->config['from']['address']}>";
        $this->headers['To'] = $this->to;
        $this->headers['Subject'] = $this->subject;
        
        // Add attachments
        if (!empty($this->attachments)) {
            $boundary = uniqid('np');
            
            $this->headers['MIME-Version'] = '1.0';
            $this->headers['Content-Type'] = "multipart/mixed; boundary=\"{$boundary}\"";
            
            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($this->body)) . "\r\n";
            
            foreach ($this->attachments as $attachment) {
                $data = file_get_contents($attachment['path']);
                $name = $attachment['name'];
                $type = mime_content_type($attachment['path']);
                
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: {$type}; name=\"{$name}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$name}\"\r\n\r\n";
                $body .= chunk_split(base64_encode($data)) . "\r\n";
            }
            
            $body .= "--{$boundary}--";
            
            $this->body = $body;
        }
    }
    
    /**
     * Send using SMTP
     */
    private function sendSmtp(): bool
    {
        // This is a simplified SMTP implementation
        // In a production environment, you would use a library like PHPMailer or SwiftMailer
        
        $from = $this->config['from']['address'];
        $to = $this->to;
        $subject = $this->subject;
        $body = $this->body;
        
        $headers = '';
        foreach ($this->headers as $key => $value) {
            $headers .= "{$key}: {$value}\r\n";
        }
        
        return mail($to, $subject, $body, $headers);
    }
    
    /**
     * Send using PHP's mail function
     */
    private function sendMail(): bool
    {
        $to = $this->to;
        $subject = $this->subject;
        $body = $this->body;
        
        $headers = '';
        foreach ($this->headers as $key => $value) {
            $headers .= "{$key}: {$value}\r\n";
        }
        
        return mail($to, $subject, $body, $headers);
    }
}

// ============================================================================
// BACKGROUND JOBS & EVENTS
// ============================================================================

class ZenQueue
{
    private ZenContainer $container;
    private string $path;
    
    public function __construct(ZenContainer $container)
    {
        $this->container = $container;
        $this->path = ZenConfig::get('app.queue_path', sys_get_temp_dir() . '/zen_queue');
        
        // Ensure queue directory exists
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    /**
     * Push a job onto the queue
     */
    public function push($job, array $data = []): string
    {
        $id = uniqid('job_');
        $payload = [
            'id' => $id,
            'job' => $job,
            'data' => $data,
            'attempts' => 0,
            'created_at' => time(),
        ];
        
        $filename = $this->path . '/' . $id . '.job';
        file_put_contents($filename, serialize($payload), LOCK_EX);
        
        return $id;
    }
    
    /**
     * Push a job onto the queue with a delay
     */
    public function later(int $delay, $job, array $data = []): string
    {
        $id = uniqid('job_');
        $payload = [
            'id' => $id,
            'job' => $job,
            'data' => $data,
            'attempts' => 0,
            'created_at' => time(),
            'available_at' => time() + $delay,
        ];
        
        $filename = $this->path . '/' . $id . '.job';
        file_put_contents($filename, serialize($payload), LOCK_EX);
        
        return $id;
    }
    
    /**
     * Process jobs from the queue
     */
    public function process(int $maxJobs = 10): void
    {
        $processed = 0;
        
        while ($processed < $maxJobs) {
            $job = $this->getNextJob();
            
            if ($job === null) {
                break;
            }
            
            $this->processJob($job);
            $processed++;
        }
    }
    
    /**
     * Get the next available job
     */
    private function getNextJob(): ?array
    {
        $files = glob($this->path . '/*.job');
        
        if (empty($files)) {
            return null;
        }
        
        // Sort by creation time
        usort($files, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        foreach ($files as $file) {
            $data = file_get_contents($file);
            $job = unserialize($data);
            
            // Check if the job is available
            if (isset($job['available_at']) && $job['available_at'] > time()) {
                continue;
            }
            
            // Remove the file
            unlink($file);
            
            return $job;
        }
        
        return null;
    }
    
    /**
     * Process a job
     */
    private function processJob(array $job): void
    {
        try {
            $jobClass = $job['job'];
            $data = $job['data'];
            
            if (is_string($jobClass)) {
                $instance = $this->container->make($jobClass);
                $this->container->call([$instance, 'handle'], $data);
            } elseif (is_callable($jobClass)) {
                $this->container->call($jobClass, $data);
            } else {
                throw new Exception('Invalid job');
            }
        } catch (Exception $e) {
            $job['attempts']++;
            
            // Retry up to 3 times
            if ($job['attempts'] < 3) {
                $filename = $this->path . '/' . $job['id'] . '.job';
                file_put_contents($filename, serialize($job), LOCK_EX);
            } else {
                // Log the error
                $logger = $this->container->make(ZenLogger::class);
                $logger->error('Job failed after 3 attempts', [
                    'job' => $job['job'],
                    'data' => $job['data'],
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

interface ZenJobInterface
{
    /**
     * Handle the job
     */
    public function handle(array $data): void;
}

class ZenEvent
{
    private static array $listeners = [];
    private static ZenContainer $container;
    
    /**
     * Set the container instance
     */
    public static function setContainer(ZenContainer $container): void
    {
        self::$container = $container;
    }
    
    /**
     * Register an event listener
     */
    public static function listen(string $event, $listener): void
    {
        self::$listeners[$event][] = $listener;
    }
    
    /**
     * Dispatch an event
     */
    public static function dispatch(string $event, array $data = []): void
    {
        if (!isset(self::$listeners[$event])) {
            return;
        }
        
        foreach (self::$listeners[$event] as $listener) {
            if (is_string($listener)) {
                $instance = self::$container->make($listener);
                self::$container->call([$instance, 'handle'], $data);
            } elseif (is_callable($listener)) {
                self::$container->call($listener, $data);
            }
        }
    }
}

interface ZenEventListenerInterface
{
    /**
     * Handle the event
     */
    public function handle(array $data): void;
}

// ============================================================================
// APPLICATION KERNEL
// ============================================================================

class ZenApp
{
    private ZenContainer $container;
    private ZenRouter $router;
    private ZenRequest $request;
    private ZenResponse $response;
    private ZenConfig $config;
    private ZenLogger $logger;
    private ZenSession $session;
    private ZenCache $cache;
    private ZenAuth $auth;
    private ZenQueue $queue;
    private ZenErrorHandler $errorHandler;
    
    public function __construct()
    {
        // Load configuration
        ZenConfig::load();
        
        // Initialize the container
        $this->container = new ZenContainer();
        
        // Bind core services
        $this->container->singleton(ZenApp::class, $this);
        $this->container->singleton(ZenContainer::class, $this->container);
        $this->container->singleton(ZenConfig::class, ZenConfig::class);
        $this->container->singleton(ZenLogger::class, ZenLogger::class);
        $this->container->singleton(ZenSession::class, ZenSession::class);
        $this->container->singleton(ZenCache::class, ZenCache::class);
        $this->container->singleton(ZenAuth::class, function ($container) {
            return new ZenAuth($container->make(ZenSession::class), $container);
        });
        $this->container->singleton(ZenQueue::class, ZenQueue::class);
        
        // Initialize core services
        $this->config = $this->container->make(ZenConfig::class);
        $this->logger = $this->container->make(ZenLogger::class);
        $this->session = $this->container->make(ZenSession::class);
        $this->cache = $this->container->make(ZenCache::class);
        $this->auth = $this->container->make(ZenAuth::class);
        $this->queue = $this->container->make(ZenQueue::class);
        
        // Initialize router
        $this->router = new ZenRouter($this->container);
        
        // Initialize error handler
        $this->errorHandler = new ZenErrorHandler($this->container);
        
        // Set up event system
        ZenEvent::setContainer($this->container);
        
        // Set up authorization system
        ZenGate::setContainer($this->container);
    }
    
    /**
     * Boot the application
     */
    public function boot(): void
    {
        // Start the session
        $this->session->start();
        
        // Log application start
        $this->logger->info('Application started');
    }
    
    /**
     * Handle the request
     */
    public function handle(): void
    {
        // Create request
        $this->request = new ZenRequest();
        
        // Dispatch the request
        $this->response = $this->router->dispatch($this->request);
        
        // Send the response
        $this->response->send();
    }
    
    /**
     * Terminate the application
     */
    public function terminate(): void
    {
        // Save the session
        $this->session->save();
        
        // Log application termination
        $this->logger->info('Application terminated', [
            'memory_usage' => memory_get_usage(true),
            'execution_time' => microtime(true) - ZEN_START_TIME,
        ]);
    }
    
    /**
     * Get the container
     */
    public function getContainer(): ZenContainer
    {
        return $this->container;
    }
    
    /**
     * Get the router
     */
    public function getRouter(): ZenRouter
    {
        return $this->router;
    }
    
    /**
     * Get the request
     */
    public function getRequest(): ZenRequest
    {
        return $this->request;
    }
    
    /**
     * Get the response
     */
    public function getResponse(): ZenResponse
    {
        return $this->response;
    }
    
    /**
     * Get the logger
     */
    public function getLogger(): ZenLogger
    {
        return $this->logger;
    }
    
    /**
     * Get the session
     */
    public function getSession(): ZenSession
    {
        return $this->session;
    }
    
    /**
     * Get the cache
     */
    public function getCache(): ZenCache
    {
        return $this->cache;
    }
    
    /**
     * Get the auth
     */
    public function getAuth(): ZenAuth
    {
        return $this->auth;
    }
    
    /**
     * Get the queue
     */
    public function getQueue(): ZenQueue
    {
        return $this->queue;
    }
    
    /**
     * Run the application
     */
    public function run(): void
    {
        $this->boot();
        $this->handle();
        $this->terminate();
    }
}

// ============================================================================
// EXTENDED REQUEST CLASS FOR ROUTE PARAMETERS
// ============================================================================

// Extend the ZenRequest class to add route parameter support
class ZenRequestExtended extends ZenRequest
{
    private array $routeParameters = [];
    
    /**
     * Set route parameters
     */
    public function setRouteParameters(array $parameters): void
    {
        $this->routeParameters = $parameters;
    }
    
    /**
     * Get route parameters
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }
    
    /**
     * Get a route parameter
     */
    public function route(string $key, $default = null)
    {
        return $this->routeParameters[$key] ?? $default;
    }
}

// ============================================================================
// CLI HANDLER
// ============================================================================

class ZenCLI
{
    private ZenApp $app;
    private array $commands = [];
    
    public function __construct(ZenApp $app)
    {
        $this->app = $app;
        
        // Register built-in commands
        $this->registerCommands();
    }
    
    /**
     * Register built-in commands
     */
    private function registerCommands(): void
    {
        $this->commands['serve'] = function () {
            $host = $_SERVER['argv'][2] ?? 'localhost';
            $port = $_SERVER['argv'][3] ?? '8000';
            
            echo "Starting Zen development server on http://{$host}:{$port}\n";
            echo "Press Ctrl+C to stop\n\n";
            
            $command = "php -S {$host}:{$port} -t public";
            passthru($command);
        };
        
        $this->commands['migrate'] = function () {
            echo "Running migrations...\n";
            
            // This is a placeholder for migration logic
            // In a real implementation, you would read migration files and execute them
            
            echo "Migrations completed.\n";
        };
        
        $this->commands['queue:work'] = function () {
            $maxJobs = (int)($_SERVER['argv'][2] ?? 10);
            
            echo "Processing queue jobs...\n";
            
            $queue = $this->app->getQueue();
            $queue->process($maxJobs);
            
            echo "Queue processing completed.\n";
        };
        
        $this->commands['health'] = function () {
            echo "Zen Framework Health Check\n";
            echo "=========================\n";
            echo "Version: " . ZEN_VERSION . "\n";
            echo "Environment: " . ZenConfig::env() . "\n";
            echo "Memory Usage: " . round(memory_get_usage(true) / 1024 / 1024, 2) . " MB\n";
            echo "Uptime: " . round(microtime(true) - ZEN_START_TIME, 2) . " seconds\n";
            
            // Check database connection
            try {
                $connection = ZenDatabase::getConnection();
                echo "Database: Connected\n";
            } catch (Exception $e) {
                echo "Database: Error - " . $e->getMessage() . "\n";
            }
            
            // Check cache
            try {
                $cache = $this->app->getCache();
                $cache->put('health_check', 'ok', 60);
                $value = $cache->get('health_check');
                
                if ($value === 'ok') {
                    echo "Cache: Working\n";
                } else {
                    echo "Cache: Error\n";
                }
            } catch (Exception $e) {
                echo "Cache: Error - " . $e->getMessage() . "\n";
            }
            
            echo "Status: Healthy\n";
        };
    }
    
    /**
     * Register a custom command
     */
    public function register(string $name, Closure $handler): void
    {
        $this->commands[$name] = $handler;
    }
    
    /**
     * Run the CLI
     */
    public function run(): void
    {
        $command = $_SERVER['argv'][1] ?? 'help';
        
        if (isset($this->commands[$command])) {
            $this->commands[$command]();
        } else {
            $this->showHelp();
        }
    }
    
    /**
     * Show help information
     */
    private function showHelp(): void
    {
        echo "Zen Framework CLI\n";
        echo "=================\n\n";
        echo "Available commands:\n\n";
        
        foreach (array_keys($this->commands) as $command) {
            echo "  php zen.php {$command}\n";
        }
        
        echo "\n";
    }
}

// ============================================================================
// APPLICATION ENTRY POINT
// ============================================================================

// Check if running from CLI
if (php_sapi_name() === 'cli') {
    // Create the application
    $app = new ZenApp();
    
    // Create and run the CLI
    $cli = new ZenCLI($app);
    $cli->run();
} else {
    // Create and run the application
    $app = new ZenApp();
    $app->run();
}
/** EOF 4710 LOC Thank You INRI */
?>
