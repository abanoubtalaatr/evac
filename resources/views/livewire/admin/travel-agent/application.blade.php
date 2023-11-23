<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded alig">
                <div class="col-3 form-group" wire:ignore id="travelAgentContainer">
                    <label for="status-select">@lang('admin.travel_agent')</label>

                    <div class="input-group">
                        <input
                            id="agent_search"
                            type="text"
                            class="form-control contact-input"
                            placeholder="Search Travel Agent"
                            autocomplete="off"

                        />
                        <ul class="autocomplete-results list-group position-absolute w-100" style="padding-left: 0px; margin-top: 51px; display: none;z-index: 200">
                        </ul>
                    </div>
                    @error('form.travel_agent_id')<p class="mt-2" style="color: red;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.visa_type')</label>
                    <select wire:model='visaType' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        @foreach($visaTypes as $visaType)
                            <option value="{{$visaType->id}}">{{$visaType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.status')</label>
                    <select wire:model='status' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        <option value="new">New</option>
                        <option value="appraised">Appraised</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>

                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>


                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

                @include('livewire.admin.travel-agent.popup.send-email')
                <div class="form-group col-3 mt-4">
                    <button class="btn btn-secondary form-control contact-input" wire:click="toggleShowModal">Send email</button>
                </div>
                @if(isset($message) && !empty($message) && !is_null($message))
                <div class="form-group col-3 mt-4">
                    <div class="btn btn-danger form-control contact-">You must choose travel agent </div>
                </div>
                @endif

            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" >@lang('admin.applicant')</th>
                        <th class="text-center">@lang('admin.submit_date')</th>
                        <th class="text-center">@lang('admin.visa_type')</th>

                        <th class="text-center">@lang('admin.status')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->application_ref . ' - '. $record->first_name . ' ' . $record->last_name}}</td>
                            <td class='text-center'>{{ \Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d h:m')}}</td>
                            <td class='text-center'><button class="border-0">{{$record->visaType->name}}</button></td>
                            <td class='text-center'><button class="border-0">{{$record->status}}</button></td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>

                {{$records->links()}}
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>
    </div>
</main>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationInvoiceModal', function (application) {
            $('#showApplicationInvoiceModal' + application).modal('show');
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationModal', function (applicationId) {
            $('#applicationModal' + applicationId).modal('show');
        });
    });
</script>

<script>
    // Function to load content into an iframe and trigger printing
    function printPage(url) {
        // Create an iframe element
        var iframe = document.createElement('iframe');

        // Set the source URL of the iframe
        iframe.src = url;

        // Set styles to hide the iframe
        iframe.style.position = 'absolute';
        iframe.style.top = '-9999px';
        iframe.style.left = '-9999px';
        iframe.style.width = '0';
        iframe.style.height = '0';

        // Append the iframe to the document body
        document.body.appendChild(iframe);

        // Wait for the iframe to load
        iframe.onload = function() {
            // Print the content of the iframe
            iframe.contentWindow.print();
        };
    }

    document.addEventListener('livewire:load', function () {
    Livewire.on('show-message', function () {
        var errorMessage = document.getElementById('error-message');

        // Show the message
        errorMessage.style.display = 'block';

        // Hide the message after 1000 milliseconds (1 second)
        setTimeout(function () {
            errorMessage.style.display = 'none';
        }, 1000);
    });
    });
</script>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#agent_search').on('input', function () {
                var query = $(this).val();
                if (query.length >= 0) {
                    // Make an AJAX request to get search results
                    $.ajax({
                        url: '/api/agents/search', // Replace with your actual endpoint
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            // Update the search results dynamically
                            var resultsContainer = $('.autocomplete-results');
                            resultsContainer.empty();

                            if (data.length > 0) {
                                resultsContainer.show();
                                data.forEach(function (result) {
                                    resultsContainer.append('<li class="list-group-item border-top-0 rounded-0" data-id="' + result.id + '">' + result.name + '</li>')
                                        .css('cursor', 'pointer');
                                });
                            } else {
                                resultsContainer.hide();
                            }
                        }
                    });
                } else {
                    // Hide the results if the search query is less than 2 characters
                    $('.autocomplete-results').hide();
                }
            });

            // Handle click on search result
            $('.autocomplete-results').on('click', 'li', function () {
                var selectedName = $(this).text();
                $('#agent_search').val(selectedName); // Set the selected result in the input field

                var travelAgentId = $(this).data('id');
                // Perform the necessary action with the selected travel agent ID
                // e.g., update a hidden input field or trigger a Livewire method
            @this.set('agent_id', travelAgentId)
                // Hide the results container after selecting
                $('.autocomplete-results').hide();
            });

            // Hide results when clicking outside the input and results container
            $(document).on('click', function (event) {
                if (!$(event.target).closest('.input-group').length) {
                    $('.autocomplete-results').hide();
                }
            });

            // Watch for changes to the checkbox state
            $('input[type="checkbox"]').on('change', function () {
                if (!$(this).is(':checked')) {
                    // If the checkbox is unchecked, set form.agent_id to null
                @this.set('agent_id', null);
                @this.set('message', null);

                }
            });

            // Watch for changes to the input search value
            $('#agent_search').on('change', function () {
                if ($(this).val() === '') {
                    // If the search input is empty, set form.agent_id to null
                @this.set('agent_id', null);
                @this.set('message', null);

                }
            });
        });
    </script>
@endpush


