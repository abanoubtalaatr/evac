<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"/>

<div class="btn-group d-flex gap-2">
    <button class="btn btn-secondary rounded" onclick="printPage('{{$url}}')">Print <i class="fa fa-print"></i></button>
    <button type="button" class="btn btn-info rounded" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Send email
    </button>

    <a class="btn btn-success rounded" href="{{ $routeName??null }}">Download CSV</a>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Enter email</label>
                    <input class="form-control" name="email" id="email" type="email" required placeholder="Please Enter email"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendEmailWithAttachment('{{$className??null}}')">Send Email</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function sendEmailWithAttachment(className) {
        const recipientEmail = $('#email').val();

        if (recipientEmail) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const data = {
                'email' : recipientEmail,
                'className' : className,
                _token: csrfToken,

            };

            $.ajax({
                url: "{{route('admin.send.email')}}",
                method: 'POST',  // Change the method to POST
                data: data,      // Pass the data to be sent in the request body
                success: function (response) {
                    window.location.reload()
                },
                error: function (error) {
                    console.error(error);
                    alert('Error sending email. Please try again.');
                }
            });
        } else {
            alert('Email not provided. Please try again.');
        }
    }
</script>

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
