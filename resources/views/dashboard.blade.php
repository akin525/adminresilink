@extends('layouts.sidebar')
@section('tittle', 'Admin Dashboard')
@section('content')
    <div class="flex justify-between items-center">
        <div class="alert alert-success">
            <h5 class="text-xl font-semibold">Hello, {{Auth::user()->username}}</h5>
            <h6 class="text-slate-400">Welcome back!</h6>
        </div>
    </div>

    <div class="grid xl:grid-cols-5 md:grid-cols-3 grid-cols-1 mt-6 gap-6">
        <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
            <div class="p-5 flex items-center justify-between">
                                    <span class="me-3">
                                        <span class="text-slate-400 block">Total Revenue</span>
                                        <span class="flex items-center justify-between mt-1">
                                            <span class="text-2xl font-medium">₦ <span class="counter-value" data-target="{{number_format(intval($data['revenue'] *1))}}">{{number_format(intval($data['revenue'] *1))}}</span></span>
                                        </span>
                                    </span>

                <span class="flex justify-center items-center rounded-md size-12 min-w-[48px] bg-slate-50 dark:bg-slate-800 shadow-sm shadow-gray-100 dark:shadow-gray-700 text-green-600">
                                        <i class="mdi mdi-currency-ngn text-[28px]"></i>
                                    </span>
            </div>
        </div><!--end-->

        <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
            <div class="p-5 flex items-center justify-between">
                                    <span class="me-3">
                                        <span class="text-slate-400 block">Total Users</span>
                                        <span class="flex items-center justify-between mt-1">
                                            <span class="text-2xl font-medium"><span class="counter-value" data-target="{{number_format(intval($data['users'] *1))}}">{{number_format(intval($data['users'] *1))}}</span></span>
                                        </span>
                                    </span>

                <span class="flex justify-center items-center rounded-md size-12 min-w-[48px] bg-slate-50 dark:bg-slate-800 shadow-sm shadow-gray-100 dark:shadow-gray-700 text-green-600">
                                        <i class="mdi mdi-account-group-outline text-[28px]"></i>
                                    </span>
            </div>
        </div><!--end-->

        <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
            <div class="p-5 flex items-center justify-between">
                                    <span class="me-3">
                                        <span class="text-slate-400 block">Total Properties</span>
                                        <span class="flex items-center justify-between mt-1">
                                            <span class="text-2xl font-medium"><span class="counter-value" data-target="{{number_format(intval($data['property'] *1))}}">{{number_format(intval($data['property'] *1))}}</span></span>
                                        </span>
                                    </span>

                <span class="flex justify-center items-center rounded-md size-12 min-w-[48px] bg-slate-50 dark:bg-slate-800 shadow-sm shadow-gray-100 dark:shadow-gray-700 text-green-600">
                                        <i class="mdi mdi-home-city-outline text-[28px]"></i>
                                    </span>
            </div>
        </div><!--end-->

        <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
            <div class="p-5 flex items-center justify-between">
                                    <span class="me-3">
                                        <span class="text-slate-400 block">Properties for Sell</span>
                                        <span class="flex items-center justify-between mt-1">
                                            <span class="text-2xl font-medium"><span class="counter-value" data-target="{{number_format(intval($data['property_sell'] *1))}}">{{number_format(intval($data['property_sell'] *1))}}</span></span>
                                        </span>
                                    </span>

                <span class="flex justify-center items-center rounded-md size-12 min-w-[48px] bg-slate-50 dark:bg-slate-800 shadow-sm shadow-gray-100 dark:shadow-gray-700 text-green-600">
                                        <i class="mdi mdi-home-lightning-bolt-outline text-[28px]"></i>
                                    </span>
            </div>
        </div><!--end-->

        <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
            <div class="p-5 flex items-center justify-between">
                                    <span class="me-3">
                                        <span class="text-slate-400 block">Properties for Rent</span>
                                        <span class="flex items-center justify-between mt-1">
                                            <span class="text-2xl font-medium"><span class="counter-value" data-target="{{number_format(intval($data['property_rent'] *1))}}">{{number_format(intval($data['property_rent'] *1))}}</span></span>
                                        </span>
                                    </span>

                <span class="flex justify-center items-center rounded-md size-12 min-w-[48px] bg-slate-50 dark:bg-slate-800 shadow-sm shadow-gray-100 dark:shadow-gray-700 text-green-600">
                                        <i class="mdi mdi-home-clock-outline text-[28px]"></i>
                                    </span>
            </div>
        </div><!--end-->
    </div>
    <div class="grid lg:grid-cols-12 grid-cols-1 mt-6 gap-6">
        <div class="lg:col-span-8">
            <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
                <div class="p-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
                    <h6 class="text-lg font-semibold">Revenue Analytics</h6>

                    <div class="position-relative">
                        <select class="form-select form-input w-full py-2 h-10 bg-white dark:bg-slate-900 dark:text-slate-200 rounded outline-none border !border-gray-200 dark:!border-gray-800 focus:ring-0" id="yearchart">
                            <option value="Y" selected>Yearly</option>
                            <option value="M">Monthly</option>
                            <option value="W">Weekly</option>
                            <option value="T">Today</option>
                        </select>
                    </div>
                </div>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <div class="xl:col-span-3 lg:col-span-6 xl:order-3 order-2">
            <div class="relative overflow-hidden rounded-md shadow-sm dark:shadow-gray-700 bg-white dark:bg-slate-900">
                <div class="p-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
                    <h6 class="text-lg font-semibold">Top Properties</h6>

                    <a href="" class="btn btn-link font-normal text-slate-400 hover:text-green-600 after:bg-green-600 transition duration-500">See More <i class="mdi mdi-arrow-right ms-1"></i></a>
                </div>

                <div class="relative overflow-x-auto block w-full max-h-[284px] p-6" data-simplebar>
                    @forelse($data['recent_property'] as $datas)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            @php
                                $images = json_decode($datas['images'], true);
                                $firstImage = $images[0] ?? 'default.jpg';

                                // Add /public before /images
                                $imageSrc = str_replace('/images/', '/public/images/', $firstImage);
                            @endphp

                            <div class="relative md:shrink-0">
                                <img src="{{ $imageSrc }}" class="object-cover size-14 min-w-[56px] rounded-md shadow-sm dark:shadow-gray-700" alt="">
                            </div>


                            <div class="ms-2">
                                <a href="" class="font-medium hover:text-green-600 block text-lg">{{$datas['title']}}</a>
                                <span class="text-slate-400">{{$datas['address']}}</span>
                            </div>
                        </div>

                        <span class="w-20 text-red-600 text-end"><i class="mdi mdi-arrow-bottom-right"></i> 11%</span>
                    </div>
                    @empty
                    <p>No recent property Posted</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ],
                datasets: [
                    {
                        label: 'No. of Sales',
                        data: @json($data['monthlySales']),
                        borderColor: '#94a3b8',
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Revenue',
                        data: @json($data['monthlyRevenue']),
                        borderColor: '#22c55e',
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    </script>
@endsection
