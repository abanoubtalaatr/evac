<script>
    $(document).ready(function () {
        // Capture the keypress event on the document
        $(document).keypress(function (e) {
            // Check if the pressed key is Enter (key code 13)
            if (e.which === 13) {
                // Trigger the click event on the button with the specified id
                $("#searchButton").click();
            }
        });
    });
</script>

<script>
    // Add event listener for keydown event
    document.addEventListener('keydown', function(event) {
        // Check if Tab key is pressed
        if (event.keyCode === 9) {
            // Get all focusable elements within the input container
            var focusableElements = document.querySelectorAll('.input-container input, .input-container select, .input-container textarea');
            // Get the current active element
            var activeElement = document.activeElement;
            // Get the index of the active element in the focusable elements array
            var index = Array.prototype.indexOf.call(focusableElements, activeElement);
            // If the current active element is the last element, focus on the first element
            if (index === focusableElements.length - 1) {
                focusableElements[0].focus();
                event.preventDefault();
            }
        }
    });

    // Function to toggle the visibility of the travel agent container
    function toggleShowTravelAgent() {
        var travelAgentContainer = document.getElementById('travelAgentContainer');
        var showTravelAgentCheckbox = document.getElementById('showTravelAgent');
        travelAgentContainer.classList.toggle('hidden');
        // Update the checkbox state based on the visibility of the container
        showTravelAgentCheckbox.checked = !travelAgentContainer.classList.contains('hidden');
    }
</script>
