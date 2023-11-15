<div wire:ignore.self class="modal fade " id="applicationModal" tabindex="-1" role="dialog" aria-labelledby="applicationModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <!-- Add your modal content here -->
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModalLabel">Add Application</h5>
                <button type="button" onclick="$('#applicationModal').modal('hide');" class="close btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group my-2 ">
                            <label class="" for="visaType">Visa Type:</label>
                            <select wire:model="form.visa_type_id" class="form-control" id="visaType">
                                <option value="">Select Visa Type</option>
                                @foreach ($visaTypes as $visaType)
                                    <option value="{{ $visaType->id }}">{{ $visaType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.visa_type_id')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-12">
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

                    <div class="col-12">
                        <div class="form-group my-2">
                            <div class="">
                                <label for="travelAgent">Travel Agent:</label>
                                <input class="" type="checkbox" wire:click="chooseTravelAgent">
                                @if ($isChecked)
                                    <div class="form-group " wire:ignore style="width: 50%">

                                        <select id='agent_id' wire:model='form.travel_agent_id'
                                                class="@error('form.agent_id') is-invalid @enderror form-control contact-input">
                                            <option value="" >Select Option</option>

                                            @foreach($travelAgents as $travelAgent)
                                                <option selected value="{{$travelAgent->id}}">{{$travelAgent->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('form.travel_agent_id')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-12">
                        <label for="passport_no" class="">Passport no:</label>
                        <input type="text" class="form-control" wire:model.lazy="passportNumber" wire:blur="checkPassportNumber">
                        @error('form.passport_no')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-12 my-2">
                        <div class="form-group my-2 ">
                            <label for="title">Title:</label>
                            <select wire:model="form.title" class="form-control" id="title">
                                <option value="">Select Title</option>
                                <option value="Mr">Mr.</option>
                                <option value="Mrs">Mrs.</option>
                                <option value="Ms">Ms.</option>
                            </select>
                        </div>
                        @error('form.title')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>


                    @if (!$isChecked)
                    <div>
                        <div class="col-12 my-2">
                            <label for="first_name" class="">First name:</label>
                            <input type="text" wire:model="form.first_name"  class="form-control">
                            @error('form.first_name')<p style="color: red;">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-12 my-2">
                            <label for="last_name">Last name:</label>
                            <input type="text" wire:model="form.last_name" class="form-control">
                            @error('form.last_name')<p style="color: red;">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @endif
                    <div class="col-12 my-2">
                        <div class="form-group ">
                            <label for="expiry_date"> Expiry date:</label>
                            <div>
                                <input class="form-control" type="date" wire:model="form.expiry_date">
                            </div>

                        </div>
                        @error('form.expiry_date')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-12 my-2">
                        <label for="notes" class="">Note:</label>
                        <textarea class="form-control" wire:model="form.notes"></textarea>
                        @error('form.notes')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-12 my-2">
                        <label for="amount" class="">Amount:</label>
                        <input class="form-control" wire:model="form.amount">
                        @error('form.amount')<p style="color: red;">{{ $message }}</p>@enderror
                    </div>

                </div>

            </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#applicationModal').modal('hide');" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary" wire:click="store">Save</button>

                </div>

        </div>
    </div>
</div>


@push('scripts')
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        window.addEventListener('onContentChanged', () => {
            $('#agent_id').select2();
        });

        $(document).ready(()=>{
           $('#agent_id').select2();
            $('#agent_id').change(e=>{
                @this.set('form.agent_id', $('#agent_id').select2('val'));
            });

        });

    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
    {{-- <link rel="stylesheet" href="{{asset('frontAssets/css/multiselect.css')}}"> --}}
    <style>
        .whats-p {
            /* background: url('whats-app-b.png'); */
            width: 100%;
            float: right;
            padding-bottom: 15px;
        }

        .w-det {
            background: rgb(0, 0, 0, .05);
            border-radius: 5px;
            overflow: hidden;
        }

        .chat {
            background: #dbf8c7;
            /* margin: 10px 25px 3px 0px; */
            padding: 10px;
            background: #DCF8C6;

            width: 100%;

            display: block;
            border-radius: 5px;
            position: relative;
            box-shadow: 0px 2px 1px rgb(0 0 0 / 20%);
        }

        .chat p, .chat h5, .chat h4 {
            color: rgb(89, 89, 89);
            text-decoration: none;
        }

        .chat a {
            text-decoration: none;
        }

        .chat .bubble-arrow.alt {
            position: absolute;
            bottom: 20px;
            left: auto;
            right: 4px;
            float: right;
            top: 0;
        }

        .chat .bubble-arrow:after {
            content: "";
            position: absolute;
            border-top: 15px solid #DCF8C6;
            transform: scaleX(-1);
            border-left: 15px solid transparent;
            border-radius: 4px 0 0 0px;
            width: 0;

        }

        .chat a {
            color: #4285f3;
        }

        .chat img {
            max-width: 100%;
        }
    </style>
@endpush
