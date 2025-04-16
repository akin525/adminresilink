@extends('layouts.sidebar')
@section('tittle', 'All Users')

@section('content')

    <div class="md:flex justify-between items-center">
        <h5 class="text-lg font-semibold">All users</h5>

        <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
            <li class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white">
                <a href="#">Hously</a>
            </li>
            <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5 ltr:rotate-0 rtl:rotate-180">
                <i class="mdi mdi-chevron-right"></i>
            </li>
            <li class="inline-block capitalize text-[16px] font-medium text-green-600 dark:text-white" aria-current="page">Users</li>
        </ul>
    </div>



    <div class="table-container mt-6">
        <input type="text" id="searchInput" placeholder="Search by name..." class="border px-4 py-2 rounded-md w-full mb-4" />
        <div class="mb-4">
            <button onclick="exportToCSV()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export to CSV</button>
        </div>

        <div class="overflow-auto rounded-lg shadow">
            <table id="dataTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300">
                <tr>
                    <th onclick="sortTable(0)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th onclick="sortTable(1)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                    <th onclick="sortTable(2)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                    <th onclick="sortTable(3)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Username</th>
                    <th onclick="sortTable(4)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Number</th>
{{--                    <th onclick="sortTable(5)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>--}}
                    <th onclick="sortTable(6)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                    <th onclick="sortTable(7)" class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                </tr>
                </thead>
                <tbody id="tableBody" class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-700">
                @foreach($data as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->type  }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->username  }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->phone  }}</td>
{{--                        <td class="px-6 py-4 whitespace-nowrap">--}}
{{--                            @if($user->status ==1)--}}
{{--                                <span class="btn btn-success">Active</span>--}}
{{--                            @else--}}
{{--                                <span class="btn btn-danger">Block</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at  }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <i class="mdi mdi-pencil"></i>
                            <i class="mdi mdi-recycle"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Create User Modal -->
            <div id="createUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-xl w-full max-w-lg relative animate-fade-in">
                    <!-- Close Button -->
                    <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl">&times;</button>

                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Create New User</h2>

                    <form method="POST"  class="space-y-5">
                        @csrf
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                            <input type="number" name="age" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-700 rounded-md dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-md border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- Modal -->
        <script>
            function openModal() {
                document.getElementById('createUserModal').classList.remove('hidden');
                document.getElementById('createUserModal').classList.add('flex');
            }

            function closeModal() {
                document.getElementById('createUserModal').classList.remove('flex');
                document.getElementById('createUserModal').classList.add('hidden');
            }
        </script>

        <div class="pagination flex justify-between items-center mt-4">
            <button onclick="changePage(-1)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Previous</button>
            <span id="pageInfo" class="text-sm text-gray-700 dark:text-gray-300"></span>
            <button onclick="changePage(1)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Next</button>
        </div>
    </div>

    <!-- Table JS logic -->
    <script>
        const rowsPerPage = 5;
        let currentPage = 1;
        let allRows = Array.from(document.querySelectorAll("#tableBody tr"));
        let filteredRows = [...allRows];

        function renderTable() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            allRows.forEach(row => row.style.display = 'none');
            filteredRows.slice(start, end).forEach(row => row.style.display = '');
            document.getElementById("pageInfo").textContent = `Page ${currentPage} of ${Math.ceil(filteredRows.length / rowsPerPage)}`;
        }

        function changePage(direction) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            currentPage += direction;
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            renderTable();
        }

        document.getElementById('searchInput').addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            filteredRows = allRows.filter(row => row.cells[0].textContent.toLowerCase().includes(filter));
            currentPage = 1;
            renderTable();
        });

        function sortTable(index) {
            const isAsc = document.getElementById("dataTable").getAttribute("data-sort-dir") !== "asc";
            filteredRows.sort((a, b) => {
                const aText = a.cells[index].textContent.trim().toLowerCase();
                const bText = b.cells[index].textContent.trim().toLowerCase();
                return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });
            document.getElementById("dataTable").setAttribute("data-sort-dir", isAsc ? "asc" : "desc");
            renderTable();
        }

        function exportToCSV() {
            let csv = "Name,Email,Age\n";
            filteredRows.forEach(row => {
                const cols = Array.from(row.cells).map(td => td.textContent.trim());
                csv += cols.join(",") + "\n";
            });

            const blob = new Blob([csv], { type: "text/csv" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "users.csv";
            a.click();
            URL.revokeObjectURL(url);
        }

        window.addEventListener('DOMContentLoaded', renderTable);
    </script>

@endsection
