/**
 * Real-time Auto-Refresh System
 * Automatically refreshes data without page reload
 */

// Global configuration
const REFRESH_INTERVALS = {
    fast: 10000,    // 10 seconds - for critical data
    normal: 30000,  // 30 seconds - for regular data
    slow: 60000     // 60 seconds - for less critical data
};

/**
 * Dashboard Stats Widget
 */
function dashboardStatsWidget() {
    return {
        stats: {},
        
        init() {
            this.loadInitialData();
            // Auto-refresh every 30 seconds
            setInterval(() => {
                this.refreshStats();
            }, REFRESH_INTERVALS.normal);
        },
        
        loadInitialData() {
            // Load from page data
            const statsElement = document.getElementById('stats-data');
            if (statsElement) {
                this.stats = JSON.parse(statsElement.textContent);
            }
        },
        
        async refreshStats() {
            try {
                const response = await fetch('/api/dashboard/stats');
                const data = await response.json();
                this.stats = data.stats;
            } catch (error) {
                console.error('Failed to refresh stats:', error);
            }
        }
    }
}

/**
 * Activity Widget (Already implemented)
 */
function activityWidget() {
    return {
        activities: [],
        
        init() {
            this.loadInitialData();
            setInterval(() => {
                this.fetchLatestActivities();
            }, REFRESH_INTERVALS.normal);
        },
        
        loadInitialData() {
            const activitiesElement = document.getElementById('activities-data');
            if (activitiesElement) {
                this.activities = JSON.parse(activitiesElement.textContent);
            }
        },
        
        async fetchLatestActivities() {
            try {
                const response = await fetch('/admin/activity-logs/latest');
                const data = await response.json();
                this.activities = data.activities;
            } catch (error) {
                console.error('Failed to fetch activities:', error);
            }
        }
    }
}

/**
 * Managed Users Widget
 */
function managedUsersWidget() {
    return {
        users: [],
        
        init() {
            this.loadInitialData();
            setInterval(() => {
                this.refreshUsers();
            }, REFRESH_INTERVALS.slow);
        },
        
        loadInitialData() {
            const usersElement = document.getElementById('managed-users-data');
            if (usersElement) {
                this.users = JSON.parse(usersElement.textContent);
            }
        },
        
        async refreshUsers() {
            try {
                const response = await fetch('/api/dashboard/managed-users');
                const data = await response.json();
                this.users = data.users;
            } catch (error) {
                console.error('Failed to refresh users:', error);
            }
        }
    }
}

/**
 * Data Table Auto-Refresh
 */
function dataTableWidget(endpoint, interval = 'normal') {
    return {
        data: [],
        loading: false,
        lastUpdate: null,
        
        init() {
            this.loadInitialData();
            setInterval(() => {
                this.refreshData();
            }, REFRESH_INTERVALS[interval]);
        },
        
        loadInitialData() {
            // Data will be loaded from server-side rendering
            this.lastUpdate = new Date();
        },
        
        async refreshData() {
            if (this.loading) return;
            
            this.loading = true;
            try {
                const url = new URL(endpoint, window.location.origin);
                // Preserve current filters and pagination
                const params = new URLSearchParams(window.location.search);
                params.forEach((value, key) => {
                    url.searchParams.append(key, value);
                });
                
                const response = await fetch(url);
                const html = await response.text();
                
                // Update table content
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('[data-table-content]');
                const currentTable = document.querySelector('[data-table-content]');
                
                if (newTable && currentTable) {
                    currentTable.innerHTML = newTable.innerHTML;
                    this.lastUpdate = new Date();
                }
            } catch (error) {
                console.error('Failed to refresh data:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}

/**
 * Pengeluaran Widget (User Dashboard)
 */
function pengeluaranWidget() {
    return {
        pengeluaran: {},
        totalPengeluaran: 0,
        
        init() {
            this.loadInitialData();
            setInterval(() => {
                this.refreshPengeluaran();
            }, REFRESH_INTERVALS.normal);
        },
        
        loadInitialData() {
            const pengeluaranElement = document.getElementById('pengeluaran-data');
            if (pengeluaranElement) {
                const data = JSON.parse(pengeluaranElement.textContent);
                this.pengeluaran = data.pengeluaran;
                this.totalPengeluaran = data.total;
            }
        },
        
        async refreshPengeluaran() {
            try {
                const response = await fetch('/api/dashboard/pengeluaran');
                const data = await response.json();
                this.pengeluaran = data.pengeluaran;
                this.totalPengeluaran = data.total;
            } catch (error) {
                console.error('Failed to refresh pengeluaran:', error);
            }
        },
        
        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
    }
}

/**
 * Generic Stats Card Widget
 */
function statsCardWidget(endpoint, interval = 'normal') {
    return {
        value: 0,
        label: '',
        
        init() {
            setInterval(() => {
                this.refreshValue();
            }, REFRESH_INTERVALS[interval]);
        },
        
        async refreshValue() {
            try {
                const response = await fetch(endpoint);
                const data = await response.json();
                this.value = data.value;
                this.label = data.label || this.label;
            } catch (error) {
                console.error('Failed to refresh stat:', error);
            }
        }
    }
}

// Export functions for global use
window.dashboardStatsWidget = dashboardStatsWidget;
window.activityWidget = activityWidget;
window.managedUsersWidget = managedUsersWidget;
window.dataTableWidget = dataTableWidget;
window.pengeluaranWidget = pengeluaranWidget;
window.statsCardWidget = statsCardWidget;
