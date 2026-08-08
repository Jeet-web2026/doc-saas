@props([
'companyInformation' => '',
'userData' => '',
'backurl' => ''
])
<header class="border-b border-white/10 bg-white/[0.03]">
    <div class="mx-auto flex flex-wrap gap-3 max-w-5xl items-center justify-between px-6 py-4 relative">
        <div class="flex flex-wrap gap-2 items-start">
            <div class="relative" data-menu>
                <button type="button" data-menu-toggle="companyMenu"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full hover:bg-white/10 transition">
                    <!-- building icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                        <path d="M10 6h4" />
                        <path d="M10 10h4" />
                        <path d="M10 14h4" />
                        <path d="M10 18h4" />
                    </svg>
                </button>
                <div id="companyMenu" class="hidden absolute lg:right-0  lg:right-auto mt-2 w-56 rounded-md border border-white/10 bg-card shadow-xl z-30 py-1">
                    <div class="px-3 py-1.5 text-xs font-semibold text-muted-foreground">Switch company</div>
                    <div class="border-t border-white/10 my-1"></div>
                    <!-- Companies -->
                    <button
                        data-company="{{ $companyInformation->Orga_Name }}"
                        data-company-id="{{ $companyInformation->Orga_UIN }}"
                        class="company-item flex w-full items-center justify-between px-3 py-1.5 text-sm hover:bg-white/5">
                        <span>{{ $companyInformation->Orga_Name }}</span>

                        <svg
                            class="check-icon"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <div class="text-lg font-semibold" id="companyName">{{ $companyInformation->Orga_Name }}</div>
                <p class="text-xs text-muted-foreground">{{ $userData['data'][0]['User_Name'] ?? '' }}</p>
            </div>
        </div>

        <div class="flex flex-col items-end">
            <div class="text-xl font-semibold lg:absolute lg:left-[45%]">Agreements</div>
            <!-- Company switcher -->
            <div class="relative flex flex-row items-center">
                <a href="{{ $backurl }}" class="inline-flex items-center gap-2 px-3 py-1.5 border border-white/10 rounded-md text-sm text-white/80 hover:text-white hover:bg-white/10 transition">
                    Back
                </a>
            </div>
        </div>
    </div>
</header>