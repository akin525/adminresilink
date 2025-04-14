@extends('layouts.sidebar')
@section('tittle', 'Properties')
@section('content')
    <style>[x-cloak] { display: none !important; }</style>

    <div class="md:flex justify-between items-center">
        <h5 class="text-lg font-semibold">Explore Properties</h5>

        <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
            <li class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white"><a href="#">Hously</a></li>
            <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block capitalize text-[16px] font-medium text-green-600 dark:text-white" aria-current="page">Properties</li>
        </ul>
    </div>

    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6 mt-6">
        @forelse($data as $datas)
        <div class="group rounded-xl bg-white dark:bg-slate-900 shadow-sm hover:shadow-xl dark:hover:shadow-xl dark:shadow-gray-700 dark:hover:shadow-gray-700 overflow-hidden ease-in-out duration-500">
            <div class="relative">
                @php
                    $images = json_decode($datas['images'], true);
                    $imageUrls = array_map(function($img) { return url($img); }, $images);
                    $firstImage = $imageUrls[0] ?? url('default.jpg');
                @endphp

                {{--                <img src="{{ url($firstImage) }}" alt="Property Image" class="w-full h-60 object-cover">--}}
                <img src="{{ url($firstImage) }}"
                     alt="Property Image"
                     class="w-full h-60 object-cover cursor-pointer"
                     @click="images = {{ json_encode(array_map('url', $images)) }}; current = 0; showModal = true;">

                <div class="absolute top-4 end-4">
                    <a href="javascript:void(0)" class="btn btn-icon bg-white dark:bg-slate-900 shadow-sm dark:shadow-gray-700 !rounded-full text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600"><i class="mdi mdi-heart text-[20px]"></i></a>
                </div>
            </div>

            <div class="p-6">
                <div class="pb-6">
                    <a href="#" class="text-lg hover:text-green-600 font-medium ease-in-out duration-500">{{$datas['title']}}</a>
                </div>

                <ul class="py-6 border-y border-slate-100 dark:border-gray-800 flex items-center list-none">
                    <li class="flex items-center me-4">
                        <i class="mdi mdi-arrow-expand-all text-2xl me-2 text-green-600"></i>
                        <span>{{$datas['description']}}</span>
                    </li>

{{--                    <li class="flex items-center me-4">--}}
{{--                        <i class="mdi mdi-bed text-2xl me-2 text-green-600"></i>--}}
{{--                        <span>4 Beds</span>--}}
{{--                    </li>--}}

{{--                    <li class="flex items-center">--}}
{{--                        <i class="mdi mdi-shower text-2xl me-2 text-green-600"></i>--}}
{{--                        <span>4 Baths</span>--}}
{{--                    </li>--}}
                </ul>

                <ul class="pt-6 flex justify-between items-center list-none">
                    <li>
                        <span class="text-slate-400">Price</span>
                        <p class="text-lg font-medium">₦{{number_format(intval($datas['price'] *1))}}</p>
                    </li>

                    <li>
                        <span class="text-slate-400">Rating</span>
                        <ul class="text-lg font-medium text-amber-400 list-none">
                            <li class="inline"><i class="mdi mdi-star"></i></li>
                            <li class="inline"><i class="mdi mdi-star"></i></li>
                            <li class="inline"><i class="mdi mdi-star"></i></li>
                            <li class="inline"><i class="mdi mdi-star"></i></li>
                            <li class="inline"><i class="mdi mdi-star"></i></li>
                            <li class="inline text-black dark:text-white">5.0(30)</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!--end property content-->
        @empty
        <p>No Property Posted yet</p>
        @endforelse
        </div><!--en grid-->


    <div class="grid md:grid-cols-12 grid-cols-1 mt-6">
        <div class="md:col-span-12 text-center">
            <nav>
                <ul class="inline-flex items-center -space-x-px">
                    <li>
                     {{$data->links()}}
                    </li>
                </ul>
            </nav>
        </div>
    </div><!--end grid-->

    <!-- Modal for Image Preview -->
    <div x-data="{ showModal: false, images: [], current: 0 }" x-show="showModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-cloak>
        <div class="bg-white rounded-xl max-w-2xl w-full p-4 relative">
            <button @click="showModal = false" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>

            <div class="flex justify-center">
                <img :src="images[current]" class="rounded-md w-full h-96 object-cover" alt="Property Image">
            </div>

            <div class="flex justify-center space-x-2 mt-4">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img" @click="current = index"
                         class="w-16 h-16 object-cover cursor-pointer rounded border border-gray-300 hover:border-green-500"
                         :class="{ 'ring-2 ring-green-600': index === current }">
                </template>
            </div>
        </div>
    </div>

@endsection
