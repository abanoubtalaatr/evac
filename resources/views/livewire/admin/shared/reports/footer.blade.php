<div>
    @php 
        $settings = \App\Models\Setting::query()->first(); 
    @endphp

    <div class="my-3" style="font-size: 12px;">
        {{ $settings->invoice_footer }}
    </div>

    
    @if (\App\Helpers\isExistVat())
        @if ($agentInvoices)  <!-- This checks the passed value of agentInvoices -->
            {{-- <span style="font-size: 12px;">VAT Reg: {{ \App\Helpers\registrationNumber() }}</span> --}}
        @else
            <div style="font-size: 12px;">VAT Reg: {{ \App\Helpers\registrationNumber() }}</div>
        @endif
    @endif
</div>
