
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
    document.addEventListener('keydown', function(event) {
        var inputs = document.querySelectorAll('.input-container input, .input-container select');
        var focusedInput = document.activeElement; // Get the currently focused input element

        // Find the index of the focused input
        var focusedIndex = Array.prototype.indexOf.call(inputs, focusedInput);

        // Move forward or backward based on the arrow key pressed
        switch (event.key) {
            case 'ArrowRight':
                focusedIndex = (focusedIndex + 1) % inputs.length; // Move forward
                break;
            case 'ArrowLeft':
                focusedIndex = (focusedIndex - 1 + inputs.length) % inputs.length; // Move backward
                break;
            case 'ArrowDown':
                if (focusedInput.id === 'agent_search') {
                    var results = document.querySelectorAll('.autocomplete-results li');
                    var activeIndex = Array.prototype.findIndex.call(results, function(result) {
                        return result.classList.contains('active');
                    });

                    if (activeIndex !== -1) {
                        results[activeIndex].classList.remove('active');
                        activeIndex = (activeIndex + 1) % results.length; // Move to the next result
                    } else {
                        activeIndex = 0; // Set the first result as active
                    }

                    results[activeIndex].classList.add('active');
                } else if (focusedInput.tagName === 'SELECT') {
                    focusedInput.size = focusedInput.length; // Open the select and display all options
                }
                return; // Exit to prevent further navigation
            case 'ArrowUp':
                if (focusedInput.id === 'agent_search') {
                    var results = document.querySelectorAll('.autocomplete-results li');
                    var activeIndex = Array.prototype.findIndex.call(results, function(result) {
                        return result.classList.contains('active');
                    });

                    if (activeIndex !== -1) {
                        results[activeIndex].classList.remove('active');
                        activeIndex = (activeIndex - 1 + results.length) % results.length; // Move to the previous result
                    } else {
                        activeIndex = results.length - 1; // Set the last result as active
                    }

                    results[activeIndex].classList.add('active');
                } else if (focusedInput.tagName === 'SELECT') {
                    focusedInput.size = 1; // Close the select and display a single option
                }
                return; // Exit to prevent further navigation
            default:
                return; // Exit if it's not an arrow key
        }

        // Save and restore the selected option when moving between inputs
        var selectedOption = focusedInput.value;

        // Focus on the new input element
        inputs[focusedIndex].focus();

        // Restore the selected option for select element
        if (inputs[focusedIndex].tagName === 'SELECT') {
            inputs[focusedIndex].value = selectedOption;
        }
    });

    document.addEventListener('click', function(event) {
        var inputContainer = document.querySelector('.input-container');
        var clickedElement = event.target;

        if (!inputContainer.contains(clickedElement)) {
            var selectElement = document.querySelector('.custom-select');
            selectElement.size = 1; // Close the select and display a single option
        }
    });
</script>


