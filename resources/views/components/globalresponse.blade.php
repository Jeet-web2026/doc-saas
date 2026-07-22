@if(session('success'))
<div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
    <div class="flex items-center">
        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>

        <span>{{ session('success') }}</span>
    </div>
</div>
@endif
@if(session('error'))
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
    <div class="flex items-center">
        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>

        <span>{{ session('error') }}</span>
    </div>
</div>
@endif