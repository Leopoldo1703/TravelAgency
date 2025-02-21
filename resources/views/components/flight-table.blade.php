<div class="px-4 sm:px-6 lg:px-8 mt-10">
    <table id="flights-table" class="min-w-full divide-y divide-gray-300">
    <thead>
        <tr>
        <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-3">ID</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Airline</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Origin</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Destination</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Departure</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Arrival</th>
        <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-3">
            <span class="sr-only">Edit</span>
        </th>
        <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-3">
            <span class="sr-only">Delete</span>
        </th>
        </tr>
    </thead>
    <tbody id="flights-table-body" class="bg-white">
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

<script>
    let editingFlightId = 0;

    document.addEventListener("DOMContentLoaded", function () {
        loadFlights();
    });

    async function loadFlights(page = 1) {
        try {
            const response = await axios.get(`/api/flights?page=${page}`);
            const flights = response.data.data;
            const paginationMeta = response.data.pagination;
            const tbody = document.querySelector("#flights-table-body");

            tbody.innerHTML = "";

            flights.forEach(flight => {
                if (editingFlightId == flight.id) {
                    tbody.innerHTML += `
                        <tr data-id="${flight.id}">
                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${flight.id}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.airline_name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.origin_name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.destination_name}</td>
                            <td class="px-3 py-4 text-sm">
                                <input type="datetime-local" id="edit-departure-${flight.id}" value="${flight.departure}" class="rounded-md border border-gray-300 p-1 w-full">
                            </td>
                            <td class="px-3 py-4 text-sm">
                                <input type="datetime-local" id="edit-arrival-${flight.id}" value="${flight.arrival}" class="rounded-md border border-gray-300 p-1 w-full">
                            </td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <button class="save text-green-600 hover:text-green-900 mr-2" data-id="${flight.id}">Save</button>
                                <button class="cancel text-gray-600 hover:text-gray-900 ml-2" data-id="${flight.id}">Cancel</button>
                            </td>
                        </tr>`;
                } else {
                    tbody.innerHTML += `
                        <tr data-id="${flight.id}">
                            <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-3">${flight.id}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.airline_name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.origin_name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.destination_name}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.departure}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500">${flight.arrival}</td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <a href="#" class="edit text-indigo-600 hover:text-indigo-900" data-id="${flight.id}">Edit</a>
                            </td>
                            <td class="relative py-4 pl-3 text-right text-sm font-medium">
                                <a href="#" class="delete text-red-600 hover:text-red-900" data-id="${flight.id}">Delete</a>
                            </td>
                        </tr>
                    `;
                }
            });

            updatePaginationControls(paginationMeta);

        } catch (error) {
            console.error("Error loading flights:", error);
        }
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
            loadFlights(meta.currentPage - 1);
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
                    loadFlights(i);
                });
            }

            pageLink.textContent = i;
            paginationPages.appendChild(pageLink);
        }

        nextPage.classList.toggle("hidden", meta.currentPage === meta.totalPages);
        nextPage.onclick = (event) => {
            event.preventDefault();
            loadFlights(meta.currentPage + 1);
        };
    }



    async function deleteFlight(id) {
        if (!confirm("Are you sure you want to delete this flight?")) return;

        try {
            await axios.delete(`/api/flights/${id}`);
            loadFlights();
        } catch (error) {
            console.error("Error deleting flight:", error);
        }
    }

    async function updateFlight(id, departure, arrival) {
        try {
            await axios.put(`/api/flights/${id}`, { departure, arrival });
            editingFlightId = 0;
            loadFlights();
        } catch (error) {
            console.error("Error updating flight:", error);
        }
    }

    document.body.addEventListener("click", (event) => {
        const editButton = event.target.closest(".edit");
        const saveButton = event.target.closest(".save");
        const cancelButton = event.target.closest(".cancel");
        const deleteButton = event.target.closest(".delete");

        if (editButton) {
            editingFlightId = editButton.dataset.id;
            console.log(editingFlightId);
            loadFlights();
        }

        if (saveButton) {
            const id = saveButton.dataset.id;
            const departure = document.getElementById(`edit-departure-${id}`).value;
            const arrival = document.getElementById(`edit-arrival-${id}`).value;
            updateFlight(id, departure, arrival);
        }

        if (cancelButton) {
            editingFlightId = 0;
            loadFlights();
        }

        if (deleteButton) {
            const id = deleteButton.dataset.id;
            deleteFlight(id);
        }
    });
</script>