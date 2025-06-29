@extends('layouts.sidebar')
@section('tittle', 'Properties')
@section('content')
    <style>[x-cloak] { display: none !important; }</style>

    <div x-data="propertyEditor()" x-init="init()">

        {{-- Page Header --}}
        <div class="md:flex justify-between items-center">
            <h5 class="text-lg font-semibold">Explore Properties</h5>
            <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
                <li class="inline-block capitalize text-[16px] font-medium dark:text-white/70 hover:text-green-600"><a href="#">Hously</a></li>
                <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5"><i class="mdi mdi-chevron-right"></i></li>
                <li class="inline-block capitalize text-[16px] font-medium text-green-600 dark:text-white">Properties</li>
            </ul>
        </div>

        {{-- Property Cards --}}
        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6 mt-6">
            @forelse($data as $datas)
                @php


                    $images = json_decode($datas['images'], true);

                    $imageUrls = array_map(function ($img) {
                        if (Str::startsWith($img, ['http://', 'https://'])) {
                            // Insert /public before /images
                            return str_replace('/images/', '/public/images/', $img);
                        } else {
                            // Use asset() for relative paths
                            return asset($img);
                        }
                    }, $images);

                    $firstImage = $imageUrls[0] ?? asset('default.jpg');
                @endphp
                <div class="group rounded-xl bg-white dark:bg-slate-900 shadow-sm hover:shadow-xl overflow-hidden duration-500">
                    <div class="relative">
                        <img src="{{ $firstImage }}"
                             alt="Property Image"
                             class="w-full h-60 object-cover cursor-pointer"
                             @click="images = {{ json_encode($imageUrls) }}; current = 0; showImageModal = true;">

                        <div class="absolute top-4 end-4">
                            <a href="javascript:void(0)" class="btn btn-icon bg-white shadow-sm !rounded-full text-slate-100 hover:text-red-600"><i class="mdi mdi-heart text-[20px]"></i></a>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="pb-4">
                            <a href="#" class="text-lg hover:text-green-600 font-medium">{{ $datas['title'] }}</a>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-300 mb-4">{{ $datas['description'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-white font-semibold mb-2">₦{{ number_format($datas['price']) }}</p>

                        <button
                            @click="
                                editModal = true;
                                editing = {
                                    id: {{ $datas['id'] }},
                                    title: @js($datas['title']),
                                    description: @js($datas['description']),
                                    price: @js($datas['price']),
                                    image: '{{ $images[0] ?? 'default.jpg' }}'
                                }
                            "
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mt-2 w-full">
                            Edit
                        </button>
                    </div>
                </div>
            @empty
                <p>No Property Posted yet</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="grid md:grid-cols-12 grid-cols-1 mt-6">
            <div class="md:col-span-12 text-center">
                {{ $data->links() }}
            </div>
        </div>

        {{-- Image Modal --}}
        <div
            x-show="showImageModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div class="absolute inset-0 bg-black bg-opacity-60" @click="showImageModal = false"></div>
            <div class="relative z-10 bg-white dark:bg-slate-800 rounded-xl p-4 shadow-lg max-w-3xl w-full">
                <button @click="showImageModal = false" class="absolute top-2 right-3 text-gray-400 hover:text-red-600 text-2xl">&times;</button>
                <img :src="images[current]" class="rounded-lg w-full h-[450px] object-cover" />
                <div class="flex justify-between mt-4">
                    <button @click="current = current > 0 ? current - 1 : images.length - 1" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 rounded hover:bg-gray-300">Previous</button>
                    <button @click="current = current < images.length - 1 ? current + 1 : 0" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 rounded hover:bg-gray-300">Next</button>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div
            x-show="editModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="editModal = false"></div>

            <div class="relative z-10 bg-white dark:bg-slate-800 rounded-lg p-6 w-full max-w-xl">
                <button @click="editModal = false" class="absolute top-2 right-3 text-gray-400 hover:text-red-600 text-2xl">&times;</button>

                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Edit Property</h2>

                <form method="POST" :action="`/properties/${editing.id}`" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Image Preview --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Image</label>
                        <img :src="imageUrl"
                             class="w-full h-64 mt-3 rounded-xl object-cover border border-gray-300 dark:border-slate-600 shadow-md" />
                    </div>


                    {{-- Upload New Image --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Change Image</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-700 border rounded px-3 py-2 dark:bg-slate-700 dark:text-white">
                    </div>

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" name="title" x-model="editing.title" class="w-full px-3 py-2 border rounded mt-1 dark:bg-slate-700 dark:text-white">
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" x-model="editing.description" class="w-full px-3 py-2 border rounded mt-1 dark:bg-slate-700 dark:text-white"></textarea>
                    </div>

                    {{-- Price --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                        <input type="number" name="price" x-model="editing.price" class="w-full px-3 py-2 border rounded mt-1 dark:bg-slate-700 dark:text-white">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Update Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine Component Script --}}
    <script>
        function propertyEditor() {
            return {
                showImageModal: false,
                images: [],
                current: 0,
                editModal: false,
                editing: {},
                imageUrl: '',

                init() {
                    this.$watch('editing', value => {
                        this.imageUrl = value.image
                            ? '{{ url('/') }}/' + value.image
                            : '{{ url('default.jpg') }}';
                    });
                }
            }
        }
    </script>
@endsection
