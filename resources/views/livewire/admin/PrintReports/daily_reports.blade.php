    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$title ?? 'Report'}}</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
    .table-responsive{
        overflow: hidden;
    }
            main {
                padding: 20px;
            }

            .card {
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }

            .card-header {
                color: #fff;
                padding: 15px;
                border-bottom: 1px solid #ddd;
                border-radius: 8px 8px 0 0;
            }

            .card-title {
                margin: 0;
                font-size: 1.25rem;
                font-weight: bolder;
                color: black;
            }

            .card-body {
                padding: 15px;
            }

            .table {
                width: 100%;
                margin-bottom: 0;
                background-color: #fff;
                border-collapse: collapse;
            }

            .table th, .table td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            .table th {
                background-color: #f8f9fa;
            }

            .table tbody tr:hover {
                background-color: #f5f5f5;
            }

            .total-row {
                font-weight: bold;
            }
            .text-center{
                text-align: center;
            }

            /* Set a fixed width for each column */
            .table th:nth-child(1),
            .table td:nth-child(1),
            .table th:nth-child(2),
            .table td:nth-child(2),
            .table th:nth-child(3),
            .table td:nth-child(3) {
                width: 33.33%; /* Equal width for each column */
            }
        </style>
    </head>
    <body>

    <main>
        @include('livewire.admin.shared.reports.header')

        <!--dashboard-->
        <section class="dashboard">
            <div class="row">
                @include('livewire.admin.PrintReports.DailyReport.day')
                @include('livewire.admin.PrintReports.DailyReport.month')
                @include('livewire.admin.PrintReports.DailyReport.year')
            </div>
        </section>
        @include('livewire.admin.shared.reports.footer')

    </main>

    </body>
    </html>
