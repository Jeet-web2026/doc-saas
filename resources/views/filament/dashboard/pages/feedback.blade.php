<x-filament-panels::page>
    <div class="min-h-screen py-6 w-full px-5 rounded">

        <h1 class="text-center text-xl font-medium text-white mb-6">
            Page Feedback and Reviews
        </h1>

        <form wire:submit="submit" class="bg-sky-300 border border-sky-400 rounded-lg p-8">

            <h2 class="text-center text-3xl font-semibold text-slate-800 mb-8">
                You are in the page
            </h2>

            <p class="text-slate-800 mb-3">How satisfied were you using this page?</p>

            <div class="overflow-hidden rounded-md border border-sky-500">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="bg-sky-300 border border-sky-500 px-4 py-3 font-semibold text-slate-800">
                                Page Rating
                            </th>
                            <th class="bg-green-700 border border-sky-500 px-4 py-3 font-semibold text-white text-center">
                                Very Good
                            </th>
                            <th class="bg-lime-500 border border-sky-500 px-4 py-3 font-semibold text-slate-900 text-center">
                                Good
                            </th>
                            <th class="bg-yellow-400 border border-sky-500 px-4 py-3 font-semibold text-slate-900 text-center">
                                Ok
                            </th>
                            <th class="bg-amber-600 border border-sky-500 px-4 py-3 font-semibold text-white text-center">
                                Poor
                            </th>
                            <th class="bg-red-600 border border-sky-500 px-4 py-3 font-semibold text-white text-center">
                                Very Poor
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $rows = [
                        'page_load' => 'Page Load Quickly',
                        'page_design' => 'Page Design',
                        'overall' => 'Overall',
                        ];
                        $columns = ['very_good', 'good', 'ok', 'poor', 'very_poor'];
                        @endphp

                        @foreach ($rows as $rowKey => $rowLabel)
                        <tr>
                            <td class="bg-sky-300 border border-sky-500 px-4 py-3 text-slate-800">
                                {{ $rowLabel }}
                            </td>
                            @foreach ($columns as $col)
                            <td class="bg-sky-300 border border-sky-500 px-4 py-3 text-center">
                                <input
                                    type="radio"
                                    wire:model="rating.{{ $rowKey }}"
                                    value="{{ $col }}"
                                    class="h-5 w-5 rounded border-2 border-slate-400 text-blue-600 focus:ring-blue-500 cursor-pointer" />
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @error('rating.page_load') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            @error('rating.page_design') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            @error('rating.overall') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror

            <p class="text-sm text-slate-800 mt-2">Tick mark one of the cells</p>

            <div class="mt-6">
                <label class="block text-slate-800 mb-2">Feedback and Reviews Type</label>
                <input
                    type="text"
                    wire:model="feedback_type"
                    class="w-72 rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('feedback_type') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6">
                <label class="block text-slate-800 mb-2">Tell Us More</label>
                <textarea
                    wire:model="comments"
                    rows="4"
                    placeholder="Comments here"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('comments') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex items-end justify-between flex-wrap gap-4">
                <div>
                    <label class="block text-slate-800 mb-2">Present Location Pincode</label>
                    <input
                        type="text"
                        wire:model="pincode"
                        maxlength="6"
                        placeholder="6 Digits Only"
                        class="w-72 rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('pincode') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-md transition-colors">
                    Submit
                </button>
            </div>

        </form>

    </div>
</x-filament-panels::page>