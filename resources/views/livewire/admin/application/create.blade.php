
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

            <div class="col-6">
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

            <div class="col-6">
                <div class="form-group my-2">
                    <div class="d-flex gap-2">
                        <label for="travelAgent">Travel Agent:</label>
{{--                        <input class="" type="checkbox" wire:click="chooseTravelAgent">--}}

                            <div class="form-group " wire:ignore style="width: 50%">

                                <select id='agent_id' wire:model='form.travel_agent_id' class="@error('form.agent_id') is-invalid @enderror form-control contact-input">
                                    <option value="" >Select Option</option>

                                    @foreach($travelAgents as $travelAgent)
                                        <option selected value="{{$travelAgent->id}}">{{$travelAgent->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                    </div>
                </div>
                @error('form.travel_agent_id')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

            <div class="col-6">
                <label for="passport_no" class="">Passport no:</label>
                <input type="text" class="form-control" wire:model.lazy="passportNumber" wire:blur="checkPassportNumber">
                @error('form.passport_no')<p style="color: red;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6 my-2">
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
            <div class="col-6">
                <label for="amount" class="">Amount:</label>
                <input class="form-control" wire:model="form.amount">
                @error('form.amount')<p style="color: red;">{{ $message }}</p>@enderror
            </div>

            <div class="col-12 text-center my-2">
                <button type="submit" class="btn btn-primary" wire:click="store">Save</button>
            </div>
        </div>
    </div>
</main>



@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

        Livewire.on('openBlackListModal', function () {
            $('#blackListModal').modal('show');
        });

        Livewire.on('showExpiryPopup', function () {
          $('#expiryPassportModal').modal('show');
        });

        Livewire.on('showMultipleApplicationsPopup', function () {
          $('#passportHasMoreThanOneModal').modal('show');
        });
    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
@endpush
