<div class="mt-6">
    <x-airline-select />
    <x-city-select />

    <div class="relative mt-4">
        <label for="departure" class="block text-sm font-medium text-gray-900">Departure</label>
        <div class="mt-2">
            <input type="datetime-local" name="departure" id="departure"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 
                       focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <div class="relative mt-4">
        <label for="arrival" class="block text-sm font-medium text-gray-900">Arrival</label>
        <div class="mt-2">
            <input type="datetime-local" name="arrival" id="arrival"
                class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 
                       focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <p id="error-message" class="mt-2 text-sm text-red-600 hidden"></p>

    <button id="create-flight"
        class="relative mt-6 block w-full rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs 
               hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Create Flight
    </button>
    <div id="success-message" class="relative mt-4 hidden rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Flight created successfully!</p>
            </div>
            <div class="ml-auto pl-3">
                <button id="dismiss-success" type="button"
                    class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                    <span class="sr-only">Dismiss</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const departureInput = document.getElementById("departure");
        const arrivalInput = document.getElementById("arrival");
        const errorMessage = document.getElementById("error-message");

        function getCurrentDateTime() {
            const now = new Date();
            return now.toISOString().slice(0, 16);
        }

        function updateMinMaxDates() {
            const departureTime = new Date(departureInput.value);
            const arrivalTime = new Date(arrivalInput.value);

            departureInput.min = getCurrentDateTime();

            if (departureInput.value) {
                arrivalInput.min = departureInput.value;
            }

            if (arrivalInput.value) {
                departureInput.max = arrivalInput.value;
            } else {
                departureInput.removeAttribute("max");
            }

            if (departureInput.value && arrivalTime <= departureTime) {
                arrivalInput.value = "";
                errorMessage.textContent = "Arrival must be after departure.";
                errorMessage.classList.remove("hidden");
            } else if (arrivalInput.value && departureTime >= arrivalTime) {
                departureInput.value = "";
                errorMessage.textContent = "Departure must be before arrival.";
                errorMessage.classList.remove("hidden");
            } else {
                errorMessage.classList.add("hidden");
            }
        }

        departureInput.addEventListener("input", updateMinMaxDates);
        arrivalInput.addEventListener("input", updateMinMaxDates);

        document.getElementById("create-flight").addEventListener("click", createFlight);
    });

    function formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toISOString().slice(0, 16).replace("T", " ");
    }

    async function createFlight() {
        const airlineId = document.getElementById("selected-airline").dataset.id;
        const originId = document.getElementById("selected-origin").dataset.id;
        const destinationId = document.getElementById("selected-destination").dataset.id;
        const departure = document.getElementById("departure").value;
        const arrival = document.getElementById("arrival").value;
        const successMessage = document.getElementById("success-message");

        if (!airlineId || !originId || !destinationId || !departure || !arrival) {
            showErrorMessage("Please fill in all fields.");
            return;
        }

        const formattedDeparture = formatDateTime(departure);
        const formattedArrival = formatDateTime(arrival);

        try {
            const response = await axios.post("/api/flights", {
                airline_id: airlineId,
                origin_id: originId,
                destination_id: destinationId,
                departure: formattedDeparture,
                arrival: formattedArrival
            });

            successMessage.classList.remove("hidden");
        } catch (error) {
            console.error("Error creating flight:", error);
        }
    }

    function clearFormFields() {
        document.getElementById("selected-airline").textContent = "Select Airline";
        document.getElementById("selected-origin").textContent = "Select Origin City";
        document.getElementById("selected-destination").textContent = "Select Destination City";

        document.getElementById("selected-airline").dataset.id = "";
        document.getElementById("selected-origin").dataset.id = "";
        document.getElementById("selected-destination").dataset.id = "";

        document.getElementById("departure").value = "";
        document.getElementById("arrival").value = "";
    }

    document.getElementById("dismiss-success").addEventListener("click", function () {
        document.getElementById("success-message").classList.add("hidden");
    });

</script>
