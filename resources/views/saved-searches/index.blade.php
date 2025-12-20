@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Saved Searches</h1>
                <p class="text-gray-600 mt-2">Manage your saved searches and get alerts for new properties</p>
            </div>
            <a href="{{ route('saved-searches.create') }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-plus mr-2"></i>Save New Search
            </a>
        </div>

        @if($savedSearches->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No saved searches</h3>
                    <p class="mt-2 text-gray-500">Start by searching for properties and save your searches to get alerts when new matching properties are added.</p>
                    <a href="{{ route('properties.index') }}" 
                       class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                        Browse Properties
                    </a>
                </div>
            </div>
        @else
            <!-- Saved Searches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($savedSearches as $search)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <!-- Search Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $search->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $search->search_summary }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Active Toggle -->
                                    <button onclick="toggleActive({{ $search->id }})" 
                                            class="toggle-active {{ $search->is_active ? 'text-green-600' : 'text-gray-400' }}"
                                            data-search-id="{{ $search->id }}"
                                            title="{{ $search->is_active ? 'Active' : 'Inactive' }}">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    
                                    <!-- Email Alerts -->
                                    @if($search->email_alerts)
                                        <span class="text-blue-600" title="Email alerts enabled">
                                            <i class="fas fa-bell"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Results Count -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           {{ $search->results_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $search->results_count }} {{ Str::plural('property', $search->results_count) }} found
                                </span>
                            </div>

                            <!-- Search Details -->
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                @if($search->email_alerts)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope w-4 mr-2"></i>
                                        <span>{{ ucfirst($search->alert_frequency) }} alerts</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-4 mr-2"></i>
                                    <span>Created {{ $search->created_at->diffForHumans() }}</span>
                                </div>
                                
                                @if($search->last_alert_sent)
                                    <div class="flex items-center">
                                        <i class="fas fa-paper-plane w-4 mr-2"></i>
                                        <span>Last alert {{ $search->last_alert_sent->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('saved-searches.show', $search) }}" 
                                   class="flex-1 text-center bg-indigo-600 text-white px-3 py-2 rounded-md text-sm hover:bg-indigo-700 transition duration-200">
                                    View Results
                                </a>
                                
                                <button onclick="refreshResults({{ $search->id }})" 
                                        class="bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm hover:bg-gray-200 transition duration-200"
                                        title="Refresh results">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                
                                <div class="relative">
                                    <button onclick="toggleDropdown({{ $search->id }})" 
                                            class="bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm hover:bg-gray-200 transition duration-200"
                                            id="dropdown-button-{{ $search->id }}">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div id="dropdown-{{ $search->id }}" 
                                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                        <div class="py-1">
                                            <a href="{{ route('saved-searches.edit', $search) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-edit mr-2"></i>Edit Search
                                            </a>
                                            
                                            @if($search->email_alerts && $search->is_active)
                                                <button onclick="sendTestAlert({{ $search->id }})" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-paper-plane mr-2"></i>Send Test Alert
                                                </button>
                                            @endif
                                            
                                            <button onclick="deleteSearch({{ $search->id }})" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash mr-2"></i>Delete Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bulk Actions -->
            @if($savedSearches->count() > 1)
                <div class="mt-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Bulk Actions</h3>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="selectAll()" 
                                class="text-sm bg-white border border-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-50">
                            Select All
                        </button>
                        <button onclick="bulkAction('activate')" 
                                class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                            Activate Selected
                        </button>
                        <button onclick="bulkAction('deactivate')" 
                                class="text-sm bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">
                            Deactivate Selected
                        </button>
                        <button onclick="bulkAction('disable_alerts')" 
                                class="text-sm bg-orange-600 text-white px-3 py-1 rounded hover:bg-orange-700">
                            Disable Alerts
                        </button>
                        <button onclick="bulkAction('delete')" 
                                class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Delete Selected
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
// Toggle active status
function toggleActive(searchId) {
    fetch(`/saved-searches/${searchId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[data-search-id="${searchId}"]`);
            if (data.is_active) {
                button.classList.remove('text-gray-400');
                button.classList.add('text-green-600');
                button.title = 'Active';
            } else {
                button.classList.remove('text-green-600');
                button.classList.add('text-gray-400');
                button.title = 'Inactive';
            }
            showNotification(data.message, 'success');
        } else {
            showNotification('Failed to toggle search status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Refresh results
function refreshResults(searchId) {
    fetch(`/saved-searches/${searchId}/results`)
    .then(response => response.json())
    .then(data => {
        showNotification(`Found ${data.count} properties`, 'success');
        // Update the results count display
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to refresh results', 'error');
    });
}

// Toggle dropdown
function toggleDropdown(searchId) {
    const dropdown = document.getElementById(`dropdown-${searchId}`);
    const isHidden = dropdown.classList.contains('hidden');
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
    
    if (isHidden) {
        dropdown.classList.remove('hidden');
    }
}

// Send test alert
function sendTestAlert(searchId) {
    fetch(`/saved-searches/${searchId}/test-alert`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification(data.error || 'Failed to send test alert', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Delete search
function deleteSearch(searchId) {
    if (confirm('Are you sure you want to delete this saved search?')) {
        fetch(`/saved-searches/${searchId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification('Failed to delete search', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
}

// Bulk actions (placeholder functions)
function selectAll() {
    showNotification('Bulk selection feature coming soon', 'info');
}

function bulkAction(action) {
    showNotification(`Bulk ${action} feature coming soon`, 'info');
}

// Show notification
function showNotification(message, type = 'info') {
    // Simple notification - you can replace with a more sophisticated solution
    const colors = {
        success: 'green',
        error: 'red',
        info: 'blue'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 bg-${colors[type]}-500 text-white px-4 py-2 rounded-md shadow-lg z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[id^="dropdown-button-"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
    }
});
</script>
@endpush
@endsection
