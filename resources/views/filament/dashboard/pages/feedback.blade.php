<x-filament-panels::page>
    <style>
        .input,
        select.input,
        textarea.input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: .5rem;
            padding: .5rem .75rem;
            color: #e2e8f0;
            font-size: .875rem;
        }

        .input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
        }
    </style>
    <div class="min-h-screen py-6 w-full px-5 rounded text-white">

        <h1 class="text-center text-xl font-medium text-white mb-6">
            Page Feedback and Reviews
        </h1>

        <x-globalresponse />

        <form action="{{ route('feedback-store') }}" method="POST" class="bg-card border border-white/10 rounded-lg p-8 text-white"  enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="orga_uin" value="{{ $orgaUin }}">

            <p class="text-white mb-3 text-white">How satisfied were you using this page?</p>

            <div class="overflow-hidden rounded-md border border-white/10">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="bg-card border border-white/10 px-4 py-3 font-semibold text-white">
                                Page Rating
                            </th>
                            <th class="bg-green-700 border border-white/10 px-4 py-3 font-semibold text-white text-center">
                                Very Good
                            </th>
                            <th class="bg-lime-600 border border-white/10 px-4 py-3 font-semibold text-white text-center">
                                Good
                            </th>
                            <th class="bg-yellow-500 border border-white/10 px-4 py-3 font-semibold text-white text-center">
                                Ok
                            </th>
                            <th class="bg-amber-700 border border-white/10 px-4 py-3 font-semibold text-white text-center">
                                Poor
                            </th>
                            <th class="bg-red-700 border border-white/10 px-4 py-3 font-semibold text-white text-center">
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
                            <td class="bg-card border border-white/10 px-4 py-3 text-white">
                                {{ $rowLabel }}
                            </td>
                            @foreach ($columns as $col)
                            <td class="bg-card border border-white/10 px-4 py-3 text-center">
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
            @error('page_load') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            @error('page_design') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            @error('overall') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror

            <p class="text-sm text-white mt-2">Tick mark one of the cells</p>

            <div class="flex flex-col gap-4 mt-6 lg:flex-row items-center">
                <div class="flex-1">
                    <label class="block text-white mb-2">Feedback and Reviews Type</label>
                    <select
                        name="feedback_type"
                        class="w-full rounded-md input px-3 py-2 text-white">
                        <option value="" class="bg-slate-900" disabled {{ old('feedback_type') ? '' : 'selected' }}>Select a type</option>
                        <option value="Query/Question" class="bg-slate-900" @selected(old('feedback_type')==='Query/Question' )>Query/Question</option>
                        <option value="Page Bug/Error" class="bg-slate-900" @selected(old('feedback_type')==='Page Bug/Error' )>Page Bug/Error</option>
                        <option value="Appreciation" class="bg-slate-900" @selected(old('feedback_type')==='Appreciation' )>Appreciation</option>
                        <option value="Problem Face" class="bg-slate-900" @selected(old('feedback_type')==='Problem Face' )>Problem Face</option>
                        <option value="Idea/New Features" class="bg-slate-900" @selected(old('feedback_type')==='Idea/New Features' )>Idea/New Features</option>
                        <option value="Other" class="bg-slate-900" @selected(old('feedback_type')==='Other' )>Other</option>
                    </select>
                    @error('feedback_type') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-white mb-2">Present Location Pincode</label>
                    <input
                        type="text"
                        name="pincode"
                        value="{{ old('pincode') }}"
                        maxlength="6"
                        placeholder="6 Digits Only"
                        class="w-full rounded-md input px-3 py-2 text-white placeholder-slate-400 border border-white/10" />
                    @error('pincode') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-white mb-2">Tell Us More</label>
                <textarea
                    name="comments"
                    rows="4"
                    placeholder="Comments here"
                    class="w-full rounded-md input px-3 py-2 text-white placeholder-slate-400 border border-white/10">{{ old('comments') }}</textarea>
                @error('comments') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex items-end justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <label class="block text-white mb-2">Attachment</label>
                    <input
                        type="file"
                        class="input file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-slate-700 file:text-slate-100 file:text-sm hover:file:bg-slate-600 cursor-pointer"
                        name="attachment"
                        id="attachment">
                    @error('attachment') <p class="text-sm text-red-700 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button
                type="submit"
                class="bg-blue-800 mt-5 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-md transition-colors">
                Submit
            </button>

        </form>

    </div>

    <script>
        var AGREEMENTS_URL = "{{ route('drafts.data') }}";
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js" integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/feedback-page-functions.js') }}"></script>
    <script src="{{ asset('assets/common.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</x-filament-panels::page>