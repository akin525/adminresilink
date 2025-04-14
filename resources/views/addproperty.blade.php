@extends('layouts.sidebar')
@section('tittle', 'Add Properties')
@section('content')
    <!-- Start Content -->
    <div class="md:flex justify-between items-center">
        <h5 class="text-lg font-semibold">Add Property</h5>

        <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
            <li class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white"><a href="#">Hously</a></li>
            <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block capitalize text-[16px] font-medium text-green-600 dark:text-white" aria-current="page">Add Property</li>
        </ul>
    </div>
    <x-validation-errors class="mb-4" />

    <div class="container relative">
        <form action="{{route('add-property')}}" method="post" class="" enctype="multipart/form-data">
@csrf
        <div class="grid md:grid-cols-2 grid-cols-1 gap-6 mt-6">
            <div class="rounded-md shadow-sm dark:shadow-gray-700 p-6 bg-white dark:bg-slate-900 h-fit">
                <div>
                    <p class="font-medium mb-4">Upload your property image here, Please click "Upload Image" Button.</p>
                    <div class="preview-box flex justify-center rounded-md shadow-sm dark:shadow-gray-800 overflow-hidden bg-gray-50 dark:bg-slate-800 text-slate-400 p-2 text-center small w-auto max-h-60">Supports JPG, PNG and MP4 videos. Max file size : 10MB.</div>
                    <input type="file" id="input-file" name="images[]" accept="image/*" onchange="handleChange()" multiple hidden>
                    <label class="btn-upload btn bg-green-600 hover:bg-green-700 border-green-600 hover:border-green-700 text-white rounded-md mt-6 cursor-pointer" for="input-file">Upload Image</label>
                </div>
            </div>

            <div class="rounded-md shadow-sm dark:shadow-gray-700 p-6 bg-white dark:bg-slate-900 h-fit">
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12">
                            <label for="name" class="font-medium">Title:</label>
                            <input name="tittle" id="name" type="text" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" placeholder="Property Title :">
                        </div>
                        <div class="md:col-span-4 col-span-12">
                            <label for="name" class="font-medium">Type:</label>
                            <select name="type" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" required>
                                <option>Select Option</option>
                                <option value="SINGLE_ROOM">SINGLE ROOM</option>
                                <option value="SELF_CONTAINER">SELF CONTAINER</option>
                                <option value="FLAT">FLAT</option>
                                <option value="DUB">DUB</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 col-span-12">
                            <label for="name" class="font-medium">Mode:</label>
                            <select name="mode" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" required>
                                <option>Select Option</option>
                                <option value="RENT">RENT</option>
                                <option value="SALE">SALE</option>
                            </select>
                        </div>

                        <div class="md:col-span-4 col-span-12">
                            <label for="name" class="font-medium">Price:</label>
                            <div class="form-icon relative mt-2">
                                <i class="mdi mdi-currency-ngn absolute top-2 start-4 text-green-600"></i>
                                <input name="price" id="price" type="number" class="form-input border !border-gray-200 dark:!border-gray-800 !ps-11" placeholder="Price :">
                            </div>
                        </div>
                        <div class="md:col-span-4 col-span-12">
                            <label for="name" class="font-medium">Commission:</label>
                            <div class="form-icon relative mt-2">
                                <i class="mdi mdi-currency-ngn absolute top-2 start-4 text-green-600"></i>
                                <input name="commission" id="Commission" type="number" class="form-input border !border-gray-200 dark:!border-gray-800 !ps-11" placeholder="Commission :">
                            </div>
                        </div>
                        <div class="md:col-span-4 col-span-12">
                            <label for="address" class="font-medium">State:</label>
                            <input name="state" id="address" type="text" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" placeholder="Property state :">
                        </div>
                        <div class="md:col-span-4 col-span-12">
                            <label for="rooms" class="font-medium">Rooms:</label>
                            <input name="rooms" id="rooms" type="number" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" placeholder="Numbers of rooms :">
                        </div>
                        <div class="col-span-12">
                            <label for="address" class="font-medium">Address:</label>
                            <input name="address" id="address" type="text" class="form-input border !border-gray-200 dark:!border-gray-800 mt-2" placeholder="Property Address :">
                        </div>


                        <div class="col-span-12">
                            <label for="description" class="font-medium">Description:</label>
                            <textarea name="description" id="description"  class="form-input border !border-gray-200 dark:!border-gray-800" ></textarea>
                        </div>
                    </div>

                    <button type="submit" id="submit" name="send" class="btn bg-green-600 hover:bg-green-700 border-green-600 hover:border-green-700 text-white rounded-md mt-5">Add Property</button>
            </div>
        </div>
        </form>
    </div>

@endsection
@section('script')
    <script>
        const handleChange = () => {
            const fileUploader = document.querySelector('#input-file');
            const getFiles = fileUploader.files;
            const parent = document.querySelector('.preview-box');
            parent.innerHTML = ''; // Clear existing previews

            if (getFiles.length !== 0) {
                Array.from(getFiles).forEach(file => readFile(file, parent));
            }
        }

        const readFile = (file, parent) => {
            const reader = new FileReader();
            reader.onload = () => {
                const img = document.createElement('img');
                img.classList.add('preview-content', 'm-2', 'h-32', 'rounded-md');
                img.src = reader.result;
                parent.appendChild(img);
            };
            reader.readAsDataURL(file);
        };
    </script>

@endsection
