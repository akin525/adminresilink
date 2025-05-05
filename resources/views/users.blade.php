@extends('layouts.app')

@section('title', 'All Users')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold leading-tight">All Users</h2>
                <button onclick="openModal()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+ Add User</button>
            </div>

            <div class="mb-4 flex flex-col sm:flex-row sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <input type="text" id="searchInput" placeholder="Search by name..." class="w-full sm:w-64 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <button onclick="exportToCSV()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Export to CSV</button>
                </div>
            </div>

            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table id="dataTable" class="min-w-full leading-normal">
                        <thead>
                        <tr>
                            <th onclick="sortTable(0)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Name</th>
                            <th onclick="sortTable(1)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Email</th>
                            <th onclick="sortTable(2)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Type</th>
                            <th onclick="sortTable(3)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Username</th>
                            <th onclick="sortTable(4)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Phone</th>
                            <th onclick="sortTable(5)" class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">Date</th>
                            <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        @foreach($data as $user)
                            <tr class="border-b">
                                <td class="px-5 py-5 text-sm">{{ $user->name }}</td>
                                <td class="px-5 py-5 text-sm">{{ $user->email }}</td>
                                <td class="px-5 py-5 text-sm">{{ $user->type }}</td>
                                <td class="px-5 py-5 text-sm">{{ $user->username }}</td>
                                <td class="px-5 py-5 text-sm">{{ $user->phone }}</td>
                                <td class="px-5 py-5 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="px-5 py-5 text-sm">
                                    <button class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                                    <button class="text-red-500 hover:text-red-700">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                        <span id="pageInfo" class="text-xs xs:text-sm text-gray-900"></span>
                        <div class="inline-flex mt-2 xs:mt-0">
                            <button onclick="changePage(-1)" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-l">Prev</button>
                            <button onclick="changePage(1)" class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-r">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="createUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Create New User</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Age</label>
                    <input type="number" name="age" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
            document.getElementById('createUserModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('createUserModal').classList.remove('flex');
            document.getElementById('createUserModal').classList.add('hidden');
        }

        // Table functionalities
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
            let csv = "Name,Email,Type,Username,Phone,Date\n";
            filteredRows.forEach(row => {
                const cols = Array.from(row.cells).slice(0, 6).map(td => td.textContent.trim());
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
