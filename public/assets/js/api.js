// API Manager for SMM Turk Panel
class APIManager {
    constructor() {
        this.baseURL = 'http://localhost:8000/public/api/';
        this.cache = new Map();
        this.cacheTimeout = 5 * 60 * 1000; // 5 minutes
        this.init();
    }
    
    init() {
        console.log('API Manager initialized');
    }
    
    // Generic API call method
    async apiCall(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const cacheKey = `${endpoint}_${JSON.stringify(options)}`;
        
        // Check cache first
        if (this.cache.has(cacheKey)) {
            const cached = this.cache.get(cacheKey);
            if (Date.now() - cached.timestamp < this.cacheTimeout) {
                return cached.data;
            }
        }
        
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                ...options
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Cache the response
            this.cache.set(cacheKey, {
                data: data,
                timestamp: Date.now()
            });
            
            return data;
        } catch (error) {
            console.error('API call failed:', error);
            return {
                success: false,
                error: error.message,
                data: null
            };
        }
    }
    
    // Get all services
    async getServices(category = 'all') {
        return await this.apiCall(`services.php?endpoint=services&category=${category}`);
    }
    
    // Get statistics
    async getStats() {
        return await this.apiCall('services.php?endpoint=stats');
    }
    
    // Get user data
    async getUserData() {
        return await this.apiCall('services.php?endpoint=user');
    }
    
    // Get orders
    async getOrders() {
        return await this.apiCall('services.php?endpoint=orders');
    }
    
    // Get categories
    async getCategories() {
        return await this.apiCall('services.php?endpoint=categories');
    }
    
    // Clear cache
    clearCache() {
        this.cache.clear();
    }
    
    // Get cached data
    getCachedData(endpoint) {
        const cacheKey = `${endpoint}_${JSON.stringify({})}`;
        const cached = this.cache.get(cacheKey);
        if (cached && Date.now() - cached.timestamp < this.cacheTimeout) {
            return cached.data;
        }
        return null;
    }
}

// Global API Manager instance
window.apiManager = new APIManager();
