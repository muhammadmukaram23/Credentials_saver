@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $secureNote['title'] ?? 'Secure Note' }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('secure-notes.edit', $secureNote['id'] ?? '') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('secure-notes.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Content</h3>
                        <button onclick="copyToClipboard('{{ addslashes($secureNote['content'] ?? '') }}')" 
                                class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    
                    <div class="prose max-w-none">
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $secureNote['content'] ?? 'No content available' }}</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Category -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Category</h4>
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-folder mr-1"></i>
                        {{ $secureNote['category']['name'] ?? 'Uncategorized' }}
                    </span>
                </div>

                <!-- Tags -->
                @if(isset($secureNote['tags']) && $secureNote['tags'])
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $secureNote['tags']) as $tag)
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">
                                <i class="fas fa-tag mr-1"></i>{{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Metadata -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Information</h4>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <div class="font-medium">{{ isset($secureNote['created_at']) ? date('M j, Y g:i A', strtotime($secureNote['created_at'])) : 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Modified:</span>
                            <div class="font-medium">{{ isset($secureNote['updated_at']) ? date('M j, Y g:i A', strtotime($secureNote['updated_at'])) : 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Character Count:</span>
                            <div class="font-medium">{{ strlen($secureNote['content'] ?? '') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Actions</h4>
                    <div class="space-y-2">
                        <button onclick="copyToClipboard('{{ addslashes($secureNote['content'] ?? '') }}')" 
                                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copy Content
                        </button>
                        <button onclick="printNote()" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <form method="POST" action="{{ route('secure-notes.destroy', $secureNote['id'] ?? '') }}" 
                              onsubmit="return confirm('Are you sure you want to delete this note?')" 
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    if (!text) return;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Content copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}

function printNote() {
    const printWindow = window.open('', '_blank');
    const noteTitle = '{{ $secureNote["title"] ?? "Secure Note" }}';
    const noteContent = `{{ addslashes($secureNote['content'] ?? '') }}`;
    
    printWindow.document.write(`
        <html>
        <head>
            <title>${noteTitle}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
                .content { white-space: pre-wrap; line-height: 1.6; }
                .meta { margin-top: 20px; font-size: 12px; color: #666; border-top: 1px solid #ccc; padding-top: 10px; }
            </style>
        </head>
        <body>
            <h1>${noteTitle}</h1>
            <div class="content">${noteContent}</div>
            <div class="meta">
                Printed on: ${new Date().toLocaleDateString()}
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection 