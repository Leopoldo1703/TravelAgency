<div class="relative mt-4">
    <label id="origin-listbox-label" class="block text-sm/6 font-medium text-gray-900">Select Origin City</label>

    <button id="origin-dropdown-btn" type="button" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 border border-gray-300  focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm/6">
        <span id="selected-origin" class="col-start-1 row-start-1 truncate pr-6">Select Airline First</span>
        <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <ul id="origin-list" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base ring-1 shadow-lg ring-black/5 hidden sm:text-sm">
    </ul>
</div>

<div class="relative mt-4">
    <label id="destination-listbox-label" class="block text-sm/6 font-medium text-gray-900">Select Destination City</label>

    <button id="destination-dropdown-btn" type="button" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 border border-gray-300  focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm/6">
        <span id="selected-destination" class="col-start-1 row-start-1 truncate pr-6">Select Origin First</span>
        <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <ul id="destination-list" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base ring-1 shadow-lg ring-black/5 hidden sm:text-sm">
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const originDropdownBtn = document.getElementById("origin-dropdown-btn");
        const originDropdownList = document.getElementById("origin-list");
        const selectedOrigin = document.getElementById("selected-origin");

        const destinationDropdownBtn = document.getElementById("destination-dropdown-btn");
        const destinationDropdownList = document.getElementById("destination-list");
        const selectedDestination = document.getElementById("selected-destination");

        let cities = [];

        originDropdownBtn.addEventListener("click", function () {
            if (originDropdownList.children.length > 0) {
                originDropdownList.classList.toggle("hidden");
            }
        });

        destinationDropdownBtn.addEventListener("click", function () {
            if (destinationDropdownList.children.length > 0) {
                destinationDropdownList.classList.toggle("hidden");
            }
        });

        originDropdownList.addEventListener("click", function (event) {
            if (event.target.tagName === "LI") {
                selectedOrigin.textContent = event.target.textContent;
                selectedOrigin.dataset.id = event.target.dataset.id;
                originDropdownList.classList.add("hidden");

                loadDestinationCities(event.target.dataset.id);

                document.dispatchEvent(new CustomEvent("origin-selected", {
                    detail: event.target.dataset.id
                }));
            }
        });

        destinationDropdownList.addEventListener("click", function (event) {
            if (event.target.tagName === "LI") {
                selectedDestination.textContent = event.target.textContent;
                selectedDestination.dataset.id = event.target.dataset.id;
                destinationDropdownList.classList.add("hidden");

                document.dispatchEvent(new CustomEvent("destination-selected", {
                    detail: event.target.dataset.id
                }));
            }
        });

        document.addEventListener("click", function (event) {
            if (!originDropdownBtn.contains(event.target) && !originDropdownList.contains(event.target)) {
                originDropdownList.classList.add("hidden");
            }
            if (!destinationDropdownBtn.contains(event.target) && !destinationDropdownList.contains(event.target)) {
                destinationDropdownList.classList.add("hidden");
            }
        });

        document.addEventListener("airline-selected", function (event) {
            loadCities(event.detail);
        });

        async function loadCities(airlineId) {
            try {
                const response = await axios.get(`/api/airlines/${airlineId}/cities`);
                cities = response.data.data || [];

                originDropdownList.innerHTML = "";
                cities.forEach(city => {
                    originDropdownList.innerHTML += `<li class="cursor-pointer px-3 py-2 hover:bg-gray-100" data-id="${city.id}">${city.name}</li>`;
                });

                selectedOrigin.textContent = "Select Origin City";
                selectedDestination.textContent = "Select Origin First";
            } catch (error) {
                console.error("Error loading cities:", error);
            }
        }

        function loadDestinationCities(excludeCityId) {
            console.log(excludeCityId);
            destinationDropdownList.innerHTML = "";
            cities.forEach(city => {
                if (city.id != excludeCityId) {
                    destinationDropdownList.innerHTML += `<li class="cursor-pointer px-3 py-2 hover:bg-gray-100" data-id="${city.id}">${city.name}</li>`;
                }
            });

            selectedDestination.textContent = "Select Destination City";
        }
    });
</script>
