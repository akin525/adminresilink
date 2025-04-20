@extends('layouts.sidebar')
@section('tittle', 'Team')

@section('content')

    <div x-data="{ showModal: false, member: {}, imageUrl: '' }">

        <div class="md:flex justify-between items-center">
            <h5 class="text-lg font-semibold">All Team Members</h5>
            <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
                <li class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white">
                    <a href="#">Dashboard</a>
                </li>
                <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5">
                    <i class="mdi mdi-chevron-right"></i>
                </li>
                <li class="inline-block capitalize text-[16px] font-medium text-green-600 dark:text-white">Team</li>
            </ul>
        </div>

        {{-- Create Member Form --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg mt-6 shadow-md max-w-3xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Create Team Member</h2>

            <form method="POST" action="{{ route('teams') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Photo</label>
                    <input type="file" name="image" id="photoInput" accept="image/*" required class="w-full px-3 py-2 border rounded focus:outline-none" />
                    <div id="previewBox" class="mt-4 hidden">
                        <img id="previewImage" src="#" alt="Preview" class="w-24 h-24 rounded-full object-cover border" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300">Position</label>
                    <input type="text" name="position" required class="w-full px-3 py-2 border rounded focus:outline-none" />
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                        Save Member
                    </button>
                </div>
            </form>
        </div>

        {{-- Team Cards --}}
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6 mt-10">
            @forelse ($teams as $member)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 text-center relative">
                    <img src="{{ asset('public/images/' . $member->image) }}" alt="Photo" class="w-24 h-24 mx-auto rounded-full object-cover mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $member->position }}</p>
                    <button
                        @click="showModal = true; member = {{ json_encode($member) }}; imageUrl = '{{ asset('images/' . $member->image) }}'"
                        class=" bg-green-600 hover:bg-green-700 mt-3 px-6 rounded text-sm text-white hover:underline"
                    >
                        Edit Team
                    </button>
                </div>
            @empty
                <p class="text-gray-700 dark:text-white col-span-full text-center">No team members yet.</p>
            @endforelse
        </div>

        {{-- Edit Modal --}}
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            {{-- Overlay --}}
            <div
                class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
                @click="showModal = false"
            ></div>

            {{-- Modal Content --}}
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl w-full max-w-lg relative z-10 shadow-2xl">
                <button @click="showModal = false" class="absolute top-2 right-3 text-gray-400 hover:text-red-600 text-2xl">&times;</button>
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Edit Team Member</h2>

                <form method="POST" :action="'/team/update/' + member.id" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Photo</label>
                        <input type="file" name="image" class="w-full px-3 py-2 border rounded focus:outline-none" />
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="w-20 h-20 mt-3 rounded-full object-cover border" />
                        </template>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" x-model="member.name" class="w-full px-3 py-2 border rounded focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Position</label>
                        <input type="text" name="position" x-model="member.position" class="w-full px-3 py-2 border rounded focus:outline-none" />
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    {{-- Image Preview Script --}}
    <script>
        const photoInput = document.getElementById('photoInput');
        const previewImage = document.getElementById('previewImage');
        const previewBox = document.getElementById('previewBox');

        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                previewImage.src = URL.createObjectURL(file);
                previewBox.classList.remove('hidden');
            } else {
                previewImage.src = '#';
                previewBox.classList.add('hidden');
            }
        });
    </script>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection
