@extends('components.layout')

@section('title', 'Manage Cities')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Cities</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all the cities available as travel origins or destinations.</p>
            </div>
        </div>
        <div class="flex flex-col space-y-2 mt-6">
            <div class="flex space-x-2">
                <input type="text" id="new-city-name" class="rounded-md border-2 border-gray-400 text-sm p-2 w-64" placeholder="Enter city name">
                <button id="add-city-btn" type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add city</button>
            </div>
            <div id="message-box" class="hidden w-64 p-2 text-center text-white text-sm font-semibold rounded-md"></div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="flex space-x-4 mb-4">
                <select id="filter-airline" class="rounded-md border-2 border-gray-400 text-sm">
                    <option value="">Filter by Airline</option>
                </select>
                <button id="apply-filters" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Apply</button>
            </div>
                <table id="cities-table" class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                    <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-3 cursor-pointer items-center gap-1" id="sort-id">ID<span class="sort-icon">⇅</span></th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 cursor-pointer items-center gap-1" id="sort-name">Name<span class="sort-icon">⇅</span></th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Number of incoming flights</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Number of outgoing flights</th>
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
    </div>
@endsection

@push('scripts')
    <script>
        let sortDirection = 'asc';
        let currentSort = 'id';
        let editingCityId = 0;

        function toggleSort(column) {
            if (currentSort === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = column;
                sortDirection = 'asc';
            }
            loadCities();
        }

        function loadAirlines(){
            $.ajax({
                url: `api/airlines`,
                method: 'GET',
                dataType: 'json',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    const { data } = response;
                    const select = $('#filter-airline');
                    select.empty();
                    select.append('<option value="">Filter by Airline</option>');

                    data.forEach(airline => {
                        select.append(`<option value="${airline.id}">${airline.name}</option>`);
                    });
                },
                error: function() {
                    alert('Error loading airlines');
                }
            })
        }

        function loadCities(page = 1) {
            const airline = $('#filter-airline').val();
            $.ajax({
                url: `api/cities`,
                method: 'GET',
                data: {
                    'page': page,
                    'filter[airline]': airline,
                    'sort': sortDirection === 'asc' ? currentSort : `-${currentSort}`,

                },
                dataType: 'json',
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    const { data, pagination } = response;
                    const tbody = $('#cities-table tbody');
                    tbody.empty();

                    if (data && data.length > 0) {
                        data.forEach(city => {
                            if (editingCityId === city.id) {
                                tbody.append(`
                                    <tr data-id="${city.id}">
                                        <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${city.id}</td>
                                        <td class="px-3 py-4 text-sm">
                                            <input type="text" id="edit-name-${city.id}" value="${city.name}" class="rounded-md border border-gray-300 p-1 w-full">
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${city.number_of_arrivals}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${city.number_of_departures}</td>
                                        <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                            <button class="save text-green-600 hover:text-green-900 mr-2" data-id="${city.id}">Save</button>
                                            <button class="cancel text-gray-600 hover:text-gray-900 ml-2" data-id="${city.id}">Cancel</button>
                                        </td>
                                    </tr>
                                `);
                            } else {
                                tbody.append(`
                                    <tr data-id="${city.id}">
                                        <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${city.id}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${city.name}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${city.number_of_arrivals}</td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${city.number_of_departures}</td>
                                        <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                            <a href="#" class="edit text-indigo-600 hover:text-indigo-900" data-id="${city.id}" data-name="${city.name}">Edit</a>
                                        </td>
                                        <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                            <a href="#" class="delete text-red-600 hover:text-red-900" data-id="${city.id}">Delete</a>
                                        </td>
                                    </tr>
                                `);
                            }
                        });
                    } else {
                        tbody.append('<tr><td colspan="5">No cities found.</td></tr>');
                    }
                    updatePaginationControls(pagination);
                },
                error: function(xhr) {
                    console.error("Error en la llamada AJAX:", xhr);
                }
            });
        }

        function updatePaginationControls(meta) {
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
                loadCities(meta.currentPage - 1);
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
                        loadCities(i);
                    });
                }

                pageLink.textContent = i;
                paginationPages.appendChild(pageLink);
            }

            nextPage.classList.toggle("hidden", meta.currentPage === meta.totalPages);
            nextPage.onclick = (event) => {
                event.preventDefault();
                loadCities(meta.currentPage + 1);
            };
        }


        function showMessage(message, type = 'success') {
            const box = $('#message-box');
            box.removeClass().addClass(`p-2 mb-4 text-center font-semibold rounded-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`);
            box.text(message).fadeIn(300);

            setTimeout(() => {
                box.fadeOut(500);
            }, 3000);
        }

        $(document).on('click', '.edit', function() {
            const cityId = $(this).data('id');
            editingCityId = cityId;
            loadCities();
        });

        $(document).on('click', '.cancel', function() {
            editingCityId = 0;
            loadCities();
        });

        $(document).on('click', '.save', function() {
            const cityId = $(this).data('id');
            const newName = $(`#edit-name-${cityId}`).val();

            $.ajax({
                url: `/api/cities/${cityId}`,
                method: 'PATCH',
                data: {
                    name: newName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    editingCityId = 0;
                    loadCities();
                    alert('City updated successfully!');
                },
                error: function(xhr) {
                    console.error("Error updating city:", xhr);
                    alert('Failed to update city.');
                }
            });
        });


        $(document).on('click', '.delete', function() {
            const cityId = $(this).data('id');

            if (confirm("Are you sure you want to delete this city?")) {
                $.ajax({
                    url: `/api/cities/${cityId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        loadCities();
                        alert("City deleted successfully!");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting city:", error);
                        alert("Failed to delete city!");
                    }
                });
            }
        });

        $('#add-city-btn').on('click', function() {
            const cityName = $('#new-city-name').val().trim();

            if (!cityName) {
                alert('Please enter a valid city name.');
                return;
            }

            $.ajax({
                url: '/api/cities',
                method: 'POST',
                data: {
                    name: cityName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#new-city-name').val('');
                    loadCities();
                    showMessage('City added successfully!', 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Error adding city:', error);
                    showMessage('Failed to add city!', 'error');
                }
            });
        });

        $('#apply-filters').on('click', function() {
            loadCities();
        });

        $('#sort-name').on('click', function() {
            toggleSort('name');
        });

        $('#sort-id').on('click', function() {
            toggleSort('id');
        });

        $(document).ready(function() {
            loadAirlines();
            loadCities();
        });
    </script>
@endpush
