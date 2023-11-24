
<main class="main-content">
    <x-admin.head/>
    @include('livewire.admin.application.popup.blackListPassport')
    @include('livewire.admin.application.popup.expiryPassport')
    @include('livewire.admin.application.popup.passportHasMoreThanOne')
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">
            @if($showPrint)
            <div class="col-12 text-center my-2">
                <button class="btn btn-success mt-2"> Application created successfully you can print </button>     <button class="btn btn-primary mt-2" onclick="printPage('{{route('admin.applications.print', ['application' => $record->id])}}')">Print</button>
            </div>
        @endif
            <div class="col-6">
                <div class="form-group my-2 ">
                    <label class="" for="visaType">Visa Type:</label>
                    <select wire:model="form.visa_type_id" class="form-control" id="visaType">
{{--                        <option value="">Select Visa Type</option>--}}

                        @foreach ($visaTypes as $visaType)
                            <option value="{{ $visaType->id }}">{{ $visaType->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('form.visa_type_id')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6 mt-3">
                <input type="checkbox" onclick="toggleShowTravelAgent()"> Show Travel Agent

                <div class="col-12 form-group my-2 hidden" wire:ignore  id="travelAgentContainer">
                    <div class="input-group">
                        <input
                            id="agent_search"
                            type="text"
                            class="form-control contact-input"
                            placeholder="Search Travel Agent"
                            autocomplete="off"

                        />
                        <ul class="autocomplete-results list-group position-absolute w-100" style="padding-left: 0px; margin-top: 51px;z-index: 200; display: none;">
                        </ul>
                    </div>
                    @error('form.travel_agent_id')<p class="mt-2" style="color: red;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="col-6">
                <div class="form-group my-2">
                    <label for="visaProvider">Visa Provider:</label>
                    <div>
                        @foreach ($visaProviders as $visaProvider)
                            <label>
                                <input type="radio" wire:model="form.visa_provider_id" value="{{ $visaProvider->id }}" id="visaProvider{{ $visaProvider->id }}">
                                {{ $visaProvider->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('form.visa_provider_id')<p style="color: red;">{{ $message }}</p>@enderror
                </div>
            </div>


            <script>
                function toggleShowTravelAgent() {
                    var container = document.getElementById('travelAgentContainer');
                    container.classList.toggle('hidden');
                }
            </script>


            <div class="col-6">
                <label for="passport_no" class="">Passport no:</label>
                <input type="text" class="form-control" wire:model="form.passport_no" >
                @error('form.passport_no')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

{{--            <div class="col-6 my-2">--}}
{{--                <div class="form-group my-2 ">--}}
{{--                    <label for="title">Title:</label>--}}
{{--                    <select wire:model="form.title" class="form-control" id="title">--}}
{{--                        <option value="">Select Title</option>--}}
{{--                        <option value="Mr">Mr.</option>--}}
{{--                        <option value="Mrs">Mrs.</option>--}}
{{--                        <option value="Ms">Ms.</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                @error('form.title')<p style="color: red;">{{ $message }}</p>@enderror--}}
{{--            </div>--}}


                <div class="col-6">
                    <div class="form-group my-2">
                        <label for="first_name" class="">First name:</label>
                        <input type="text" wire:model="form.first_name"  class="form-control">
                        @error('form.first_name')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group my-2">
                        <label for="last_name">Last name:</label>
                        <input type="text" wire:model="form.last_name" class="form-control">
                        @error('form.last_name')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>
                </div>

            <div class="col-6">
                <div class="form-group ">
                    <label for="expiry_date"> Expiry date:</label>
                    <div>
                        <input class="form-control" type="date" wire:model="form.expiry_date">
                    </div>
                </div>
                @error('form.expiry_date')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

            <div class="col-6">
                <label for="notes" class="">Note:</label>
                <textarea class="form-control" wire:model="form.notes"></textarea>
                @error('form.notes')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6 my-2 d-flex gap-5">
                <label>Payment method:</label>
                <div class="">
                    <input class="form-check-input" type="radio" id="invoice" wire:model="form.payment_method" checked value="invoice">
                    <label class="form-check-label" for="invoice">Invoice</label>
                </div>

                <div class="">
                    <input class="form-check-input" type="radio" id="cash" wire:model="form.payment_method" value="cash">
                    <label class="form-check-label" for="cash">Cash</label>
                </div>

                @error('form.payment_method') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-6" wire:ignore>
                <label for="amount" class="">Amount:</label>
                <div class="input-group">
                    <input class="form-control" disabled wire:model="form.amount" id="amount">
                    <div class="input-group-append">
                    <span class="input-group-text bg-warning" id="editIcon" style="cursor: pointer; height: 38px;" onclick="enableInput()">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                    </div>
                </div>
                @error('form.amount')<p style="color: red;">{{ $message }}</p>@enderror
            </div>



            <div class="col-12 text-center my-2">
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
            <hr>



        </div>
    </div>
</main>

<script>
    document.addEventListener('livewire:load', function () {
      Livewire.on('closePopups', function () {
          $('#expiryPassportModal').modal('hide');
          $('#passportHasMoreThanOneModal').modal('hide');
          $('#blackListModal').modal('hide');
      });
  });
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
                $('#agent_search').val(selectedName);  // Set the selected result in the input field

                var travelAgentId = $(this).data('id');
                // Perform the necessary action with the selected travel agent ID
                // e.g., update a hidden input field or trigger a Livewire method
                @this.set('form.agent_id', travelAgentId)
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
@endpush


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        document.addEventListener('livewire:load', function () {
          Livewire.on('agentSelected', (agentId, agentName) => {
              // Populate the input field with the selected agent's name
              document.getElementById('agent_search').value = agentName;


              // Close the results dropdown
              document.querySelector('.autocomplete-results').innerHTML = '';
          });
      });
      window.addEventListener('onContentChanged', () => {
          $('#agent_id').select2();
      });

      $(document).ready(()=>{
         $('#agent_id').select2();
          $('#agent_id').change(e=>{
              @this.set('form.agent_id', $('#agent_id').select2('val'));
          });

      });

      Livewire.on('openBlackListModal', function () {
          $('#blackListModal').modal('show');
      });

      Livewire.on('showExpiryPopup', function () {
        $('#expiryPassportModal').modal('show');
      });

      Livewire.on('showMultipleApplicationsPopup', function () {
        $('#passportHasMoreThanOneModal').modal('show');
      });
      function enableInput() {
          document.getElementById('amount').removeAttribute('disabled');
      }
      function toggleShowTravelAgent() {
        var container = document.getElementById('travelAgentContainer');
        container.classList.toggle('hidden');
    }
    </script>

@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
    <style>
        .hidden {
    visibility: hidden;;
}

        </style>
@endpush
