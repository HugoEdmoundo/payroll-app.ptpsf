/**
 * Universal Real-Time Auto-Refresh System
 * Automatically refreshes ANY data table without page reload
 * Uses Alpine.js + Fetch API with smart polling
 */

// Configuration
const REALTIME_CONFIG = {
    intervals: {
        fast: 10000,    // 10 seconds - for critical data
        normal: 30000,  // 30 seconds - for regular data  
        slow: 60000     // 60 seconds - for less critical data
    },
    enabled: true,
    debug: false
};

/**
 * Universal Data Table Widget
 * Can be used on ANY page with data tables
 */
function realtimeTable(config = {}) {
    return {
        loading: false,
        lastUpdate: null,
        updateInterval: null,
        refreshInterval: config.interval || 'normal',
        endpoint: config.endpoint || window.location.pathname,
        selector: config.selector || '[data-realtime-content]',
        enabled: config.enabled !== false,
        
        init() {
            if (!this.enabled || !REALTIME_CONFIG.enabled) {
                return;
            }
            
            this.lastUpdate = new Date();
            this.startAutoRefresh();
            
            if (REALTIME_CONFIG.debug) {
                console.log('[Realtime] Initialized for:', this.endpoint);
            }
        },
        
        startAutoRefresh() {
            const interval = REALTIME_CONFIG.intervals[this.refreshInterval];
            
            this.updateInterval = setInterval(() => {
                this.refresh();
            }, interval);
            
            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                this.stopAutoRefresh();
            });
        },
        
        stopAutoRefresh() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
                this.updateInterval = null;
            }
        },
        
        async refresh() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                // Build URL with current query parameters
                const url = new URL(this.endpoint, window.location.origin);
                const params = new URLSearchParams(window.location.search);
                params.forEach((value, key) => {
                    url.searchParams.append(key, value);
                });
                
                // Add realtime flag to prevent full page render
                url.searchParams.append('realtime', '1');
                
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const html = await response.text();
                
                // Update content
                this.updateContent(html);
                
                this.lastUpdate = new Date();
                
                if (REALTIME_CONFIG.debug) {
                    console.log('[Realtime] Refreshed:', this.endpoint);
                }
                
                // Dispatch event for other components
                window.dispatchEvent(new CustomEvent('realtime:updated', {
                    detail: { endpoint: this.endpoint, time: this.lastUpdate }
                }));
                
            } catch (error) {
                if (REALTIME_CONFIG.debug) {
                    console.error('[Realtime] Refresh failed:', error);
                }
            } finally {
                this.loading = false;
            }
        },
        
        updateContent(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector(this.selector);
            const currentContent = document.querySelector(this.selector);
            
            if (newContent && currentContent) {
                // Preserve scroll position
                const scrollTop = window.scrollY;
                
                // Update content
                currentContent.innerHTML = newContent.innerHTML;
                
                // Restore scroll position
                window.scrollTo(0, scrollTop);
                
                // Re-initialize any Alpine components in the new content
                if (window.Alpine) {
                    Alpine.initTree(currentContent);
                }
            }
        },
        
        forceRefresh() {
            this.refresh();
        },
        
        getLastUpdateText() {
            if (!this.lastUpdate) return 'Never';
            
            const seconds = Math.floor((new Date() - this.lastUpdate) / 1000);
            
            if (seconds < 60) return `${seconds}s ago`;
            if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
            return `${Math.floor(seconds / 3600)}h ago`;
        }
    }
}

/**
 * Stats Card Widget (for dashboard stats)
 */
function realtimeStats(config = {}) {
    return {
        stats: {},
        loading: false,
        updateInterval: null,
        refreshInterval: config.interval || 'normal',
        endpoint: config.endpoint || '/api/dashboard/stats',
        enabled: config.enabled !== false,
        
        init() {
            if (!this.enabled || !REALTIME_CONFIG.enabled) {
                return;
            }
            
            this.loadInitialData();
            this.startAutoRefresh();
        },
        
        loadInitialData() {
            const statsElement = document.getElementById('stats-data');
            if (statsElement) {
                try {
                    this.stats = JSON.parse(statsElement.textContent);
                } catch (e) {
                    console.error('[Realtime] Failed to parse initial stats:', e);
                }
            }
        },
        
        startAutoRefresh() {
            const interval = REALTIME_CONFIG.intervals[this.refreshInterval];
            
            this.updateInterval = setInterval(() => {
                this.refresh();
            }, interval);
        },
        
        async refresh() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(this.endpoint);
                const data = await response.json();
                this.stats = data.stats || data;
                
                if (REALTIME_CONFIG.debug) {
                    console.log('[Realtime] Stats updated:', this.stats);
                }
            } catch (error) {
                if (REALTIME_CONFIG.debug) {
                    console.error('[Realtime] Stats refresh failed:', error);
                }
            } finally {
                this.loading = false;
            }
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        },
        
        formatCurrency(num) {
            return 'Rp ' + this.formatNumber(num);
        }
    }
}

/**
 * List Widget (for activity logs, users, etc)
 */
function realtimeList(config = {}) {
    return {
        items: [],
        loading: false,
        updateInterval: null,
        refreshInterval: config.interval || 'normal',
        endpoint: config.endpoint,
        dataKey: config.dataKey || 'items',
        enabled: config.enabled !== false,
        
        init() {
            if (!this.enabled || !REALTIME_CONFIG.enabled || !this.endpoint) {
                return;
            }
            
            this.loadInitialData();
            this.startAutoRefresh();
        },
        
        loadInitialData() {
            const dataElement = document.getElementById(config.dataElementId || 'list-data');
            if (dataElement) {
                try {
                    const data = JSON.parse(dataElement.textContent);
                    this.items = data[this.dataKey] || data;
                } catch (e) {
                    console.error('[Realtime] Failed to parse initial list:', e);
                }
            }
        },
        
        startAutoRefresh() {
            const interval = REALTIME_CONFIG.intervals[this.refreshInterval];
            
            this.updateInterval = setInterval(() => {
                this.refresh();
            }, interval);
        },
        
        async refresh() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(this.endpoint);
                const data = await response.json();
                this.items = data[this.dataKey] || data;
                
                if (REALTIME_CONFIG.debug) {
                    console.log('[Realtime] List updated:', this.items.length, 'items');
                }
            } catch (error) {
                if (REALTIME_CONFIG.debug) {
                    console.error('[Realtime] List refresh failed:', error);
                }
            } finally {
                this.loading = false;
            }
        }
    }
}

// Export functions for global use
window.realtimeTable = realtimeTable;
window.realtimeStats = realtimeStats;
window.realtimeList = realtimeList;
window.REALTIME_CONFIG = REALTIME_CONFIG;

// Auto-initialize tables with data-realtime attribute
document.addEventListener('DOMContentLoaded', () => {
    const realtimeTables = document.querySelectorAll('[data-realtime]');
    
    realtimeTables.forEach(table => {
        const interval = table.dataset.realtimeInterval || 'normal';
        const endpoint = table.dataset.realtimeEndpoint || window.location.pathname;
        
        // Initialize Alpine component
        if (window.Alpine && !table._x_dataStack) {
            Alpine.data('autoRealtime', () => realtimeTable({
                interval: interval,
                endpoint: endpoint
            }));
        }
    });
});
