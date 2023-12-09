<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"/>

<div class="btn-group d-flex gap-2">
    <div class="form-group col-3">
        <button class="btn btn-secondary rounded  form-control contact-input" onclick="printPage('{{$url}}')">Print <i class="fa fa-print"></i></button>

    </div>
    @include('livewire.admin.travel-agent.popup.send-email')
    <div class="form-group col-3">
        <button class="btn btn-info form-control contact-input" wire:click="toggleShowModal">Send email</button>
    </div>
    <div class="form-group col-3">
        <a class="btn btn-success rounded form-control contact-input p-2" href="{{ $routeName??null }}">Download CSV</a>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script>
    // Function to load content into an iframe and trigger printing with default scale
    function printPage(url, scale = 100) {
        // Create an iframe element
        var iframe = document.createElement('iframe');

        // Set the source URL of the iframe
        iframe.src = url;

        // Set styles to hide the iframe
        iframe.style.position = 'absolute';
        iframe.style.top = '-9999px';
        iframe.style.left = '-9999px';
        iframe.style.width = '800px';
        iframe.style.height = '0';

        // Append the iframe to the document body
        document.body.appendChild(iframe);

        // Wait for the iframe to load
        iframe.onload = function() {
            // Access the document inside the iframe
            var doc = iframe.contentWindow.document;

            // Set the scale for printing
            doc.body.style.transform = 'scale(' + (scale / 100) + ')';
            doc.body.style.transformOrigin = 'top left';

            // Print the content of the iframe
            iframe.contentWindow.print();
        };
    }
</script>
