@extends('layouts.sidebar')
@section('tittle', 'Team')

@section('content')

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

        <form method="POST" action="{{route('teams')}}" enctype="multipart/form-data" class="space-y-4">
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
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 text-center">
                <img src="{{ asset('images/' . $member->image) }}" alt="Photo" class="w-24 h-24 mx-auto rounded-full object-cover mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $member->position }}</p>
            </div>
        @empty
            <p class="text-gray-700 dark:text-white col-span-full text-center">No team members yet.</p>
        @endforelse
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

@endsection
