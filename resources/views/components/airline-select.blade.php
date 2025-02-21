<div class="relative">
    <label id="listbox-label" class="block text-sm/6 font-medium text-gray-900">Select Airline</label>

    <button id="airline-dropdown-btn" type="button" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 border border-gray-300  focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm/6">
        <span id="selected-airline" class="col-start-1 row-start-1 truncate pr-6">Airlines</span>
        <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <ul id="airline-list" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base ring-1 shadow-lg ring-black/5 hidden sm:text-sm">
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        loadAirlines();

        const dropdownBtn = document.getElementById("airline-dropdown-btn");
        const dropdownList = document.getElementById("airline-list");
        const selectedAirline = document.getElementById("selected-airline");

        dropdownBtn.addEventListener("click", function () {
            dropdownList.classList.toggle("hidden");
        });

        dropdownList.addEventListener("click", function (event) {
            if (event.target.tagName === "LI") {
                selectedAirline.textContent = event.target.textContent;
                selectedAirline.dataset.id = event.target.dataset.id;
                dropdownList.classList.add("hidden");

                document.dispatchEvent(new CustomEvent("airline-selected", {
                    detail: event.target.dataset.id
                }));
            }
        });


        document.addEventListener("click", function (event) {
            if (!dropdownBtn.contains(event.target) && !dropdownList.contains(event.target)) {
                dropdownList.classList.add("hidden");
            }
        });
    });

    async function loadAirlines() {
        try {
            const response = await axios.get('/api/airlines');
            const airlines = response.data.data;
            console.log(airlines);
            const dropdownList = document.getElementById("airline-list");

            dropdownList.innerHTML = "";
            airlines.forEach(airline => {
                dropdownList.innerHTML += `<li class="cursor-pointer px-3 py-2 hover:bg-gray-100" data-id="${airline.id}">${airline.name}</li>`;
            });
        } catch (error) {
            console.error("Error loading airlines:", error);
        }
    }
</script>
