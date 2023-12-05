<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        var previousQuery = ''; // Variable to store the previous search query

        $('#agent_search').on('input', function () {
            var query = $(this).val();

            // Check if the previous query was not empty and the current query is empty
            if (previousQuery.trim() !== '' && query.trim() === '') {
                // Update Livewire property to "no_result"
            @this.set('agent', 'no_result');
            }

            if (query.length >= 0) {
                // Make an AJAX request to get search results
                $.ajax({
                    url: '/admin/agents/search', // Replace with your actual endpoint
                    method: 'GET',
                    data: { query: query },
                    success: function (data) {
                        // Update the search results dynamically
                        var resultsContainer = $('.autocomplete-results');
                        resultsContainer.empty();

                        if (data.length > 0) {
                            resultsContainer.show();
                            data.forEach(function (result) {
                                resultsContainer.append('<li class="list-group-item border-top-0 rounded-0" data-id="' + result.id + '">' + result.name + '</li>').css('cursor', 'pointer');
                            });
                        } else {
                            // Update Livewire property to "no_result" when search results are empty
                        @this.set('agent', query.trim() === '' ? 'no_result' : '');
                            resultsContainer.append('<li class="list-group-item border-top-0 rounded-0" data-id="nr">No results found</li>');
                            resultsContainer.show();
                        }
                    }
                });
            } else {
                // Hide the results if the search query is empty or less than 2 characters
                $('.autocomplete-results').hide();

                // Update Livewire property to "no_result" when search input is empty
            @this.set('agent', 'no_result');
            }

            // Save the current query for the next input event
            previousQuery = query;
        });

        // Handle click on search result
        $('.autocomplete-results').on('click', 'li', function () {
            var selectedName = $(this).text();
            $('#agent_search').val(selectedName);  // Set the selected result in the input field

            var travelAgentId = $(this).data('id');

            // Perform the necessary action with the selected travel agent ID
            // e.g., update a hidden input field or trigger a Livewire method
        @this.set('agent', travelAgentId);

            // Hide the results container after selecting
            $('.autocomplete-results').hide();
        });

        // Hide results when clicking outside the input and results container
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.input-group').length) {
                $('.autocomplete-results').hide();
            }
        });
    });
</script>
