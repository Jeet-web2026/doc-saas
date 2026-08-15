@props([
'companyInformation' => '',
'userData' => '',
'backurl' => '',
'title' => ''
])
<header class="w-full">
    <div class="mx-auto flex gap-3 items-center justify-between px-6 py-4 relative">
        <div class="flex flex-row relative items-center">
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
                        @forelse($companyInformation as $info)
                        <button
                            data-company="{{ $info->organisation?->Orga_Name }}"
                            data-company-id="{{ $info->organisation?->Orga_UIN }}"
                            class="company-item flex w-full items-center justify-between px-3 py-1.5 text-sm hover:bg-white/5">
                            <span>{{ $info->organisation?->Orga_Name }}</span>

                            <svg
                                class="check-icon {{ $loop->first ? 'text-primary' : 'hidden' }}"
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
                        @empty
                        <p>No Company Details found.</p>
                        @endforelse
                    </div>
                </div>
                <div>
                    <div class="text-lg font-semibold" id="companyName">Ujan Logistic</div>
                    <p class="text-xs text-muted-foreground">{{ $userData['User_Name'] ?? '' }}</p>
                </div>
            </div>
            <i
                class="absolute right-[-115px] text-xl cursor-pointer hidden lg:block"
                x-bind:class="$store.sidebar.isOpen ? 'ri-menu-unfold-2-line' : 'ri-menu-fold-2-line'"
                x-on:click="$store.sidebar.isOpen = !$store.sidebar.isOpen"></i>
        </div>

        <div class="text-xl font-semibold lg:absolute lg:left-[45%] hidden lg:block">Agreements</div>

        <div class="flex flex-col items-end gap-2">
            <div class="text-xl font-semibold lg:absolute lg:left-[45%] lg:hidden block">Agreements</div>
            <!-- Company switcher -->
            <div class="relative flex flex-row items-center" x-data="{ open: false }" @click.outside="open = false">
                <button
                    type="button"
                    @click="open = !open"
                    class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-white/10 transition"
                    aria-label="Menu">
                    <i class="ri-menu-3-line text-lg"></i>
                </button>

                <div
                    x-show="open"
                    x-cloak
                    x-transition
                    class="absolute right-0 top-[50px] mt-2 w-40 rounded-md border border-white/10 bg-card shadow-xl z-50 py-1">

                    <a
                        href="{{ $backurl }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition">
                        <i class="ri-arrow-left-long-line"></i>
                        Back
                    </a>

                    <a
                        href="{{ \Filament\Facades\Filament::getPanel('settings')->getUrl() }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition">
                        <i class="bi bi-gear"></i>
                        Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>