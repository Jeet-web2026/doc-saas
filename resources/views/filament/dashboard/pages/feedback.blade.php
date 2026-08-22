<x-filament-panels::page>
    <div class="min-h-screen py-6 w-full px-5 rounded">

        <h1 class="text-center text-xl font-medium text-white mb-6">
            Page Feedback and Reviews
        </h1>

        <x-globalresponse />

        <form action="{{ route('feedback-store') }}" method="POST" class="bg-sky-300 border border-sky-400 rounded-lg p-8">
            @csrf
            <input type="hidden" name="orga_uin" value="{{ $orgaUin }}">
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
                                    name="{{ $rowKey }}"
                                    value="{{ $col }}"
                                    @checked(old($rowKey)===$col)
                                    class="h-5 w-5 rounded border-2 border-slate-400 text-blue-600 focus:ring-blue-500 cursor-pointer" />
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @php
            $ratingErrors = collect(['page_load', 'page_design', 'overall'])
            ->map(fn ($field) => $errors->first($field))
            ->filter();
            @endphp
            @if ($ratingErrors->isNotEmpty())
            <p class="text-sm text-red-700 mt-1">{{ $ratingErrors->implode(', ') }}</p>
            @endif

            <p class="text-sm text-slate-800 mt-2">Tick mark one of the cells</p>

            <div class="mt-6">
                <label class="block text-slate-800 mb-2">Feedback and Reviews Type</label>
                <input
                    type="text"
                    name="feedback_type"
                    value="{{ old('feedback_type') }}"
                    class="w-72 rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                @error('feedback_type') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6">
                <label class="block text-slate-800 mb-2">Tell Us More</label>
                <textarea
                    name="comments"
                    rows="4"
                    placeholder="Comments here"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comments') }}</textarea>
                @error('comments') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex items-end justify-between flex-wrap gap-4">
                <div>
                    <label class="block text-slate-800 mb-2">Present Location Pincode</label>
                    <input
                        type="text"
                        name="pincode"
                        value="{{ old('pincode') }}"
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

    <script>
        var AGREEMENTS_URL = "{{ route('drafts.data') }}";
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js" integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/feedback-page-functions.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-filament-panels::page>