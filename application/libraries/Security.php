<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * کلاس امنیتی پیشرفته برای SMM Panel
 */
class Security {
    
    private $CI;
    private $config;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('security');
        $this->config = $this->CI->config->item('security');
    }
    
    /**
     * بررسی و اعمال امنیت
     */
    public function apply_security() {
        $this->set_security_headers();
        $this->check_rate_limiting();
        $this->validate_input();
        $this->check_suspicious_activity();
    }
    
    /**
     * تنظیم Headers امنیتی
     */
    private function set_security_headers() {
        $headers = $this->CI->config->item('security_headers');
        
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }
        
        // Content Security Policy
        $csp = $this->CI->config->item('csp');
        $csp_string = '';
        foreach ($csp as $directive => $value) {
            $csp_string .= "$directive $value; ";
        }
        header("Content-Security-Policy: " . trim($csp_string));
    }
    
    /**
     * بررسی Rate Limiting
     */
    private function check_rate_limiting() {
        if (!$this->config['rate_limiting']['enabled']) {
            return;
        }
        
        $ip = $this->get_client_ip();
        $key = 'rate_limit_' . $ip;
        
        // بررسی تعداد درخواست‌ها
        $requests = $this->CI->cache->get($key);
        if ($requests === false) {
            $requests = 0;
        }
        
        $requests++;
        
        if ($requests > $this->config['rate_limiting']['max_requests_per_minute']) {
            $this->log_security_event('RATE_LIMIT_EXCEEDED', $ip);
            $this->block_ip($ip);
            show_error('Too many requests. Please try again later.', 429);
        }
        
        $this->CI->cache->save($key, $requests, 60); // 1 دقیقه
    }
    
    /**
     * اعتبارسنجی ورودی
     */
    private function validate_input() {
        // بررسی SQL Injection
        $this->check_sql_injection();
        
        // بررسی XSS
        $this->check_xss();
        
        // بررسی File Upload
        $this->check_file_upload();
    }
    
    /**
     * بررسی SQL Injection
     */
    private function check_sql_injection() {
        $suspicious_patterns = array(
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b)/i',
            '/(\binsert\b.*\binto\b)/i',
            '/(\bupdate\b.*\bset\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\balter\b.*\btable\b)/i',
            '/(\bexec\b|\bexecute\b)/i',
            '/(\bscript\b.*\balert\b)/i',
            '/(\bjavascript\b|\bvbscript\b)/i'
        );
        
        $input = array_merge($_GET, $_POST, $_COOKIE);
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                foreach ($suspicious_patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->log_security_event('SQL_INJECTION_ATTEMPT', $key . '=' . $value);
                        $this->block_ip($this->get_client_ip());
                        show_error('Suspicious activity detected.', 403);
                    }
                }
            }
        }
    }
    
    /**
     * بررسی XSS
     */
    private function check_xss() {
        $xss_patterns = array(
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
            '/<link\b[^<]*(?:(?!<\/link>)<[^<]*)*<\/link>/mi',
            '/<meta\b[^<]*(?:(?!<\/meta>)<[^<]*)*<\/meta>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i'
        );
        
        $input = array_merge($_GET, $_POST, $_COOKIE);
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                foreach ($xss_patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->log_security_event('XSS_ATTEMPT', $key . '=' . $value);
                        $this->block_ip($this->get_client_ip());
                        show_error('Suspicious activity detected.', 403);
                    }
                }
            }
        }
    }
    
    /**
     * بررسی File Upload
     */
    private function check_file_upload() {
        if (isset($_FILES) && !empty($_FILES)) {
            foreach ($_FILES as $field => $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    // بررسی نوع فایل
                    $allowed_types = $this->config['file_upload']['allowed_types'];
                    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($file_extension, $allowed_types)) {
                        $this->log_security_event('INVALID_FILE_TYPE', $file['name']);
                        show_error('Invalid file type.', 400);
                    }
                    
                    // بررسی اندازه فایل
                    if ($file['size'] > $this->config['file_upload']['max_size']) {
                        $this->log_security_event('FILE_TOO_LARGE', $file['name']);
                        show_error('File too large.', 400);
                    }
                    
                    // بررسی محتوای فایل
                    $this->scan_file_content($file['tmp_name']);
                }
            }
        }
    }
    
    /**
     * اسکن محتوای فایل
     */
    private function scan_file_content($file_path) {
        $content = file_get_contents($file_path);
        
        // بررسی محتوای مخرب
        $malicious_patterns = array(
            '/<\?php/i',
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec\s*\(/i'
        );
        
        foreach ($malicious_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $this->log_security_event('MALICIOUS_FILE', $file_path);
                unlink($file_path);
                show_error('Malicious file detected.', 400);
            }
        }
    }
    
    /**
     * بررسی فعالیت مشکوک
     */
    private function check_suspicious_activity() {
        $ip = $this->get_client_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // بررسی User Agent مشکوک
        $suspicious_user_agents = array(
            'sqlmap',
            'nikto',
            'nmap',
            'masscan',
            'zap',
            'burp',
            'w3af',
            'acunetix',
            'nessus',
            'openvas'
        );
        
        foreach ($suspicious_user_agents as $suspicious) {
            if (stripos($user_agent, $suspicious) !== false) {
                $this->log_security_event('SUSPICIOUS_USER_AGENT', $user_agent);
                $this->block_ip($ip);
                show_error('Access denied.', 403);
            }
        }
        
        // بررسی IP مشکوک
        if ($this->is_ip_blocked($ip)) {
            show_error('Access denied.', 403);
        }
    }
    
    /**
     * مسدود کردن IP
     */
    private function block_ip($ip) {
        $blocked_ips = $this->CI->cache->get('blocked_ips') ?: array();
        $blocked_ips[] = $ip;
        $this->CI->cache->save('blocked_ips', $blocked_ips, 3600); // 1 ساعت
    }
    
    /**
     * بررسی IP مسدود شده
     */
    private function is_ip_blocked($ip) {
        $blocked_ips = $this->CI->cache->get('blocked_ips') ?: array();
        return in_array($ip, $blocked_ips);
    }
    
    /**
     * دریافت IP کلاینت
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * لاگ رویدادهای امنیتی
     */
    private function log_security_event($event_type, $details) {
        $log_data = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'event_type' => $event_type,
            'details' => $details,
            'url' => $_SERVER['REQUEST_URI'] ?? ''
        );
        
        $this->CI->load->library('log');
        $this->CI->log->write_log('security', json_encode($log_data));
    }
    
    /**
     * تولید CSRF Token
     */
    public function generate_csrf_token() {
        $token = bin2hex(random_bytes($this->config['csrf']['token_length']));
        $this->CI->session->set_userdata('csrf_token', $token);
        return $token;
    }
    
    /**
     * اعتبارسنجی CSRF Token
     */
    public function validate_csrf_token($token) {
        $session_token = $this->CI->session->userdata('csrf_token');
        return hash_equals($session_token, $token);
    }
    
    /**
     * هش کردن پسورد
     */
    public function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT, array(
            'cost' => $this->config['password']['cost']
        ));
    }
    
    /**
     * اعتبارسنجی پسورد
     */
    public function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * رمزگذاری داده
     */
    public function encrypt($data) {
        $key = $this->config['encryption']['key'];
        $iv = random_bytes($this->config['encryption']['iv_length']);
        $encrypted = openssl_encrypt($data, $this->config['encryption']['cipher'], $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * رمزگشایی داده
     */
    public function decrypt($data) {
        $key = $this->config['encryption']['key'];
        $data = base64_decode($data);
        $iv = substr($data, 0, $this->config['encryption']['iv_length']);
        $encrypted = substr($data, $this->config['encryption']['iv_length']);
        return openssl_decrypt($encrypted, $this->config['encryption']['cipher'], $key, 0, $iv);
    }
}
