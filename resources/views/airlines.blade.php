@extends('components.layout')

@section('title', 'Manage Airlines')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Airlines</h1>
            <p class="mt-2 text-sm text-gray-700">Manage all airlines and their details.</p>
            </div>
        </div>
        <div class="flex flex-col space-y-2 mt-6">
            <div class="flex space-x-2">
                <input type="text" id="new-airline-name" class="rounded-md border-2 border-gray-400 text-sm p-2 w-64" placeholder="Enter airline name">
                <input type="text" id="new-airline-desscription" class="rounded-md border-2 border-gray-400 text-sm p-2 w-64" placeholder="Enter airline description">
                <button id="add-airline-btn" type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add airline</button>
            </div>
            <div id="message-box" class="hidden w-64 p-2 text-center text-white text-sm font-semibold rounded-md"></div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="flex space-x-4 mb-4">
                <select id="filter-city" class="rounded-md border-2 border-gray-400 text-sm">
                    <option value="">Filter by City</option>
                </select>
                <input type="number" id="filter-flights" class="rounded-md border-2 border-gray-400 text-sm p-2 w-40" placeholder="Flights Count">
                <button id="apply-filters" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Apply</button>
            </div>
                <table id="airlines-table" class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                    <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-3">ID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Number of flights</th>
                    <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-3">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-3">
                        <span class="sr-only">Delete</span>
                    </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="even:bg-gray-50">
                    </tr>
                </tbody>
                </table>
                <div id="pagination-container" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-4">
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <p id="pagination-info" class="text-sm text-gray-700"></p>
                        <nav id="pagination-controls" class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                            <a href="#" id="prev-page" class="relative inline-flex items-center rounded-l-md px-3 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 hidden">
                                <span class="sr-only">Previous</span>
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <div id="pagination-pages" class="flex space-x-1"></div>
                            <a href="#" id="next-page" class="relative inline-flex items-center rounded-r-md px-3 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 hidden">
                                <span class="sr-only">Next</span>
                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let editingAirlineId = 0;
        let currentPage = 1;
        let currentFilters = { cityId: '', flightCount: '' };

        async function loadCities() {
            try {
                const response = await fetch('/api/cities?page=${page}');
                const cities = await response.json();

                const select = document.getElementById('filter-city');
                select.innerHTML = '<option value="">Filter by City</option>';

                cities.data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading cities:', error);
            }
        }

        function loadAirlines(page = 1, filters = {}) {
            let query = [`page=${page}`];

            if (filters.cityId) query.push(`filter[city]=${filters.cityId}`);
            if (filters.flightCount) query.push(`filter[active_flights]=${filters.flightCount}`);

            fetch(`/api/airlines?${query.join('&')}`)
                .then(response => response.json())
                .then(({ data, pagination }) => {
                    const tbody = document.querySelector('#airlines-table tbody');
                    tbody.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(airline => {
                            tbody.innerHTML += `
                                <tr data-id="${airline.id}">
                                    <td class="py-4 pr-3 pl-4 text-sm font-medium text-gray-900 sm:pl-3">${airline.id}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">${airline.name}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">${airline.description}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">${airline.number_of_flights}</td>
                                    <td class="py-4 pl-3 text-right text-sm font-medium">
                                        <a href="#" class="edit text-indigo-600 hover:text-indigo-900" data-id="${airline.id}">Edit</a>
                                    </td>
                                    <td class="py-4 pl-3 text-right text-sm font-medium">
                                        <a href="#" class="delete text-red-600 hover:text-red-900" data-id="${airline.id}">Delete</a>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-sm text-gray-500">No airlines found.</td></tr>`;
                    }

                    updatePaginationControls(pagination, filters);
                })
                .catch(error => console.error('Error loading airlines:', error));
        }


        function updatePaginationControls(meta, filters) {
            const paginationContainer = document.getElementById('pagination-container');
            const paginationInfo = document.getElementById('pagination-info');
            const paginationPages = document.getElementById('pagination-pages');
            const prevPage = document.getElementById('prev-page');
            const nextPage = document.getElementById('next-page');

            if (!meta || !meta.totalPages) {
                paginationContainer.classList.add("hidden");
                return;
            }

            paginationContainer.classList.remove("hidden");

            const from = (meta.currentPage - 1) * meta.perPage + 1;
            const to = Math.min(meta.currentPage * meta.perPage, meta.total);
            paginationInfo.textContent = `Showing ${from} to ${to} of ${meta.total} results`;

            paginationPages.innerHTML = '';

            prevPage.classList.toggle("hidden", meta.currentPage === 1);
            prevPage.onclick = (event) => {
                event.preventDefault();
                loadAirlines(meta.currentPage - 1, filters);
            };

            for (let i = 1; i <= meta.totalPages; i++) {
                const pageLink = document.createElement('a');
                pageLink.href = "#";
                pageLink.classList.add("relative", "inline-flex", "items-center", "px-4", "py-2", "text-sm", "font-semibold");

                if (i === meta.currentPage) {
                    pageLink.classList.add("bg-indigo-600", "text-white");
                } else {
                    pageLink.classList.add("text-gray-900", "ring-1", "ring-gray-300", "ring-inset", "hover:bg-gray-50");
                    pageLink.addEventListener("click", (event) => {
                        event.preventDefault();
                        loadAirlines(i, filters);
                    });
                }

                pageLink.textContent = i;
                paginationPages.appendChild(pageLink);
            }

            nextPage.classList.toggle("hidden", meta.currentPage === meta.totalPages);
            nextPage.onclick = (event) => {
                event.preventDefault();
                loadAirlines(meta.currentPage + 1, filters);
            };
        }



        document.getElementById('apply-filters').addEventListener('click', () => {
            currentFilters = {
                cityId: document.getElementById('filter-city').value,
                flightCount: document.getElementById('filter-flights').value
            };
            loadAirlines(1, currentFilters);
        });

        function showMessage(message, type = 'success') {
            const box = $('#message-box');
            box.removeClass().addClass(`p-2 mb-4 text-center font-semibold rounded-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`);
            box.text(message).fadeIn(300);

            setTimeout(() => {
                box.fadeOut(500);
            }, 3000);
        }

        document.getElementById('add-airline-btn').addEventListener('click', async () => {
            const name = document.getElementById('new-airline-name').value.trim();
            const description = document.getElementById('new-airline-desscription').value.trim();

            if (!name || !description) {
                document.getElementById('message-box').classList.remove('hidden');
                document.getElementById('message-box').classList.add('bg-red-500');
                document.getElementById('message-box').innerText = 'Please enter airline name and description';
                return;
            }

            try {
                const response = await fetch('/api/airlines', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name, description }),
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Airline added successfully');
                    loadAirlines();
                } else {
                    alert('Failed to add airline. It may already exist.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        async function deleteAirline(id) {
            try {
                const response = await fetch(`/api/airlines/${id}`, {
                    method: 'DELETE',
                });

                if (response.ok) {
                    alert('Airline deleted successfully!');
                    loadAirlines();
                } else {
                    alert('Failed to delete airline.');
                }
            } catch (error) {
                console.error('Error deleting airline:', error);
            }
        }

        document.addEventListener('click', (event) => {
            const deleteButton = event.target.closest('.delete');
            if (deleteButton) {
                const id = deleteButton.dataset.id;
                if (id) {
                    if (confirm('Are you sure you want to delete this airline?')) {
                        deleteAirline(id);
                    }
                } else {
                    console.error('Airline ID is missing.');
                }
            }
        });

        document.body.addEventListener('click', (event) => {
            const editButton = event.target.closest('.edit');
            const saveButton = event.target.closest('.save');
            const cancelButton = event.target.closest('.cancel');

            if (editButton) {
                editingAirlineId = editButton.dataset.id;
                loadAirlines();
            }

            if (saveButton) {
                const id = saveButton.dataset.id;
                const name = document.getElementById(`edit-name-${id}`).value;
                const description = document.getElementById(`edit-description-${id}`).value;
                updateAirline(id, name, description);
            }

            if (cancelButton) {
                editingAirlineId = 0;
                loadAirlines();
            }
        });

        async function updateAirline(id, name, description) {
            try {
                const response = await fetch(`/api/airlines/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name, description })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Airline updated successfully!');
                    editingAirlineId = 0;
                    loadAirlines();
                } else {
                    alert(`Failed to update airline: ${data.message}`);
                }
            } catch (error) {
                console.error('Error updating airline:', error);
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            loadCities();
            loadAirlines();
        });
    </script>
@endpush


