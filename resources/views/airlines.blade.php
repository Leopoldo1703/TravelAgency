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
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let editingAirlineId = 0;

        async function loadCities() {
            try {
                const response = await fetch('/api/cities');
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

        async function loadAirlines(cityId = '', flightCount = '') {
            try {
                let query = [];

                if (cityId) query.push(`filter[city]=${cityId}`);
                if (flightCount) query.push(`filter[active_flights]=${flightCount}`);

                const queryString = query.length ? `?${query.join('&')}` : '';

                const response = await fetch(`/api/airlines${queryString}`);
                const airlines = await response.json();

                const tbody = document.querySelector('#airlines-table tbody');
                tbody.innerHTML = '';

                console.log(airlines.data);

                airlines.data.forEach(airline => {
                    if (editingAirlineId == airline.id){
                        tbody.innerHTML += `
                        <tr data-id="${airline.id}">
                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${airline.id}</td>
                            <td class="px-3 py-4 text-sm">
                                <input type="text" id="edit-name-${airline.id}" value="${airline.name}" class="rounded-md border border-gray-300 p-1 w-full">
                            </td>
                            <td class="px-3 py-4 text-sm">
                                <input type="text" id="edit-description-${airline.id}" value="${airline.description}" class="rounded-md border border-gray-300 p-1 w-full">
                            </td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <button class="save text-green-600 hover:text-green-900 mr-2" data-id="${airline.id}">Save</button>
                                <button class="cancel text-gray-600 hover:text-gray-900 ml-2" data-id="${airline.id}">Cancel</button>
                            </td>
                        </tr>`;
                    }
                    else{
                    tbody.innerHTML += `
                        <tr data-id="${airline.id}">
                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${airline.id}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${airline.name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${airline.description}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${airline.number_of_flights}</td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <a href="#" class="edit text-indigo-600 hover:text-indigo-900" data-id="${airline.id}" data-name="${airline.name}">Edit</a>
                            </td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <a href="#" class="delete text-red-600 hover:text-red-900" data-id="${airline.id}">Delete</a>
                            </td>
                        </tr>`;
                    }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        document.getElementById('apply-filters').addEventListener('click', () => {
            const cityId = document.getElementById('filter-city').value;
            const flightCount = document.getElementById('filter-flights').value;
            loadAirlines(cityId, flightCount);
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


