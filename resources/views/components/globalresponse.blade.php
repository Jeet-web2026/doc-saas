@if(session('success'))
<div class="mb-4 rounded-lg border bg-blue-950 border-blue-800 p-4 text-white">
    <div class="flex flex-row items-start justify-between">
        <div class="flex items-center gap-3">
            <i class="ri-checkbox-circle-line text-2xl"></i>
            <span>Successful ! message{{ session('success') }}</span>
        </div>
        <span class="cursor-pointer"><i class="ri-close-line text-2xl"></i></span>
    </div>
</div>
@endif
@if(session('error'))
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
    <div class="flex flex-row items-start justify-between">
        <div class="flex items-center gap-3">
            <i class="ri-error-warning-line text-2xl"></i>
            <span>Error ! message{{ session('error') }}</span>
        </div>
        <span class="cursor-pointer"><i class="ri-close-line text-2xl"></i></span>
    </div>
</div>
@endif